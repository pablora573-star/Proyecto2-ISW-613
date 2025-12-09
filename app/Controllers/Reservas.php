<?php

namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\RideModel;
use App\Models\UserModel;
use App\Models\VehicleModel;
use CodeIgniter\Controller;

class Reservas extends Controller
{
    public function index()
    {
        $session = session();

        if (!$session->has('user_id') || $session->get('rol') !== 'pasajero') {
            return redirect()->to('/login');
        }

        $pasajero_id = $session->get('user_id');

        $reservationModel = new ReservationModel();
        $rideModel        = new RideModel();
        $userModel        = new UserModel();
        $vehModel         = new VehicleModel();

        // RESERVAS ACTIVAS
        $activas = $reservationModel
            ->select('reservations.*, rides.nombre AS ride_nombre, rides.origen, rides.destino, 
                      rides.fecha_viaje, rides.hora_viaje, rides.costo_espacio,
                      users.nombre AS chofer_nombre, users.apellido AS chofer_apellido, users.telefono AS chofer_telefono,
                      vehiculos.marca, vehiculos.modelo, vehiculos.placa')
            ->join('rides', 'rides.id = reservations.ride_id')
            ->join('users', 'users.id = reservations.chofer_id')
            ->join('vehiculos', 'vehiculos.id = rides.vehicle_id', 'left')
            ->where('reservations.pasajero_id', $pasajero_id)
            ->whereIn('reservations.estado', ['pendiente', 'aceptada'])
            ->where('rides.fecha_viaje >=', date('Y-m-d'))
            ->orderBy('rides.fecha_viaje', 'ASC')
            ->orderBy('rides.hora_viaje', 'ASC')
            ->findAll();


        // RESERVAS PASADAS
        $pasadas = $reservationModel
            ->select('reservations.*, rides.nombre AS ride_nombre, rides.fecha_viaje, rides.hora_viaje')
            ->join('rides', 'rides.id = reservations.ride_id')
            ->where('reservations.pasajero_id', $pasajero_id)
            ->groupStart()
                ->where('rides.fecha_viaje <', date('Y-m-d'))
                ->orWhereIn('reservations.estado', ['rechazada','cancelada'])
            ->groupEnd()
            ->orderBy('rides.fecha_viaje', 'DESC')
            ->limit(10)
            ->findAll();


        return view('/pasajeros/reservaciones', [
            'activas' => $activas,
            'pasadas' => $pasadas
        ]);
    }



    public function cancelar($id)
    {
        $session = session();

        if (!$session->has('user_id') || $session->get('rol') !== 'pasajero') {
            return redirect()->to('/login');
        }

        $pasajero_id = $session->get('user_id');
        $reserva_id  = (int)$id;

        $reservationModel = new ReservationModel();

        // Verificar la reserva del pasajero
        $reserva = $reservationModel
            ->where('id', $reserva_id)
            ->where('pasajero_id', $pasajero_id)
            ->whereIn('estado', ['pendiente', 'aceptada'])
            ->first();

        if (!$reserva) {
            return redirect()->to('/pasajeros/reservaciones?error=unauthorized');
        }

        // Cancelar
        $reservationModel->update($reserva_id, ['estado' => 'cancelada']);

        return redirect()->to('/pasajeros/reservaciones?success=reservation_cancelled');
    }

    public function reservar()
    {
      
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/buscar_rides');
        }

       
        if (!session()->get('user_id') || session()->get('rol') !== 'pasajero') {
            return redirect()->to('/login');
        }

        $ride_id           = (int)$this->request->getPost('ride_id');
        $chofer_id         = (int)$this->request->getPost('chofer_id');
        $pasajero_id       = (int)session()->get('user_id');
        $cantidad_asientos = (int)$this->request->getPost('cantidad_asientos');

       
        if ($cantidad_asientos < 1 || $cantidad_asientos > 4) {
            return redirect()->to("/crear_reserva/$ride_id?error=invalid_quantity");
        }

        $rideModel       = new RideModel();
        $reservationModel = new ReservationModel();

        //asientos disponibles
        $ride = $rideModel->select("
            rides.cantidad_espacios,
            (SELECT COUNT(*) 
             FROM reservations 
             WHERE reservations.ride_id = rides.id 
             AND estado IN ('pendiente','aceptada')
            ) AS asientos_reservados
        ")->where('rides.id', $ride_id)
          ->first();

        if (!$ride) {
            return redirect()->to("/buscar_rides?error=ride_not_found");
        }

        $asientos_disponibles = $ride['cantidad_espacios'] - $ride['asientos_reservados'];

        if ($cantidad_asientos > $asientos_disponibles) {
            return redirect()->to("/crear_reserva/$ride_id?error=insufficient_seats");
        }

    
        $yaTieneReserva = $reservationModel
                ->where('ride_id', $ride_id)
                ->where('pasajero_id', $pasajero_id)
                ->whereIn('estado', ['pendiente', 'aceptada'])
                ->first();

        if ($yaTieneReserva) {
            return redirect()->to("/crear_reserva/$ride_id?error=already_reserved");
        }

        
        $reservationModel->insert([
            'ride_id'           => $ride_id,
            'pasajero_id'       => $pasajero_id,
            'chofer_id'         => $chofer_id,
            'cantidad_asientos' => $cantidad_asientos,
            'estado'            => 'pendiente',
            'fecha_creado'      => date('Y-m-d H:i:s'),
            'notified'          => 0
        ]);

        return redirect()->to("/mis_reservas?success=reservation_created");
    }
}


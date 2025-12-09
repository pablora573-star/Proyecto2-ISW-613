<?php

namespace App\Controllers;

use App\Models\RideModel;
use App\Models\ReservationModel;
use App\Models\UserModel;
use App\Models\VehicleModel;

class Chofer extends BaseController
{
    public function index()
    {
        $session = session();

        // Validación
        if (!$session->has('user_id') || $session->get('rol') !== 'chofer') {
            return redirect()->to('/')->with('error', 'sesion_expirada');
        }

        $userId  = $session->get('user_id');

        // Models
        $rideModel   = new RideModel();
        $reservaModel = new ReservationModel();
        $userModel    = new UserModel();

        // Rides del chofer
        $rides = $rideModel
            ->select('rides.*, vehiculos.placa, vehiculos.marca, vehiculos.modelo')
            ->join('vehiculos', 'vehiculos.id = rides.vehicle_id', 'left')
            ->where('rides.user_id', $userId)
            ->orderBy('fecha_viaje', 'DESC')
            ->orderBy('hora_viaje', 'DESC')
            ->findAll();

        // Reservaciones pendientes
        $reservas = $reservaModel
            ->select("
                reservations.id AS reserva_id,
                reservations.estado,
                reservations.fecha_creado,
                users.nombre AS nombre_pasajero,
                users.apellido AS apellido_pasajero,
                rides.nombre AS nombre_ride,
                rides.origen,
                rides.destino,
                rides.fecha_viaje,
                rides.hora_viaje,
                rides.cantidad_espacios
            ")
            ->join('rides', 'rides.id = reservations.ride_id')
            ->join('users', 'users.id = reservations.pasajero_id')
            ->where('rides.user_id', $userId)
            ->where('reservations.estado', 'pendiente')
            ->orderBy('reservations.fecha_creado', 'DESC')
            ->findAll();

        return view('choferes/index', [
            'nombre' => $session->get('nombre'),
            'apellido' => $session->get('apellido'),
            'foto' => $session->get('foto'),
            'rides' => $rides,
            'reservas' => $reservas
        ]);
    }

    public function registro()
    {
        return view('/choferes/registration_chofer');
    }

    

}
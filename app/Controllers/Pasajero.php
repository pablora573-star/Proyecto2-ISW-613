<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ReservationModel;
use App\Models\RideModel;

class Pasajero extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->has('user_id') || $session->get('rol') !== 'pasajero') {
            return redirect()->to('/login?error=sesion_expirada');
        }

        $user_id = $session->get('user_id');

        $reservationModel = new ReservationModel();

        $estadisticas = $reservationModel->select("
                COUNT(*) as total_reservas,
                SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN estado = 'aceptada' THEN 1 ELSE 0 END) as aceptadas,
                SUM(CASE WHEN estado = 'rechazada' THEN 1 ELSE 0 END) as rechazadas
            ")
            ->where('pasajero_id', $user_id)
            ->first();

        $proximas = $reservationModel
            ->select("reservations.*, rides.nombre as ride_nombre, rides.origen, rides.destino,
                    rides.fecha_viaje, rides.hora_viaje, users.nombre as chofer_nombre, users.apellido as chofer_apellido")
            ->join('rides', 'rides.id = reservations.ride_id')
            ->join('users', 'users.id = rides.user_id')
            ->where('reservations.pasajero_id', $user_id)
            ->where('reservations.estado', 'aceptada')
            ->where('rides.fecha_viaje >=', date('Y-m-d'))
            ->orderBy('rides.fecha_viaje', 'ASC')
            ->orderBy('rides.hora_viaje', 'ASC')
            ->limit(3)
            ->findAll();

        return view('/pasajeros/index', [
            'session' => $session->get(),
            'estadisticas' => $estadisticas,
            'proximas' => $proximas,
        ]);
    }

    public function registro()
    {
        return view('/pasajeros/registration_pasajero');
    }

    
}
<?php

namespace App\Controllers;

use App\Models\VehicleModel;
use App\Models\RideModel;

class Ride extends BaseController
{
    public function create()
    {
        $session = session();

        if (!$session->has('user_id') || $session->get('rol') !== 'chofer') {
            return redirect()->to('/')->with('error', 'Sesión expirada.');
        }

        $vehiculoModel = new VehicleModel();

        // Obtener vehículos del chofer
        $vehiculos = $vehiculoModel
            ->where('user_id', $session->get('user_id'))
            ->orderBy('marca', 'ASC')
            ->findAll();

        return view('choferes/crear_ride', [
            'vehiculos' => $vehiculos
        ]);
    }


    public function store()
    {
        $session = session();

        if (!$session->has('user_id') || $session->get('rol') !== 'chofer') {
            return redirect()->to('/')->with('error', 'Sesión expirada.');
        }

        $rideModel = new RideModel();

        $data = [
            'user_id'           => $session->get('user_id'),
            'vehicle_id'        => $this->request->getPost('vehicle_id'),
            'nombre'            => $this->request->getPost('nombre'),
            'origen'            => $this->request->getPost('origen'),
            'destino'           => $this->request->getPost('destino'),
            'fecha_viaje'       => $this->request->getPost('fecha_viaje'),
            'hora_viaje'        => $this->request->getPost('hora_viaje'),
            'costo_espacio'     => $this->request->getPost('costo_espacio'),
            'cantidad_espacios' => $this->request->getPost('cantidad_espacios'),
        ];

        if (!$rideModel->insert($data)) {
            return redirect()->back()->with('error', 'No se pudo crear el ride.');
        }

        return redirect()->to('/dashboard/chofer')->with('success', 'Ride creado con éxito.');
    }

    public function edit($id)
    {
        $session = session();

        if (!$session->has('user_id') || $session->get('rol') !== 'chofer') {
            return redirect()->to('/')->with('error', 'Sesión expirada.');
        }

        $rideModel = new RideModel();
        $vehiculoModel = new VehicleModel();

        $ride = $rideModel
            ->where('id', $id)
            ->where('user_id', $session->get('user_id'))
            ->first();

        if (!$ride) {
            return redirect()->to('/dashboard/chofer')->with('error', 'Ride no encontrado.');
        }

        $vehiculos = $vehiculoModel
            ->where('user_id', $session->get('user_id'))
            ->orderBy('marca', 'ASC')
            ->orderBy('modelo', 'ASC')
            ->findAll();

        return view('choferes/editar_ride', [
            'ride' => $ride,
            'vehiculos' => $vehiculos
        ]);
    }

    public function update($id)
    {
        $session = session();

        if (!$session->has('user_id') || $session->get('rol') !== 'chofer') {
            return redirect()->to('/')->with('error', 'Sesión expirada.');
        }

        $rideModel = new RideModel();

        $ride = $rideModel
            ->where('id', $id)
            ->where('user_id', $session->get('user_id'))
            ->first();

        if (!$ride) {
            return redirect()->to('/dashboard/chofer')->with('error', 'Ride no encontrado.');
        }

        $rideModel->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'origen' => $this->request->getPost('origen'),
            'destino' => $this->request->getPost('destino'),
            'fecha_viaje' => $this->request->getPost('fecha_viaje'),
            'hora_viaje' => $this->request->getPost('hora_viaje'),
            'vehicle_id' => $this->request->getPost('vehicle_id'),
            'costo_espacio' => $this->request->getPost('costo_espacio'),
            'cantidad_espacios' => $this->request->getPost('cantidad_espacios')
        ]);

        return redirect()->to('/dashboard/chofer')->with('success', 'Ride actualizado correctamente.');
    }

    public function delete($id)
    {
        $model = new RideModel();
        $model->delete($id);
        return redirect()->to('/dashboard/chofer');
    }
}

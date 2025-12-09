<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\VehicleModel;

class Vehiculo extends BaseController
{
    public function index()
    {
        $session = session();

        // Verificar sesión
        if (!$session->has('user_id') || $session->get('rol') !== 'chofer') {
            return redirect()->to('/')->with('error', 'Sesión expirada.');
        }

        $vehiculoModel = new VehicleModel();

        // Obtener vehículos del usuario
        $vehiculos = $vehiculoModel
            ->where('user_id', $session->get('user_id'))
            ->orderBy('anio', 'DESC')
            ->findAll();

        return view('/choferes/vehiculos', [
            'vehiculos' => $vehiculos,
            'nombre'    => $session->get('nombre'),
            'apellido'  => $session->get('apellido'),
        ]);
    }

    public function create()
    {
        // Verificar sesión CI4
        $session = session();

        if (!$session->has('user_id') || $session->get('rol') !== 'chofer') {
            return redirect()->to('/');
        }

        return view('/choferes/registration_vehiculos');
    }

    public function store()
    {
        $session = session();

        if (!$session->has('user_id') || $session->get('rol') !== 'chofer') {
            return redirect()->to('/');
        }

        $validation = service('validation');

        $rules = [
            'placa'   => 'required',
            'color'   => 'required',
            'marca'   => 'required',
            'modelo'  => 'required',
            'anio'    => 'required|integer|greater_than[1900]|less_than[2026]',
            'capacidad_asientos' => 'required|integer|greater_than[0]|less_than[9]',
            'foto'    => 'uploaded[foto]|is_image[foto]|max_size[foto,4096]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Subir imagen
        $foto     = $this->request->getFile('foto');
        $newName  = $foto->getRandomName();
        $foto->move('uploads/vehiculos', $newName);

        $vehiculos = new VehicleModel();

        $vehiculos->insert([
            'user_id'            => $session->get('user_id'),
            'placa'              => $this->request->getPost('placa'),
            'color'              => $this->request->getPost('color'),
            'marca'              => $this->request->getPost('marca'),
            'modelo'             => $this->request->getPost('modelo'),
            'anio'               => $this->request->getPost('anio'),
            'capacidad_asientos' => $this->request->getPost('capacidad_asientos'),
            'foto_url'           => $newName,
        ]);

        return redirect()->to('/vehiculos')->with('success', 'Vehículo agregado correctamente.');
    }

    public function editar($id)
    {
        $session = session();

        // Validar sesión y rol
        if (!$session->has('user_id') || $session->get('rol') !== 'chofer') {
            return redirect()->to('/')->with('error', 'Sesión expirada.');
        }

        $vehiculoModel = new VehicleModel();

        // Obtener vehículo del usuario logueado
        $vehiculo = $vehiculoModel
            ->where('id', $id)
            ->where('user_id', $session->get('user_id'))
            ->first();

        if (!$vehiculo) {
            return redirect()->to('/vehiculos')->with('error', 'Vehículo no encontrado.');
        }

        return view('/choferes/editar_vehiculo', [
            'vehiculo' => $vehiculo
        ]);
    }

    public function update($id)
    {
        $session = session();
        
        if (!$session->has('user_id') || $session->get('rol') !== 'chofer') {
            return redirect()->to('/')->with('error', 'Sesión expirada.');
        }

        $vehiculoModel = new VehicleModel();

        $id = $this->request->getPost('vehicle_id');

        // Verificar que el vehículo pertenece al usuario
        $vehiculo = $vehiculoModel
            ->where('id', $id)
            ->where('user_id', $session->get('user_id'))
            ->first();

        if (!$vehiculo) {
            return redirect()->to('/vehiculos')->with('error', 'Vehículo no encontrado.');
        }

        // Subir archivo si viene uno nuevo
        $foto = $this->request->getFile('foto');
        $nombreFoto = $vehiculo['foto_url']; // mantener la actual

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $nombreFoto = $foto->getRandomName();
            $foto->move('uploads/vehiculos', $nombreFoto);
        }

        // Guardar cambios
        $vehiculoModel->update($id, [
            'placa' => $this->request->getPost('placa'),
            'color' => $this->request->getPost('color'),
            'marca' => $this->request->getPost('marca'),
            'modelo' => $this->request->getPost('modelo'),
            'anio' => $this->request->getPost('anio'),
            'capacidad_asientos' => $this->request->getPost('capacidad_asientos'),
            'foto_url' => $nombreFoto
        ]);

        return redirect()->to('/vehiculos')->with('success', 'Vehículo actualizado exitosamente.');
    }

    public function delete($id)
    {
        $model = new VehicleModel();
        $model->delete($id);
        return redirect()->to('/vehiculos');
    }
}

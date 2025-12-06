<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RideModel;

class Login extends BaseController
{
    public function index(): string
    {
        $session = session();

        if ($session->has('rol')) {
            switch ($session->get('rol')) {
                case 'chofer':
                    return redirect()->to('/dashboard/chofer');
                case 'pasajero':
                    return redirect()->to('/dashboard/pasajero');
                case 'administrador':
                    return redirect()->to('/dashboard/admin');
            }
        }

       
        $error = $session->getFlashdata('error');

        return view('/logins/index', ['error' => $error]);
    }

    public function inicio()
    {
        return redirect()->to('/');
    }

    public function authentication()
    {
        $userModel = new UserModel();
        $session = session();

        $cedula = $this->request->getPost('cedula');
        $password = $this->request->getPost('password');

        // Buscar usuario
        $user = $userModel->where('cedula', $cedula)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Cédula o contraseña incorrecta');
        }

        // Verificar contraseña
        if (!password_verify($password, $user['contra'])) {
            return redirect()->to('/login')->with('error', 'Cédula o contraseña incorrecta');
        }

        // Verificar estado
        switch ($user['estado']) {
            case 'pendiente':
                return redirect()->to('/login')->with('error', 'Tu cuenta está pendiente de aprobación.');
            case 'inactiva':
                return redirect()->to('/login')->with('error', 'Tu cuenta está inactiva. Contacta con el administrador.');
            case 'activa':
                break;
            default:
                return redirect()->to('/login')->with('error', 'El estado de tu cuenta no es válido.');
        }

        // Guardar sesión
        $session->set([
            'user_id' => $user['id'],
            'cedula'  => $user['cedula'],
            'nombre'  => $user['nombre'],
            'apellido'=> $user['apellido'],
            'rol'     => $user['rol'],
            'foto'    => $user['foto_url'],
            'logged_in' => true
        ]);

        // Redirigir según rol
        switch ($user['rol']) {
            case 'chofer':
                return redirect()->to('/dashboard/chofer');
            case 'pasajero':
                return redirect()->to('/dashboard/pasajero');
            case 'admin':
                return redirect()->to('/dashboard/admin');
        }

        return redirect()->to('/login')->with('error', 'Estado o rol no válido.');
    }

    public function buscarRides()
    {
        $ridesModel = new RideModel();

        $origen  = $this->request->getGet('origen');
        $destino = $this->request->getGet('destino');
        $orden   = $this->request->getGet('orden') ?? 'fecha_asc';

        $rides = $ridesModel->getRides($origen, $destino, $orden);

        return view('logins/buscar_rides', [
            'rides'  => $rides,
            'origen' => $origen,
            'destino' => $destino,
            'orden' => $orden,
        ]);
    }
    
    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to('/');
    }

}
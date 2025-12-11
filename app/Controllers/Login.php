<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RideModel;
use App\Models\LoginTokenModel;

class Login extends BaseController
{
    public function index()
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
                case 'administrador':
                    return redirect()->to('/dashboard/admin');
        }

        return redirect()->to('/login')->with('error', 'Estado o rol no válido.');
    }
    
    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to('/');
    }

    public function sendLink()
    {
        $email = $this->request->getPost('email');

        $userModel = new UserModel();
        $tokenModel = new LoginTokenModel();
       
        $user = $userModel->where('correo', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'No existe un usuario con ese correo.');
        }

     
        $token = bin2hex(random_bytes(32));

        $tokenModel->save([
           'user_id'    => $user['id'],
           'token'      => $token,
           'used'       => 0
            
        ]);

        $link = base_url("login/magic/$token");

        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject("Tu enlace de acceso");
        $emailService->setMessage("Haz click aquí para ingresar: <a href='$link'>$link</a>");
        $emailService->send();

        return redirect()->back()->with('success', 'Se ha enviado un link a tu correo.');
    }

    public function magicLogin($token)
    {
        $tokenModel = new LoginTokenModel();
        $userModel = new UserModel();
        $session = session();

        $record = $tokenModel
            ->where('token', $token)
            ->where('used', 0)
            ->first();

        if (!$record) {
            return redirect()->to('/login')->with('error', 'Token inválido o ya utilizado.');
        }

        $user = $userModel->find($record['user_id']);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Usuario no encontrado.');
        }

        if ($user['estado'] !== 'activa') {
            return redirect()->to('/login')->with('error', 'Tu cuenta no está activa.');
        }

        $tokenModel->update($record['id'], ['used' => 1]);

        $session->set([
            'user_id' => $user['id'],
            'cedula'  => $user['cedula'],
            'nombre'  => $user['nombre'],
            'apellido'=> $user['apellido'],
            'rol'     => $user['rol'],
            'foto'    => $user['foto_url'],
            'logged_in' => true
        ]);

        switch ($user['rol']) {
            case 'chofer':
                return redirect()->to('/dashboard/chofer');
            case 'pasajero':
                return redirect()->to('/dashboard/pasajero');
            case 'administrador':
                return redirect()->to('/dashboard/admin');
        }

        return redirect()->to('/login')->with('error', 'Error desconocido.');
    }

}
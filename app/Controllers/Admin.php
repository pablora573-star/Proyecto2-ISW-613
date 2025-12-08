<?php

namespace App\Controllers;
use App\Models\UserModel;

class Admin extends BaseController
{
    public function index()
    {
         $session = session();

        if (!$session->has('user_id') || $session->get('rol') !== 'administrador') {
            return redirect()->to('/login?error=sesion_expirada');
        }

        $userModel = new UserModel();

        $rol     = $this->request->getGet('rol');
        $estado  = $this->request->getGet('estado');

        $usuarios = $userModel->filtrar($rol, $estado);

        return view('/admins/index', [
            'usuarios'     => $usuarios,
            'filtroRol'    => $rol,
            'filtroEstado' => $estado,
            'user'         => [
                'id'       => $session->get('user_id'),
                'nombre'   => $session->get('nombre'),
                'apellido' => $session->get('apellido'),
                'foto'     => $session->get('foto')
            ]
        ]);
    }
}
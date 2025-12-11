<?php

namespace App\Controllers;
use App\Models\UserModel;

class Admin extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->has('user_id') || $session->get('rol') !== 'administrador') {
            return redirect()->to('/login');
        }

        $user = [
            'id'       => $session->get('user_id'),
            'nombre'   => $session->get('nombre'),
            'apellido' => $session->get('apellido'),
            'foto'     => $session->get('foto'),
        ];

        $filtroRol    = $this->request->getGet('rol');
        $filtroEstado = $this->request->getGet('estado');

        $userModel = new \App\Models\UserModel();

        if ($filtroRol) {
            $userModel->where('rol', $filtroRol);
        }

        if ($filtroEstado) {
            $userModel->where('estado', $filtroEstado);
        }

        $usuarios = $userModel->findAll();

        //reporte

        $fechaDesde = $this->request->getGet('desde');
        $fechaHasta = $this->request->getGet('hasta');
        $filtroUsuario = $this->request->getGet('user');

        $reporteModel = new \App\Models\ReporteModel();

        if ($fechaDesde) {
            $reporteModel->where('fecha >=', $fechaDesde);
        }

        if ($fechaHasta) {
            $reporteModel->where('fecha <=', $fechaHasta);
        }

        if ($filtroUsuario) {
            $reporteModel->where('user_id', $filtroUsuario);
        }

        $reportes = $reporteModel
            ->orderBy('fecha', 'DESC')
            ->findAll();

        $todosUsuarios = (new \App\Models\UserModel())
            ->select('id, nombre, apellido')
            ->orderBy('nombre', 'ASC')
            ->findAll();

        return view('/admins/index', [
            'user'          => $user,
            'usuarios'      => $usuarios,
            'filtroRol'     => $filtroRol,
            'filtroEstado'  => $filtroEstado,
            'reportes'      => $reportes,
            'fechaDesde'    => $fechaDesde,
            'fechaHasta'    => $fechaHasta,
            'todosUsuarios' => $todosUsuarios,
            'filtroUsuario' => $filtroUsuario
        ]);
    }


    public function registro()
    {
        return view('/admins/registration_admin');
    }

    public function cambiar_estado($id, $estado)
    {
        $session = session();

        // Solo administrador puede cambiar estados
        if (!$session->has('user_id') || $session->get('rol') !== 'administrador') {
            return redirect()->to('/login?error=unauthorized');
        }

        $adminId = $session->get('user_id');

        // Validaciones de estado permitido
        if (!in_array($estado, ['activa', 'inactiva', 'pendiente'])) {
            return redirect()->to('/dashboard/admin?error=invalid_state');
        }

        // No permitir cambiarse a sí mismo
        if ((int)$id === (int)$adminId) {
            return redirect()->to('/dashboard/admin?error=cannot_modify_self');
        }

        $userModel = new UserModel();

        // Verificar que exista el usuario
        $usuario = $userModel->find($id);

        if (!$usuario) {
            return redirect()->to('/dashboard/admin?error=user_not_found');
        }

        // Actualizar estado
        $ok = $userModel->update($id, ['estado' => $estado]);

        if (!$ok) {
            return redirect()->to('/dashboard/admin?error=update_failed');
        }

        // Seleccionar mensaje correcto
        $mensaje = match($estado) {
            'activa'    => 'user_activated',
            'inactiva'  => 'user_deactivated',
            'pendiente' => 'user_pending',
            default     => 'state_changed'
        };

        return redirect()->to('/dashboard/admin?success=' . $mensaje);
    }

    

}
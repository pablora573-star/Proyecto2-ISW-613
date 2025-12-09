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

    public function registro()
    {
        return view('/admins/registration_admin');
    }
    
    /*
    public function store()
    {
        helper(['form']);

        $userModel = new UserModel();

        $name       = $this->request->getPost('name');
        $lastName   = $this->request->getPost('lastName');
        $cedula     = $this->request->getPost('cedula');
        $nacimiento = $this->request->getPost('nacimiento');
        $correo     = $this->request->getPost('correo');
        $telefono   = $this->request->getPost('telefono');
        $rol        = $this->request->getPost('rol');
        $password   = $this->request->getPost('password');
        $password2  = $this->request->getPost('password2');

        if ($password !== $password2) {
            return redirect()->back()->with('error', 'Las contraseñas no coinciden');
        }

        // Validar cédula única
        if ($userModel->where('cedula', $cedula)->first()) {
            return redirect()->back()->with('error', 'Esa cédula ya está registrada');
        }

        // Hash
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Token
        $token = bin2hex(random_bytes(32));
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // FOTO
        $foto = $this->request->getFile('foto');
        if (!$foto->isValid()) {
            return redirect()->back()->with('error', 'Debe subir una fotografía');
        }

        // Guardar foto
        $newName = $foto->getRandomName();
        $foto->move('uploads/fotos', $newName);
        $fotoRuta = 'uploads/fotos/' . $newName;

        // Insertar usuario
        $userModel->insert([
            'nombre'           => $name,
            'apellido'         => $lastName,
            'cedula'           => $cedula,
            'fecha_nacimiento' => $nacimiento,
            'correo'           => $correo,
            'telefono'         => $telefono,
            'foto_url'         => $fotoRuta,
            'rol'              => $rol,
            'contra'           => $passwordHash,
            'estado'           => 'pendiente',
            'activation_token' => $token,
            'token_expiry'     => $tokenExpiry,
            'fecha_creado'     => date('Y-m-d H:i:s'),
        ]);

        // Enviar correo de activación
        $email = \Config\Services::email();
        $email->setFrom('tu_correo@gmail.com', 'Aventones');
        $email->setTo($correo);

        $email->setSubject("Activa tu cuenta");
        $email->setMessage(
            "Hola $name $lastName,<br><br>" .
            "Activa tu cuenta haciendo clic aquí:<br>" .
            "<a href='" . base_url("activar/" . $token) . "'>Activar Cuenta</a><br><br>" .
            "Este enlace vence en 24 horas."
        );

        $email->send();

        return redirect()->to('/registro/confirmado?email=' . urlencode($correo));

    }
    */

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
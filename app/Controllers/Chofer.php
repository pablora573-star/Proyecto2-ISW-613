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

        return view('chofer/index', [
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

    public function update($id)
    {
        $choferModel = new \App\Models\ChoferModel();

        // Obtener datos actuales
        $chofer = $choferModel->find($id);

        if (!$chofer) {
            return redirect()->back()->with('error', 'Chofer no encontrado');
        }

        // ================================
        // 1. Datos del formulario
        // ================================
        $data = [
            'nombre'           => $this->request->getPost('nombre'),
            'apellido'         => $this->request->getPost('apellido'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'correo'           => $this->request->getPost('correo'),
            'telefono'         => $this->request->getPost('telefono'),
        ];

        // --------------------------------
        // IMPORTANTE:
        // NO permitir cambiar el rol
        // --------------------------------
        $data['rol'] = $chofer['rol'];

        // ================================
        // 2. Cambiar contraseña (opcional)
        // ================================
        $currentPassword = $this->request->getPost('current_password');
        $password = $this->request->getPost('password');
        $password2 = $this->request->getPost('password2');

        $cambiarPass = !empty($currentPassword) || !empty($password) || !empty($password2);

        if ($cambiarPass) {

            // Validar que todos los campos estén llenos
            if (empty($currentPassword) || empty($password) || empty($password2)) {
                return redirect()->back()->with('error', 'Debe completar todos los campos de contraseña.');
            }

            // Validar coincidencia
            if ($password !== $password2) {
                return redirect()->back()->with('error', 'Las contraseñas no coinciden.');
            }

            // Verificar contraseña actual
            if (!password_verify($currentPassword, $chofer['contra'])) {
                return redirect()->back()->with('error', 'La contraseña actual no es correcta.');
            }

            // Guardar nueva contraseña
            $data['contra'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // ================================
        // 3. FOTO (opcional)
        // ================================
        $foto = $this->request->getFile('foto');

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {

            // Validar tamaño (5MB)
            if ($foto->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()->with('error', 'La imagen supera el límite permitido (5MB).');
            }

            // Validar extensión
            $ext = $foto->getExtension();
            if (!in_array($ext, ['jpg','jpeg','png','gif'])) {
                return redirect()->back()->with('error', 'Formato de imagen no permitido.');
            }

            // Generar nombre único
            $nombreNuevo = $foto->getRandomName();
            $foto->move('uploads/fotos', $nombreNuevo);

            $data['foto_url'] = 'uploads/fotos/' . $nombreNuevo;

            // Eliminar foto anterior si no era default
            if (!empty($chofer['foto_url']) 
                && file_exists($chofer['foto_url'])
                && strpos($chofer['foto_url'], 'default_user.png') === false) 
            {
                unlink($chofer['foto_url']);
            }
        }

        // ================================
        // 4. Guardar cambios
        // ================================
        if (!$choferModel->update($id, $data)) {
            return redirect()->back()->with('error', 'No se pudo actualizar la información.');
        }

        // ================================
        // 5. Redirigir con mensaje
        // ================================
        return redirect()->to(base_url('chofer/lista'))
                        ->with('success', 'Chofer actualizado correctamente.');
    }


}
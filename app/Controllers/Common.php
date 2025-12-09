<?php

namespace App\Controllers;


use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\RideModel;
use App\Models\ReservationModel;
use App\Models\ReporteModel;
class Common extends Controller
{
   /* public function buscarRides()
    {
        $origen  = $this->request->getGet('origen');
        $destino = $this->request->getGet('destino');
        $orden   = $this->request->getGet('orden') ?? 'fecha_asc';

        $rideModel = new RideModel();

        $rides = $rideModel->buscarRides($origen, $destino, $orden);

        return view('/logins/buscar_rides', [
            'rides' => $rides,
            'origen' => $origen,
            'destino' => $destino,
            'orden' => $orden
        ]);
    }
    */

    public function buscarRides()
    {
        $origen  = $this->request->getGet('origen');
        $destino = $this->request->getGet('destino');
        $orden   = $this->request->getGet('orden') ?? 'fecha_asc';

        $rideModel = new RideModel();

        
        $rides = $rideModel->buscarRides($origen, $destino, $orden);

      
        $cantidadResultados = count($rides);

       
        if (session()->get('user_id') && ($origen !== null || $destino !== null)) {

            $reporte = new ReporteModel();

            $reporte->insert([
                'user_id'            => session()->get('user_id'),
                'fecha'              => date('Y-m-d H:i:s'),
                'lugar_salida'       => $origen ?? '',
                'lugar_llegada'      => $destino ?? '',
                'cantidad_resultados' => $cantidadResultados
            ]);
        }

        
        return view('/logins/buscar_rides', [
            'rides'   => $rides,
            'origen'  => $origen,
            'destino' => $destino,
            'orden'   => $orden
        ]);
    }

    public function createReserva($ride_id)
    {
       
        if (!session()->get('user_id') || session()->get('rol') !== 'pasajero') {
            return redirect()->to('/');
        }

        $rideModel = new RideModel();

        // info del ride
        $ride = $rideModel->getRideConDatos($ride_id);

        if (!$ride) {
            return redirect()->to('/buscar_rides')->with('error', 'Ride no encontrado');
        }

        //asientos disponibles
        $asientos_disponibles = $ride['cantidad_espacios'] - $ride['asientos_reservados'];

 
        $reservationModel = new ReservationModel();

        $yaReservado = $reservationModel
            ->where('ride_id', $ride_id)
            ->where('pasajero_id', session()->get('user_id'))
            ->whereIn('estado', ['pendiente', 'aceptada'])
            ->first();

        return view('/logins/crear_reserva', [
            'ride' => $ride,
            'asientos_disponibles' => $asientos_disponibles,
            'yaReservado' => $yaReservado
        ]);
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

    public function editar()
    {
        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/login?error=sesion_expirada');
        }

        $userModel = new UserModel();
        $user_id   = $session->get('user_id');
        $rol       = $session->get('rol');

        $usuario = $userModel->find($user_id);

        if (!$usuario) {
            return redirect()->to('/login?error=usuario_no_encontrado');
        }

        $dashboardUrl = match($rol) {
            'pasajero' => '/dashboard/pasajero',
            'chofer' => '/dashboard/chofer',
            'administrador' => '/dashboard/admin',
            default => '/login'
        };

        $badgeClass = match($rol) {
            'pasajero' => 'badge-pasajero',
            'chofer' => 'badge-chofer',
            'administrador' => 'badge-admin',
            default => 'badge'
        };

        $stylesrol = match($rol) {
            'pasajero' => 'pasajero',
            'chofer' => 'chofer',
            'administrador' => 'admin',
        };

        return view('common/editar_perfil', [
            'usuario' => $usuario,
            'dashboardUrl' => $dashboardUrl,
            'badgeClass' => $badgeClass,
            'stylesrol' => $stylesrol,
            'rol' => $rol
        ]);
    }


    public function update($id)
    {
        $userModel = new UserModel();
        $session = session();

        // Obtener datos actuales
        $usuario = $userModel->find($id);

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        $data = [
            'nombre'           => $this->request->getPost('nombre'),
            'apellido'         => $this->request->getPost('apellido'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'correo'           => $this->request->getPost('correo'),
            'telefono'         => $this->request->getPost('telefono'),
        ];

        // Mantener el rol original
        $data['rol'] = $usuario['rol'];

        $currentPassword = $this->request->getPost('current_password');
        $password = $this->request->getPost('password');
        $password2 = $this->request->getPost('password2');

        $cambiarPass = !empty($currentPassword) || !empty($password) || !empty($password2);

        if ($cambiarPass) {
            if (empty($currentPassword) || empty($password) || empty($password2)) {
                return redirect()->back()->with('error', 'Debe completar todos los campos de contraseña.');
            }

            if ($password !== $password2) {
                return redirect()->back()->with('error', 'Las contraseñas no coinciden.');
            }

            if (!password_verify($currentPassword, $usuario['contra'])) {
                return redirect()->back()->with('error', 'La contraseña actual no es correcta.');
            }

            $data['contra'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $foto = $this->request->getFile('foto');

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            if ($foto->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()->with('error', 'La imagen supera el límite permitido (5MB).');
            }

            $ext = $foto->getExtension();
            if (!in_array($ext, ['jpg','jpeg','png','gif'])) {
                return redirect()->back()->with('error', 'Formato de imagen no permitido.');
            }

            $nombreNuevo = $foto->getRandomName();
            $foto->move('uploads/fotos', $nombreNuevo);

            $data['foto_url'] = 'uploads/fotos/' . $nombreNuevo;

            if (!empty($usuario['foto_url']) && file_exists($usuario['foto_url']) && strpos($usuario['foto_url'], 'default_user.png') === false) {
                unlink($usuario['foto_url']);
            }
        }

        if (!$userModel->update($id, $data)) {
            return redirect()->back()->with('error', 'No se pudo actualizar la información.');
        }


        $rol = $session->get('rol');

        $dashboardUrl = match($rol) {
            'pasajero' => '/dashboard/pasajero',
            'chofer' => '/dashboard/chofer',
            'administrador' => '/dashboard/admin',
            default => '/login'
        };

        return redirect()->to($dashboardUrl)->with('success', 'Información actualizada correctamente.');
    }


}

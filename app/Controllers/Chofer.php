<?php

namespace App\Controllers;

class Chofer extends BaseController
{
    public function index(): string
    {
        return view('/choferes/index');
    }

    public function registro()
    {
        return view('/choferes/registration_chofer');
    }

    public function store()
    {
        $request = $this->request;
        $userModel = new UserModel();

    
        $validationRules = [
            'name'        => 'required',
            'lastName'    => 'required',
            'cedula'      => 'required|is_unique[users.cedula]',
            'nacimiento'  => 'required|valid_date',
            'correo'      => 'required|valid_email|is_unique[users.correo]',
            'telefono'    => 'required',
            'password'    => 'required|min_length[6]',
            'password2'   => 'required|matches[password]',
            'foto'        => 'uploaded[foto]|max_size[foto,5120]|is_image[foto]',
        ];

        if (! $this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

     
        $foto = $request->getFile('foto');
        $nombreFoto = $foto->getRandomName();

        $foto->move('/uploads/fotos/', $nombreFoto);

        $fotoRuta = '/uploads/fotos/' . $nombreFoto;

       
        $passwordHash = password_hash($request->getPost('password'), PASSWORD_DEFAULT);

        
        $activationToken = bin2hex(random_bytes(32));
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

        
        $data = [
            'nombre'          => $request->getPost('name'),
            'apellido'        => $request->getPost('lastName'),
            'cedula'          => $request->getPost('cedula'),
            'fecha_nacimiento'=> $request->getPost('nacimiento'),
            'correo'          => $request->getPost('correo'),
            'telefono'        => $request->getPost('telefono'),
            'foto_url'        => $fotoRuta,
            'rol'             => 'chofer',
            'contra'          => $passwordHash,
            'estado'          => 'pendiente',
            'activation_token'=> $activationToken,
            'token_expiry'    => $tokenExpiry,
        ];

        $userModel->insert($data);

        $email = \Config\Services::email();
        $email->setFrom('jpr12cr@gmail.com', 'Aventones');
        $email->setTo($request->getPost('correo'));
        $email->setSubject('Activa tu cuenta en Aventones');

        $activationLink = base_url('/activarCuenta?token=' . $activationToken);

        $mensaje = "
            Hola {$data['nombre']} {$data['apellido']},

            ¡Gracias por registrarte en Aventones como chofer!

            Para activar tu cuenta, haz clic en el siguiente enlace:

            $activationLink

            Este enlace expirará en 24 horas.

            Si no solicitaste esta cuenta, ignora este mensaje.
        ";

        $email->setMessage($mensaje);

        if (! $email->send()) {
            // WARNING (correo no enviado pero usuario creado)
            return redirect()->to('/registration_success?email=' . urlencode($data['correo']) . '&warning=email_failed');
        }

        // ÉXITO
        return redirect()->to('/registration_success?email=' . urlencode($data['correo']));
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
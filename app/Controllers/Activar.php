<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Activar extends Controller
{
    public function index($token)
    {
        $userModel = new UserModel();

        $user = $userModel->where('activation_token', $token)->first();

        if (!$user) {
            return redirect()->to('/activation_error')->with('error', 'Token inválido');
        }

        if (date('Y-m-d H:i:s') > $user['token_expiry']) {
            return redirect()->to('/activation_error')->with('error', 'Token expirado');
        }

        if ($user['estado'] === 'activa') {
            return redirect()->to('/login')->with('info', 'La cuenta ya estaba activa');
        }

        $userModel->update($user['id'], [
            'estado'           => 'activa',
            'activation_token' => null,
            'token_expiry'     => null
        ]);

        return redirect()->to('/registro/activado?name=' . urlencode($user['nombre']));
    }
}

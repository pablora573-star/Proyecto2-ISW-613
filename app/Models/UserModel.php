<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nombre',
        'apellido',
        'cedula',
        'fecha_nacimiento',
        'correo',
        'telefono',
        'foto_url',
        'contra',
        'rol',
        'estado',
        'activation_token',
        'token_expiry',
        'fecha_creado'
    ];
}

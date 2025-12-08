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

    public function filtrar($rol = null, $estado = null)
    {
        $builder = $this->builder();

        if (!empty($rol)) {
            $builder->where('rol', $rol);
        }

        if (!empty($estado)) {
            $builder->where('estado', $estado);
        }

        return $builder->get()->getResultArray();
    }
}


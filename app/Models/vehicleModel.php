<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleModel extends Model
{
    protected $table            = 'vehiculos';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'placa',
        'color',
        'marca',
        'modelo',
        'anio',
        'capacidad_asientos',
        'foto_url',
        'user_id'
    ];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class RideModel extends Model
{
    protected $table            = 'rides';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'user_id',
        'nombre',
        'origen',
        'destino',
        'fecha_viaje',
        'hora_viaje',
        'costo_espacio',
        'cantidad_espacios',
        'vehicle_id',
        'fecha_creado'
    ];
}

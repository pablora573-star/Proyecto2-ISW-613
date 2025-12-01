<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table            = 'reservations';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'ride_id',
        'pasajero_id',
        'chofer_id',
        'cantidad_asientos',
        'estado',
        'fecha_creado',
        'notified'
    ];
}

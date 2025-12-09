<?php

namespace App\Models;

use CodeIgniter\Model;

class ReporteModel extends Model
{
    protected $table      = 'reporte';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'fecha',
        'lugar_salida',
        'lugar_llegada',
        'cantidad_resultados'
    ];
}

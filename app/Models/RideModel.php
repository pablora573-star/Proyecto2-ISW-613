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


    public function getRides($origen = null, $destino = null, $orden = 'fecha_asc')
    {
        $builder = $this->builder();


        if (!empty($origen)):
            $builder->like('origen', $origen);
        endif;

        if (!empty($destino)):
            $builder->like('destino', $destino);
        endif;

       
        switch ($orden) {
            case 'fecha_desc':
                $builder->orderBy('fecha_viaje', 'DESC');
                break;

            case 'origen_asc':
                $builder->orderBy('origen', 'ASC');
                break;

            case 'origen_desc':
                $builder->orderBy('origen', 'DESC');
                break;

            case 'destino_asc':
                $builder->orderBy('destino', 'ASC');
                break;

            case 'destino_desc':
                $builder->orderBy('destino', 'DESC');
                break;

            case 'fecha_asc':
            default:
                $builder->orderBy('fecha_viaje', 'ASC');
                break;
        }

        return $builder->get()->getResultArray();
    }
}


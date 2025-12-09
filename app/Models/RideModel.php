<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\UserModel;
use App\Models\VehicleModel;

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


   public function buscarRides($origen, $destino, $orden)
    {
        $builder = $this->builder()
            ->select('
                rides.*,
                vehiculos.marca,
                vehiculos.modelo,
                vehiculos.anio,
                (SELECT IFNULL(SUM(cantidad_asientos),0)
                FROM reservations
                WHERE ride_id = rides.id AND estado != "cancelada") AS asientos_reservados
            ')
            ->join('vehiculos', 'vehiculos.user_id = rides.user_id', 'left');

        if ($origen)  $builder->like('origen', $origen);
        if ($destino) $builder->like('destino', $destino);

        switch ($orden) {
            case 'fecha_desc':   $builder->orderBy('fecha_viaje', 'DESC'); break;
            case 'origen_asc':   $builder->orderBy('origen', 'ASC'); break;
            case 'origen_desc':  $builder->orderBy('origen', 'DESC'); break;
            case 'destino_asc':  $builder->orderBy('destino', 'ASC'); break;
            case 'destino_desc': $builder->orderBy('destino', 'DESC'); break;
            default:             $builder->orderBy('fecha_viaje', 'ASC');
        }

        return $builder->get()->getResultArray();
    }

    public function getRideConDatos($id)
    {
        return $this->select("
                rides.*,
                users.nombre AS chofer_nombre, 
                users.apellido AS chofer_apellido,
                vehiculos.marca, vehiculos.modelo, vehiculos.anio, vehiculos.placa,
                (SELECT COUNT(*) 
                    FROM reservations 
                    WHERE reservations.ride_id = rides.id 
                    AND reservations.estado IN ('pendiente','aceptada')
                ) AS asientos_reservados
            ")
            ->join('users', 'users.id = rides.user_id')
            ->join('vehiculos', 'vehiculos.id = rides.vehicle_id', 'left')
            ->where('rides.id', $id)
            ->first();
    }

}


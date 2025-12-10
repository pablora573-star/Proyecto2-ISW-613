<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

class NotificacionesPendientes extends BaseCommand
{
    protected $group       = 'Notificaciones';
    protected $name        = 'notificaciones:pendientes';
    protected $description = 'Envía correos de reservas pendientes con más de X minutos sin atender.';
    protected $usage       = 'notificaciones:pendientes <minutos>';
    protected $arguments   = ['minutos' => 'Minutos transcurridos desde la creación de la reserva'];

    public function run(array $params)
    {
        if (count($params) < 1) {
            CLI::error("Debe indicar el número de minutos.");
            return;
        }

        $minutes = (int) $params[0];

        if ($minutes <= 0) {
            CLI::error("El valor de minutos debe ser mayor a 0.");
            return;
        }

        $db = \Config\Database::connect();

        $sql = "
            SELECT 
                r.id AS N_reservacion,
                r.fecha_creado,
                r.cantidad_asientos,
                r.notified,
                u_ch.correo AS chofer_email,
                u_ch.nombre AS chofer_nombre,
                u_pa.nombre AS pasajero_nombre,
                rides.nombre AS ride_nombre,
                rides.origen,
                rides.destino,
                rides.fecha_viaje,
                rides.hora_viaje
            FROM reservations r
            INNER JOIN users u_ch ON u_ch.id = r.chofer_id
            INNER JOIN users u_pa ON u_pa.id = r.pasajero_id
            INNER JOIN rides ON rides.id = r.ride_id
            WHERE r.estado = 'pendiente'
            AND r.notified = 0
            AND r.fecha_creado <= (NOW() - INTERVAL $minutes MINUTE)
        ";

        $query = $db->query($sql);
        $rows = $query->getResultArray();

        if (empty($rows)) {
            CLI::write("No hay reservas pendientes sin notificar con más de $minutes minutos.");
            return;
        }

        $emailsSent = 0;
        $db->transBegin();

        $email = Services::email();

        foreach ($rows as $row) {

            $email->clear();
            $email->setFrom('jpr12cr@gmail.com', 'Aventones');
            $email->setTo($row['chofer_email']);
            $email->setSubject("Reserva pendiente (ID: {$row['N_reservacion']})");

            $message = "Hola {$row['chofer_nombre']},\n\n"
                . "Tienes una solicitud de reserva pendiente de {$row['pasajero_nombre']} "
                . "para el ride '{$row['ride_nombre']}'.\n\n"
                . "Origen: {$row['origen']}\nDestino: {$row['destino']}\n"
                . "Fecha: {$row['fecha_viaje']}\nHora: {$row['hora_viaje']}\n\n"
                . "Por favor revisa tu panel de reservas para aceptar o rechazar la solicitud.\n\n"
                . "Saludos,\nEquipo Aventones";

            $email->setMessage($message);

            if ($email->send()) {
                $db->query("UPDATE reservations SET notified = 1 WHERE id = {$row['N_reservacion']}");
                $emailsSent++;
            } else {
                CLI::error("Error enviando correo a {$row['chofer_email']}");
            }
        }

        if ($emailsSent > 0) {
            $db->transCommit();
        } else {
            $db->transRollback();
        }

        CLI::write("Proceso finalizado. Correos enviados: $emailsSent");
    }
}

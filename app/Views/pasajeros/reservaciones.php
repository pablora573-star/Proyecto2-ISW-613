<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/nav.css" rel="stylesheet">
    <link href="/css/mis_reservas.css" rel="stylesheet">
    <title>Mis Reservas - Aventones</title>
     
</head>

<body>

<nav>
    <h2>Aventones - Mis Reservas</h2>

    <div class="nav-links">
        <a href="/dashboard/pasajero">Dashboard</a>
        <a href="/buscar_rides">Buscar Rides</a>
        <a href="/logout">Cerrar Sesión</a>
    </div>
</nav>

<div class="container">

    <!-- MENSAJE -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert success">
            <?= esc($_GET['success']) ?>
        </div>
    <?php endif; ?>

    <!-- 🔹 Reservas Activas -->
    <div class="section">
        <h2>Reservas Activas</h2>

        <?php if (!empty($activas)): ?>
            <div class="reservations-grid">

                <?php foreach ($activas as $reserva): ?>
                    <?php $total = $reserva['cantidad_asientos'] * $reserva['costo_espacio']; ?>

                    <div class="reservation-card">

                        <div class="card-header <?= esc($reserva['estado']) ?>">
                            <h3><?= esc($reserva['ride_nombre']) ?></h3>
                            <span class="badge badge-<?= esc($reserva['estado']) ?>">
                                <?= ucfirst($reserva['estado']) ?>
                            </span>
                        </div>

                        <div class="card-body">

                            <div class="info-row">
                                <strong>Ruta:</strong>
                                <?= esc($reserva['origen']) ?> → <?= esc($reserva['destino']) ?>
                            </div>

                            <div class="info-row">
                                <strong>Fecha:</strong>
                                <?= date('d/m/Y', strtotime($reserva['fecha_viaje'])) ?>
                                a las <?= date('H:i', strtotime($reserva['hora_viaje'])) ?>
                            </div>

                            <div class="info-row">
                                <strong>Asientos:</strong> <?= esc($reserva['cantidad_asientos']) ?>
                            </div>

                            <div class="info-row">
                                <strong>Total:</strong>
                                <span class="price">₡<?= number_format($total, 0) ?></span>
                            </div>

                            <?php if ($reserva['estado'] === 'aceptada'): ?>
                                <div class="driver-info">
                                    <strong>Chofer:</strong>
                                    <?= esc($reserva['chofer_nombre'] . ' ' . $reserva['chofer_apellido']) ?><br>
                                    <strong>Teléfono:</strong>
                                    <?= esc($reserva['chofer_telefono']) ?><br>

                                    <?php if (!empty($reserva['placa'])): ?>
                                        <strong>Vehículo:</strong>
                                        <?= esc($reserva['marca'] . ' ' . $reserva['modelo']) ?>
                                        - <?= esc($reserva['placa']) ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="card-footer">
                            <a href="/mis-reservas/cancelar/<?= esc($reserva['id']) ?>"
                               class="btn btn-danger"
                               onclick="return confirm('¿Deseas cancelar esta reserva?')">
                                Cancelar Reserva
                            </a>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>

        <?php else: ?>
            <div class="no-data">
                No tienes reservas activas.
                <a href="/buscar_rides">Buscar rides</a>
            </div>
        <?php endif; ?>

    </div>


    <!-- 🔹 Historial -->
    <div class="section">
        <h2>Historial de Reservas</h2>

        <?php if (!empty($pasadas)): ?>
            <table>
                <thead>
                <tr>
                    <th>Ride</th>
                    <th>Fecha</th>
                    <th>Asientos</th>
                    <th>Estado</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($pasadas as $reserva): ?>
                    <tr>
                        <td><?= esc($reserva['ride_nombre']) ?></td>
                        <td><?= date('d/m/Y', strtotime($reserva['fecha_viaje'])) ?></td>
                        <td><?= esc($reserva['cantidad_asientos']) ?></td>
                        <td>
                            <span class="badge badge-<?= esc($reserva['estado']) ?>">
                                <?= ucfirst($reserva['estado']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>

        <?php else: ?>
            <div class="no-data">No hay historial de reservas.</div>
        <?php endif; ?>

    </div>

</div>

</body>
</html>

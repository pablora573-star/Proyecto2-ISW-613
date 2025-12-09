<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/nav.css" rel="stylesheet">
    <link href="/css/dashboard_chofer.css" rel="stylesheet">
    <link href="/css/tables.css" rel="stylesheet">
    <title>Dashboard Chofer - Aventones</title>
   
</head>
<body>

<nav>
    <h2>Aventones - Dashboard Chofer</h2>
    <div class="nav-links">
        <a href="/user/edit" class="btn btn-edit">Editar Perfil</a>
        <a href="/vehiculos">Mis Vehículos</a>
        <a href="/logout">Cerrar Sesión</a>
    </div>
</nav>

<div class="container">
    <div class="welcome">

        <?php if (!empty($foto) && file_exists(FCPATH . $foto)): ?>
            <img src="<?= base_url($foto) ?>" class="foto-perfil">
        <?php else: ?>
            <img src="<?= base_url('images/default_user.png') ?>" class="foto-perfil">
        <?php endif; ?>

        <h1>Bienvenido, <?= esc($nombre . ' ' . $apellido) ?>!</h1>
        <p>Gestiona tus rides y vehículos desde este panel.</p>
    </div>

    <!-- ===== MIS RIDES ===== -->
    <div class="section">
        <h2>Mis Rides</h2>

        <a href="/rides/crear" class="btn">Crear Nuevo Ride</a>

        <?php if (!empty($rides)): ?>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Origen → Destino</th>
                    <th>Fecha y Hora</th>
                    <th>Vehículo</th>
                    <th>Costo</th>
                    <th>Espacios</th>
                    <th>Acciones</th>
                </tr>

                <?php foreach ($rides as $ride): ?>
                <tr>
                    <td><?= esc($ride['nombre']) ?></td>
                    <td>
                        <strong><?= esc($ride['origen']) ?></strong> →
                        <?= esc($ride['destino']) ?>
                    </td>

                    <td>
                        <?= date('d/m/Y', strtotime($ride['fecha_viaje'])) ?><br>
                        <?= date('H:i', strtotime($ride['hora_viaje'])) ?>
                    </td>

                    <td>
                        <?php if (!empty($ride['placa'])): ?>
                            <?= esc($ride['marca'] . ' ' . $ride['modelo']) ?><br>
                            <small><?= esc($ride['placa']) ?></small>
                        <?php else: ?>
                            <em>Sin vehículo</em>
                        <?php endif; ?>
                    </td>

                    <td>₡<?= number_format($ride['costo_espacio'], 0) ?></td>
                    <td><?= esc($ride['cantidad_espacios']) ?></td>

                    <td>
                        <a href="<?= ('/rides/edit/' . $ride['id']) ?>">Editar</a>
                        <a href="<?= ('/rides/eliminar/' . $ride['id']) ?>"
                           class="delete"
                           onclick="return confirm('¿Eliminar este ride?')">
                           Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>

            </table>
        <?php else: ?>
            <div class="no-data">No tienes rides registrados.</div>
        <?php endif; ?>
    </div>
</div>

<!-- ===== RESERVACIONES ===== -->
<div class="section">
    <h2>Reservaciones Recibidas</h2>

    <?php if (!empty($reservas)): ?>
        <table>
            <tr>
                <th>Pasajero</th>
                <th>Ride</th>
                <th>Fecha y Hora</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

            <?php foreach ($reservas as $res): ?>
                <tr>
                    <td><?= esc($res['nombre_pasajero'] . ' ' . $res['apellido_pasajero']) ?></td>

                    <td>
                        <strong><?= esc($res['nombre_ride']) ?></strong>
                        <?= esc($res['origen']) ?> → <?= esc($res['destino']) ?>
                    </td>

                    <td>
                        <?= date('d/m/Y', strtotime($res['fecha_viaje'])) ?><br>
                        <?= date('H:i', strtotime($res['hora_viaje'])) ?>
                    </td>

                    <td><strong><?= ucfirst($res['estado']) ?></strong></td>

                    <td>
                        <a href="<?= base_url('chofer/reservas/aceptar/' . $res['reserva_id']) ?>"
                           class="btn btn-accept">
                           Aceptar
                        </a>

                        <a href="<?= base_url('chofer/reservas/rechazar/' . $res['reserva_id']) ?>"
                           class="btn btn-reject">
                           Rechazar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php else: ?>
        <div class="no-data">No tienes reservaciones por ahora.</div>
    <?php endif; ?>

</div>

</body>
</html>

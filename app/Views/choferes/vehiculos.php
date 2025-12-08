<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/vehiculo.css" rel="stylesheet">
    <title>Mis Vehículos - Aventones</title>
</head>

<body>

<nav>
    <h2>Aventones - Mis Vehículos</h2>
    <div class="nav-links">
        <a href="/dashboard/chofer">Dashboard</a>
        <a href="/logout">Cerrar Sesión</a>
    </div>
</nav>

<div class="container">

    <h2>Mis Vehículos</h2>

    <!-- Mensajes -->
    <?php if (session('success')): ?>
        <div class="success-message"><?= session('success') ?></div>
    <?php endif ?>

    <?php if (session('error')): ?>
        <div class="error-message"><?= session('error') ?></div>
    <?php endif ?>

    <a href="vehiculo/crear" class="btn">Registrar Nuevo Vehículo</a>

    <?php if (!empty($vehiculos)): ?>
    <table>
        <tr>
            <th>Foto</th>
            <th>Placa</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Color</th>
            <th>Capacidad</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($vehiculos as $v): ?>
        <tr>
            <td>
                <?php if (!empty($v['foto'])): ?>
                    <img src="<?= base_url('uploads/vehiculos/' . $v['foto']) ?>"
                         alt="Foto"
                         style="width:80px;height:60px;border-radius:4px;">
                <?php else: ?>
                    <div style="width:80px;height:60px;background:#ddd;display:flex;align-items:center;justify-content:center;border-radius:4px;">Sin foto</div>
                <?php endif; ?>
            </td>
            <td><strong><?= esc($v['placa']) ?></strong></td>
            <td><?= esc($v['marca']) ?></td>
            <td><?= esc($v['modelo']) ?></td>
            <td><?= esc($v['anio']) ?></td>
            <td><?= esc($v['color']) ?></td>
            <td><?= esc($v['capacidad_asientos']) ?> asientos</td>

            <td>
                <a href="<?= site_url('/vehiculo/editar' . $v['id']) ?>">Editar</a>
                <a href="<?= site_url('/vehiculo/eliminar' . $v['id']) ?>"
                   onclick="return confirm('¿Eliminar este vehículo?')"
                   class="delete">Eliminar</a>
            </td>
        </tr>
        <?php endforeach ?>

    </table>

    <?php else: ?>

        <div class="no-data">
            No tienes vehículos registrados aún. ¡Registra tu primer vehículo!
        </div>

    <?php endif ?>

</div>

</body>
</html>

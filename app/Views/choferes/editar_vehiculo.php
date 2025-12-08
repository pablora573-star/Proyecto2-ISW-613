<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/registration.css" rel="stylesheet">
    <link href="/css/crear_vehiculo.css" rel="stylesheet">
    <title>Editar Vehículo - Aventones</title>
    
</head>

<body>

<nav>
    <h2>Aventones - Editar Vehículo</h2>
    <div class="nav-links">
        <a href="<?= site_url('vehiculos') ?>">← Volver a Vehículos</a>
    </div>
</nav>

<div class="container">
    <div class="form-container">
        <h1>Editar Vehículo</h1>

        <?php if (!empty($vehiculo['foto'])): ?>
        <div class="current-photo">
            <p><strong>Foto actual:</strong></p>
            <img src="<?= base_url('uploads/vehiculos/' . $vehiculo['foto']) ?>" alt="Foto del vehículo">
        </div>
        <?php endif; ?>

        <form action="<?= site_url('vehiculo/actualizar') ?>" method="post" enctype="multipart/form-data">

            <input type="hidden" name="vehicle_id" value="<?= $vehiculo['id'] ?>">

            <label for="placa">Placa:</label>
            <input type="text" name="placa" required value="<?= esc($vehiculo['placa']) ?>">

            <label for="color">Color:</label>
            <input type="text" name="color" required value="<?= esc($vehiculo['color']) ?>">

            <label for="marca">Marca:</label>
            <input type="text" name="marca" required value="<?= esc($vehiculo['marca']) ?>">

            <label for="modelo">Modelo:</label>
            <input type="text" name="modelo" required value="<?= esc($vehiculo['modelo']) ?>">

            <label for="anio">Año:</label>
            <input type="number" name="anio" min="1900" max="2025" required value="<?= $vehiculo['anio'] ?>">

            <label for="capacidad_asientos">Capacidad de Asientos:</label>
            <input type="number" name="capacidad_asientos" min="1" max="50" required value="<?= $vehiculo['capacidad_asientos'] ?>">

            <label for="foto">Nueva Foto (opcional):</label>
            <input type="file" name="foto" accept="image/*">

            <p class="photo-note">* Deja vacío si no deseas cambiar la foto actual</p>

            <button type="submit">Actualizar Vehículo</button>
        </form>

    </div>
</div>

</body>
</html>

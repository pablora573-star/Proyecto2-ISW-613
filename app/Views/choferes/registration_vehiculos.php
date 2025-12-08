<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/registration.css" rel="stylesheet">
    <link href="/css/crear_vehiculo.css" rel="stylesheet">
    <title>Crear Vehículo</title>
</head>

<body>

<nav>
    <h2>Aventones</h2>
    <div class="nav-links">
        <a href="<?= site_url('vehiculos') ?>">← Volver</a>
    </div>
</nav>

<div class="container">
    <h1>Registrar Nuevo Vehículo</h1>

    <!-- Mostrar errores -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div style="color:red">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <form action="<?= site_url('vehiculo/store') ?>" method="post" enctype="multipart/form-data">

        <label>Placa:</label>
        <input type="text" name="placa" required><br>

        <label>Color:</label>
        <input type="text" name="color" required><br>

        <label>Marca:</label>
        <input type="text" name="marca" required><br>

        <label>Modelo:</label>
        <input type="text" name="modelo" required><br>

        <label>Año:</label>
        <input type="number" name="anio" min="1900" max="2025" required><br>

        <label>Capacidad de Asientos:</label>
        <input type="number" name="capacidad_asientos" min="1" max="8" required><br>

        <label>Fotografía del Vehículo:</label>
        <input type="file" name="foto" accept="image/*" required><br>

        <button type="submit">Registrar Vehículo</button>
    </form>
</div>

</body>
</html>

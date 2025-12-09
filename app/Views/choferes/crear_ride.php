<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/nav.css" rel="stylesheet">
    <link href="/css/forms.css" rel="stylesheet">
    <link href="/css/crear_ride.css" rel="stylesheet">
    <title>Crear Ride - Aventones</title>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const vehicleSelect = document.getElementById("vehicle_id");
            const espaciosInput = document.getElementById("cantidad_espacios");

            vehicleSelect.addEventListener("change", function () {
                const selected = vehicleSelect.options[vehicleSelect.selectedIndex];
                const capacidad = selected.getAttribute("data-capacidad");

                espaciosInput.value = capacidad ? capacidad : 1;
            });
        });
    </script>

</head>

<body>

<nav>
    <h2>Aventones - Crear Ride</h2>
    <div class="nav-links">
        <a href="/dashboard/chofer">← Volver al Dashboard</a>
    </div>
</nav>

<div class="container">
    <div class="form-container">

        <h1>Crear Ride</h1>

        <?php if (session('error')): ?>
            <div class="error-message"><?= session('error') ?></div>
        <?php endif; ?>

        <?php if (empty($vehiculos)): ?>
            <div class="warning">
                <strong>No tienes vehículos registrados.</strong><br>
                <a href="/vehiculos/registro">Registra un vehículo primero</a>
            </div>

        <?php else: ?>

        <form action="/rides/store" method="post">

            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Origen:</label>
            <input type="text" name="origen" required>

            <label>Destino:</label>
            <input type="text" name="destino" required>

            <label>Fecha:</label>
            <input type="date" name="fecha_viaje" min="<?= date('Y-m-d') ?>" required>

            <label>Hora:</label>
            <input type="time" name="hora_viaje" required>

            <label>Vehículo:</label>
            <select name="vehicle_id" id="vehicle_id" required>
                <option value="">Seleccione...</option>

                <?php foreach ($vehiculos as $v): ?>
                    <option 
                        value="<?= $v['id'] ?>"
                        data-capacidad="<?= $v['capacidad_asientos'] ?>">
                        <?= esc($v['marca'] . ' ' . $v['modelo']) ?> (<?= esc($v['placa']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Costo por espacio:</label>
            <input type="number" name="costo_espacio" min="0" step="100" required>

            <label>Cantidad de espacios:</label>
            <input type="number" name="cantidad_espacios" id="cantidad_espacios" min="1" max="12" required>

            <button type="submit">Crear Ride</button>

        </form>

        <?php endif; ?>

    </div>
</div>

</body>
</html>

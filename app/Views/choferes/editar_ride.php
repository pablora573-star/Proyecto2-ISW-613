<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/nav.css" rel="stylesheet">
    <link href="/css/forms.css" rel="stylesheet">
    <link href="/css/editar_ride.css" rel="stylesheet">
    <title>Editar Ride - Aventones</title>
    
</head>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const vehicleSelect = document.getElementById("vehicle_id");
        const espaciosInput = document.getElementById("cantidad_espacios");

        vehicleSelect.addEventListener("change", function () {
            const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
            const capacidad = selectedOption.getAttribute("data-capacidad");

            if (capacidad) {
                espaciosInput.value = capacidad;
            } else {
                espaciosInput.value = "1";
            }
        });
    });
</script>

<body>
<nav>
    <h2>Aventones - Editar Ride</h2>
    <div class="nav-links">
        <a href="/dashboard/chofer">← Volver al Dashboard</a>
    </div>
</nav>

<div class="container">
    <div class="form-container">
        <h1>Editar Ride</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="error-message">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?=('/rides/update/' . $ride['id'])?>" method="post">
            <input type="hidden" name="ride_id" value="<?= esc($ride['id']) ?>">

            <label for="nombre">Nombre del Ride:</label>
            <input type="text" id="nombre" name="nombre"
                   value="<?= esc($ride['nombre']) ?>" required>

            <label for="origen">Lugar de Salida (Origen):</label>
            <input type="text" id="origen" name="origen"
                   value="<?= esc($ride['origen']) ?>" required>

            <label for="destino">Lugar de Llegada (Destino):</label>
            <input type="text" id="destino" name="destino"
                   value="<?= esc($ride['destino']) ?>" required>

            <div class="row">
                <div class="col-2">
                    <label for="fecha_viaje">Fecha del Viaje:</label>
                    <input type="date" id="fecha_viaje" name="fecha_viaje"
                           value="<?= esc($ride['fecha_viaje']) ?>" required>
                </div>

                <div class="col-2">
                    <label for="hora_viaje">Hora del Viaje:</label>
                    <input type="time" id="hora_viaje" name="hora_viaje"
                           value="<?= esc($ride['hora_viaje']) ?>" required>
                </div>
            </div>

            <label for="vehicle_id">Vehículo:</label>
            <select id="vehicle_id" name="vehicle_id" required>
                <option value="">Selecciona un vehículo</option>

                <?php foreach ($vehiculos as $vehicle): ?>
                    <option value="<?= $vehicle['id'] ?>"
                            data-capacidad="<?= $vehicle['capacidad_asientos'] ?>"
                        <?= $vehicle['id'] == $ride['vehicle_id'] ? 'selected' : '' ?>>
                        <?= esc($vehicle['marca'] . ' ' . $vehicle['modelo']) ?>
                        (<?= esc($vehicle['placa']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="row">
                <div class="col-2">
                    <label for="costo_espacio">Costo por Espacio (₡):</label>
                    <input type="number" id="costo_espacio" name="costo_espacio"
                           value="<?= esc($ride['costo_espacio']) ?>"
                           min="0" step="100" required>
                </div>

                <div class="col-2">
                    <label for="cantidad_espacios">Cantidad de Espacios:</label>
                    <input type="number" id="cantidad_espacios" name="cantidad_espacios"
                           value="<?= esc($ride['cantidad_espacios']) ?>"
                           min="1" max="10" required>
                </div>
            </div>

            <button type="submit">Actualizar Ride</button>
        </form>
    </div>
</div>

</body>
</html>

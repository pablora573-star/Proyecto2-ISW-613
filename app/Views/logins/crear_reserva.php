<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('css/variables.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/nav.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/forms.css') ?>" rel="stylesheet">
   
    <title>Crear Reserva - Aventones</title>
   
</head>
<body>

<nav>
    <h2>Aventones - Crear Reserva</h2>
    <div class="nav-links">
        <a href="/buscar_rides">← Volver a Búsqueda</a>
        <a href="/dashboard/pasajero">Dashboard</a>
    </div>
</nav>

<div class="container">
    <div class="reservation-container">

        <h1>Confirmar Reserva</h1>

        <div class="ride-summary">

            <h2>Detalles del Viaje</h2>

            <div class="detail-row">
                <strong>Ride:</strong>
                <span><?= esc($ride['nombre']) ?></span>
            </div>

            <div class="detail-row route">
                <div>
                    <strong>Origen:</strong>
                    <span><?= esc($ride['origen']) ?></span>
                </div>
                <div class="arrow">→</div>
                <div>
                    <strong>Destino:</strong>
                    <span><?= esc($ride['destino']) ?></span>
                </div>
            </div>

            <div class="detail-row">
                <strong>Fecha:</strong>
                <span><?= date('d/m/Y', strtotime($ride['fecha_viaje'])) ?></span>
            </div>

            <div class="detail-row">
                <strong>Hora:</strong>
                <span><?= date('H:i', strtotime($ride['hora_viaje'])) ?></span>
            </div>

            <div class="detail-row">
                <strong>Vehículo:</strong>
                <span><?= esc($ride['marca'] . ' ' . $ride['modelo'] . ' (' . $ride['anio'] . ')') ?></span>
            </div>

            <div class="detail-row">
                <strong>Costo por asiento:</strong>
                <span class="price">₡<?= number_format($ride['costo_espacio'], 0) ?></span>
            </div>

            <div class="detail-row">
                <strong>Asientos disponibles:</strong>
                <span class="<?= $asientos_disponibles > 0 ? 'available' : 'full' ?>">
                    <?= $asientos_disponibles ?> de <?= $ride['cantidad_espacios'] ?>
                </span>
            </div>

        </div>

        <?php if ($yaReservado): ?>

            <div class="alert warning">
                Ya tienes una reserva activa para este ride.
                <a href="/mis_reservas_pasajero">Ver mis reservas</a>
            </div>

        <?php elseif ($asientos_disponibles <= 0): ?>

            <div class="alert error">
                Este ride ya no tiene asientos disponibles.
            </div>

            <a href="/buscar_rides" class="btn btn-secondary">Buscar otros rides</a>

        <?php else: ?>

            <form action="/reservar" method="POST" class="reservation-form">

                <input type="hidden" name="ride_id" value="<?= esc($ride['id']) ?>">
                <input type="hidden" name="chofer_id" value="<?= esc($ride['user_id']) ?>">

                <label for="cantidad_asientos">¿Cuántos asientos deseas reservar?</label>
                <select name="cantidad_asientos" id="cantidad_asientos" required onchange="calcularTotal()">
                    <option value="">Selecciona cantidad</option>

                    <?php for ($i = 1; $i <= min(4, $asientos_disponibles); $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> asiento<?= $i > 1 ? 's' : '' ?></option>
                    <?php endfor; ?>

                </select>

                <div class="total-container">
                    <strong>Total a pagar:</strong>
                    <span id="total" class="total-price">₡0</span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Confirmar Reserva</button>
                    <a href="/buscar_rides" class="btn btn-secondary">Cancelar</a>
                </div>

            </form>

            <script>
                const costoEspacio = <?= $ride['costo_espacio'] ?>;

                function calcularTotal() {
                    const cantidad = document.getElementById('cantidad_asientos').value;
                    const total = cantidad * costoEspacio;
                    document.getElementById('total').textContent = '₡' + total.toLocaleString('es-CR');
                }
            </script>

        <?php endif; ?>

    </div>
</div>

</body>
</html>

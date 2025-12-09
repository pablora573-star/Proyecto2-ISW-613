<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('css/variables.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/nav.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/buscar_ride.css') ?>" rel="stylesheet">

    <title>Buscar Rides - Aventones</title>
   
</head>

<body>

<nav>
    <h2>Aventones - Buscar Rides</h2>

    <div class="nav-links">
        <?php if (session()->get('user_id')): ?>

            <?php if (session()->get('rol') === 'pasajero'): ?>
                <a href="/dashboard/pasajero">Dashboard</a>
                <a href="/mis-reservas">Mis Reservas</a>
            <?php else: ?>
                <a href="/dashboard/chofer">Dashboard</a>
            <?php endif; ?>

            <a href="/logout">Cerrar Sesión</a>

        <?php else: ?>
            <a href="/">Iniciar Sesión</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">

    <!-- Formulario de búsqueda -->
    <div class="search-section">
        <h1>Buscar Rides Disponibles</h1>

        <form method="GET" action="/buscar_rides" class="search-form">

            <div class="search-inputs">
                <input type="text" 
                       name="origen" 
                       placeholder="¿Desde dónde?"
                       value="<?= esc($origen) ?>">

                <input type="text" 
                       name="destino" 
                       placeholder="¿Hacia dónde?"
                       value="<?= esc($destino) ?>">

                <button type="submit">Buscar</button>
            </div>

            <div class="sort-options">
                <label>Ordenar por:</label>

                <select name="orden" onchange="this.form.submit()">
                    <option value="fecha_asc"   <?= $orden === 'fecha_asc' ? 'selected' : '' ?>>Fecha (Más próximo)</option>
                    <option value="fecha_desc"  <?= $orden === 'fecha_desc' ? 'selected' : '' ?>>Fecha (Más lejano)</option>
                    <option value="origen_asc"  <?= $orden === 'origen_asc' ? 'selected' : '' ?>>Origen (A-Z)</option>
                    <option value="origen_desc" <?= $orden === 'origen_desc' ? 'selected' : '' ?>>Origen (Z-A)</option>
                    <option value="destino_asc" <?= $orden === 'destino_asc' ? 'selected' : '' ?>>Destino (A-Z)</option>
                    <option value="destino_desc"<?= $orden === 'destino_desc' ? 'selected' : '' ?>>Destino (Z-A)</option>
                </select>
            </div>

        </form>
    </div>

    <!-- Resultados -->
    <div class="results-section">

        <?php if (!empty($rides)): ?>
            <h2>Rides Disponibles (<?= count($rides) ?>)</h2>

            <div class="rides-grid">

                <?php foreach ($rides as $ride): ?>

                    <?php 
                        $asientos_disponibles = 
                            $ride['cantidad_espacios'] - $ride['asientos_reservados']; 
                    ?>

                    <div class="ride-card">

                        <div class="ride-header">
                            <h3><?= esc($ride['nombre']) ?></h3>
                            <span class="ride-price">₡<?= number_format($ride['costo_espacio'], 0) ?></span>
                        </div>

                        <div class="ride-route">
                            <div class="location">
                                <strong>Origen:</strong>
                                <?= esc($ride['origen']) ?>
                            </div>

                            <div class="arrow">→</div>

                            <div class="location">
                                <strong>Destino:</strong>
                                <?= esc($ride['destino']) ?>
                            </div>
                        </div>

                        <div class="ride-info">
                            <div class="info-item">
                                <strong>Fecha:</strong>
                                <?= date('d/m/Y', strtotime($ride['fecha_viaje'])) ?>
                            </div>

                            <div class="info-item">
                                <strong>Hora:</strong>
                                <?= date('H:i', strtotime($ride['hora_viaje'])) ?>
                            </div>
                        </div>

                        <div class="ride-vehicle">
                            <strong>Vehículo:</strong>
                            <?= esc($ride['marca'] . ' ' . $ride['modelo'] . ' (' . $ride['anio'] . ')') ?>
                        </div>

                        <div class="ride-seats">
                            <strong>Asientos:</strong>
                            <span class="<?= $asientos_disponibles > 0 ? 'available' : 'full' ?>">
                                <?= $asientos_disponibles ?> disponibles de <?= $ride['cantidad_espacios'] ?>
                            </span>
                        </div>

                        <?php if (session()->get('rol') === 'pasajero' && $asientos_disponibles > 0): ?>
                            <a href="/crear_reserva/<?= esc($ride['id']) ?>" class="btn-reserve">
                                Reservar Ahora
                            </a>

                        <?php elseif (!session()->get('user_id')): ?>
                            <a href="/" class="btn-login">
                                Inicia sesión para reservar
                            </a>

                        <?php elseif ($asientos_disponibles <= 0): ?>
                            <button class="btn-full" disabled>
                                Sin Disponibilidad
                            </button>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="no-results">
                <h2>No se encontraron rides</h2>
                <p>Intenta con otros criterios de búsqueda.</p>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>

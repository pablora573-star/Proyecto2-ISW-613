<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/nav.css" rel="stylesheet">
    <link href="/css/tables.css" rel="stylesheet">
    <link href="/css/dashboard_pasajero.css" rel="stylesheet">
    <title>Dashboard Pasajero - Aventones</title>
    
</head>
<body>

<nav>
    <h2>Aventones - Dashboard Pasajero</h2>

    <div class="nav-links">
        <a href="/perfil/editar">Editar Perfil</a>
        <a href="/rides/buscar">Buscar Rides</a>
        <a href="/reservas/mias">Mis Reservas</a>
        <a href="/logout">Cerrar Sesión</a>
    </div>
</nav>

<div class="container">

    <div class="welcome">
        <img src="<?= !empty($session['foto']) ? base_url($session['foto']) : base_url('images/default_user.png') ?>" 
             class="foto-perfil">
        
        <h1>Bienvenido, <?= esc($session['nombre']) . ' ' . esc($session['apellido']) ?>!</h1>
        <p>Gestiona tus reservas y encuentra nuevos rides desde este panel.</p>
    </div>

    <!-- Estadísticas -->
    <div class="stats-section">
        <h2>Mis Estadísticas</h2>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $estadisticas['total_reservas'] ?? 0 ?></div>
                <div class="stat-label">Total Reservas</div>
            </div>

            <div class="stat-card">
                <div class="stat-value"><?= $estadisticas['pendientes'] ?? 0 ?></div>
                <div class="stat-label">Pendientes</div>
            </div>

            <div class="stat-card">
                <div class="stat-value"><?= $estadisticas['aceptadas'] ?? 0 ?></div>
                <div class="stat-label">Aceptadas</div>
            </div>

            <div class="stat-card">
                <div class="stat-value"><?= $estadisticas['rechazadas'] ?? 0 ?></div>
                <div class="stat-label">Rechazadas</div>
            </div>
        </div>
    </div>

    <!-- Próximas Reservas -->
    <div class="section">
        <h2>Próximas Reservas Confirmadas</h2>

        <?php if (!empty($proximas)): ?>
            <div class="reservations-grid">

                <?php foreach ($proximas as $r): ?>
                    <div class="reservation-card">

                        <h3><?= esc($r['ride_nombre']) ?></h3>

                        <p><strong>Origen:</strong> <?= esc($r['origen']) ?></p>
                        <p><strong>Destino:</strong> <?= esc($r['destino']) ?></p>
                        <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($r['fecha_viaje'])) ?></p>
                        <p><strong>Hora:</strong> <?= date('H:i', strtotime($r['hora_viaje'])) ?></p>

                        <p><strong>Chofer:</strong> 
                            <?= esc($r['chofer_nombre'] . ' ' . $r['chofer_apellido']) ?>
                        </p>

                        <p><strong>Asientos:</strong> <?= $r['cantidad_asientos'] ?></p>
                    </div>
                <?php endforeach; ?>

            </div>

        <?php else: ?>
            <p>No tienes reservas confirmadas próximamente.</p>
        <?php endif; ?>
    </div>

</div>
</body>
</html>

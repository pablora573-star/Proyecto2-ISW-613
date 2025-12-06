<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/nav.css" rel="stylesheet">
    <link href="/css/buscar_rides.css" rel="stylesheet">
    <title>Buscar Rides - Aventones</title>

</head>
<body>
    <nav>
        <h2>Aventones - Buscar Rides</h2>
        <div class="nav-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['rol'] === 'pasajero'): ?>
                    <a href="../pages/dashboard_pasajero.php">Dashboard</a>
                    <a href="../pages/mis_reservas_pasajero.php">Mis Reservas</a>
                <?php else: ?>
                    <a href="../pages/dashboard_chofer.php">Dashboard</a>
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
            <form method="GET" class="search-form">
                <div class="search-inputs">
                    <input type="text" name="origen" placeholder="¿Desde dónde?" 
                           value="<?= htmlspecialchars($origen) ?>">
                    <input type="text" name="destino" placeholder="¿Hacia dónde?" 
                           value="<?= htmlspecialchars($destino) ?>">
                    <button type="submit">Buscar</button>
                </div>
                
                <div class="sort-options">
                    <label>Ordenar por:</label>
                    <select name="orden" onchange="this.form.submit()">
                        <option value="fecha_asc" <?= $orden === 'fecha_asc' ? 'selected' : '' ?>>
                            Fecha (Más próximo)
                        </option>
                        <option value="fecha_desc" <?= $orden === 'fecha_desc' ? 'selected' : '' ?>>
                            Fecha (Más lejano)
                        </option>
                        <option value="origen_asc" <?= $orden === 'origen_asc' ? 'selected' : '' ?>>
                            Origen (A-Z)
                        </option>
                        <option value="origen_desc" <?= $orden === 'origen_desc' ? 'selected' : '' ?>>
                            Origen (Z-A)
                        </option>
                        <option value="destino_asc" <?= $orden === 'destino_asc' ? 'selected' : '' ?>>
                            Destino (A-Z)
                        </option>
                        <option value="destino_desc" <?= $orden === 'destino_desc' ? 'selected' : '' ?>>
                            Destino (Z-A)
                        </option>
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
                        <div class="ride-card">
                            <div class="ride-header">
                                <h3><?= esc($ride['nombre']) ?></h3>
                                <span class="ride-price">₡<?= number_format($ride['costo_espacio'], 0) ?></span>
                            </div>

                            <div class="ride-route">
                                <div class="location">
                                    <strong>Origen:</strong>
                                    <span><?= esc($ride['origen']) ?></span>
                                </div>
                                <div class="arrow">→</div>
                                <div class="location">
                                    <strong>Destino:</strong>
                                    <span><?= esc($ride['destino']) ?></span>
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

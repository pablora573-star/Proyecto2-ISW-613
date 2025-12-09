<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/nav.css" rel="stylesheet">
    <link href="/css/tables.css" rel="stylesheet">
    <link href="/css/admin.css" rel="stylesheet">
    <link href="/css/dashboard_admin.css" rel="stylesheet">
    <title>Dashboard Admin - Aventones</title>
    
</head>
<body>

    <nav>
        <h2>Aventones - Dashboard Admin</h2>
        <div class="nav-links">
            <a href="/admin/crear">Nuevo Usuario</a>
            <a href="/user/edit" class="btn btn-edit">Editar Perfil</a>
            <a href="/logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">

        <div class="welcome">

            <?php if (!empty($user['foto']) && file_exists(FCPATH . $user['foto'])): ?>
                <img src="<?= base_url($user['foto']) ?>" class="foto-perfil">
            <?php else: ?>
                <img src="<?= base_url('images/default_user.png') ?>" class="foto-perfil">
            <?php endif; ?>

            <h1>Bienvenido, <?= esc($user['nombre'].' '.$user['apellido']) ?>!</h1>
            <p>Gestiona todos los usuarios de la plataforma desde este panel.</p>
        </div>

        <div class="filters">
            <form method="GET" class="filter-form">

                <div class="filter-group">
                    <label>Filtrar por Rol:</label>
                    <select name="rol" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="administrador" <?= $filtroRol === 'administrador' ? 'selected' : '' ?>>Admin</option>
                        <option value="chofer" <?= $filtroRol === 'chofer' ? 'selected' : '' ?>>Chofer</option>
                        <option value="pasajero" <?= $filtroRol === 'pasajero' ? 'selected' : '' ?>>Pasajero</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Filtrar por Estado:</label>
                    <select name="estado" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="activa" <?= $filtroEstado === 'activa' ? 'selected' : '' ?>>Activo</option>
                        <option value="pendiente" <?= $filtroEstado === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="inactiva" <?= $filtroEstado === 'inactiva' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <?php if ($filtroRol || $filtroEstado): ?>
                    <a href="/dashboard/admin" class="btn-clear">Limpiar Filtros</a>
                <?php endif; ?>

            </form>
        </div>

        <div class="section">
            <h2>Gestión de Usuarios (<?= count($usuarios) ?>)</h2>

            <?php if (!empty($usuarios)): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Cédula</th>
                                <th>Fecha Nac.</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td class="td-foto">
                                        <?php 
                                            $rutaFoto = !empty($u['foto_url']) && file_exists(FCPATH.$u['foto_url']) 
                                                ? base_url($u['foto_url']) 
                                                : base_url('images/default_user.png');
                                        ?>
                                        <img src="<?= $rutaFoto ?>" class="foto-perfil">
                                    </td>

                                    <td><?= esc($u['nombre']) ?></td>
                                    <td><?= esc($u['apellido']) ?></td>
                                    <td><?= esc($u['cedula']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($u['fecha_nacimiento'])) ?></td>
                                    <td><?= esc($u['correo']) ?></td>

                                    <td><span class="badge badge-<?= esc($u['rol']) ?>"><?= ucfirst($u['rol']) ?></span></td>
                                    <td><span class="badge badge-estado-<?= esc($u['estado']) ?>"><?= ucfirst($u['estado']) ?></span></td>

                                    <td class="actions">
                                        <?php if ($u['id'] != $user['id']): ?>
                                            <?php if ($u['estado'] === 'activa'): ?>
                                                <a href="<?= ('/admin/cambiarEstado/'.$u['id'].'/inactiva') ?>" class="btn-action btn-deactivate">Desactivar</a>
                                            <?php else: ?>
                                                <a href="<?= ('/admin/cambiarEstado/'.$u['id'].'/activa') ?>" class="btn-action btn-activate">Activar</a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Tú</span>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">No se encontraron usuarios con los filtros aplicados.</div>
            <?php endif; ?>

        </div>

    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/nav.css" rel="stylesheet">
    <link href="/css/forms.css" rel="stylesheet">
    <link href="<?='/css/' . $stylesrol . '.css'?>" rel="stylesheet">
    <title>Editar Perfil - Aventones</title>
   
    <style>
    .foto-perfil {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #000;
      margin-bottom: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,.15);
      transition: transform .3s;
    }
    .foto-perfil:hover {
      transform: scale(1.05);
    }
    </style>
</head>

<body>

<nav>
    <h2>Aventones - Editar Perfil</h2>

    <div class="nav-links">
        <a href="<?= $dashboardUrl ?>">← Volver al Dashboard</a>
        <a href="/logout">Cerrar Sesión</a>
    </div>
</nav>


<div class="container">
    <h1>Editar mi Perfil</h1>
    <p class="subtitle">Actualiza tu información personal como 
        <span class="badge <?= $badgeClass ?>"><?= strtoupper($rol) ?></span></p>


    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert error">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>


    <form action="/user/update/<?= $usuario['id'] ?>"  method="post" enctype="multipart/form-data" class="edit-form">
        
        <!-- Datos personales -->
        <div class="form-section">
            <h2>Información Personal</h2>

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre:</label>
                    <input type="text" name="nombre" value="<?= esc($usuario['nombre']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Apellido:</label>
                    <input type="text" name="apellido" value="<?= esc($usuario['apellido']) ?>" required>
                </div>
            </div>

            <div class="form-group readonly">
                <label>Cédula:</label>
                <input type="text" value="<?= esc($usuario['cedula']) ?>" readonly disabled>
            </div>

            <div class="form-group">
                <label>Fecha de nacimiento:</label>
                <input type="date" name="fecha_nacimiento" value="<?= esc($usuario['fecha_nacimiento']) ?>" required>
            </div>

            <div class="form-group">
                <label>Correo:</label>
                <input type="email" name="correo" value="<?= esc($usuario['correo']) ?>" required>
            </div>

            <div class="form-group">
                <label>Teléfono:</label>
                <input type="tel" name="telefono" value="<?= esc($usuario['telefono']) ?>" required>
            </div>
        </div>


        <!-- Foto -->
        <div class="form-section">
            <h2>Fotografía Personal</h2>

            <div class="foto-preview">
                <img src="<?= base_url($usuario['foto_url'] ?: 'images/default_user.png') ?>" 
                     alt="Foto actual" class="foto-perfil" id="preview-img">
            </div>

            <div class="form-group">
                <label>Cambiar fotografía:</label>
                <input type="file" name="foto" accept="image/*">
            </div>
        </div>


        <!-- Contraseña -->
        <div class="form-section">
            <h2>Cambiar Contraseña (Opcional)</h2>

            <div class="form-group">
                <label>Contraseña actual:</label>
                <input type="password" name="current_password">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nueva contraseña:</label>
                    <input type="password" name="password">
                </div>

                <div class="form-group">
                    <label>Repetir nueva contraseña:</label>
                    <input type="password" name="password2">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="<?= $dashboardUrl ?>" class="btn btn-secondary">Cancelar</a>
        </div>

    </form>
</div>


<script>
document.getElementById('foto').addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
        }
        reader.readAsDataURL(this.files[0]);
    }
});
</script>

</body>
</html>

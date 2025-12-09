<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/registration.css" rel="stylesheet">
    <link href="/css/admin.css" rel="stylesheet">

    <title>Registro - Administrador</title>
</head>

<body>
    <nav>
        <h2>Aventones</h2>
        <div class="nav-links">
            <a href="/dashboard/admin">← Volver al Dashboard</a>
            <a href="/login">Volver al Login</a>
        </div>
    </nav>

    <div class="container">
        <h1>Registro de Usuario</h1>
        <p class="subtitle">Registrándose como: <span class="badge">Admin</span></p>
        
        <form action="/user/store" method="post" enctype="multipart/form-data">
           
            <!-- Campo oculto con el rol quemado -->
            <input type="hidden" name="rol" value="administrador">

            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="lastName">Apellido:</label>
            <input type="text" id="lastName" name="lastName" required>

            <label for="cedula">Número de cédula:</label>
            <input type="text" id="cedula" name="cedula" required>

            <label for="nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="nacimiento" name="nacimiento" required>

            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="telefono">Número de teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required>

            <label for="foto">Fotografía personal:</label>
            <input type="file" id="foto" name="foto" accept="image/*" required>
            <p class="file-info">Formatos aceptados: JPG, PNG, GIF (máx. 5MB)</p>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="password2">Repetir Contraseña:</label>
            <input type="password" id="password2" name="password2" required>

            <button type="submit">Registrar como administrador</button>
        </form>
    </div>
</body>

</html>
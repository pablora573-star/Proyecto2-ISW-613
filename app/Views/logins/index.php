
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/variables.css" rel="stylesheet">
    <link href="css/index_style.css" rel="stylesheet">

    <title>Login - Aventones</title>
    
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <h2>Aventones</h2>

        <?php if (!empty($error_message)): ?>
        <div class="error-alert">
            <?= htmlspecialchars($error_message) ?>
        </div>
        <?php endif; ?>

        <form action="/login/entrar" method="post">
            <input type="text" name="cedula" placeholder="Cedula" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        
        <div class="register-links">
           
            <p>¿No tienes cuenta?<br> Regístrate como:</p>

            <a href="/registro/pasajero" class="pasajero">Pasajero</a>

            <a href="/registro/chofer" class="chofer">Chofer</a>
       
            <a href="/buscar_rides" class="reserva">Viajes</a>

        </div>
    </div>
</body>
</html>

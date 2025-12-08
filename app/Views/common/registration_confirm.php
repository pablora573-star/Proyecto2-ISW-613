<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/css/variables.css" rel="stylesheet">
    <link href="/css/registration_confirm.css" rel="stylesheet">
    <title>Registro Exitoso</title>
</head>
<body>

<div class="container">
    <h1>¡Registro Exitoso!</h1>

    <?php if (isset($email)): ?>
        <p>Hemos enviado un correo de activación a:</p>
        <div><?= esc($email) ?></div>
        <p>Revisa tu bandeja de entrada y haz clic en el enlace.</p>
    <?php else: ?>
        <p>Tu registro se completó correctamente.</p>
    <?php endif; ?>

    <a href="/login">Ir al Login</a>
</div>

</body>
</html>

<?php
session_start();

// Si entran directamente sin mensaje, los mando al formulario
if (!isset($_SESSION['mensaje'])) {
    header("Location: login.html");
    exit;
}

$mensaje = $_SESSION['mensaje'];
unset($_SESSION['mensaje']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error de acceso</title>
    <style>
        body { background-color: wheat; padding: 3%; margin: 0; text-align: center; }
        .caja { max-width: 800px; margin: auto; padding: 5%; border: solid white; border-radius: 3%;
                background-color: rgb(173, 189, 83); font-size: 1.3rem; }
        p { color: darkred; font-weight: bold; }
    </style>
</head>
<body>
    <div class="caja">
        <h1>No se ha podido iniciar sesión</h1>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
        <a href="login.html">Volver al login</a>
    </div>
</body>
</html>
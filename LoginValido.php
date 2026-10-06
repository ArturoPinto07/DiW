<?php
require "ComprobarPerfil.php";
requerirPerfil(); // cualquier usuario con sesión
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Login válido</title></head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['correo']); ?></h1>
    <p>Perfil: <?php echo htmlspecialchars($_SESSION['perfil']); ?></p>

    <a href="Opciones.php">Opciones</a>

    <?php if ($_SESSION['perfil'] === 'admin'): ?>
        <br><a href="Admin.php">Panel de administración</a>
    <?php endif; ?>
</body>
</html>
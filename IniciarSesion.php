<?php
session_start();

const MAX_INTENTOS    = 3;
const BLOQUEO_SEGUNDOS = 900; // 15 minutos

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit;
}

$email      = trim($_POST['correo'] ?? '');
$contrasena = $_POST['contraseña'] ?? '';

$conexion = new mysqli("localhost", "root", "", "usuarios");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

function volver($conexion, $mensaje) {
    $_SESSION['mensaje'] = $mensaje;
    $conexion->close();
    header("Location: ErrorLogin.php");
    exit;
}

// Buscar usuario (los segundos desde el último intento se calculan en MySQL)
$stmt = $conexion->prepare(
    "SELECT EMAIL, PASSWORD_CIFRADA, numeroIntentos,
            TIMESTAMPDIFF(SECOND, hora_login, NOW()) AS segundos
     FROM usuarios2 WHERE EMAIL = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$fila) {
    volver($conexion, "Correo o contraseña incorrectos.");
}

$intentos = (int)$fila['numeroIntentos'];
$segundos = (int)$fila['segundos'];

// Si pasaron 15 min desde el último intento fallido, se resetea el contador
if ($intentos > 0 && $segundos >= BLOQUEO_SEGUNDOS) {
    $intentos = 0;
}

// Si ya agotó los intentos, bloqueado (aunque la contraseña sea correcta)
if ($intentos >= MAX_INTENTOS) {
    $minutos = ceil((BLOQUEO_SEGUNDOS - $segundos) / 60);
    volver($conexion, "Has superado los 3 intentos. Inténtalo de nuevo en $minutos minuto(s).");
}

// Comprobar contraseña
if (hash_equals($fila['PASSWORD_CIFRADA'], md5($contrasena))) {
    // Login correcto: reset de intentos
    $stmt = $conexion->prepare("UPDATE usuarios2 SET numeroIntentos = 0, hora_login = NOW() WHERE EMAIL = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();

    $_SESSION['correo'] = $fila['EMAIL'];
    $conexion->close();
    header("Location: LoginValido.php");
    exit;
}

// Login incorrecto: sumar intento y guardar la hora
$intentos++;
$stmt = $conexion->prepare("UPDATE usuarios2 SET numeroIntentos = ?, hora_login = NOW() WHERE EMAIL = ?");
$stmt->bind_param("is", $intentos, $email);
$stmt->execute();
$stmt->close();

$restantes = MAX_INTENTOS - $intentos;
if ($restantes > 0) {
    volver($conexion, "Contraseña incorrecta. Te quedan $restantes intento(s).");
} else {
    volver($conexion, "Has superado los 3 intentos. Inténtalo de nuevo en 15 minutos.");
}
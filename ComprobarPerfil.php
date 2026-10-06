<?php
session_start();

function requerirPerfil($perfil = null) {
    // Sin sesión: al login
    if (!isset($_SESSION['correo'])) {
        header("Location: Login.html");
        exit;
    }
    // Con sesión pero sin el perfil necesario: a la página de inicio
    if ($perfil !== null && ($_SESSION['perfil'] ?? '') !== $perfil) {
        header("Location: LoginValido.php");
        exit;
    }
}
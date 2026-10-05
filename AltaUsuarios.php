<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php
    $servername = "localhost";
    $username   = "root";
    $password_db = ""; 
    $dbname     = "usuarios";

    $conn = new mysqli($servername, $username, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Ha ocurrido un error: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre    = $_POST['nombre']    ?? '';
        $apellido1 = $_POST['apellido1'] ?? '';
        $apellido2 = $_POST['apellido2'] ?? '';
        $email     = $_POST['email']     ?? '';
        $password  = $_POST['contraseña']  ?? '';

        // password_cifrada es varchar(32) -> hash MD5
        $password_cifrada = md5($password);

        $stmt = $conn->prepare(
            "INSERT INTO usuarios2 (NOMBRE, APELLIDO, APELLIDO2, EMAIL, PASSWORD, PASSWORD_CIFRADA)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "ssssss",
            $nombre,
            $apellido1,
            $apellido2,
            $email,
            $password,
            $password_cifrada
        );
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: Opciones.html");
            exit;
        } else {
            echo "Error al insertar: " . $stmt->error;
            $stmt->close();
        }
    }

    
?>
</body>
</html>


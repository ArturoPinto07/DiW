<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
         body{
            background-color: wheat;
            padding: 3%;
            margin: 0;
        }
        div{
            display: flex;
            justify-content: center;
            width: 100%;
        }
        form{
            width: 100%;
            max-width: 800px;
            box-sizing: border-box;
            border-radius: 3%;
            border: solid;
            padding: 5%;
            border-color: white;
            background-color: rgb(173, 189, 83);
            font-size: 1.3rem;
        } 
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea{
            width: 100%;
            box-sizing: border-box;
            font-family: inherit;
            font-size: 1.4rem;
            padding: 12px;
            margin: 8px 0 24px 0;
        }
        
        textarea{
            display: block;
            resize: none;
            overflow: hidden;
            min-height: 3.2rem;
        }
        input[type="submit"]{
            font-size: 1.5rem;
            padding: 14px 40px;
            cursor: pointer;
        }
        
    </style>
</head>
<body>
    <?php
        require "ComprobarPerfil.php";
        requerirPerfil("admin");

        $conexion=new mysqli("localhost", "root", "", "usuarios");
        if ($conexion->connect_error) {
            die("error de conexion: ". $conexion->connect_error);
        }
        $mail=$_SESSION['correo'];
        $consulta = mysqli_query($conexion,"SELECT NOMBRE,APELLIDO,APELLIDO2,EMAIL FROM usuarios2 WHERE EMAIL='$mail'");
        $usuario=mysqli_fetch_assoc($consulta);
        $conexion->close();
    ?>
    <div>
    <form action="AltaUsuarios.php" method="post">
        Ingrese el nombre: <br>
        <input type="text" name="nombre" placeholder="<?php echo $usuario['NOMBRE']  ?>" required disabled><br>
        Ingrese el 1º apellido: <br>
        <input type="text" name="apellido1" placeholder=" <?php echo $usuario['APELLIDO']  ?> " required disabled><br>
        Ingrese el 2º apellido: <br>        
        <input type="text" name="apellido2" placeholder="<?php echo $usuario['APELLIDO2'] ?? '' ?>"><br>
        Ingrese el correo:<br>
        <input type="email" name="email" placeholder="<?php echo $usuario['EMAIL']  ?>" required disabled><br>
        <br>
        <input type="submit" value="Enviar">    
    </form>
    </div>
</body>
</html>
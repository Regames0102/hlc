<?php
session_start();
include 'conexion.php';
// Definir una variable predeterminada para $username
$username = isset($_SESSION['username']) ? $_SESSION['username']: null;
$avatar = isset($_SESSION['avatar']) ? $_SESSION['avatar']: "https://w7.pngwing.com/pngs/8/232/png-transparent-computer-icons-man-avatar-male-login-man-people-monochrome-black.png";

// Verificar si el usuario ha iniciado sesión
if(isset($_SESSION['username'])) {
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("location: login.php");
    exit;
}

if (!isset($_SESSION['tiempo'])) {
    $_SESSION['tiempo']=time();
}
else if (time() - $_SESSION['tiempo'] > 60) {
    session_destroy();
    $sessionClosed = true; // Variable para indicar que la sesión se ha cerrado
    // Indicar que la sesión ha expirado mediante una variable de sesión
    $_SESSION['expired'] = true;
}

$_SESSION['tiempo']=time();

// Función para generar el enlace de navegación
function generateNavLink($href, $text) {
    echo "<li><a href=\"$href\">$text</a></li>";
}



// Función para generar la parte derecha del encabezado
function generateHeaderRight($isLoggedIn, $username, $avatar,$mysqli) {
    if($isLoggedIn) {
        $sql = "SELECT avatar FROM usuario WHERE username = '$username'"; 
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    // Mostrar datos de cada fila
    while($row = $result->fetch_assoc()) {
        $avatar = $row["avatar"]; 
    }
}
        echo "<div class=\"header-right\">";
        if ($username) {
            echo "<span>Bienvenido, $username</span>";
            echo "<img src=$avatar style=\"width: 64px; height: 64px;\">";
        }
        if(isset($_SESSION['expired']) && $_SESSION['expired']) {
            echo "<script>alert('Sesión cerrada por inactividad.');</script>";
            unset($_SESSION['expired']); // Limpiar la variable de sesión
        }
        echo "<a href=\"logout.php\" class=\"btn\">Cerrar Sesión</a>";
        echo "</div>";
    } else {
        echo "<div class=\"header-right\">";
        echo "<a href=\"login.php\" class=\"btn\">Iniciar Sesión</a>";
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
}
        .header {
            background-color: #333;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .header-left ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .header-left ul li {
            display: inline;
            margin-right: 20px;
        }

        .header-left ul li a {
            color: #fff;
            text-decoration: none;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .header-right span {
            margin-right: 10px;
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="header">
        <div class="header-left">
            <ul>
                <?php
               generateNavLink("inicio.php", "Inicio");
               generateNavLink("about.php", "Contacto");
               generateNavLink("tercera.php", "Indeterminada");
                ?>
            </ul>
        </div>
        <?php generateHeaderRight($isLoggedIn, $username, $avatar,$mysqli); ?>
    </div>
    <div class="container">
        <h2>Bienvenido a Nuestra Página</h2>
        <p>Somos una plataforma en línea que ofrece una variedad de servicios para nuestros usuarios.</p>
        <p>Características destacadas:</p>
        <ul>
            <li>Acceso a contenido exclusivo después de iniciar sesión.</li>
            <li>Sistema de registro seguro y fácil de usar.</li>
            <li>Navegación intuitiva y diseño atractivo.</li>
            <li>Soporte técnico disponible para resolver cualquier problema.</li>
        </ul>
    </div>
</body>
</html>

<?php
session_start();

// Definir una variable predeterminada para $username
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

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
}

$_SESSION['tiempo']=time();

// Función para generar el enlace de navegación
function generateNavLink($href, $text) {
    echo "<li><a href=\"$href\">$text</a></li>";
}

// Función para generar la parte derecha del encabezado
function generateHeaderRight($isLoggedIn, $username) {
    if($isLoggedIn) {
        echo "<div class=\"header-right\">";
        if ($username) {
            echo "<span>Bienvenido, $username</span>";
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
    <title>Tercera pagina protegida</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
        <?php generateHeaderRight($isLoggedIn, $username,$mysqli); ?>
    </div>
    <div class="container">
        <h2>Bienvenido a la Tercera Página Protegida</h2>
        <p>¡Hola, <?php echo htmlspecialchars($username); ?>! Esta es otra página protegida que solo puede ser accesible después de iniciar sesión.</p>
        <p>Aquí puedes agregar contenido relevante que solo esté disponible para usuarios autenticados.</p>
        <p><a href="logout.php">Cerrar sesión</a></p>
    </div>
</body>
</html>

<?php
session_start();

// Definir una variable predeterminada para $username
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Verificar si el usuario ha iniciado sesión
if(isset($_SESSION['username'])) {
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
}



function generateNavLink($href, $text) {
    echo "<li><a href=\"$href\">$text</a></li>";
}
// Función para generar la parte derecha del encabezado
function generateHeaderRight($isLoggedIn, $username) {
    if($isLoggedIn) {
        echo "<div class=\"header-right\">";
        if ($username) {
            echo "<span>Bienvenido, $username, <img src=\"<img src=\"D:\\Programas\\XAMPP\\htdocs\\usuario.png\"> </span>";
            
        }
        if(isset($sessionClosed) && $sessionClosed) {
            echo "<span>Sesión cerrada por inactividad.</span>";
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
    <title>Acerca de</title>
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
        <h2>Acerca de Nosotros</h2>
        <p>Bienvenido a nuestro sitio web. Somos una empresa dedicada a proporcionar soluciones tecnológicas innovadoras para nuestros clientes.</p>
        
        <h3>Contacto</h3>
        <ul>
            <li>Nombre de la Empresa: Mi Empresa S.A.</li>
            <li>Dirección: 123 Calle Principal, Ciudad, País</li>
            <li>Teléfono: (123) 456-7890</li>
            <li>Correo Electrónico: info@miempresa.com</li>
        </ul>

        <h3>¿Quiénes Somos?</h3>
        <p>Somos un equipo de profesionales apasionados por la tecnología y comprometidos con brindar soluciones de alta calidad a nuestros clientes. Nuestro objetivo es crear productos innovadores que impulsen el éxito de su negocio.</p>

        <h3>Nuestra Misión</h3>
        <p>Proveer soluciones tecnológicas de vanguardia que satisfagan las necesidades y excedan las expectativas de nuestros clientes, contribuyendo así a su éxito y crecimiento continuo.</p>

        <h3>Nuestra Visión</h3>
        <p>Ser reconocidos como líderes en el desarrollo de software y servicios tecnológicos, estableciendo relaciones duraderas y mutuamente beneficiosas con nuestros clientes.</p>

        <!-- Aquí podrías agregar más contenido, como información sobre el equipo, la historia de la empresa, etc. -->
    </div>
</body>
</html>

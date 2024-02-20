<?php
session_start();

// Inicializar las variables de error y el contador de intentos
$username_err = $password_err = "";
$attempts_remaining = 3;

// Verificar si se han realizado demasiados intentos de inicio de sesión fallidos
if (isset($_SESSION['login_attempts'])) {
    if ($_SESSION['login_attempts'] >= 3) {
        //El siguente codigo es cuando el numero de intentos se ha excedido , es para reinicirlo
        //$_SESSION['login_attempts'] = 0;
        echo "Ha excedido el número máximo de intentos de inicio de sesión. Por favor, inténtelo de nuevo más tarde.";
        exit;
    } else {
        $attempts_remaining = 3 - $_SESSION['login_attempts'];
        
    }
}

if (isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Procesar datos del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir el archivo de conexión
    require_once 'conexion.php';

    // Obtener los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];
    

    // Consulta preparada para verificar si el usuario y la contraseña coinciden en la base de datos
    $sql = "SELECT username, contrasena, avatar FROM usuario WHERE username = ?";
if ($stmt = $mysqli->prepare($sql)) {
    // Bind variable
    $stmt->bind_param("s", $username);

    // Intentar ejecutar la consulta preparada
    if ($stmt->execute()) {
        // Almacenar el resultado
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // Asociar variables de resultado
            $stmt->bind_result($db_username, $db_password, $db_avatar);
            $stmt->fetch();
        
            // Verificar la contraseña
            if ($password == $db_password) {
                // Contraseña correcta, iniciar sesión
                $_SESSION['username'] = $username;
                $_SESSION['avatar'] = $db_avatar; // Guardar el avatar en la sesión
                header("location: principio.php");
            } else {
                // Contraseña incorrecta
                $password_err = "Nombre de usuario o contraseña incorrectos.";
                // Incrementar el contador de intentos de inicio de sesión fallidos
                if (isset($_SESSION['login_attempts'])) {
                    $_SESSION['login_attempts']++;
                } else {
                    $_SESSION['login_attempts'] = 1;
                }
            }
        } else {
            // Nombre de usuario incorrecto
            $username_err = "Nombre de usuario o contraseña incorrectos.";
            // Incrementar el contador de intentos de inicio de sesión fallidos
            if (isset($_SESSION['login_attempts'])) {
                $_SESSION['login_attempts']++;
            } else {
                $_SESSION['login_attempts'] = 1;
            }
        }
    } else {
        $username_err = "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
    }

    // Cerrar la consulta preparada
    $stmt->close();
}

// Cerrar la conexión
$mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlazar tu hoja de estilos aquí -->
    <style>   
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .help-block {
            color: #dc3545;
            font-size: 14px;
        }

        .register-link {
            margin-top: 20px;
            text-align: center;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        } 
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <p>Por favor, complete sus credenciales para iniciar sesión.</p>
        <?php if ($attempts_remaining > 0): ?>
            <p>Intentos restantes: <?php echo $attempts_remaining; ?></p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="username" value="<?php echo isset($username) ? $username : ''; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" value="Iniciar Sesión">
                </div>
            </form>
        <?php else: ?>
            <p>Ha excedido el número máximo de intentos de inicio de sesión. Por favor, inténtelo de nuevo más tarde.</p>
        <?php endif; ?>
        <div class="register-link">
            ¿No tienes una cuenta? <a href="registro.php">Regístrate ahora</a>.
        </div>
    </div>
</body>
</html>

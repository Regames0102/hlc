<?php
session_start();

// Inicializar las variables de error
$name_err = $username_err = $password_err = $gender_err = $dob_err = "";

// Procesar datos del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir el archivo de conexión
    require_once 'conexion.php';

    // Obtener los datos del formulario
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $avatar = $_POST['avatar'];

    // Consulta preparada para verificar si el nombre de usuario ya existe en la base de datos
    $sql = "SELECT idUsuario FROM usuario WHERE username = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables
        $stmt->bind_param("s", $username);

        // Intentar ejecutar la consulta preparada
        if ($stmt->execute()) {
            // Almacenar el resultado
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                // El nombre de usuario ya existe
                $username_err = "El nombre de usuario ya está en uso.";
            }
        } else {
            echo "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
        }

        // Cerrar la consulta preparada
        $stmt->close();
    }

    // Consulta SQL para obtener el ID más alto de la tabla
    $sql = "SELECT MAX(idUsuario) AS max_id FROM usuario";
    if ($result = $mysqli->query($sql)) {
        // Verificar si se encontraron filas
        if ($result->num_rows > 0) {
            // Obtener el resultado como un array asociativo
            $row = $result->fetch_assoc();
            // Obtener el valor máximo del ID y guardarlo en una variable
            $max_id = $row['max_id'];
        } else {
            // Si no se encontraron filas, asignar un valor predeterminado a la variable
            $max_id = 0;
        }
        // Liberar el resultado
        $result->free();
    } else {
        // Si hubo un error en la consulta, mostrar un mensaje de error
        echo "Error al ejecutar la consulta: " . $mysqli->error;
    }
    $max_id=$max_id+1;
    
    // Verificar si hay errores antes de insertar en la base de datos
    if (empty($name_err) && empty($username_err) && empty($password_err) && empty($gender_err) && empty($dob_err)) {
        // Consulta preparada para insertar el usuario en la base de datos
        $sql = "INSERT INTO usuario (idUsuario, nombre, username, contrasena, sexo, fechaNacimiento, avatar) VALUES (?,?,?,?,?,?,?)";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables
            $stmt->bind_param("sssssss", $max_id, $name, $username, $password, $gender, $dob,$avatar);

            // Intentar ejecutar la consulta preparada
            if ($stmt->execute()) {
                // Redirigir a la página de inicio de sesión
                header("location: login.php");
                exit();
            } else {
                echo "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            }

            // Cerrar la consulta preparada
            $stmt->close();
        }
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
    <title>Registro</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlazar tu hoja de estilos aquí -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .help-block {
            color: red;
            font-size: 12px;
        }

        .login-link {
            margin-top: 10px;
            text-align: center;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registro</h2>
        <p>Por favor, complete este formulario para crear una cuenta.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Nombre de Usuario</label>
                <input type="text" name="username" value="<?php echo isset($username) ? $username : ''; ?>" required>
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Sexo</label>
                <select name="gender">
                    <option value="">Seleccionar</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
                <span class="help-block"><?php echo $gender_err; ?></span>
            </div>
            <div class="form-group">
                <label>Fecha de Nacimiento</label>
                <input type="date" name="dob">
                <span class="help-block"><?php echo $dob_err; ?></span>
            </div>
            <div class="form-group">
                <label>Avatar</label>
                <input type="file" name="avatar">
                <span class="help-block"><?php echo $dob_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Registrarse">
            </div>
        </form>
        <div class="login-link">
            ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.
        </div>
    </div>
</body>
</html>

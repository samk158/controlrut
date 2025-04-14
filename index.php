<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Iniciar la sesión
session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el nombre de usuario y la contraseña
    $username = $_POST['username'];
    $contraseña = $_POST['contraseña'];

    // Consultar la base de datos para verificar el nombre de usuario
    $sql = "SELECT * FROM conductores WHERE username = ?";

    // Verificar si la conexión es válida
    if (!$conn) {
        die("Error en la conexión: " . $conn->connect_error);
    }

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Verificar si la preparación fue exitosa
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->error);
    }

    // Bindeamos los parámetros
    $stmt->bind_param("s", $username);

    // Ejecutar la consulta
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si existe el usuario
    if ($result->num_rows > 0) {
        // Obtener los datos del usuario
        $usuario = $result->fetch_assoc();

        // Verificar la contraseña usando password_verify
        if (password_verify($contraseña, $usuario['contraseña'])) {
            // Si la contraseña es correcta, almacenar el nombre de usuario en la sesión
            $_SESSION['username'] = $usuario['username'];

            // Redirigir al usuario a la página principal o dashboard
            header("Location: datos.php"); // Redirigir a la página de bienvenida
            exit();
        } else {
            // Si la contraseña no es correcta, mostrar un mensaje de error
            echo "<script>alert('Usuario o contraseña incorrectos');</script>";
        }
    } else {
        // Si no existe el usuario, mostrar un mensaje de error
        echo "<script>alert('Usuario o contraseña incorrectos');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al Transporte Público</title>
    <link rel="stylesheet" href="inicio.css">
</head>
<body>
    <div class="navbar">
        <a href="administrador_login.php" class="button1"></a>
        <a href="pagina2.php" class="button">Página 2</a>
    </div>
    
    <div class="container">
        <h1>Bienvenido al Sistema de Transporte Público</h1>
        <p>"Estamos aquí para hacer tu experiencia lo más cómoda y segura posible. ¡Tu bienestar es nuestra prioridad!"</p>

        <!-- Formulario de inicio de sesión -->
        <h2>Iniciar Sesión</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            
            <button type="submit" class="login-btn">Ingresar</button>
            <!-- Botón Registrarse -->
            <a href="registro_conductor.php" class="register-btn">Registrarse</a>
        </form>

    </div>
</body>
</html>

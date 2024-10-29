<?php
session_start(); // Iniciar sesión para manejar mensajes

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$host = "localhost";
$db = "ventas";  // Cambia por tu base de datos
$user = "root";  // Cambia por tu usuario
$pass = "";      // Cambia por tu contraseña

// Intentar conectar a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

// Variables para mensajes
$message = "";  // Mensaje de error o éxito
$mostrarMensaje = false;  // Control de visualización del mensaje

// Verificar si el formulario se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $nuevaClave = trim($_POST['nueva_clave']);

    // Validar longitud de la nueva contraseña
    if (strlen($nuevaClave) < 7) {
        $message = 'La contraseña debe tener al menos 7 caracteres.';
        $mostrarMensaje = true;  // Activar el mensaje de error
    } else {
        // Encriptar la nueva contraseña
        $nuevaClave = password_hash($nuevaClave, PASSWORD_DEFAULT);

        // Llamamos a la función que actualiza la contraseña
        if (actualizarClave($pdo, $username, $nuevaClave)) {
            $message = 'Contraseña actualizada exitosamente.';
            $mostrarMensaje = true;  // Activar el mensaje de éxito
        } else {
            $message = 'Error al actualizar la contraseña.';
            $mostrarMensaje = true;  // Activar el mensaje de error
        }
    }
}

// Función que actualiza la contraseña en la base de datos
function actualizarClave($pdo, $username, $nuevaClave) {
    $stmt = $pdo->prepare("UPDATE usuario SET usuario_clave = :nuevaClave WHERE usuario_usuario = :username");
    $stmt->bindParam(':nuevaClave', $nuevaClave, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    return $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> 
    <link rel="stylesheet" href="../css/stilo.css"> <!-- Verifica que la ruta sea correcta -->
    <title>Restablecer Contraseña</title>
</head>
<body>
    <center>
        <div class="container">
            <div class="icon-title">
                <i class="bi bi-shield-lock"></i>
                <h2>Nueva contraseña</h2>

                <div class="cont">
                    <?php if ($mostrarMensaje): ?>
                        <div id="error-message-box" class="<?php echo (strpos($message, 'error') !== false) ? 'alert alert-danger' : 'alert alert-success'; ?>">
                            <?php echo htmlspecialchars($message); ?> <!-- Sanitización para prevenir XSS -->
                        </div>
                    <?php endif; ?>
                </div>
                
                <div>
                    <form action="" method="POST">
                        <input type="hidden" name="username" value="<?php echo isset($_GET['user']) ? htmlspecialchars($_GET['user']) : ''; ?>">
                        <label for="nueva_clave">Nueva contraseña:</label>
                        <input type="password" name="nueva_clave" id="nueva_clave" required onkeyup="validarClave()">
                        <div id="mensaje-error" style="color: red;"></div> <!-- Mensaje de error -->
                        <input type="submit" value="Actualizar" id="boton-actualizar" style="display: inline-block;">
                        <a href="http://localhost/pepe/"><button type="button" class="btn-regresar">Login</button></a>
                    </form>
                </div>
            </div>
        </div>
    </center>

    <!-- JavaScript para validar la clave y ocultar el mensaje -->
    <script>
        function validarClave() {
            var nuevaClave = document.getElementById('nueva_clave').value;
            var mensajeError = document.getElementById('mensaje-error');
            var botonActualizar = document.getElementById('boton-actualizar');

            // Validar longitud de la nueva contraseña
            if (nuevaClave.length < 7) {
                mensajeError.innerText = 'La contraseña debe tener al menos 7 caracteres.';
                botonActualizar.style.display = 'none';  // Ocultar botón de Actualizar
            } else {
                mensajeError.innerText = '';  // Limpiar mensaje de error
                botonActualizar.style.display = 'inline-block';  // Mostrar botón de Actualizar
            }
        }

        // Ocultar el mensaje de éxito/error después de 5 segundos
        setTimeout(function() {
            var messageBox = document.getElementById('error-message-box');
            if (messageBox) {
                messageBox.style.display = 'none';
            }
        }, 5000); 
    </script>
</body>
</html>

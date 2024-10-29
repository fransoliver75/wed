<?php
session_start(); // Iniciar sesión para manejar mensajes

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$host = "localhost";  // Cambia según tu configuración
$db = "ventas";       // Cambia por tu base de datos
$user = "root";       // Cambia por tu usuario
$pass = "";           // Cambia por tu contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

// Inicializar variable para almacenar el mensaje
$message = '';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']); // Sanitizar entrada

    // Llamamos a la función que verifica el usuario en la base de datos
    if (verificarUsuario($pdo, $username)) {
        // Usuario encontrado, redirigir a la página para cambiar la contraseña
        header("Location: resetPassword.php?user=" . urlencode($username));
        exit();  // Asegurarse de que el script se detiene después de redirigir
    } else {
        // Asignar mensaje de error si el usuario no existe
        $message = 'El nombre de usuario no existe.';
    }
}

// Función que verifica si el usuario existe en la base de datos
function verificarUsuario($pdo, $username) {
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE usuario_usuario = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    // Si el usuario existe, retorna true, de lo contrario false
    return $stmt->rowCount() > 0;
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
    <link rel="stylesheet" href="../css/stile.css"> <!-- Asegúrate de que el nombre del archivo sea correcto -->
    <link rel="stylesheet" href="boxicons/css/boxicons.css">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <center>
        <div class="container">
            <div class="icon-title">
                <i class="bi bi-person-bounding-box"></i>
                <h2> Usuario</h2>

                <div class="cont">
                    <?php if (!empty($message)): ?>
                        <div id="error-message-box" class="alert alert-danger">
                            <h4>Ocurrió un error inesperado</h4>
                            <p><?php echo htmlspecialchars($message); ?></p> <!-- Sanitización para prevenir XSS -->
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <form action="" method="POST">
                        <label for="username">Nombre de usuario:</label>
                        <input type="text" name="username" id="username" required>
                        <input type="submit" value="Verificar">
                        <a href="http://localhost/pepe/"><button type="button" class="btn-regresar">Login</button></a>
                    </form>
                </div>
            </div>
        </div>
    </center>
    <script>
        setTimeout(function() {
            var messageBox = document.getElementById('error-message-box');
            if (messageBox) {
                messageBox.style.display = 'none';
            }
        }, 5000); 
    </script>
</body>
</html>

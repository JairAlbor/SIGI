<?php
header('Content-Type: application/json');

// Conexión a la base de datos (estamos en CRUD, así que conexion.php está en la misma carpeta)
include 'conexion.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos. En login.php enviamos 'identificador' y 'contrasena'
    $user = mysqli_real_escape_string($conn, $_POST['identificador']);
    $pass = mysqli_real_escape_string($conn, $_POST['contrasena']);

    // Verificar si el usuario existe (por id_usuario o correo)
    // Asumimos que la columna de email se llama 'correo' o 'email'
    // Utilizaremos 'correo', si en la bd es diferente se debe ajustar.
    $consulta_user = "SELECT * FROM usuarios WHERE identificador = '$user' OR correo = '$user'";
    $resultado_user = mysqli_query($conn, $consulta_user);

    if (mysqli_num_rows($resultado_user) === 0) {
        // Usuario no existe en el sistema
        echo json_encode(['status' => 'error', 'message' => 'El usuario o correo ingresado no existe.']);
        exit();
    }

    $datos_usuario = mysqli_fetch_array($resultado_user);

    // Verificar si la contraseña es correcta
    if (!password_verify($pass, $datos_usuario['pass'])) {
        echo json_encode(['status' => 'error', 'message' => 'La contraseña es incorrecta.']);
        exit();
    }

    // Credenciales correctas: guardar sesión
    $_SESSION['usuario_nombre'] = $datos_usuario['nombre'];
    $_SESSION['rol']            = $datos_usuario['rol'];
    // Asumimos que la tabla usa id_usuario como el identificador principal
    $_SESSION['user_id']        = $datos_usuario['id_usuario'];

    echo json_encode(['status' => 'success', 'message' => '¡Bienvenido de nuevo!', 'redirect' => 'maestro.php']);
    exit();
}
?>

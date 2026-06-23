<?php
header('Content-Type: application/json');
include 'CRUD/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que todos los campos requeridos estén presentes
    if (empty($_POST['nombre']) || empty($_POST['identificador']) || empty($_POST['correo']) || empty($_POST['contrasena']) || empty($_POST['rol'])) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
        exit();
    }

    // Recibir y desinfectar los datos del formulario
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $identificador = mysqli_real_escape_string($conn, $_POST['identificador']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $rol = mysqli_real_escape_string($conn, $_POST['rol']);

    // Verificar si el correo o el identificador ya existen en la base de datos (tabla 'usuarios')
    $consulta_existente = "SELECT * FROM usuarios WHERE correo = '$correo' OR identificador = '$identificador'";
    $resultado_existente = mysqli_query($conn, $consulta_existente);

    if (mysqli_num_rows($resultado_existente) > 0) {
        $usuario_duplicado = mysqli_fetch_assoc($resultado_existente);
        if ($usuario_duplicado['correo'] === $correo) {
            echo json_encode(['status' => 'error', 'message' => 'El correo ya está registrado.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El identificador (Matrícula/ID) ya está registrado.']);
        }
        exit();
    }

    // Encriptar la contraseña de forma segura
    $hashed_pass = password_hash($contrasena, PASSWORD_DEFAULT);
    $hashed_pass_escaped = mysqli_real_escape_string($conn, $hashed_pass);

    // Insertar el nuevo usuario en la base de datos
    $insertar_usuario = "INSERT INTO usuarios (identificador, nombre, correo, pass, rol) VALUES ('$identificador', '$nombre', '$correo', '$hashed_pass_escaped', '$rol')";
    
    if (mysqli_query($conn, $insertar_usuario)) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario registrado exitosamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar el usuario: ' . mysqli_error($conn)]);
    }
}
?>
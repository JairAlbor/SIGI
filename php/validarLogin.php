<?php

//inicio de sesion con datos de la base de datos
session_start();

//recibir los datos del formulario
$identificador = $_POST['identificador'];
$password = $_POST['contrasena'];

//conexion a la base de datos
require_once 'conn.php';

//consulta para verificar si el usuario existe en la base de datos
$query = "SELECT * FROM usuarios WHERE (matricula = :identificador OR correo = :identificador) AND contrasena = :pass";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':identificador', $identificador);
$stmt->bindParam(':pass', $password);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    //usuario encontrado, iniciar sesion
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nombre'] = $user['nombre'];
    $_SESSION['rol'] = $user['rol'];
    //redireccionar al usuario a la pagina principal sin usar header()
    echo "<script>window.location.href='../index.html';</script>";
   
    exit();
} else {
    //usuario no encontrado, mostrar mensaje de error
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='../login.html';</script>";
}

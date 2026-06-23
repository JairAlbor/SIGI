<?php
//conexion a la base de datos para el sistema de incidencias

$host = "localhost";
$dbname = "sistema_incidencias";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($conn) {
    //echo "Conexión exitosa a la base de datos.";
} else {
    echo "Error al conectar a la base de datos.";
}
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}


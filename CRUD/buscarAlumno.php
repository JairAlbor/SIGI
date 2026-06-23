<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$q_escaped = mysqli_real_escape_string($conn, $q);

$sql = "SELECT id_alumno, matricula, nombre, apellidos, grado, grupo, correo_alumno, datos_tutor
        FROM alumnos
        WHERE matricula LIKE '%{$q_escaped}%'
           OR nombre    LIKE '%{$q_escaped}%'
           OR apellidos LIKE '%{$q_escaped}%'
        ORDER BY nombre, apellidos
        LIMIT 10";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['error' => 'Error en la consulta']);
    exit;
}

$alumnos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $alumnos[] = $row;
}

echo json_encode($alumnos);
mysqli_close($conn);

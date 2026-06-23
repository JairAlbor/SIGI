<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
include 'conexion.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión no válida.']);
    exit;
}

$id_creador = (int) $_SESSION['user_id'];

// ── Leer y sanitizar campos ──────────────────────────────────────────────────
function esc($conn, $v) { return mysqli_real_escape_string($conn, trim($v ?? '')); }

$tipo_incidencia = esc($conn, $_POST['tipo_incidencia'] ?? '');
$descripcion     = esc($conn, $_POST['descripcion']     ?? '');
$evidencia_url   = esc($conn, $_POST['evidencia_url']   ?? '');

// ID del alumno: puede venir de un alumno existente o de datos nuevos
$id_alumno_existente = isset($_POST['id_alumno']) ? (int) $_POST['id_alumno'] : 0;

// Validaciones mínimas
if (empty($tipo_incidencia) || empty($descripcion)) {
    echo json_encode(['status' => 'error', 'message' => 'Tipo de incidencia y descripción son obligatorios.']);
    exit;
}

// ── Resolver id_alumno ───────────────────────────────────────────────────────
$id_alumno = 0;

if ($id_alumno_existente > 0) {
    // Alumno ya existente en BD
    $id_alumno = $id_alumno_existente;
} else {
    // Alumno nuevo: se deben proporcionar sus datos
    $matricula      = esc($conn, $_POST['matricula']      ?? '');
    $nombre_alumno  = esc($conn, $_POST['nombre_alumno']  ?? '');
    $apellidos      = esc($conn, $_POST['apellidos']      ?? '');
    $grado          = (int) ($_POST['grado']              ?? 0);
    $grupo          = esc($conn, $_POST['grupo']          ?? '');
    $correo_alumno  = esc($conn, $_POST['correo_alumno']  ?? '');
    $datos_tutor    = esc($conn, $_POST['datos_tutor']    ?? '');

    if (empty($matricula) || empty($nombre_alumno) || empty($apellidos)) {
        echo json_encode(['status' => 'error', 'message' => 'Datos del alumno incompletos.']);
        exit;
    }

    // Verificar si la matrícula ya existe (por si acaso)
    $check = mysqli_query($conn, "SELECT id_alumno FROM alumnos WHERE matricula = '{$matricula}'");
    if ($check && mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        $id_alumno = (int) $row['id_alumno'];
    } else {
        // Insertar alumno nuevo
        $ins_alumno = "INSERT INTO alumnos (matricula, nombre, apellidos, grado, grupo, correo_alumno, datos_tutor)
                       VALUES ('{$matricula}', '{$nombre_alumno}', '{$apellidos}', {$grado}, '{$grupo}', '{$correo_alumno}', '{$datos_tutor}')";
        if (!mysqli_query($conn, $ins_alumno)) {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar el alumno: ' . mysqli_error($conn)]);
            exit;
        }
        $id_alumno = (int) mysqli_insert_id($conn);
    }
}

// ── Generar folio único ──────────────────────────────────────────────────────
$res_folio = mysqli_query($conn, "SELECT COUNT(*) AS total FROM incidencias");
$row_folio = mysqli_fetch_assoc($res_folio);
$folio_num = (int) $res_folio ? $row_folio['total'] + 1 : 1;
$folio = 'INC-' . str_pad($folio_num, 4, '0', STR_PAD_LEFT);

// ── Insertar incidencia ──────────────────────────────────────────────────────
$sql_inc = "INSERT INTO incidencias (id_alumno, folio, id_creador, tipo_incidencia, descripcion, evidencia_url, estado)
            VALUES ({$id_alumno}, '{$folio}', {$id_creador}, '{$tipo_incidencia}', '{$descripcion}', '{$evidencia_url}', 'pendiente')";

if (!mysqli_query($conn, $sql_inc)) {
    echo json_encode(['status' => 'error', 'message' => 'Error al guardar la incidencia: ' . mysqli_error($conn)]);
    exit;
}

$id_incidencia = (int) mysqli_insert_id($conn);

echo json_encode([
    'status'        => 'success',
    'message'       => "Incidencia {$folio} registrada exitosamente.",
    'folio'         => $folio,
    'id_incidencia' => $id_incidencia
]);

mysqli_close($conn);

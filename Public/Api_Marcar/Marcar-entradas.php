<?php
require __DIR__ . '/../../SRC/Config/Inicio.php';
require RAIZ . '/SRC/Modelos/Registro_de_las_horas.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['ok' => false, 'mensaje' => 'Método no permitido.']));
}

$u = usuario_actual();
if (!$u || $u['rol'] !== 'alumno') {
    http_response_code(403);
    exit(json_encode(['ok' => false, 'mensaje' => 'Sesión no válida.']));
}

$st = db()->prepare('SELECT estatus FROM alumnos WHERE id = ?');
$st->execute([$u['id']]);
if ($st->fetchColumn() !== 'activo') {
    exit(json_encode(['ok' => false, 'mensaje' => 'Tu servicio social no está activo.']));
}
if (Registo_Horas::turno_Abierto($u['id'])) {
    exit(json_encode(['ok' => false, 'mensaje' => 'Ya tienes una entrada abierta.']));
}

db()->prepare('INSERT INTO registro_horas (alumno_id, fecha, hora_entrada) VALUES (?,?,?)')
    ->execute([$u['id'], date('Y-m-d'), date('Y-m-d H:i:s')]);
bitacora('alumno', $u['id'], 'entrada');

echo json_encode(['ok' => true, 'mensaje' => 'Entrada registrada.']);
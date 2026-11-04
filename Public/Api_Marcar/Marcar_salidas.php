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

$reg = Registo_Horas::turno_Abierto($u['id']);
if (!$reg) {
    exit(json_encode(['ok' => false, 'mensaje' => 'No tienes una entrada abierta.']));
}

$ahora = date('Y-m-d H:i:s');
$horas = max(0, round((strtotime($ahora) - strtotime($reg['hora_entrada'])) / 3600, 2));

$pdo = db();
try {
    $pdo->beginTransaction();
    $pdo->prepare("UPDATE registro_horas SET hora_salida=?, horas=?, estatus='completado' WHERE id=?")
        ->execute([$ahora, $horas, $reg['id']]);
    $pdo->prepare('UPDATE alumnos SET horas_acumuladas = horas_acumuladas + ? WHERE id = ?')
        ->execute([$horas, $u['id']]);
    $pdo->prepare("UPDATE alumnos SET estatus='finalizado' WHERE id=? AND horas_acumuladas >= ?")
        ->execute([$u['id'], HORAS_REQUERIDAS]);
    $pdo->commit();
} catch (Throwable $ex) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    http_response_code(500);
    exit(json_encode(['ok' => false, 'mensaje' => 'No se pudo registrar la salida. Intenta de nuevo.']));
}
bitacora('alumno', $u['id'], 'salida', "Horas de la jornada: $horas");

echo json_encode(['ok' => true, 'mensaje' => "Salida registrada. Sumaste $horas horas."]);
<?php
function requerir_rol(string $rol): array {
    $u = usuario_actual();
    if (!$u) { header('Location: ' . BASE_URL . '/Inicio_de_sesion.php'); exit; }
    if ($u['rol'] !== $rol) { header('Location: ' . BASE_URL . '/Error_403.php'); exit; }
    return $u;
}
function bitacora(string $tipo, int $id, string $accion, string $detalle = ''): void {
    if ($tipo === 'admin') { $tipo = 'maestro'; }
    db()->prepare('INSERT INTO bitacora (actor_tipo, actor_id, accion, detalle) VALUES (?,?,?,?)')
        ->execute([$tipo, $id, $accion, $detalle]);
}

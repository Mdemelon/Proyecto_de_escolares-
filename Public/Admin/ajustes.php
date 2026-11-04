<?php
require __DIR__ . '/../../SRC/Config/Inicio.php';
$u = requerir_rol('admin');
$pdo = db();
$errores = [];
$ok = isset($_GET['ok']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumnoId = (int)($_POST['alumno_id'] ?? 0);
    $tipo = $_POST['tipo'] ?? '';
    $horas = (float)($_POST['horas'] ?? 0);
    $motivo = trim($_POST['motivo'] ?? '');

    $st = $pdo->prepare('SELECT estatus, horas_acumuladas FROM alumnos WHERE id = ?');
    $st->execute([$alumnoId]);
    $al = $st->fetch();

    if (!$al) $errores[] = 'Selecciona un alumno.';
    elseif ($al['estatus'] !== 'activo') $errores[] = 'Solo se pueden ajustar horas de alumnos activos.';
    if (!in_array($tipo, ['abonar', 'descontar'], true)) $errores[] = 'Elige el tipo de ajuste.';
    if ($horas <= 0 || $horas > 500) $errores[] = 'Las horas deben ser mayores a 0 y no más de 500.';
    if ($motivo === '') $errores[] = 'Escribe el motivo del ajuste.';
    if (!$errores && $tipo === 'descontar' && $horas > (float)$al['horas_acumuladas']) $errores[] = 'No puedes descontar más horas de las que tiene el alumno.';

    if (!$errores) {
        $pdo->beginTransaction();
        $pdo->prepare('INSERT INTO ajustes_horas (alumno_id, maestro_id, tipo, horas, motivo) VALUES (?,?,?,?,?)')
            ->execute([$alumnoId, $u['id'], $tipo, $horas, $motivo]);
        $delta = $tipo === 'abonar' ? $horas : -$horas;
        $pdo->prepare('UPDATE alumnos SET horas_acumuladas = horas_acumuladas + ? WHERE id = ?')->execute([$delta, $alumnoId]);
        $pdo->prepare("UPDATE alumnos SET estatus='finalizado' WHERE id=? AND horas_acumuladas >= ?")->execute([$alumnoId, HORAS_REQUERIDAS]);
        $pdo->commit();
        bitacora('admin', $u['id'], 'ajuste_horas', "$tipo $horas h · alumno $alumnoId · $motivo");
        header('Location: ajustes.php?ok=1'); exit;
    }
}
$activos = $pdo->query("SELECT id, numero_control, nombre, horas_acumuladas FROM alumnos WHERE estatus='activo' ORDER BY nombre")->fetchAll();
$hist = $pdo->query('SELECT j.creado_en, j.tipo, j.horas, j.motivo, a.nombre FROM ajustes_horas j JOIN alumnos a ON a.id=j.alumno_id ORDER BY j.id DESC LIMIT 50')->fetchAll();
$titulo = 'Ajustes de horas';
require RAIZ . '/SRC/Plantillas/Header.php';
?>
<main class="contenido">
  <h1>Ajustes de horas</h1>
  <?php if ($ok): ?><p class="alerta ok" role="status">Ajuste guardado.</p><?php endif; ?>
  <?php foreach ($errores as $er): ?><p class="alerta error" role="alert"><?= e($er) ?></p><?php endforeach; ?>
  <form method="post" class="formulario">
    <label>Alumno
      <select name="alumno_id" required><option value="">Elige un alumno</option>
        <?php foreach ($activos as $a): ?>
          <option value="<?= (int)$a['id'] ?>"><?= e($a['numero_control'] . ' · ' . $a['nombre'] . ' (' . number_format($a['horas_acumuladas'], 2) . ' h)') ?></option>
        <?php endforeach; ?>
      </select></label>
    <label>Tipo
      <select name="tipo" required><option value="abonar">Abonar horas (apoyo)</option><option value="descontar">Descontar horas (sanción)</option></select></label>
    <label>Cantidad de horas <input name="horas" type="number" step="0.25" min="0.25" required></label>
    <label>Motivo <input name="motivo" maxlength="255" required></label>
    <button class="boton">Guardar ajuste</button>
  </form>

  <h2 class="h5 mt-4">Últimos ajustes</h2>
  <?php if (!$hist): ?><p class="vacio">Aún no hay ajustes.</p><?php else: ?>
  <div class="tabla-scroll"><table>
    <thead><tr><th>Fecha</th><th>Alumno</th><th>Tipo</th><th>Horas</th><th>Motivo</th></tr></thead>
    <tbody><?php foreach ($hist as $h): ?>
      <tr><td><?= e(date('d/m/Y H:i', strtotime($h['creado_en']))) ?></td><td><?= e($h['nombre']) ?></td>
          <td><?= $h['tipo'] === 'abonar' ? 'Abono' : 'Descuento' ?></td><td><?= e(number_format($h['horas'], 2)) ?></td><td><?= e($h['motivo']) ?></td></tr>
    <?php endforeach; ?></tbody>
  </table></div>
  <?php endif; ?>
</main>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

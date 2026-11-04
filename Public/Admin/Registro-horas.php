<?php
require __DIR__ . '/../../SRC/Config/Inicio.php';
$u = requerir_rol('admin');
$q = trim($_GET['q'] ?? '');
$f = $_GET['fecha'] ?? '';
$sql = 'SELECT r.fecha, r.hora_entrada, r.hora_salida, r.horas, r.estatus, a.numero_control, a.nombre
        FROM registro_horas r JOIN alumnos a ON a.id = r.alumno_id WHERE 1=1';
$p = [];
if ($q !== '') { $sql .= ' AND (a.nombre LIKE ? OR a.numero_control LIKE ?)'; $p[] = "%$q%"; $p[] = "%$q%"; }
if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $f)) { $sql .= ' AND r.fecha = ?'; $p[] = $f; }
$st = db()->prepare($sql . ' ORDER BY r.hora_entrada DESC LIMIT 500');
$st->execute($p);
$filas = $st->fetchAll();
$titulo = 'Registro de horas';
require RAIZ . '/SRC/Plantillas/Header.php';
?>
<main class="contenido">
  <h1>Registro de horas</h1>
  <form method="get" class="d-flex flex-wrap gap-2 mb-3 no-imprimir">
    <input name="q" value="<?= e($q) ?>" placeholder="Nombre o número de control" class="form-control w-auto">
    <input name="fecha" type="date" value="<?= e($f) ?>" class="form-control w-auto">
    <button class="boton">Filtrar</button>
    <button type="button" class="boton" onclick="window.print()">Imprimir</button>
  </form>
  <?php if (!$filas): ?><p class="vacio">No hay registros con esos criterios.</p><?php else: ?>
  <div class="tabla-scroll"><table>
    <thead><tr><th>Fecha</th><th>No. control</th><th>Alumno</th><th>Entrada</th><th>Salida</th><th>Horas</th><th>Estado</th></tr></thead>
    <tbody>
    <?php foreach ($filas as $r): ?>
      <tr>
        <td><?= e(date('d/m/Y', strtotime($r['fecha']))) ?></td>
        <td><?= e($r['numero_control']) ?></td><td><?= e($r['nombre']) ?></td>
        <td><?= e(date('H:i', strtotime($r['hora_entrada']))) ?></td>
        <td><?= $r['hora_salida'] ? e(date('H:i', strtotime($r['hora_salida']))) : '—' ?></td>
        <td><?= $r['horas'] !== null ? e(number_format($r['horas'], 2)) : '—' ?></td>
        <td><?= $r['estatus'] === 'en_curso' ? 'En curso' : 'Completado' ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <?php endif; ?>
</main>
<style>@media print{nav,.no-imprimir{display:none!important}}</style>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

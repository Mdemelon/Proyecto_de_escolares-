<?php
require __DIR__ . '/../../SRC/Config/Inicio.php';
$u = requerir_rol('admin');
$filas = db()->query('SELECT creado_en, actor_tipo, actor_id, accion, detalle FROM bitacora ORDER BY id DESC LIMIT 200')->fetchAll();
$titulo = 'Bitácora de auditoría';
require RAIZ . '/SRC/Plantillas/Header.php';
?>
<main class="contenido">
  <h1>Bitácora de auditoría</h1>
  <p class="vacio">Últimos 200 movimientos. Los registros no se pueden borrar.</p>
  <?php if (!$filas): ?><p class="vacio">Aún no hay movimientos.</p><?php else: ?>
  <div class="tabla-scroll"><table>
    <thead><tr><th>Fecha</th><th>Quién</th><th>Acción</th><th>Detalle</th></tr></thead>
    <tbody><?php foreach ($filas as $f): ?>
      <tr><td><?= e(date('d/m/Y H:i', strtotime($f['creado_en']))) ?></td>
          <td><?= e(ucfirst($f['actor_tipo']) . ' #' . $f['actor_id']) ?></td>
          <td><?= e($f['accion']) ?></td><td><?= e($f['detalle']) ?></td></tr>
    <?php endforeach; ?></tbody>
  </table></div>
  <?php endif; ?>
</main>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

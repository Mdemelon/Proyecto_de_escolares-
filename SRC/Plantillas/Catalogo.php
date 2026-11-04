<?php

$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$errores = [];
$v = array_fill_keys(array_keys($campos), '');

if ($id) {
    $st = $pdo->prepare("SELECT * FROM $tabla WHERE id = ?");
    $st->execute([$id]);
    if (!($fila = $st->fetch())) { header('Location: ' . basename($_SERVER['PHP_SELF'])); exit; }
    foreach ($campos as $c => $_) { $v[$c] = (string)$fila[$c]; }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($campos as $c => [$etq, $tipo, $obl]) {
        $v[$c] = trim($_POST[$c] ?? '');
        if ($obl && $v[$c] === '') { $errores[] = "Escribe: $etq."; }
    }
    if (!$errores) {
        try {
            $cols = array_keys($campos);
            if ($id) {
                $pdo->prepare("UPDATE $tabla SET " . implode('=?, ', $cols) . '=? WHERE id=?')
                    ->execute([...array_values($v), $id]);
            } else {
                $pdo->prepare("INSERT INTO $tabla (" . implode(',', $cols) . ') VALUES (' . implode(',', array_fill(0, count($cols), '?')) . ')')
                    ->execute(array_values($v));
            }
            bitacora('admin', $u['id'], ($id ? 'editar_' : 'alta_') . $tabla, reset($v));
            header('Location: ' . basename($_SERVER['PHP_SELF']) . '?ok=1'); exit;
        } catch (PDOException $ex) {
            $errores[] = $ex->getCode() === '23000' ? 'Ese registro ya existe.' : 'No se pudo guardar.';
        }
    }
}
$filas = $pdo->query("SELECT * FROM $tabla ORDER BY id DESC")->fetchAll();
require RAIZ . '/SRC/Plantillas/Header.php';
?>
<main class="contenido">
  <h1><?= e($titulo) ?></h1>
  <?php if (isset($_GET['ok'])): ?><p class="alerta ok" role="status">Guardado correctamente.</p><?php endif; ?>
  <?php foreach ($errores as $er): ?><p class="alerta error" role="alert"><?= e($er) ?></p><?php endforeach; ?>

  <form method="post" class="formulario">
    <h2 class="h5"><?= $id ? 'Editar registro' : 'Agregar registro' ?></h2>
    <?php foreach ($campos as $c => [$etq, $tipo, $obl]): ?>
      <label><?= e($etq) ?>
        <input name="<?= e($c) ?>" type="<?= e($tipo) ?>" value="<?= e($tipo === 'time' ? substr($v[$c], 0, 5) : $v[$c]) ?>" <?= $obl ? 'required' : '' ?>>
      </label>
    <?php endforeach; ?>
    <button class="boton">Guardar</button>
    <?php if ($id): ?><a href="<?= e(basename($_SERVER['PHP_SELF'])) ?>">Cancelar</a><?php endif; ?>
  </form>

  <?php if (!$filas): ?>
    <p class="vacio">Aún no hay registros.</p>
  <?php else: ?>
  <div class="tabla-scroll"><table>
    <thead><tr><?php foreach ($campos as [$etq]): ?><th><?= e($etq) ?></th><?php endforeach; ?><th></th></tr></thead>
    <tbody>
    <?php foreach ($filas as $f): ?>
      <tr>
        <?php foreach ($campos as $c => [$etq, $tipo]): ?><td><?= e($tipo === 'time' ? substr($f[$c], 0, 5) : $f[$c]) ?></td><?php endforeach; ?>
        <td><a href="?id=<?= (int)$f['id'] ?>">Editar</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <?php endif; ?>
</main>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

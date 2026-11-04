<?php
require __DIR__ . '/../../../SRC/Config/Inicio.php';
$u = requerir_rol('admin');
$pdo = db();

// Listas para los <select> (salen de la base de datos)
$carreras      = $pdo->query('SELECT id, nombre FROM carreras ORDER BY nombre')->fetchAll();
$dependencias  = $pdo->query('SELECT id, nombre FROM dependencias ORDER BY nombre')->fetchAll();
$turnos        = $pdo->query('SELECT id, nombre, hora_inicio, hora_fin FROM turnos ORDER BY id')->fetchAll();

$v = ['numero_control'=>'','nombre'=>'','carrera_id'=>'','telefono'=>'','dependencia_id'=>'','turno_id'=>'','periodo'=>'','fecha_inicio'=>''];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $campo => $_) { $v[$campo] = trim($_POST[$campo] ?? ''); }
    $clave = $_POST['clave'] ?? '';

    if (!preg_match('/^[0-9]{8}$/', $v['numero_control'])) { $errores[] = 'El número de control debe tener 8 dígitos.'; }
    if ($v['nombre'] === '')                                { $errores[] = 'Escribe el nombre completo.'; }
    if (!in_array((int)$v['carrera_id'], array_map('intval', array_column($carreras, 'id')), true)) { $errores[] = 'Selecciona una carrera.'; }
    if (!in_array((int)$v['turno_id'], array_map('intval', array_column($turnos, 'id')), true))      { $errores[] = 'Selecciona un turno.'; }
    if ($v['dependencia_id'] !== '' && !in_array((int)$v['dependencia_id'], array_map('intval', array_column($dependencias, 'id')), true)) { $errores[] = 'Dependencia no válida.'; }
    if ($v['periodo'] === '')                               { $errores[] = 'Escribe el periodo escolar.'; }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $v['fecha_inicio'])) { $errores[] = 'Selecciona la fecha de inicio.'; }
    if (strlen($clave) < 6)                                 { $errores[] = 'La contraseña debe tener mínimo 6 caracteres.'; }

    if (!$errores) {
        $st = $pdo->prepare('SELECT COUNT(*) FROM alumnos WHERE numero_control = ?');
        $st->execute([$v['numero_control']]);
        if ($st->fetchColumn() > 0) { $errores[] = 'Ya existe un alumno con ese número de control.'; }
    }

    if (!$errores) {
        try {
            $pdo->beginTransaction();
            $pdo->prepare('INSERT INTO alumnos
                (numero_control, nombre, carrera_id, telefono, dependencia_id, password_hash)
                VALUES (?,?,?,?,?,?)')
                ->execute([
                    $v['numero_control'], $v['nombre'], (int)$v['carrera_id'],
                    $v['telefono'] !== '' ? $v['telefono'] : null,
                    $v['dependencia_id'] !== '' ? (int)$v['dependencia_id'] : null,
                    password_hash($clave, PASSWORD_DEFAULT),
                ]);
            $alumnoId = (int)$pdo->lastInsertId();
            $pdo->prepare('INSERT INTO asignaciones_servicio (alumno_id, turno_id, periodo, fecha_inicio)
                           VALUES (?,?,?,?)')
                ->execute([$alumnoId, (int)$v['turno_id'], $v['periodo'], $v['fecha_inicio']]);
            $pdo->commit();
        } catch (Throwable $ex) {
            if ($pdo->inTransaction()) { $pdo->rollBack(); }
            $errores[] = 'No se pudo guardar el alumno. Intenta de nuevo.';
        }
    }

    if (!$errores) {
        bitacora('admin', $u['id'], 'alta_alumno', 'Número de control: ' . $v['numero_control']);
        header('Location: lista.php?ok=1');
        exit;
    }
}

$titulo = 'Agregar alumno';
require RAIZ . '/SRC/Plantillas/Header.php';
?>
<main class="contenido">
    <div class="titulo-pagina">
        <h1>Agregar alumno</h1>
        <a href="lista.php">Volver a la lista</a>
    </div>

    <?php if ($errores): ?>
        <div class="alerta error" role="alert">
            <ul><?php foreach ($errores as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <form method="post" class="formulario formulario-ancho">

        <fieldset>
            <legend>Datos del alumno</legend>

            <label>Número de control
                <input name="numero_control" required maxlength="8" inputmode="numeric" pattern="[0-9]{8}"
                       title="8 dígitos" value="<?= e($v['numero_control']) ?>">
            </label>

            <label>Nombre completo
                <input name="nombre" required maxlength="120" autocomplete="off" value="<?= e($v['nombre']) ?>">
            </label>

            <label>Carrera
                <select name="carrera_id" required>
                    <option value="">Selecciona una carrera</option>
                    <?php foreach ($carreras as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= (string)$c['id'] === (string)$v['carrera_id'] ? 'selected' : '' ?>><?= e($c['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Teléfono
                <input name="telefono" type="tel" maxlength="20" inputmode="tel" autocomplete="off" value="<?= e($v['telefono']) ?>">
            </label>

            <label>Dependencia de origen
                <select name="dependencia_id">
                    <option value="">Sin dependencia</option>
                    <?php foreach ($dependencias as $d): ?>
                        <option value="<?= (int)$d['id'] ?>" <?= (string)$d['id'] === (string)$v['dependencia_id'] ? 'selected' : '' ?>><?= e($d['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </fieldset>

        <fieldset>
            <legend>Asignación de servicio</legend>

            <label>Turno
                <select name="turno_id" required>
                    <option value="">Selecciona un turno</option>
                    <?php foreach ($turnos as $t): ?>
                        <option value="<?= (int)$t['id'] ?>" <?= (string)$t['id'] === (string)$v['turno_id'] ? 'selected' : '' ?>>
                            <?= e($t['nombre']) ?> (<?= e(substr($t['hora_inicio'], 0, 5)) ?> – <?= e(substr($t['hora_fin'], 0, 5)) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Periodo escolar
                <input name="periodo" required placeholder="2026-2" maxlength="20" value="<?= e($v['periodo']) ?>">
            </label>

            <label>Fecha de inicio
                <input name="fecha_inicio" type="date" required value="<?= e($v['fecha_inicio']) ?>">
            </label>
        </fieldset>

        <fieldset>
            <legend>Acceso al sistema</legend>

            <label>Contraseña inicial
                <input name="clave" type="password" required minlength="6" autocomplete="new-password">
            </label>
            <p class="ayuda">Mínimo 6 caracteres.</p>
        </fieldset>

        <div class="acciones-formulario">
            <button class="boton">Guardar alumno</button>
            <a href="lista.php" class="boton secundario">Cancelar</a>
        </div>
    </form>
</main>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

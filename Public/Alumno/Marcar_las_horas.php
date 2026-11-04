<?php
require __DIR__ . '/../../SRC/Config/Inicio.php';
require RAIZ . '/SRC/Modelos/Registro_de_las_horas.php';
$u = requerir_rol('alumno');

$st = db()->prepare('SELECT estatus, horas_acumuladas FROM alumnos WHERE id = ?');
$st->execute([$u['id']]);
$alumno = $st->fetch();
if (!$alumno) { header('Location: ' . BASE_URL . '/Salir.php'); exit; }

$abierto = Registo_Horas::turno_Abierto($u['id']);
$titulo = 'Inicio';
require RAIZ . '/SRC/Plantillas/Header.php';
?>
<main class="contenido estrecho">
    <h1>Hola, <?= e($u['nombre']) ?></h1>
    <p class="progreso"><strong><?= number_format($alumno['horas_acumuladas'], 2) ?></strong> de <?= HORAS_REQUERIDAS ?> horas</p>

    <?php if ($alumno['estatus'] === 'suspendido'): ?>
        <p class="alerta error">Tu servicio social fue dado de baja por sanción. Acércate con el encargado del Centro de Cómputo.</p>
    <?php elseif ($alumno['estatus'] === 'finalizado'): ?>
        <p class="alerta ok">Completaste tus <?= HORAS_REQUERIDAS ?> horas de servicio social.</p>
    <?php else: ?>
        <?php if ($abierto): ?>
            <p>Entrada registrada a las <strong><?= date('H:i', strtotime($abierto['hora_entrada'])) ?></strong>.
                Tiempo transcurrido: <strong id="contador" data-inicio="<?= strtotime($abierto['hora_entrada']) ?>">00:00:00</strong></p>
            <button class="boton" id="btn-marcar" data-accion="Marcar_salidas">Marcar salida</button>
        <?php else: ?>
            <button class="boton" id="btn-marcar" data-accion="Marcar-entradas">Marcar entrada</button>
        <?php endif; ?>
        <p id="mensaje" class="alerta" hidden role="status"></p>
    <?php endif; ?>
</main>

<script>const API = '<?= BASE_URL ?>/Api_Marcar/';</script>
<script src="<?= BASE_URL ?>/Assets/js/marcar.js"></script>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

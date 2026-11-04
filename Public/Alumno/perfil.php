<?php
require __DIR__ . '/../../SRC/Config/Inicio.php';
$u = requerir_rol('alumno');
$st = db()->prepare('SELECT a.*, c.nombre AS carrera, d.nombre AS dependencia
    FROM alumnos a JOIN carreras c ON c.id = a.carrera_id
    LEFT JOIN dependencias d ON d.id = a.dependencia_id WHERE a.id = ?');
$st->execute([$u['id']]);
$a = $st->fetch();
if (!$a) { header('Location: ' . BASE_URL . '/Salir.php'); exit; }
$titulo = 'Mi perfil';
require RAIZ . '/SRC/Plantillas/Header.php';
?>

<main class="contenido estrecho">
    <h1>Mi perfil</h1>
    <table>
        <tr><th>Número de control</th><td><?= e($a['numero_control']) ?></td></tr>
        <tr><th>Nombre</th><td><?= e($a['nombre']) ?></td></tr>
        <tr><th>Carrera</th><td><?= e($a['carrera']) ?></td></tr>
        <tr><th>Teléfono</th><td><?= e($a['telefono'] ?: '—') ?></td></tr>
        <tr><th>Dependencia</th><td><?= e($a['dependencia'] ?: '—') ?></td></tr>
        <tr><th>Estatus</th><td><?= e(ucfirst($a['estatus'])) ?></td></tr>
        <tr><th>Horas acumuladas</th><td><?= e(number_format((float)$a['horas_acumuladas'], 2)) ?> de <?= HORAS_REQUERIDAS ?></td></tr>
    </table>
</main>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

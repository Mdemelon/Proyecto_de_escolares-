<?php
require __DIR__ . '/../../SRC/Config/Inicio.php';
$u = requerir_rol('admin');
$pdo = db();

$activos   = (int)$pdo->query("SELECT COUNT(*) FROM alumnos WHERE estatus = 'activo'")->fetchColumn();
$finaliz   = (int)$pdo->query("SELECT COUNT(*) FROM alumnos WHERE estatus = 'finalizado'")->fetchColumn();
$enCurso   = (int)$pdo->query("SELECT COUNT(*) FROM registro_horas WHERE estatus = 'en_curso'")->fetchColumn();

$titulo = 'Panel';
require RAIZ . '/SRC/Plantillas/Header.php';
?>
<main class="contenido">
    <h1>Panel de administración</h1>
    <p>Bienvenido, <?= e($u['nombre']) ?>.</p>
    <table>
        <tr><th>Alumnos activos</th><td><?= $activos ?></td></tr>
        <tr><th>Alumnos que ya finalizaron</th><td><?= $finaliz ?></td></tr>
        <tr><th>Jornadas en curso ahora</th><td><?= $enCurso ?></td></tr>
    </table>
</main>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

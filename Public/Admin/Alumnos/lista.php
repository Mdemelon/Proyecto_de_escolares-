<?php
require __DIR__ . '/../../../SRC/Config/Inicio.php';
$u = requerir_rol('admin');

$alumnos = db()->query(
    'SELECT a.id, a.numero_control, a.nombre, a.estatus, a.horas_acumuladas, c.nombre AS carrera
     FROM alumnos a JOIN carreras c ON c.id = a.carrera_id
     ORDER BY a.nombre'
)->fetchAll();

$titulo = 'Alumnos';
require RAIZ . '/SRC/Plantillas/Header.php';
?>
<main class="contenido">
    <div class="titulo-pagina">
        <h1>Alumnos</h1>
        <a class="boton" href="formulario.php">Agregar alumno</a>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <p class="alerta ok" role="status">Alumno guardado correctamente.</p>
    <?php endif; ?>

    <?php if (!$alumnos): ?>
        <p class="vacio">Aún no hay alumnos registrados.</p>
    <?php else: ?>
    <div class="tabla-scroll">
        <table>
            <thead>
                <tr><th>Número de control</th><th>Nombre</th><th>Carrera</th><th>Horas</th><th>Estatus</th></tr>
            </thead>
            <tbody>
            <?php foreach ($alumnos as $a): ?>
                <tr>
                    <td><?= e($a['numero_control']) ?></td>
                    <td><?= e($a['nombre']) ?></td>
                    <td><?= e($a['carrera']) ?></td>
                    <td><?= e(number_format((float)$a['horas_acumuladas'], 2)) ?> / <?= HORAS_REQUERIDAS ?></td>
                    <td><?= e(ucfirst($a['estatus'])) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</main>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

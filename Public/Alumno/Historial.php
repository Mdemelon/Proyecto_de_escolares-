<?php
require __DIR__ . '/../../SRC/Config/Inicio.php';
$u = requerir_rol('alumno');
$pdo = db();

$st = $pdo->prepare('SELECT fecha, hora_entrada, hora_salida, horas, estatus
                     FROM registro_horas WHERE alumno_id = ? ORDER BY hora_entrada DESC');
$st->execute([$u['id']]);
$filas = $st->fetchAll();

$st = $pdo->prepare('SELECT horas_acumuladas FROM alumnos WHERE id = ?');
$st->execute([$u['id']]);
$total = (float)$st->fetchColumn();

$nombres = [1=>'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$meses = [];
foreach ($filas as $f) {
    $m = substr($f['fecha'], 0, 7);
    $meses[$m] = $nombres[(int)substr($m, 5, 2)] . ' ' . substr($m, 0, 4);
}

$titulo = 'Historial';
require RAIZ . '/SRC/Plantillas/Header.php';
?>
<main class="contenido">
    <h1>Mi historial de horas</h1>

    <div class="barra-herramientas">
        <label>Mes
            <select id="filtro-mes">
                <option value="">Todos</option>
                <?php foreach ($meses as $valor => $texto): ?>
                    <option value="<?= e($valor) ?>"><?= e($texto) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="button" class="boton" onclick="window.print()">Imprimir</button>
    </div>

    <p class="progreso">Total acumulado: <strong><?= number_format($total, 2) ?></strong> de <?= HORAS_REQUERIDAS ?> horas</p>

    <?php if (!$filas): ?>
        <p class="vacio">Aún no tienes jornadas registradas.</p>
    <?php else: ?>
    <div class="tabla-scroll">
        <table id="tabla-horas">
            <caption class="vacio">Tus jornadas registradas</caption>
            <thead>
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Entrada</th>
                    <th scope="col">Salida</th>
                    <th scope="col">Horas</th>
                    <th scope="col">Estatus</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($filas as $f): ?>
                <tr data-mes="<?= e(substr($f['fecha'], 0, 7)) ?>">
                    <td><?= e($f['fecha']) ?></td>
                    <td><?= e(date('H:i', strtotime($f['hora_entrada']))) ?></td>
                    <td><?= $f['hora_salida'] ? e(date('H:i', strtotime($f['hora_salida']))) : '—' ?></td>
                    <td><?= $f['horas'] !== null ? e(number_format((float)$f['horas'], 2)) : '—' ?></td>
                    <td><?= $f['estatus'] === 'en_curso' ? 'En curso' : 'Completado' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <p id="sin-resultados" class="vacio" hidden>No hay jornadas registradas en ese mes.</p>
</main>

<script>
    const filtro = document.getElementById('filtro-mes');
    const filas = document.querySelectorAll('#tabla-horas tbody tr');
    const vacio = document.getElementById('sin-resultados');

    filtro.addEventListener('change', () => {
        let visibles = 0;
        filas.forEach(f => {
            const mostrar = !filtro.value || f.dataset.mes === filtro.value;
            f.hidden = !mostrar;
            if (mostrar) visibles++;
        });
        vacio.hidden = visibles > 0 || filas.length === 0;
    });
</script>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

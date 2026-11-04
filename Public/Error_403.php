<?php
require __DIR__ . '/../SRC/Config/Inicio.php';
http_response_code(403);
$titulo = 'Acceso denegado';
require RAIZ . '/SRC/Plantillas/Header.php';
?>
<main class="contenido estrecho">
    <h1>Acceso denegado</h1>
    <p>No tienes permiso para ver esta página.</p>
    <p><a href="<?= BASE_URL ?>/index.php">Volver al inicio</a></p>
</main>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

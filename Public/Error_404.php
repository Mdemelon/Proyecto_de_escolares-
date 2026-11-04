<?php
require __DIR__ . '/../SRC/Config/Inicio.php';
http_response_code(404);
$titulo = 'Página no encontrada';
require RAIZ . '/SRC/Plantillas/Header.php';
?>
<main class="contenido estrecho">
    <h1>Página no encontrada</h1>
    <p>La dirección no existe o fue movida.</p>
    <p><a href="<?= BASE_URL ?>/index.php">Volver al inicio</a></p>
</main>
<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

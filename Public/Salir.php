<?php
require __DIR__ . '/../SRC/Config/Inicio.php';
$_SESSION = [];
session_destroy();
header('Location: ' . BASE_URL . '/Inicio_de_sesion.php');
exit;

<?php
require __DIR__ . '/../../SRC/Config/Inicio.php';
$u = requerir_rol('admin');
$tabla = 'carreras';
$titulo = 'Carreras';
$campos = ['nombre' => ['Nombre de la carrera', 'text', true]];
require RAIZ . '/SRC/Plantillas/Catalogo.php';

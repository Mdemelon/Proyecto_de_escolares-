<?php
require __DIR__ . '/../../SRC/Config/Inicio.php';
$u = requerir_rol('admin');
$tabla = 'turnos';
$titulo = 'Turnos';
$campos = ['nombre' => ['Nombre del turno', 'text', true], 'hora_inicio' => ['Hora de inicio', 'time', true], 'hora_fin' => ['Hora de fin', 'time', true], 'periodo' => ['Periodo escolar (ej. 2026-2)', 'text', true]];
require RAIZ . '/SRC/Plantillas/Catalogo.php';

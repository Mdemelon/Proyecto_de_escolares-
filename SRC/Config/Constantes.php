<?php
date_default_timezone_set('America/Hermosillo');
define('RAIZ', dirname(__DIR__, 2));
define('BASE_URL', '/Servicio_de_tec/Public');
define('HORAS_REQUERIDAS', 500);

function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
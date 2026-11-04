<?php
require __DIR__ . '/../SRC/Config/Inicio.php';
$u = usuario_actual();
if (!$u)                      { $destino = '/Inicio_de_sesion.php'; }
elseif ($u['rol'] === 'admin') { $destino = '/Admin/Panel.php'; }
else                          { $destino = '/Alumno/Marcar_las_horas.php'; }
header('Location: ' . BASE_URL . $destino);
exit;

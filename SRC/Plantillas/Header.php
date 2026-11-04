<?php $u = usuario_actual(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titulo ?? 'Servicio social') ?> · ITAP</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/Assets/CSS/bootstrap.min.css">

  <link rel="stylesheet" href="<?= BASE_URL ?>/Assets/CSS/componestes.css">

</head>
<body>

<nav class="navbar navbar-expand-xl navbar-dark" style="background:#1B396A;border-bottom:4px solid #F2C400">
  <div class="container-fluid">
    <span class="navbar-brand">Servicio social <small class="d-block fs-6 opacity-75">Centro de Cómputo · ITAP</small></span>
    <?php if ($u): ?>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
              aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menú">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menuPrincipal">
        <ul class="navbar-nav ms-auto">
          <?php require RAIZ . '/SRC/Plantillas/' . ($u['rol'] === 'admin' ? 'Menu-admin.php' : 'Menu-alumno.php'); ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/Salir.php">Cerrar sesión</a></li>
        </ul>
      </div>
    <?php endif; ?>
  </div>
</nav>
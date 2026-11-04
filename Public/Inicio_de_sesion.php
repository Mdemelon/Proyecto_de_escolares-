<?php
require __DIR__ . '/../SRC/Config/Inicio.php';
require RAIZ . '/SRC/Auth/Login.php';

if (usuario_actual()) { header('Location: ' . BASE_URL . '/index.php'); exit; }

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $clave   = $_POST['clave'] ?? '';
    $error = intentar_login($usuario, $clave);
}

$titulo = 'Inicio de Sesión - Servicio Social';
require RAIZ . '/SRC/Plantillas/Header.php';
?>

<main class="contenido estrecho contenedor_de_Inicio_de_sesion">
    <h1>Iniciar Sesión</h1>
    <?php if ($error): ?>
        <p class="alerta error" role="alert"><?= e($error) ?></p>
    <?php endif; ?>
    <form method="post" class="formulario">
        <label>Usuario o número de control
            <input name="usuario" required autocomplete="username">
        </label>
        <label>Contraseña
            <input name="clave" type="password" required autocomplete="current-password">
        </label>
        <button class="boton">Entrar</button>
    </form>
</main>

<?php require RAIZ . '/SRC/Plantillas/Footer.php'; ?>

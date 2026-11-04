<?php
function intentar_login(string $usuario, string $clave): ?string {
    $usuario = trim($usuario);
    $pdo = db();

    $rol = 'admin';
    $st = $pdo->prepare('SELECT id, nombre, password_hash FROM maestros WHERE usuario = ? LIMIT 1');
    $st->execute([$usuario]);
    $fila = $st->fetch();

    if (!$fila) {
        $rol = 'alumno';
        $st = $pdo->prepare('SELECT id, nombre, password_hash FROM alumnos WHERE numero_control = ? LIMIT 1');
        $st->execute([$usuario]);
        $fila = $st->fetch();
    }

    if (!$fila || !password_verify($clave, $fila['password_hash'])) {
        return 'Usuario o contraseña incorrectos.';
    }

    session_regenerate_id(true);
    $_SESSION['usuario'] = [
        'id'     => (int)$fila['id'],
        'rol'    => $rol,
        'nombre' => $fila['nombre'],
    ];
    bitacora($rol, (int)$fila['id'], 'login');

    header('Location: ' . BASE_URL . ($rol === 'admin' ? '/Admin/Panel.php' : '/Alumno/Marcar_las_horas.php'));
    exit;
}

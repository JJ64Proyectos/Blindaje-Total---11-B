<?php
session_start();
$archivo_db = 'usuarios.json';

// Crear usuario de prueba por defecto si no existe la base de datos
if (!file_exists($archivo_db)) {
    $usuario_inicial = [
        [
            'correo' => 'admin@ironvault.com',
            'usuario' => 'IronAdmin',
            'password' => password_hash('vault2026', PASSWORD_DEFAULT)
        ]
    ];
    file_put_contents($archivo_db, json_encode($usuario_inicial, JSON_PRETTY_PRINT));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    $contenido = file_get_contents($archivo_db);
    $usuarios = json_decode($contenido, true) ?: [];

    $encontrado = false;
    foreach ($usuarios as $u) {
        if (($u['correo'] === $correo || $u['usuario'] === $usuario) && password_verify($password, $u['password'])) {
            $encontrado = true;
            $_SESSION['usuario'] = $u['usuario'];
            break;
        }
    }

    if ($encontrado) {
        // Redirección rápida a tu panel
        header("Location: IRONVAULT.html");
        exit();
    } else {
        echo "<script>alert('Correo, usuario o contraseña incorrectos.'); window.location.href='login.html';</script>";
        exit();
    }
} else {
    header("Location: login.html");
    exit();
}
?>
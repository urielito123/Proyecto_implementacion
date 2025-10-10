<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = $user['username'];
        echo "<h2 style='color:#00ffcc;'>Bienvenido, {$user['username']} 🎮</h2>";
        echo "<p><a href='logout.php'>Cerrar sesión</a></p>";
    } else {
        echo "<p style='color:#ff0066;'>❌ Usuario o contraseña incorrectos</p>";
    }
}
?>

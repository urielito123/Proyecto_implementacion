<?php
require 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token=? AND token_expire > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET password_hash=?, reset_token=NULL, token_expire=NULL WHERE id=?");
            $stmt->execute([$new_pass, $user['id']]);
            echo "<div class='msj-ok'>✅ Contraseña actualizada. <a href='index.html'>Iniciar sesión</a></div>";
            exit;
        }
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Restablecer Contraseña</title>
<style>
    body {
        background-color: #0e0e0e;
        font-family: Arial, sans-serif;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    form {
        background: #1a1a1a;
        padding: 30px;
        width: 350px;
        border-radius: 8px;
        box-shadow: 0px 0px 15px rgba(255, 0, 0, 0.8);
        text-align: center;
    }
    h2 {
        margin-bottom: 20px;
        color: #ff3b3b;
    }
    input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        background: #2a2a2a;
        border: none;
        color: white;
        border-radius: 5px;
    }
    button {
        width: 100%;
        padding: 10px;
        background: #e60000;
        border: none;
        color: white;
        font-weight: bold;
        cursor: pointer;
        border-radius: 5px;
        transition: 0.3s;
    }
    button:hover {
        background: #ff1a1a;
        box-shadow: 0 0 10px red;
    }
    a { color: #ff4040; text-decoration: none; }
    a:hover { text-decoration: underline; }
    .msj-ok, .msj-error {
        background: black;
        color: white;
        padding: 20px;
        text-align: center;
    }
</style>
</head>
<body>
<form method="POST">
  <h2>Restablecer Contraseña</h2>
  <input type="password" name="password" placeholder="Nueva contraseña" required>
  <button type="submit">Actualizar</button>
</form>
</body>
</html>
<?php
    } else {
        echo "<div class='msj-error'>⚠️ Enlace inválido o expirado.</div>";
    }
}
?>

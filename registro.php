<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $recaptcha_secret = "6LcIreUrAAAAAAYkDa67tTKRTu5_XGIq_JesZ-rn"; // tu clave secreta
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Verificar respuesta del CAPTCHA con Google
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $responseKeys = json_decode($response, true);

    if(!$responseKeys["success"]) {
        die("<div style='font-family:Orbitron,sans-serif;color:#ff0066;text-align:center;background:#0a0a0f;height:100vh;display:flex;align-items:center;justify-content:center;'>
        ⚠️ Verificación fallida: demuestra que no eres un robot.<br><a href='registro.php' style='color:#00ffcc;'>Volver</a></div>");
    }

    if (strlen($password) < 8) {
        die("<div style='font-family:Orbitron,sans-serif;color:#ff0066;text-align:center;background:#0a0a0f;height:100vh;display:flex;align-items:center;justify-content:center;'>
        ⚠️ La contraseña debe tener al menos 8 caracteres.<br><a href='registro.php' style='color:#00ffcc;'>Volver</a></div>");
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    try {
        $stmt->execute([$username, $password_hash]);
        echo "<div style='font-family:Orbitron,sans-serif;color:#00ffcc;text-align:center;background:#0a0a0f;height:100vh;display:flex;align-items:center;justify-content:center;flex-direction:column;'>
        ✅ Usuario registrado con éxito<br><a href='index.html' style='color:#00ffff;text-decoration:none;'>Iniciar sesión</a></div>";
    } catch (PDOException $e) {
        echo "<div style='font-family:Orbitron,sans-serif;color:#ff0066;text-align:center;background:#0a0a0f;height:100vh;display:flex;align-items:center;justify-content:center;flex-direction:column;'>
        ⚠️ Error: El usuario ya existe o los datos son inválidos.<br><a href='registro.php' style='color:#00ffcc;'>Intentar de nuevo</a></div>";
    }
} else {
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro Gamer</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <style>
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(circle at top, #0a0a0f, #1a0022);
      font-family: 'Orbitron', sans-serif;
      overflow: hidden;
    }
    .login-container {
      background: rgba(15, 15, 25, 0.95);
      padding: 2.5rem;
      border-radius: 15px;
      box-shadow: 0 0 30px #00ffcc;
      width: 360px;
      text-align: center;
      color: #00ffcc;
      animation: glow 2s ease-in-out infinite alternate;
    }
    @keyframes glow {
      from { box-shadow: 0 0 20px #00ffff; }
      to { box-shadow: 0 0 35px #00ffcc; }
    }
    h2 {
      text-shadow: 0 0 15px #00ffff;
      margin-bottom: 1.8rem;
      letter-spacing: 1px;
    }
    .input-group { margin-bottom: 1.2rem; }
    input {
      width: 100%;
      padding: 0.8rem;
      border: none;
      border-radius: 8px;
      background: #111;
      color: #00ffcc;
      font-size: 1rem;
      box-shadow: inset 0 0 10px #00ffcc;
      outline: none;
      transition: box-shadow 0.3s;
    }
    input:focus { box-shadow: 0 0 20px #00ffff; }
    button {
      background: linear-gradient(90deg, #00ffcc, #00ffff);
      border: none;
      padding: 0.8rem 1.5rem;
      border-radius: 10px;
      color: #000;
      font-weight: bold;
      font-size: 1rem;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    button:hover {
      transform: scale(1.05);
      box-shadow: 0 0 25px #00ffff;
    }
    .g-recaptcha {
      margin: 1rem auto;
      display: flex;
      justify-content: center;
      filter: drop-shadow(0 0 10px #00ffff);
    }
    a {
      display: inline-block;
      color: #00ffff;
      margin-top: 1rem;
      text-decoration: none;
      transition: color 0.3s;
    }
    a:hover { color: #fff; }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Registro Gamer ⚔️</h2>
    <form method="POST">
      <div class="input-group">
        <input type="text" name="username" placeholder="Usuario" required />
      </div>
      <div class="input-group">
        <input type="password" name="password" placeholder="Contraseña" required />
      </div>

      <!-- reCAPTCHA -->
      <div class="g-recaptcha" data-sitekey="6LcIreUrAAAAACmNRr0Ki0QDXmE9x8Q0ciQDR5b7"></div>

      <button type="submit">Registrarme</button>
      <a href="index.html">🔙 Volver al login</a>
    </form>
  </div>
</body>
</html>
<?php } ?>

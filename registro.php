<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $recaptcha_response = $_POST['g-recaptcha-response'];

    $recaptcha_secret = "6Le07eUrAAAAALqzpuvjmRShBvHpcxQlQTPmXq6O";

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $responseKeys = json_decode($response, true);
    if(!$responseKeys["success"]) {
        die("<div class='msj-error'>⚠️ Verificación fallida: demuestra que no eres un robot.<br><a href='registro.php'>Volver</a></div>");
    }

    if (strlen($password) < 8) {
        die("<div class='msj-error'>⚠️ La contraseña debe tener al menos 8 caracteres.</div>");
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $password_hash]);
        echo "<div class='msj-ok'>✅ Usuario registrado con éxito. <a href='index.html'>Iniciar sesión</a></div>";
    } catch (PDOException $e) {
        echo "<div class='msj-error'>⚠️ Error: el usuario o correo ya existen.<br><a href='registro.php'>Volver</a></div>";
    }
} else {
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro Gamer</title>
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
    .login-container {
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
    .input-group input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: none;
      background: #2a2a2a;
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
      transition: 0.3s ease;
    }
    button:hover {
      background: #ff1a1a;
      box-shadow: 0 0 10px red;
    }
    a {
      color: #ff4040;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    .msj-ok, .msj-error {
      background: black;
      color: white;
      padding: 20px;
      text-align: center;
    }
    .g-recaptcha {
      margin-top: 15px;
      display: flex;
      justify-content: center;
    }
  </style>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
  <div class="login-container">
    <h2>Registro</h2>
    <form method="POST">
      <div class="input-group">
        <input type="text" name="username" placeholder="Usuario" required />
      </div>
      <div class="input-group">
        <input type="email" name="email" placeholder="Correo electrónico" required />
      </div>
      <div class="input-group">
        <input type="password" name="password" placeholder="Contraseña (mínimo 8 caracteres)" required />
      </div>
      <div class="g-recaptcha" data-sitekey="6Le07eUrAAAAAI0QDzNNN5Hdt4niaVOaliT54_NR"></div>
      <button type="submit">Crear cuenta</button>
      <p><a href="index.html">Ya tengo una cuenta</a></p>
    </form>
  </div>
</body>
</html>
<?php } ?>

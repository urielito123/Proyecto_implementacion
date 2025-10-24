<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar Contraseña</title>
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
  .container {
    background: #1a1a1a;
    padding: 30px;
    width: 350px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 0 15px rgba(255, 0, 0, 0.8);
  }
  h2 {
    color: #ff3b3b;
    margin-bottom: 15px;
  }
  input {
    width: 100%;
    padding: 10px;
    margin-top: 12px;
    border: none;
    background: #2a2a2a;
    color: white;
    border-radius: 5px;
  }
  button {
    width: 100%;
    padding: 10px;
    margin-top: 15px;
    background: #e60000;
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
  }
  button:hover {
    background: #ff1a1a;
    box-shadow: 0 0 10px red;
  }
  a {
    color: #ff4040;
    text-decoration: none;
    font-size: 14px;
  }
  a:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>
  <div class="container">
    <h2>Recuperar Contraseña</h2>
    <form action="procesar_reset.php" method="POST">
      <input type="email" name="email" placeholder="Correo registrado" required>
      <button type="submit">Enviar enlace</button>
      <p><a href="index.html">🔙 Volver al Login</a></p>
    </form>
  </div>
</body>
</html>

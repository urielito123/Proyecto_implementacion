<?php
require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer-master/src/Exception.php';
require 'phpmailer/PHPMailer-master/src/PHPMailer.php';
require 'phpmailer/PHPMailer-master/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $expire = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt = $pdo->prepare("UPDATE users SET reset_token=?, token_expire=? WHERE id=?");
        $stmt->execute([$token, $expire, $user['id']]);

        $reset_link = "http://192.168.100.31/Proyecto/restablecer.php?token=$token";


        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'carlosurielgonzalezgonzalez01@gmail.com';
            $mail->Password = 'ambadbuzaawmautc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('carlosurielgonzalezgonzalez01@gmail.com', 'Soporte Gamer');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña';

            // ====== CORREO CON DISEÑO GAMER ======
            $mail->Body = '
            <html>
            <body style="
                background-color:#0e0e0e;
                color:white;
                font-family:Arial,sans-serif;
                text-align:center;
                padding:30px;">
                <div style="
                    background:#1a1a1a;
                    margin:auto;
                    width:400px;
                    padding:20px;
                    border-radius:8px;
                    box-shadow:0 0 15px rgba(255,0,0,0.8);">
                    <h2 style="color:#ff3b3b;">Recuperación de contraseña</h2>
                    <p>Haz clic en el siguiente botón para restablecer tu contraseña:</p>
                    <a href="'.$reset_link.'" style="
                        display:inline-block;
                        margin-top:15px;
                        padding:10px 20px;
                        background-color:#e60000;
                        color:white;
                        text-decoration:none;
                        font-weight:bold;
                        border-radius:5px;">
                        RESTABLECER CONTRASEÑA
                    </a>
                    <p style="margin-top:15px;font-size:12px;color:gray;">
                        Este enlace expirará en 1 hora.
                    </p>
                </div>
            </body>
            </html>';

            if ($mail->send()) {
                echo "
                <div style='
                    background-color:#0e0e0e;
                    height:100vh;
                    display:flex;
                    justify-content:center;
                    align-items:center;
                    color:white;
                    font-family:Arial,sans-serif;'>
                    <div style='
                        background:#1a1a1a;
                        padding:20px;
                        border-radius:8px;
                        box-shadow:0 0 15px red;
                        text-align:center;'>
                        ✅ Se envió un correo a <b>$email</b> con instrucciones para recuperar tu contraseña.<br><br>
                        <a href=\"index.html\" style='color:#ff3b3b;'>Volver al Login</a>
                    </div>
                </div>";
            }

        } catch (Exception $e) {
            echo "<div style='background:#1a1a1a;color:white;padding:20px;text-align:center;box-shadow:0 0 15px red;margin:50px auto;width:400px;'>
            ⚠️ Error al enviar el correo: {$mail->ErrorInfo}
            </div>";
        }
    } else {
        echo "<div style='background:#1a1a1a;color:white;padding:20px;text-align:center;box-shadow:0 0 15px red;margin:50px auto;width:400px;'>
        ⚠️ No se encontró ese correo registrado.
        </div>";
    }
}
?>

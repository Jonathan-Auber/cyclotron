<?php

namespace utils;

// Start with PHPMailer class
use PHPMailer\PHPMailer\PHPMailer;

require_once './vendor/autoload.php';


class Mailtrap
{
    function sendMail(string $email, string $username, string $password)
    { // create a new object
        $mail = new PHPMailer();
        // configure an SMTP
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '5921e62712de38';
        $mail->Password = 'd05529bcedef41';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;

        $mail->setFrom('cyclotron@proton.me', 'Cyclotron');
        $mail->addAddress($email, $username);
        $mail->Subject = 'Vos identifiants de connexion';
        // Set HTML 
        $mail->isHTML(TRUE);
        $mail->Body = "
    <html>Voici vos identifiants de connexion, ne les perdez pas !
        <ul>
            <li>Nom d'utilisateur : $email</li>
            <li>Mot de passe : $password</li>
            </ul>
    </html>";

        // send the message
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }
}

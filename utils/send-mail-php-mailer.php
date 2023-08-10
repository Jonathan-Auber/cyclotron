<?php

namespace utils;

// Start with PHPMailer class
use PHPMailer\PHPMailer\PHPMailer;

require_once './vendor/autoload.php';
// create a new object
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
$mail->addAddress('me@gmail.com', 'Me');
$mail->Subject = 'Identifiants de connexion';
// Set HTML 
$mail->isHTML(TRUE);
$mail->Body = "<html>Voici vos identifiants de connexion.
<ul>
<li>Nom d'utilisateur : </li>
<li>Mot de passe : </li>
</ul>
</html>";

// send the message
if (!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

//try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->CharSet = "UTF-8";
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = '4infrabh@gmail.com';                     //SMTP username
    $mail->Password   = 'fnszozjmfockyaqy';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('4infrabh@gmail.com', '4infra');
    for($i=0 ; $i < $n_palavras ; $i++ ){
        $mail->addAddress($array_email[$i]);
    }

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Retirada de Equipamento no Laboratório 4infra - '.$nome_cliente;
    $mail->Body    = $interacao_email;
    $mail->send();
//    echo 'Message enviada';
//} catch (Exception $e) {
//    echo "Message com erro. Mailer Error: {$mail->ErrorInfo}";
//}

?>
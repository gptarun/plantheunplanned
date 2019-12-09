<?php
function Send_Mail($to,$subject,$body)
{
require_once 'class.phpmailer.php';
$from       = "canopus.testing@gmail.com";
$mail       = new PHPMailer();
$mail->IsSMTP(true);            // use SMTP
$mail->IsHTML(true);
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "tls://smtp.gmail.com"; // Amazon SES server, note "tls://" protocol
$mail->Port       =  465;                    // set the SMTP port
$mail->Username   = "canopus.testing";  // SMTP  username
$mail->Password   = "canopus121";  // SMTP password
$mail->SetFrom($from, 'Esfaira Project');
$mail->AddReplyTo($from,'From Name');
$mail->Subject    = $subject;
$mail->MsgHTML($body);
$address = $to;
$mail->AddAddress($address, $to);
$mail->Send();   
}
?>

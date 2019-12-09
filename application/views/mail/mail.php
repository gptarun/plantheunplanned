<?php
require 'PHPMailer/PHPMailerAutoload.php';
// $this->load->view('PHPMailer/PHPMailerAutoload');
$mail = new PHPMailer;

$mail->isSMTP();                                   // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';                    // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                            // Enable SMTP authentication
$mail->Username = 'nag.ravi.111@gmail.com';          // SMTP username
$mail->Password = 'sarwan@26'; // SMTP password
$mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                 // TCP port to connect to

$mail->setFrom('111.nag.ravi@gmail.com');
$mail->addReplyTo('lovekeshkhare.com');
$mail->addAddress('lovekesh.com');   // Add a recipient
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

$mail->isHTML(true);  // Set email format to HTML

$bodyContent = '<h1>your password is xxxx</h1>';

$mail->Subject = 'Password recovered';
$mail->Body    = $bodyContent;

if(!$mail->send()) {
    // echo 'Message could not be sent.';
    // echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    // echo 'Message has been sent';
}
?>

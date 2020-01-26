<?php
defined('BASEPATH') or exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}
define('GUSER', 'sayhello@plantheunplanned.com');
define('GPWD', 'infinitywar@#1');
require 'C:/xampp/htdocs/code/code_api/vendor/autoload.php';

use League\OAuth2\Client\Provider\Google;
use PHPMailer\PHPMailer\OAuth;

class EmailController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function send_email()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $users = $post["users"];
        $emailBody = $post["emailBody"];

        $this->load->library("phpmailer_library");
        $mail = $this->phpmailer_library->load();

        $mail->IsSMTP(); // enable SMTP
        //$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true;  // authentication enabled
        $mail->AuthType = 'XOAUTH2';

        $email = GUSER;
        $clientId = '800130940633-1iuh9th4k15mdh505te3ct64sbp6ioue.apps.googleusercontent.com';
        $clientSecret = '0EhyCqiCubbUOD4UkFXFPrmo';
        //Obtained by configuring and running get_oauth_token.php
        //after setting up an app in Google Developer Console.
        $refreshToken = '1//0gktP6aCWCGesCgYIARAAGBASNwF-L9IrLPn1LMqCl10pRSo-TqTO2xaWDGb-7zOVd8VljYgGZoqcpkxaTEhfMmiatL2gG_d4ZYY';
        //Create a new OAuth2 provider instance
        $provider = new Google(
            [
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
            ]
        );
        //Pass the OAuth provider instance to PHPMailer
        $mail->setOAuth(
            new OAuth(
                [
                    'provider' => $provider,
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                    'refreshToken' => $refreshToken,
                    'userName' => $email,
                ]
            )
        );

        $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;

        $mail->SetFrom(GUSER, 'PlanTheUnplanned');
        $mail->Subject = $post["subject"];
        $mail->IsHTML(true);
        $mail->Body = $emailBody;
        foreach ($users as $value) {
            $mail->AddAddress($value);
        }
        //$mail->AddAddress('tarung1201@gmail.com');
        if (isset($post["leaderEmail"])) {
            $leaderEmail = $post["leaderEmail"];
            foreach ($leaderEmail as $value) {
                $mail->AddCC($value);
               
            }
        }
        $mail->AddCC(GUSER);

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        if (!$mail->Send()) {
            $error = 'Mail error: ' . $mail->ErrorInfo;
            $return_data = array(
                "status" => 400,
                "message" =>  $error,
                "Token" => null,
                "Success" => false
            );
            echo json_encode($return_data);
        } else {
            $error = 'Message sent!';
            $return_data = array(
                "status" => 200,
                "message" => $error,
                "Token" => null,
                "Success" => true
            );
            echo json_encode($return_data);
        }
    }
}

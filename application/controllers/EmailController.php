<?php
defined('BASEPATH') or exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}
define('GUSER', 'tarun@plantheunplanned.com');
define('GPWD', 'infinitywar@$3');

class EmailController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function send_email()
    {

        $this->load->library("phpmailer_library");
        $mail = $this->phpmailer_library->load();


        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 2;  // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true;  // authentication enabled
        $mail->AuthType = 'XOAUTH2';
        $mail->oauthUserEmail = GUSER;
        $mail->oauthClientId = '800130940633-1iuh9th4k15mdh505te3ct64sbp6ioue.apps.googleusercontent.com';
        $mail->oauthClientSecret = '0EhyCqiCubbUOD4UkFXFPrmo';
        //$mail->oauthRefreshToken = $token;
        $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;

        //$mail->Username = GUSER;
        //$mail->Password = GPWD;
        $mail->SetFrom(GUSER, 'PlanTheUnplanned');
        $mail->Subject = 'Testing';
        $mail->Body = 'Testing Body';
        $mail->AddAddress('tarung1201@gmail.com');

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        if (!$mail->Send()) {
            $error = 'Mail error: ' . $mail->ErrorInfo;
            return false;
        } else {
            $error = 'Message sent!';
            return true;
        }
        // $this->load->library('email');

        // $this->email->set_newline("\r\n");

        // $config['protocol'] = 'smtp';
        // $config['smtp_host'] = 'smtp.gmail.com';
        // $config['smtp_port'] = '587';
        // $config['smtp_user'] = 'tarun@plantheunplanned.com';
        // $config['smtp_from_name'] = 'Plan The Unplanned';
        // $config['smtp_pass'] = 'infinitywar@$3';
        // $config['wordwrap'] = TRUE;
        // $config['newline'] = "\r\n";
        // $config['mailtype'] = 'html';

        // //$this->email->initialize($config);
        // $this->load->library('email',$config);

        // $this->email->from('tarun@plantheunplanned.com');
        // $this->email->to('tarung1201@gmail.com');
        // //$this->email->cc($attributes['cc']);
        // //$this->email->bcc($attributes['cc']);
        // $this->email->subject('Mail Testing');

        // $this->email->message('Testing is done');

        // if ($this->email->send()) {
        //     return "Mail Sent";
        // } else {
        //     return "Failed";
        // }
    }
}

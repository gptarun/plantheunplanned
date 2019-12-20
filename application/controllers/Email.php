<?php
defined('BASEPATH') or exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class Email extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // $this->load->library(array('session'));
        // $this->load->model('homemodel');
    }

    public function send_email()
    {
        // echo "hello";
        $from_email = "shraddhaprinters009@gmail.com";
        // $to_email = $to;Email verification link has been sent to your email, please check your email.

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.shraddhaprinters.com'; //change this
        $config['smtp_port'] = '465';
        $config['smtp_user'] = 'shraddha'; //change this
        $config['smtp_pass'] = '@@rishabh#54321'; //change this
        $config['mailtype'] = 'html';
        $config['dsn'] = true;
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n";
        $this->load->library('email', $config);
        $div = "<h1>asdasdasdasd</h1>";
        $this->email->from($from_email, 'shraddhaprinters');
        $this->email->to('rishabhvirani007@gmail.com');
        $this->email->subject('Mail permo dharm');
        $this->email->message($div);

        $result =  $this->email->send();
        if ($result) {
            echo "Mail sent";
        } else {



            // Will only print the email headers, excluding the message subject and body
            print_r($this->email->print_debugger());
            // Loop through the debugger messages.
            // foreach ($this->email->get_debugger_messages() as $debugger_message)
            //     echo $debugger_message;

            // Remove the debugger messages as they're not necessary for the next attempt.
            // $this->email->clear_debugger_messages();
        }
    }
}

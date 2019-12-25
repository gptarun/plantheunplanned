<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Phpmailer_library
{
    public function __construct()
    {
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function load()
    {
        require_once(APPPATH . 'third_party/phpmailer/src/Exception.php');
        require_once(APPPATH . 'third_party/phpmailer/src/OAuth.php');
        require_once(APPPATH . 'third_party/phpmailer/src/PHPMailer.php');
        require_once(APPPATH . 'third_party/phpmailer/src/SMTP.php');
        $mail = new PHPMailer;
        return $mail;
    }
}

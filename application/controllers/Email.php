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
        echo "asd";
        die();
        echo "Asdasd";
    }
}

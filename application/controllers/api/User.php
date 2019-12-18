<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        // echo "Asd";
        // die();
    }


    public function user_get()
    {
        echo "Asd";
        die();
        $users = array();
        $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

    }
}

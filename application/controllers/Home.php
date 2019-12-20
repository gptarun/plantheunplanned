<?php
defined('BASEPATH') or exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->model('homemodel');
    }

    public function registration()
    {
        $current_password = $this->input->post('password');
        $cpassword = $this->input->post('cpassword');
        /*password encryption*/
        $CURRENT_PASSWORD = password_hash($current_password, PASSWORD_BCRYPT);
        $registration_data = array(
            'username' => $this->input->post('username'),
            'password' => $CURRENT_PASSWORD,
            'status' => "Active",
            'user_type' => "user",
            'created_date' => date('Y-m-d'),
        );
        $num_rows = $this->homemodel->checkUsername($registration_data['username']);
        if ($num_rows == 0) {
            $this->homemodel->registration($registration_data);
            $return_data = array(
                "Data" => null,
                "Status" => 200,
                "Message" => "User successfully Registered",
                "Token" => null,
                "Success" => true
            );
        } else {
            $return_data = array(
                "Data" => null,
                "Status" => 200,
                "Message" => "User already exist",
                "Token" => null,
                "Success" => false
            );
        }

        echo json_encode($return_data);
    }

    public function login()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $username = $post["username"];
        $password = $post["password"];

        $result = $this->homemodel->verifyLogin($username, $password);
        if ($result == "WRONG_PASSWORD") {
            $return_data = array(
                "Data" => null,
                "Status" => 200,
                "Message" => "Password is incorrect",
                "Token" => null,
                "Success" => false
            );
        } else if ($result == "NO_USER_FOUND") {
            $return_data = array(
                "Data" => null,
                "Status" => 200,
                "Message" => "User not found",
                "Token" => null,
                "Success" => false
            );
        } else {
            $data = array(
                'user' => $result
            );
            $return_data = array(
                "Data" => $data,
                "Status" => 200,
                "Message" => "User logged in successfully",
                "Token" => null,
                "Success" => true
            );
        }


        echo json_encode($return_data);
    }

    public function addUser()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $data = $post["data"];
        $this->db->insert('wp_users', $data);
        $this->db->insert_id();
        echo json_encode('Success');
    }

    public function deletUser()
    {
        //Write logic to delete
    }

    public function editUser()
    {
        //write logic to edit
    }

    public function searchUser()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $offset = $post["offset"];
        $limit = $post["limit"];
        $filter = $post["filter"];
        $search = $post["search"];
        $date = $post["date"];
        $date = substr($date, 0, 10);
        $result = $this->homemodel->searchUser($offset, $limit, $filter, $search, $date);

        echo json_encode($result);
    }

    public function searchUserCount()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $filter = $post["filter"];
        $search = $post["search"];
        $date = $post["date"];
        $date = substr($date, 0, 10);
        $result = $this->homemodel->searchUserCount($filter, $search, $date);

        echo json_encode($result);
    }

    public function getTrekLeaders(){
        $result = $this->homemodel->getTrekLeaders();
        echo json_encode($result);
    }

    public function send_email()
    {
        // echo "hello";
        $from_email = "shraddhaprinters009@gmail.com";
        // $to_email = $to;Email verification link has been sent to your email, please check your email.

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.shraddhaprinters.com'; //change this
        // $config['smtp_port'] = '465';
        $config['smtp_user'] = 'shraddhaprinters'; //change this
        $config['smtp_pass'] = '@@#54321'; //change this
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 822 standard
        //Load email library 
        $this->load->library('email', $config);

        $this->email->from($from_email, 'shraddhaprinters');
        $this->email->to('rishabh virani here');
        $this->email->subject('Mail permo dharm');
        $this->email->message('Jai ram ji ki');

        //Send mail 
        return $this->email->send();
    }
}

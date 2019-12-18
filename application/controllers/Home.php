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

    public function getUsers()
    {
        $result = $this->homemodel->getUsers();
        echo json_encode($result);
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
        $filter = $post["filter"];
        $search = $post["search"];

        $result = $this->homemodel->searchUser($filter, $search);

        echo json_encode($result);
    }
}

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
        if ($result == "Success") {
            $return_data = array(
                "Data" => null,
                "Status" => 200,
                "Message" => "User logged in successfully",
                "Token" => null,
                "Success" => true
            );
        } else if ($result == "Failed") {
            $return_data = array(
                "Data" => null,
                "Status" => 200,
                "Message" => "Username or Password is incorrect",
                "Token" => null,
                "Success" => false
            );
        } 
        echo json_encode($return_data);
    }

    public function addUser()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $data = $post["data"];
        $query_result = $this->db->insert('wp_users', $data);
        //$query_result = $this->db->insert_id();
        if (!$query_result) {
            $return_data = array(
                "status" => 400,
                "message" => "User " . $data['user_nicename'] . " not added",
                "Token" => null,
                "Success" => false
            );
        } else {
            $return_data = array(
                "status" => 200,
                "message" => "User " . $data['user_nicename'] . " added successfully",
                "Token" => null,
                "Success" => true
            );
        }
        echo json_encode($return_data);
    }

    public function deletUser()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $data = $post["data"];
        $query = 'DELETE FROM wp_users WHERE ID in (';
        foreach ($data as $value) {
            $query = $query . $value . ', ';
        }
        $query = substr($query, 0, -2);
        $query = $query . ')';
        $query_result = $this->db->query($query);

        if (!$query_result) {
            $return_data = array(
                "status" => 400,
                "message" => "Users failed to delete",
                "Token" => null,
                "Success" => false
            );
        } else {
            $return_data = array(
                "status" => 200,
                "message" => "Users deleted successfully",
                "Token" => null,
                "Success" => true
            );
        }

        echo json_encode($return_data);
    }

    public function updateUser()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $data = $post["data"];
        $this->db->where('ID', $data['ID']);
        $query_result = $this->db->update('wp_users', $data);
        if (!$query_result) {
            $return_data = array(
                "status" => 400,
                "message" => "User " . $data['user_nicename'] . " not updated",
                "Token" => null,
                "Success" => false
            );
        } else {
            $return_data = array(
                "status" => 200,
                "message" => "User " . $data['user_nicename'] . " updated successfully",
                "Token" => null,
                "Success" => true
            );
        }

        echo json_encode($return_data);
    }

    public function getUserById()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $id = $post["id"];
        $result = $this->homemodel->getUserById($id);

        echo json_encode($result);
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

    public function getTrekLeaders()
    {
        $result = $this->homemodel->getTrekLeaders();
        echo json_encode($result);
    }

    public function getTreksByDate()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $date = $post["date"];
        $date = substr($date, 0, 10);
        $result = $this->homemodel->getTreksByDate($date);

        echo json_encode($result);
    }

    public function getEmailTemplates()
    {
        $result = $this->homemodel->getEmailTemplates();
        echo json_encode($result);
    }

    public function send_email()
    {

        // $this->email->from($from_email, 'shraddhaprinters');
        // $this->email->to('rishabh virani here');
        // $this->email->subject('Mail permo dharm');
        // $this->email->message('Jai ram ji ki');

        //Send mail 
        return $this->email->send();
    }

    public function getEmailText()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $email_id = $post["emailId"];
        $result = $this->homemodel->getEmailText($email_id);
        echo json_encode($result);
    }
}

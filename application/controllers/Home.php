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
        $this->load->library(array('csvreader'));
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

    public function changePassword()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $password = $post["data"];
        $this->db->set('password', $password);
        $this->db->where('username', 'admin');
        $query_result = $this->db->update('user_admin');
        if (!$query_result) {
            $return_data = array(
                "status" => 400,
                "message" => "Failed to update password",
                "Token" => null,
                "Success" => false
            );
        } else {
            $return_data = array(
                "status" => 200,
                "message" => "User Password updated successfully",
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

    public function getBookingTreks()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        if (isset($post["from"])) {
            $fromDate = $post["from"];
            $fromDate = substr($fromDate, 0, 10);
            $fromDate = strtotime($fromDate);
        } else {
            $fromDate = 0;
        }
        if (isset($post["to"])) {
            $toDate = $post["to"];
            $toDate = substr($toDate, 0, 10);
            $toDate = strtotime($toDate);
        } else {
            $toDate = time();
        }

        // echo json_encode("Before filter");
        // echo $fromDate;
        // echo $toDate;


        //  echo json_encode("After filter");
        //  echo $fromDate;
        //  echo $toDate;

        $trekIDList = [];
        $unserializeData = [];
        $result = $this->homemodel->getBookingTreks();

        foreach ($result as $value) {
            array_push($unserializeData, unserialize($value['meta_value']));
            //echo json_encode($trekIDList);
            foreach (unserialize($value['meta_value']) as $newFields) {
                $dbFrom = strtotime($newFields['from']);
                $dbTo = strtotime($newFields['to']);
                //echo json_encode($newFields['to']);
                if ($fromDate <= $dbTo && $toDate >= $dbFrom) {
                    array_push($trekIDList, $value['post_id']);
                }
                //  echo json_encode("After Search");
                //  echo $dbFrom;
                //  echo $dbTo;
            }
        }
        //echo json_encode($trekIDList);
        echo json_encode($this->homemodel->getBookingTreksName($trekIDList));
    }

    public function getBillingInfo()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $productId = $post['id'];
        echo json_encode($this->homemodel->getBillingInfo($productId));
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

    public function getTrekById()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $id = $post["id"];
        $result = $this->homemodel->getTrekById($id);

        echo json_encode($result);
    }

    public function updateTrek()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $data = $post["data"];
        $this->db->where('ID', $data['ID']);
        $query_result = $this->db->update('wp_posts', $data);
        if (!$query_result) {
            $return_data = array(
                "status" => 400,
                "message" => "User " . $data['post_title'] . " not updated",
                "Token" => null,
                "Success" => false
            );
        } else {
            $return_data = array(
                "status" => 200,
                "message" => "User " . $data['post_title'] . " updated successfully",
                "Token" => null,
                "Success" => true
            );
        }

        echo json_encode($return_data);
    }

    public function addUserTrek()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $data = $post["data"];
        $query_result = $this->db->insert('user_orders', $data);
        //$query_result = $this->db->insert_id();
        if (!$query_result) {
            $return_data = array(
                "status" => 400,
                "message" => "Order id " . $data['order_id'] . " not added",
                "Token" => null,
                "Success" => false
            );
        } else {
            $return_data = array(
                "status" => 200,
                "message" => "Order id " . $data['order_id'] . " added successfully",
                "Token" => null,
                "Success" => true
            );
        }
        echo json_encode($return_data);
    }

    //import from csv
    function csvImport()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $file_name = $_POST['file_name'];
        $result = $this->csvreader->parse_file($_FILES['csv']["tmp_name"]);
        echo "<pre>";
        var_dump($_FILES['csv']["tmp_name"]);
        var_dump($result);
        // die;
        $response = $this->processCsvImportData($file_name, $result);
        echo "<pre>";
        var_dump($response);
        $data_res['page_title'] = "User Order Data Uploaded Successfully";
    }

    //import csv
    function processCsvImportData($file_name, $data)
    {
        if ($file_name == 'user_orders') {
            foreach ($data as $v) {
                $insertArr = array(
                    'order_id' => $v['order_id'],
                    'product_name' => $v['product_name'],
                    'booking_date' => $v['booking_date'],
                    'user_name' => $v['user_name'],
                    'user_mob' => $v['user_mob'],
                    'user_email' => $v['user_email'],
                    'boarding_point' => $v['boarding_point'],
                    'quantity' => $v['quantity'],
                    'price' => $v['price'],
                    'subtotal' => $v['subtotal'],
                    'gst' => $v['gst'],
                    'payment_type' => $v['payment_type'],
                    'total' => $v['total'],
                );
                $res = $this->db->insert($file_name, $insertArr);
            }
            return true;
        } else if ($file_name == 'trek_leader') {
            foreach ($data as $v) {
                $insertArr = array(
                    'name' => $v['name'],
                    'mobile' => $v['mobile'],
                    'email' => $v['email'],
                    'bio' => $v['bio'],
                    'treks' => $v['treks'],
                    't_size' => $v['t_size'],
                    'upi' => $v['upi'],
                    'banglore_address' => $v['banglore_address'],
                    'is_active' => $v['is_active'],
                );
                $res = $this->db->insert($file_name, $insertArr);
            }
            return true;
        } else if ($file_name == 'email_template') {
            foreach ($data as $v) {
                $insertArr = array(
                    'email_name' => $v['email_name'],
                    'email_text' => $v['email_text'],
                );
                $res = $this->db->insert($file_name, $insertArr);
            }
            return true;
        }
    }
}

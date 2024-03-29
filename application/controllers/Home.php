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
        $result = $this->homemodel->getBookingTreks();
        foreach ($result as $value) {

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
        $responseObject = [];

        $first_date = explode(',', $post["from"]);
        $last_date = explode(',', $post["to"]);


        $filterTreks = $this->homemodel->getBookingTreksName($trekIDList);

        $return_data = [];
        $index = 0;
        foreach ($filterTreks as $key => $value) {
            if ($value['meta_key'] == 'tour_booking_periods') {
                foreach (unserialize($value['meta_value']) as $newFields) {
                    $datee = date("Y-m-d", strtotime($newFields['exact_dates'][0]));
                    // echo $datee;
                    // echo "\n"
                    if ($first_date[0] <= $datee && $last_date[0] >= $datee) {
                        // echo "\n";
                        $return_data[$index]['date'] = $datee;
                    }
                }
            }
            if (isset($value['meta_key']) && $value['meta_key'] == 'name') {
                if (isset($return_data[$index]['date'])) {
                    $name = $value['meta_value'];
                    $post_id = $value['post_id'];
                    $return_data[$index]['id'] = $post_id;
                    $return_data[$index]['post_title'] = $name;
                    $index++;
                }
            }
        }

        // die();

        echo json_encode($return_data);
        // print_r($return_data);
        // die();

        // foreach ($filterTreks as $value) {
        //     $check = 0;
        //     $responseTreksid = new stdClass();
        //     if ($value['meta_key'] == 'tour_booking_periods') {
        //         foreach (unserialize($value['meta_value']) as $newFields) {
        //             if ($fromDate <= $newFields['exact_dates'][0] && $toDate >= $newFields['exact_dates'][0]) {
        //                 print_r($newFields);

        //                 $check = 1;
        //                 // $responseTreksid->exact_dates->$newFields['exact_dates'][0];
        //             }
        //         }
        //     }
        //     if ($value['meta_key'] == 'name') {
        //         $responseTreksid->name = $value['meta_value'];
        //     }
        //     // echo isset($responseTreksid->id);
        //     $responseTreksid->id = $value['post_id'];

        //     // echo json_encode($responseTreksid);
        //     if ($check == 1) {
        //         array_push($responseObject, $responseTreksid);
        //     }
        // }
        // print_r($responseObject);
        // die();
        // echo json_encode($responseObject);
    }

    public function getBillingInfo()
    {
        $post = json_decode(file_get_contents("php://input"), true);
        $responseObject = [];
        $productId = $post['id'];
        if (isset($post["exactDate"])) {
            $fromDate = $post["exactDate"];
            // $fromDate = substr($fromDate, 0, 10);
            // $fromDate = strtotime($fromDate);
        } else {
            $fromDate = time();
        }
        // $trekInfo = $this->homemodel->getTrekInfo($productId);
        // foreach ($trekInfo as $value) {
        //     if ($value['meta_key'] == 'tour_booking_periods') {

        //         foreach (unserialize($value['meta_value']) as $newFields) {
        //             $dbExactDate = strtotime($newFields['exact_dates'][0]);
        //             if ($fromDate == $dbExactDate) {
        //                 //array_push($responseObject, $newFields['exact_dates'][0]);
        //             }
        //         }
        //     }
        // }

        //get billing info of customer whose payment is done   
        $getOrderMetaId = $this->homemodel->getOrderMetaId($fromDate);
        $listOrderItem = array();
        foreach ($getOrderMetaId as $value) {
            if ($value['meta_key'] == '_product_id') {
                $listOrderItem[] = $value['order_item_id'];
            }
        }
        // $billingInfo = array();

        $billingInfo = $this->homemodel->getBillingInfo($productId, $listOrderItem);
    
        // $billingInfo[0]['boarding'] = 'hello'; 
        // get boarding point
        // $getBoardingPoint = array();
        // $oVal = (object)[];
        // $oValvar1 = "something"; // PHP creates  a Warning here
        // $oVal->key1->var2 = "something else";
        $id = '';
        $boardingPointObject = new stdClass();
        $count = 0;
        //working on logic
        // boardingPointObject object will have id, BP as Meta key and MV if not exists then NA

        // --------------------------------------new code starts here-----------------------------------------------------------------
        $getBoardingPointsql = $this->homemodel->getBoardingPoint();
        $boarding_Point_Text = "boarding_point";
        foreach ($billingInfo as $key => $value) {
            foreach ($getBoardingPointsql as $value1) {
                //blank and id check
            if ($id == '' || $id =  $value['ID']) {
                $id =  $value['ID'];
                if($value['ID'] == $value1['order_id'] && $value1['meta_key'] == '_product_id' && $value1['meta_key'] == 'Boarding Point'){
                    
                    $boardingPointObject->ID = $value['ID'];  
                    $boardingPointObject->meta_key = $value1['meta_key'];
                    $boardingPointObject->meta_value = $value1['meta_value'];
                     array_push($billingInfo, $boardingPointObject);
                }else{
                    //we have to remove below condition and we show boarding point object only when it's available 
                    if($count == 0){
                        $getBoardingPoint = 'NA';
                        $boardingPointObject->ID = $value['ID'];
                        $boardingPointObject->meta_key = $boarding_Point_Text;
                        $boardingPointObject->meta_value = $getBoardingPoint;
                         array_push($billingInfo, $boardingPointObject);
                         $count++;
                    }
                }
            } else {
                if ($id != $value['ID']) {
                    $id = '';
                    //array_splice($billingInfo, $key, 0, $boardingPointObject);
                }
            }
        }
    }
        
//-------------------------------------------new code ends here-------------------------------------------------------------------

    //      foreach ($getBoardingPointsql as $value1) {

    //       if($value['ID'] == $value1['order_id'] && $count == 0){
    //       if ($value1['meta_key'] == '_product_id' && $value1['meta_key'] == 'Boarding Point') {
    //             if ($value['meta_value'] != '') {
    //                 $boardingPointObject->meta_key = $boarding_Point_Text;
    //                 $boardingPointObject->meta_value = $value1['meta_value'];
    //             }
    //         }
    //         //echo json_encode($value['meta_id']);
       
    //         if ($value1['meta_id'] == end($getOrderMetaId)['meta_id']) {
    //             $getBoardingPoint = 'NA';
    //             $boardingPointObject->meta_key = $boarding_Point_Text;
    //             $boardingPointObject->meta_value = $getBoardingPoint;
    //             array_push($billingInfo, $boardingPointObject);
    //             //array_splice($billingInfo, $key, 0, $boardingPointObject);
    //         }
    //       }
           
    //     }
    //      $count++;
    // }
   
           echo json_encode($billingInfo);
    }

    // TREK MANAGEMENT HERE
    public function getManageTreks()
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
            $toDate = 0;
        }

        //stores all the post_id which exists between from and to
        $trekIDList = [];
        $result = $this->homemodel->getBookingTreks();
        foreach ($result as $value) {
            foreach (unserialize($value['meta_value']) as $newFields) {
                $dbFrom = strtotime($newFields['from']);
                $dbTo = strtotime($newFields['to']);
                if ($fromDate <= $dbTo && $toDate >= $dbFrom) {
                    array_push($trekIDList, $value['post_id']);
                }
            }
        }

        $mangeTrekList = $this->homemodel->getBookingTreksName($trekIDList);
        echo json_encode($mangeTrekList);
    }

    public function getEmailTemplates()
    {
        $result = $this->homemodel->getEmailTemplates();
        echo json_encode($result);
    }

    public function send_email()
    {
        //Send mail - not in use, shifted to email controller.
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

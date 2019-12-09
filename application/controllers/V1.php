<?php
error_reporting(0);

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class V1 extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Apimodel');
    }

    public function designmycodes_get(){
        // $post = json_decode(file_get_contents("php://input"), true);
        // $keys = array_keys($post); //convert json into array formate
        // sort($keys); //sort array to campare with sample string
        // print("post");
       $responsearray = array("status"=>6012,"success" => z, "message" => "Phone number already exists", "result" => new stdClass());   
        $this->response($responsearray, REST_Controller::HTTP_OK);
        
    }

    public function userRegistration_post(){
        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string
        // echo json_encode($keys);die;
        
        $sample = '["name", "email_address", "password", "phone_number", "uid_number", "passport_number", "city", "address", "latitude","document_type","longitude", "device_token"]';
        
      // if (json_encode($keys) === $sample) {
            $required=array(
                "name"=>$post["name"],
                "email_address"=>$post['email_address'],
                "password"=>$post['password'],
                "phone_number"=>$post['phone_number'],
                "uid_number"=>$post['uid_number'],
                "passport_number" => $post["passport_number"],
                "device_token" => $post["device_token"]
                );

            $key = array_keys($required, "");
            if (!$key) {
                       
                       $res = $this->Apimodel->select_data('T_User', array('email_address' => $post["email_address"]));

                       $res1 = $this->Apimodel->select_data('T_User', array('phone_number' => $post["phone_number"]));                       

                       if ($res>0) {
                            $responsearray = array("status"=>6011,"success" => false, "message" => "Email address already exists", "result" => new stdClass());   
                            $this->response($responsearray, REST_Controller::HTTP_OK);

                       }
                       elseif ($res1>0) {
                           $responsearray = array("status"=>6012,"success" => false, "message" => "Phone number already exists", "result" => new stdClass());   
                            $this->response($responsearray, REST_Controller::HTTP_OK);
                       }
                       else{

                            $post_arr = 
                            array(
                                    "name"=>$post["name"],
                                    "email_address"=>$post['email_address'],
                                    "password"=>$post['password'],
                                    "phone_number"=>$post['phone_number'],
                                    "uid_number"=>$post['uid_number'],
                                    "passport_number"=>$post['passport_number'],
                                    "document_type"=>$post['document_type'],
                                    "city"=>$post['city'],
                                    "address"=>$post['address'],
                                    "latitude"=>$post['latitude'],
                                    "longitude"=>$post['longitude'],
                                    "device_token"=>$post['device_token']
                            );
                            
                            $check_email = $this->Apimodel->insert('T_User', $post_arr);


                            // echo $check_email; die;
                            
                            $url = str_replace(' ', '', base_url('index.php/Welcome/verify_email/'));
                            $res = $this->Apimodel->select_data('T_User', array('email_address' => $post["email_address"]));
                            

                            $body=" <a href='" . $url . "/" . $res[0]->user_id."'>Click</a> here to change your password";
                            $subject = 'Recover password';

                            $to_email = $post["email_address"];
                            $sts = $this->send_verification($to_email,$subject,$body);
                            if($sts){

                                   $result = [
                                        "user_id" => $check_email
                                   ]; 

                               $responsearray = array("status"=>2000,"success" => true, "message" => "Sign-up successful. Please check your email to complete verification.", "result" => $result);    
                               $this->response($responsearray, REST_Controller::HTTP_OK);

                            }
                            else{
                                $responsearray = array("status"=>6000,"success" => false, "message" => "Something went wrong,please try after sometime.", "result" => new stdClass());   
                                $this->response($responsearray, REST_Controller::HTTP_OK);
                            }
                       }

            } else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }
    
    }

        function userLogin_post(){
        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string

         // echo md5(time()); die;      

         $res = $this->Apimodel->update('T_User', array('email_address' => $post["email_address"]), array('access_token' => md5(time()) ));

         // var_dump($res);die;

         $sample = '["email_address","password","device_token"]';



            $required=array(
                "email_address"=>$post['email_address'],
                "password"=>$post['password'],
                "device_token"=>$post['device_token']
            );

            $key = array_keys($required, "");
            
            if (!$key) {
                // $user_exist = $this->Apimodel->select_data('T_User',array("email_address" => $post["email_address"]));
                //         if($user_exist){
                        // var_dump($user_exist);die;
                            $res = $this->Apimodel->select_data('T_User', array('email_address' => $post["email_address"], 'password' => $post["password"] ));
                            
                            if ($res > 0) {

                                // $res = $this->Apimodel->select_data('T_User', array('email_address' => $res[0]->email_address,'password' => $post["password"], 'is_email_verify' => $res[0]->is_email_verify));

                                $res1 = $this->Apimodel->userCarDetailsByUserId($res[0]->user_id);
if($res1[0]->user_car_id !='' || $res1[0]->name!='' || $res1[0]->registration_number!='' || $res1[0]->insurer_name !='' || $res1[0]->vin_number !='' || $res1[0]->chesis_number !='' || $res1[0]->model_number!=''){
                                $result1 = [
                                        "user_car_id" => $res1[0]->user_car_id,
                                        "name" => $res1[0]->name? : '',
                                        "registration_number" => $res1[0]->registration_number? : '',
                                        "insurer_name" => $res1[0]->insurer_name? : '',
                                        "vin_number" => $res1[0]->vin_number? : '',
                                        "chesis_number" => $res1[0]->chesis_number? : '',
                                        "model_number" => $res1[0]->model_number? : '',
                                        "year" => $res1[0]->year? : '',
                                        "created_date" => $res1[0]->created_date? : ''
                                   ];}else{
$result1=(object)array();}

                                if($res[0]->is_email_verify == 1){

                                    $result = [
                                    "user_id" => $res[0]->user_id,
                                    "name" => $res[0]->name? : '',
                                    "email_address" => $res[0]->email_address? : '',
                                    "password" => $res[0]->password? : '',
                                    "phone_number" => $res[0]->phone_number? : '',
                                    "uid_number" => $res[0]->uid_number? : '',
                                    "passport_number" => $res[0]->passport_number? : '',
                                    "city" => $res[0]->city? : '',
                                    "address" => $res[0]->address? : '',
                                    "latitude" => $res[0]->latitude? : '',
                                    "longitude" => $res[0]->longitude? : '',
                                    "access_token" => $res[0]->access_token? : '',
                                    "device_token" => $res[0]->device_token? : '',
                                    "UserCar" => $result1 ?:(object)array()
                                   ];
                                    


                                    $responsearray = array("status"=>2004,"success" => true, "message" => "You are logged in successfully.", "UserDetails" => $result);
                                    $this->response($responsearray, REST_Controller::HTTP_OK);

                                }
                                else {
                                    $responsearray = array("status"=>6004,"success" => false, "message" => "Your email address is not verified, please verify your email address.", "result" => new stdClass());
                                    $this->response($responsearray, REST_Controller::HTTP_OK);    
                                
                                 }

                            } else{
                            
                            $responsearray = array("status"=>3003,"success" => false, "message" => "User not exist", "result" => new stdClass());
                            $this->response($responsearray, REST_Controller::HTTP_OK);

                            } 
                        

                        
            }else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }

    }

    function send_verification($to_email, $subject, $body){
        // echo "hello";
        $from_email = "canopus.testing@gmail.com";
        // $to_email = $to;Email verification link has been sent to your email, please check your email.

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com'; //change this
        $config['smtp_port'] = '465';
        $config['smtp_user'] = 'canopus.testing@gmail.com'; //change this
        $config['smtp_pass'] = 'canopus123'; //change this
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 822 standard
        //Load email library 
        $this->load->library('email', $config);

        $this->email->from($from_email, 'Transformation_hub');
        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($body);

        //Send mail 
        return $this->email->send();
    
    }

    function verifyPhoneNumber_post(){
        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string

         $sample = '["user_id","otp","access_token"]';



         $required=array(
                "user_id"=>$post['user_id'],
                "otp"=>$post['otp'],
                "access_token"=>$post['access_token'],
                
                );

            $key = array_keys($required, "");
            
            if (!$key) {
                        $res1 = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"], 'access_token' => $post["access_token"] ));        

                        if($res1>0){

                            $res = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"], 'otp' => $post["otp"]));
                            
                                if ($res > 0) {

                                
                                    $responsearray = array("status"=>2001,"success" => true, "message" => "Your phone number has been verified successfully.", "result" => new stdClass());
                                    $this->response($responsearray, REST_Controller::HTTP_OK);

                                }
                                else {
                                    $responsearray = array("status"=>6001,"success" => false, "message" => "You have entered an invalid OTP.","result" => new stdClass());
                                    $this->response($responsearray, REST_Controller::HTTP_OK);    
                                
                                }

                        }   
                        else{

                            $responsearray = array("status"=>6013,"success" => false, "message" => "Invalid Access Token","result" => new stdClass());
                            $this->response($responsearray, REST_Controller::HTTP_OK); 

                        } 
            }else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }

    }

    function resendOTP_post(){
        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string

         $sample = '["user_id","access_token"]';



         $required=array(
                "user_id"=>$post['user_id'],
                "access_token"=>$post['access_token'],
            );

            $key = array_keys($required, "");
            
            if (!$key) {
                        $res1 = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"], 'access_token' => $post["access_token"] ));

                            if($res1 > 0){

                                $res = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"]));
                            
                                if ($res > 0) {

                                    // var_dump($res[0]->phone_number);die;

                                    if($res[0]->phone_number == ''){

                                         $responsearray = array("status"=>6002,"success" => false, "message" => "We are unable to find your Phone number - please try again with your valid Phone number.","result" => new stdClass());
                                        $this->response($responsearray, REST_Controller::HTTP_OK);    

                                    }
                                    else{

                                        $responsearray = array("status"=>2002,"success" => true, "message" => "OTP has been sent to your registered phone number.", "result" => $result);
                                        $this->response($responsearray, REST_Controller::HTTP_OK);    
                                    }
                                }
                                else {
                                   
                                     $responsearray = array("status"=>6002,"success" => false, "message" => "User doesn't exists", "result" => $result);
                                        $this->response($responsearray, REST_Controller::HTTP_OK);   
                                }

                            }    
                            else{

                                $responsearray = array("status"=>6013,"success" => false, "message" => "Invalid Åccess Token", "result" => $result);
                                        $this->response($responsearray, REST_Controller::HTTP_OK); 

                            }

            }else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }

    }

    function assignCar_post(){
        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string
        // echo json_encode($keys);die;
        
        $sample =  '["user_id","name","registration_number","vin_number","chesis_number","model_number","year","insurer_name","access_token"]';



        // $sample = '["name", "email_address", "password", "phone_number", "uid_number", "passport_number", "city", "address", "latitude", "longitude", "device_token"]';
        
      // if (json_encode($keys) === $sample) {
            $required=array(
                        "user_id"=>$post["user_id"],
                        "name"=>$post['name'],
                        "registration_number"=>$post['registration_number'],
                        "vin_number"=>$post['vin_number'],
                        "chesis_number"=>$post['chesis_number'],
                        "model_number"=>$post['model_number'],
                        "year"=>$post['year'],
                        "access_token" => $post['access_token']
                );

            $key = array_keys($required, "");

            if (!$key) {

                $user = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"] ));

                if($user){
                    $access_token = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"], 'access_token' => $post["access_token"] ));

                    if($access_token>0){

                        $post_arr = 
                        array(
                            "user_id"=>$post["user_id"],
                            "name"=>$post['name'],
                            "registration_number"=>$post['registration_number'],
                            "vin_number"=>$post['vin_number'],
                            "chesis_number"=>$post['chesis_number'],
                            "model_number"=>$post['model_number'],
                            "year"=>$post['year'],
                            "insurer_name"=>$post['insurer_name']
                        );
                        // var_dump($post_arr);die;

                        $insert_data = $this->Apimodel->insert('T_UserCar', $post_arr);

                        
                        if($insert_data){

                            $res_to_show = $this->Apimodel->select_data('T_UserCar', array('user_car_id' => $insert_data));

                            $result = [
                                    "user_car_id" => $res_to_show[0]->user_car_id
                                ];


                            $responsearray = array("status"=>2004,"success" => true, "message" => "You have successfully assigned a car.", "result" => $result);    
                           $this->response($responsearray, REST_Controller::HTTP_OK);

                        }
                        else{
                            $responsearray = array("status"=>6000,"success" => false, "message" => "Something went wrong,please try after sometime.", "result" => new stdClass());   
                            $this->response($responsearray, REST_Controller::HTTP_OK);
                        }

                    }   
                    else{
                        $responsearray = array("status"=>6013,"success" => false, "message" => "Invalid Access Token", "result" => new stdClass());   
                        $this->response($responsearray, REST_Controller::HTTP_OK);
                    }
                }
                else{
                    $responsearray = array("status"=>6017,"success" => false, "message" => "User Doesn't Exists.", "result" => new stdClass());    
                    $this->response($responsearray, REST_Controller::HTTP_OK);
                }
                     

            } else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }

    }

    function pairCarApp_post(){

        // $access_token = $this->Apimodel->getPairCarByUserId('2');

        // var_dump($access_token);die;



        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string
        // echo json_encode($keys);die;
        

        
        $sample = '["user_id","user_car_id","uuid","major","minor","access_token"]';


            $required=array(
                        "user_id"=>$post["user_id"],
                        "user_car_id"=>$post['user_car_id'],
                        "uuid"=>$post['uuid'],
                        "minor"=>$post['minor'],
                        "major"=>$post['major'],
                        "access_token"=>$post['access_token']
                );

            $key = array_keys($required, "");
            if (!$key) {

                $user = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"] ));

                    if($user){
                        $access_token = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"], 'access_token' => $post["access_token"] ));

                        if($access_token>0){

                            $post_arr = 
                            array(
                                 "user_car_id"=>$post['user_car_id'],
                                 "uuid"=>$post['uuid'],
                                 "minor"=>$post['minor'],
                                 "major"=>$post['major']
                            );
                            // var_dump($post_arr);die;

                            $insert_data = $this->Apimodel->insert('T_PairCarApp', $post_arr);

                            
                            if($insert_data){


                                $res_to_show = $this->Apimodel->select_data('T_PairCarApp', array('pair_car_app_id' => $insert_data));

                                $result = [
                                        "pair_car_app_id" => $res_to_show[0]->pair_car_app_id
                                    ];

                                $responsearray = array("status"=>2005,"success" => true, "message" => "Your car paired  successfully.", "result" => $result);    
                               $this->response($responsearray, REST_Controller::HTTP_OK);

                            }
                            else{
                                $responsearray = array("status"=>6007,"success" => false, "message" => "Car pairing failed, Please try after sometime.", "result" => new stdClass());   
                                $this->response($responsearray, REST_Controller::HTTP_OK);
                            }
                        
                        }else{
                                $responsearray = array("status"=>6013,"success" => false, "message" => "Invalid Access Token", "result" => new stdClass());   
                                $this->response($responsearray, REST_Controller::HTTP_OK);

                            }
                    }else{
                        $responsearray = array("status"=>6017,"success" => false, "message" => "User Doesn't Exists.", "result" => new stdClass());    
                        $this->response($responsearray, REST_Controller::HTTP_OK);
                    }

            } else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }

    }

    function getPairedCarByUserid_post(){

        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string
        // echo json_encode($keys);die;
        

        
        $sample = '["user_id","access_token"]';


            $required=array(
                        "user_id"=>$post["user_id"],
                        "access_token"=>$post["access_token"]
                );

            $key = array_keys($required, "");
            if (!$key) {

                $user = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"] ));

                if($user){
                    $access_token = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"], 'access_token' => $post["access_token"] ));
                    // $access_token = $this->Apimodel->getPairedCarByUserid($post["user_id"]);

                    if($access_token>0){

                        $res_to_show = $this->Apimodel->getPairCarDetailsByUserId($post["user_id"] );
                            
                            if($res_to_show){

                                foreach ($res_to_show as $key) {
                                            // var_dump($key->pair_car_app_id);die;
                                    $result[] = array(
                                            "pair_car_app_id" => $key->pair_car_app_id,
                                            "user_car_id" => $key->user_car_id,
                                            "name" => $key->name,
                                            "registration_number" => $key->registration_number,
                                            "insurer_name" => $key->insurer_name,
                                            "vin_number" => $key->vin_number,
                                            "chesis_number" => $key->chesis_number,
                                            "model_number" => $key->model_number,
                                            "year" => $key->year,
                                            "created_date" => $key->created_date,
                                            "uuid" => $key->uuid,
                                            "major" => $key->major,
                                            "minor" => $key->minor
                                   );  
                                        
                                }
                                    $responsearray = array("status"=>2006,"success" => true, "message" => "Get details of paired car.", "result" => $result);
                                        $this->response($responsearray, REST_Controller::HTTP_OK);

                            }   

                            else{
                                    $responsearray = array("status"=>6008,"success" => false, "message" => "No paired car found!", "result" => new stdClass());    
                                    $this->response($responsearray, REST_Controller::HTTP_OK);

                                }
                    }
                    else{

                        $responsearray = array("status"=>6013,"success" => false, "message" => "Invalid Access Token.", "result" => new stdClass());    
                                    $this->response($responsearray, REST_Controller::HTTP_OK);

                    }
                }   
                else{

                    $responsearray = array("status"=>6017,"success" => false, "message" => "User Doesn't Exists.", "result" => new stdClass());    
                    $this->response($responsearray, REST_Controller::HTTP_OK);

                }

            } else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }

    }

    function getNotificationDetailsByUserid_post(){
        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string
        // echo json_encode($keys);die;
        

        
        $sample = '["user_id","access_token"]';


            $required=array(
                        "user_id"=>$post["user_id"],
                        "access_token"=>$post["access_token"]
            );

            $key = array_keys($required, "");
            if (!$key) {
                    // $access_token = $this->Apimodel->checkUserAndAccessToken($post["user_id"],$post["access_token"] );

                $user = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"] ));

                if($user){
                    $access_token = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"], 'access_token' => $post["access_token"] ));

                    if($access_token>0){
                        $res_to_show = $this->Apimodel->select_data('T_UserNotification', array('user_id' => $post["user_id"]));
                            
                                    
                            if($res_to_show > 0){

                                $result = [
                                            "user_notification_id" => $res_to_show[0]->user_notification_id,
                                            "type" => $res_to_show[0]->type,
                                            "message" => $res_to_show[0]->message,
                                            "date" => $res_to_show[0]->date,
                                            "time" => $res_to_show[0]->time
                                        ];
                                        $responsearray = array("status"=>2007,"success" => true, "message" => "Get notification details successfully.", "result" => $result);
                                $this->response($responsearray, REST_Controller::HTTP_OK);

                            }   

                            else{
                                    $responsearray = array("status"=>6009,"success" => false, "message" => "Notification details not found.", "result" => new stdClass());    
                                    $this->response($responsearray, REST_Controller::HTTP_OK);

                                }
                    }
                    
                    else{
                        $responsearray = array("status"=>6013,"success" => false, "message" => "Invalid Access Token.", "result" => new stdClass());    
                                    $this->response($responsearray, REST_Controller::HTTP_OK);
                    }
                }
                else{

                    $responsearray = array("status"=>6017,"success" => false, "message" => "User Doesn't Exists.", "result" => new stdClass());    
                    $this->response($responsearray, REST_Controller::HTTP_OK);

                }

            } else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }
    
    }

    function clearNotificationByUserid_post(){

        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string
        // echo json_encode($keys);die;
        

        
        $sample = '["user_notification_id","access_token"]';


            $required=array(
                    "user_notification_id" => $post["user_notification_id"],
                    "access_token" => $post["access_token"]
            );

            $key = array_keys($required, "");
            
            if (!$key) {

                $access_token = $this->Apimodel->checkInNotification($post["user_notification_id"],$post["access_token"] );

                    if($access_token>0){

                        $res_to_show = $this->Apimodel->delete('T_UserNotification', array('user_notification_id' => $post["user_notification_id"]));
                            
                            
                        if($res_to_show > 0){

                            
                            $responsearray = array("status"=>2008,"success" => true, "message" => "Your Notification details cleared successfully.", "result" => new stdClass());
                            $this->response($responsearray, REST_Controller::HTTP_OK);

                        }   

                        else{
                                $responsearray = array("status"=>6010,"success" => false, "message" => "Notification details not cleared,please try again.", "result" => new stdClass());    
                                $this->response($responsearray, REST_Controller::HTTP_OK);

                        }
                    
                    }else{
                        $responsearray = array("status"=>6013,"success" => false, "message" => "Invalid Access Token.", "result" => new stdClass());    
                                $this->response($responsearray, REST_Controller::HTTP_OK);
                    }
                        
            } else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }
    
    }

    function getAllPairedCarByUserid_post(){

        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string
        // echo json_encode($keys);die;
        

        
        $sample = '["user_id","access_token"]';


            $required=array(
                        "user_id"=>$post["user_id"],
                        "access_token"=>$post["access_token"]
                );

            $key = array_keys($required, "");
            if (!$key) {


                $res = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"] ));

                if($res){
                    // $access_token = $this->Apimodel->getPairedCarByUserid($post["user_id"]);
                    $access_token = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"], 'access_token' => $post["access_token"] ));

                    if($access_token){

                        $res_to_show = $this->Apimodel->getPairCarDetailsByUserId($post["user_id"] );
                            
                            if($res_to_show){

                                foreach ($res_to_show as $key) {
                                            // var_dump($key->pair_car_app_id);die;
                                    $result[] = array(
                                            "pair_car_app_id" => $key->pair_car_app_id,
                                            "user_car_id" => $key->user_car_id,
                                            "name" => $key->name,
                                            "registration_number" => $key->registration_number,
                                            "insurer_name" => $key->insurer_name,
                                            "vin_number" => $key->vin_number,
                                            "chesis_number" => $key->chesis_number,
                                            "model_number" => $key->model_number,
                                            "year" => $key->year,
                                            "created_date" => $key->created_date
                                   );  
                                        
                                }
                                    $responsearray = array("status"=>2009,"success" => true, "message" => "Get details of paired car.", "result" => $result);
                                        $this->response($responsearray, REST_Controller::HTTP_OK);

                            }   

                            else{
                                    $responsearray = array("status"=>6014,"success" => false, "message" => "Paried car details not found ", "result" => new stdClass());    
                                    $this->response($responsearray, REST_Controller::HTTP_OK);

                                }
                    }
                    else{

                        $responsearray = array("status"=>6013,"success" => false, "message" => "Invalid Access Token.", "result" => new stdClass());    
                                    $this->response($responsearray, REST_Controller::HTTP_OK);

                    }
                }
                else{
                    $responsearray = array("status"=>6017,"success" => false, "message" => "User Doesn't Exists.", "result" => new stdClass());    
                    $this->response($responsearray, REST_Controller::HTTP_OK);                            
                }
            } else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }

    }

    function getPairedCarDetailsByCar_post(){

        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string
        // echo json_encode($keys);die;
        

        
        $sample = '["pair_car_app_id","access_token"]';


            $required=array(
                        "pair_car_app_id" => $post["pair_car_app_id"],
                        "access_token" => $post["access_token"]
                              
                );

            $key = array_keys($required, "");


            
            if (!$key) {

                $res = $this->Apimodel->select_data('T_PairCarApp', array('pair_car_app_id' => $post["pair_car_app_id"] ));

                // var_dump($res);die;
                if($res){
                    // echo "assd";die;
                    $access_token = $this->Apimodel->authByPairCarAppId( $post["pair_car_app_id"],$post["access_token"] );

                // var_dump($access_token);die;
                    if($access_token){

                            $res = $this->Apimodel->select_data('T_PairCarApp', array('pair_car_app_id' => $post["pair_car_app_id"] ));
                                
                                
                        if($res > 0){

                            $result = [
                                    // "pair_car_app_id" => $res[0]->pair_car_app_id,
                                    "pair_car_app_id" => $res[0]->pair_car_app_id? : '',
                                    "user_car_id" => $res[0]->user_car_id? : '',
                                    "uuid" => $res[0]->uuid? : '',
                                    "major" => $res[0]->major? : '',
                                    "minor" => $res[0]->minor? : '',
                                    "created_date" => $res[0]->created_date? : ''
                                   ];

                            
                            $responsearray = array("status"=>2010,"success" => true, "message" => "Get paired details.", "result" => $result);
                            $this->response($responsearray, REST_Controller::HTTP_OK);

                        }   

                        else{
                                $responsearray = array("status"=>6015,"success" => false, "message" => "No details found for this car", "result" => new stdClass());    
                                $this->response($responsearray, REST_Controller::HTTP_OK);
                            }
                    }else{

                        $responsearray = array("status"=>6010,"success" => false, "message" => "Invalid Access Token.", "result" => new stdClass());    
                                $this->response($responsearray, REST_Controller::HTTP_OK);

                    }
                }
                else{
                    $responsearray = array("status"=>6018,"success" => false, "message" => "Pair Car App Id Doesn't Exists.", "result" => new stdClass());    
                                $this->response($responsearray, REST_Controller::HTTP_OK);
                }
            } else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }
    
    }

    

    function saveUserLocation_post(){

        // $map = "http://maps.googleapis.com/maps/api/geocode/json?latlng=44.4647452,7.3553838&sensor=true";

        // echo json_decode($map);    die;

        // $post = json_decode(file_get_contents($map), true);
        // var_dump($post["results"][0]["formatted_address"]);die;

        // $d = $post["address_components"];

        // var_dump($post);die;

        $post = json_decode(file_get_contents("php://input"), true);
        $keys = array_keys($post); //convert json into array formate
        sort($keys); //sort array to campare with sample string
        // echo json_encode($keys);die;
            
        $map = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$post["latitude"].",".$post["longitude"]."&sensor=true";

                $post1 = json_decode(file_get_contents($map), true);
                $address = $post1["results"][0]["formatted_address"];



                // var_dump($post['user_id']);var_dump($post['latitude']);var_dump($post['longitude']);
                // var_dump($address);die;
        
        $sample = '["user_id","latitude","longitude","access_token"]';



            $required=array(
                        "user_id" => $post["user_id"],
                        "latitude" => $post["latitude"],
                        "longitude" => $post["longitude"],
                        "access_token" => $post["access_token"]
                    );


            $key = array_keys($required, "");
            
            if (!$key) {

                $user = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"]));

                if($user){
                    $access_token = $this->Apimodel->select_data('T_User', array('user_id' => $post["user_id"], 'access_token' => $post["access_token"] ));
                    
                    if($access_token){

                        if (preg_match("/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/", $post["latitude"]))  {

                            
                            if(preg_match("/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,6}$/",
      $post["longitude"])) {
                                $post_arr = 
                                array(
                                        "user_id"=>$post["user_id"],
                                        "latitude"=>$post['latitude'],
                                        "longitude"=>$post['longitude'],
                                        "address" => $address
                                );
                                
                                $insert_data = $this->Apimodel->insert('T_UserLocation', $post_arr);
                                    
                                

                                    if ($insert_data) {

                                        $responsearray = array("status"=>2011,"success" => true, "message" => "User location saved successfully.", "result" => new stdClass());
                                        $this->response($responsearray, REST_Controller::HTTP_OK);

                                    }
                                    else{

                                        $responsearray = array("status"=>6016,"success" => false, "message" => "User location not saved,please try again.", "result" => new stdClass());    
                                        $this->response($responsearray, REST_Controller::HTTP_OK);

                                    }
                            } else{
                                $responsearray = array("status"=>6020,"success" => false, "message" => "Invalid longitude Value.", "result" => new stdClass());    
                                    $this->response($responsearray, REST_Controller::HTTP_OK);
                            }
                              
                        } else{

                            $responsearray = array("status"=>6019,"success" => false, "message" => "Invalid latitude Value.", "result" => new stdClass());    
                                    $this->response($responsearray, REST_Controller::HTTP_OK);
                        } 

                          
                      
                    }else{

                        $responsearray = array("status"=>6010,"success" => false, "message" => "Invalid Access Token.", "result" => new stdClass());    
                                $this->response($responsearray, REST_Controller::HTTP_OK);

                    }
                }   
                else{
                    $responsearray = array("status"=>6017,"success" => false, "message" => "User Doesn't Exists.", "result" => new stdClass());    
                                $this->response($responsearray, REST_Controller::HTTP_OK);
                } 
            } else{
                $key = json_encode(array_values($key));
                $key = str_replace(array('[', ']', '"', ','), array('', '', '', ', '), $key);
                $responsearray = array("status"=>4000,"success" => false, "message" => "The $key field is required", "result" => new stdClass());
                $this->response($responsearray, REST_Controller::HTTP_OK);
            }
    
    }

}


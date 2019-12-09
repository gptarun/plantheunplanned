<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

     /**
      * Index Page for this controller.
      *
      * Maps to the following URL
      *                 http://example.com/index.php/welcome
      *         - or -
      *                 http://example.com/index.php/welcome/index
      *         - or -
      * Since this controller is set as the default controller in
      * config/routes.php, it's displayed at http://example.com/
      *
      * So any other public methods not prefixed with an underscore will
      * map to /index.php/welcome/<method_name>
      * @see https://codeigniter.com/user_guide/general/urls.html
      */
     public function index() {
         
         $this->load->helper('url');

         $this->load->view('welcome_message');
     }


     public function verify_email($user_id){
      $this->load->model('Apimodel');

      $res_to_show = $this->Apimodel->email_veri($user_id);

      if($res_to_show){
          $t = $res_to_show[0]->diff;

            if($t > 14){

                $check = $this->Apimodel->delete('T_User', array('user_id' => $user_id));
                if($check){
                    echo "No longer exits please register again"; 
                }
                else{
                    echo "something went wrong";
                }

            } 
            else{
                $res = $this->Apimodel->update('T_User', array('user_id' => $user_id),  array('is_email_verify' => 1) );

                if($res){
                    echo "Email Verification successfully";
                }
                else{
                    echo "Already Verified";
                }
                    
            }

      }

      else{
         echo "Link Is Expired Please Register Again";
      }
  }

      
      // var_dump(date("H:i:s", strtotime($res_to_show[0]->created_date))); die;
      // echo time() ;die;

      // $p = date("H:i:s", strtotime($res_to_show[0]->created_date));
      // $r = date("H:i:s", time());

      // echo $p."<br>";
      // echo $r;
      // $s = $p-$r;

      // echo $s."hours" ;die;
      // $p = $res_to_show;

      



       



     // public function verify_email($user_id){
     //  $this->load->model('Apimodel');
     //  $res = $this->Apimodel->update('T_User', array('user_id' => $user_id),  array('is_email_verify' => 1) );

     //  if($res){
     //      echo "Email Verification successfully";
     //  }
     //  else{
     //      echo "Your Email Is Already Verified";
     //  }
          
     // }

     public function changepassword($id = '', $otp = '', $type = '') {

         $this->load->model('Apimodel');
         if (!empty($_POST)) {
             $password = $_POST['password'];
             $confirmpassword = $_POST['cpassword'];

             $user_id = $_POST['id'];
             $token = $this->input->post('token');
             $type = $this->input->post('type');
             if ($password === $confirmpassword) {
$res = $this->Apimodel->update('customers', array('id' => $user_id),  
array('password' => md5($password)));
                 if ($res > 0) {

                     echo 'Password changed successfully';
                     die;
                 } else {
                     echo 'Error occurred';
                     die;
                 }
             } else {
                 echo 'Password and confirm password do not match';
                 die;
             }
         }

         $res = $this->Apimodel->select_data('customers', array('id'  
=> $id, 'password' => $otp));
         
        $pagedata = array('user' => $res[0]);
         if ($res > 0) {
             $this->load->view('changepassword', $pagedata);
         } else {
             echo "<H1>Link expired please try again later</H1>";
         }
     }

}
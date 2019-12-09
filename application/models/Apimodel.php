<?php

class Apimodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function insert($table, $post) {

        $query = $this->db->insert($table, $post);
        return $this->db->insert_id();
    }

    public function email_veri($user_id){

        $q = $this->db->query('SELECT Hour(TIMEDIFF(now(),`created_date`)) AS diff FROM T_User where user_id ='.$user_id);
        // $q =   $this->db->query('SELECT `created_date` FROM T_User WHERE user_id ='.$user_id);

        return $q->result();
    }

    public function getPairCarByUserId($user_car_id){

        $q =   $this->db->query('select T_User.access_token from T_UserCar left join T_PairCarApp on T_PairCarApp.user_car_id = T_UserCar.user_car_id left join T_User on T_User.user_id = T_UserCar.user_id where T_UserCar.user_car_id = '.$user_car_id);

        return $q->result();

    }

       public function userCarDetailsByUserId($userid){
        $q =   $this->db->query('select * from T_User left join T_UserCar on T_User.user_id = T_UserCar.user_id where T_User.user_id ='.$userid);

         return $q->result();
    }      

    public function getPairedCarByUserid($user_id){
         $q =   $this->db->query('select T_User.access_token from T_UserCar left join T_PairCarApp on T_PairCarApp.user_car_id = T_UserCar.user_car_id left join T_User on T_User.user_id = T_UserCar.user_id where T_User.user_id = '.$user_id);

         return $q->result();
    }

    public function getPairCarDetailsByUserId($user_id){
        $q =   $this->db->query('select T_PairCarApp.pair_car_app_id ,T_PairCarApp.uuid,T_PairCarApp.major,T_PairCarApp.minor,T_PairCarApp.user_car_id,T_UserCar.name,T_UserCar.registration_number,T_UserCar.vin_number,T_UserCar.chesis_number,T_UserCar.insurer_name,T_UserCar.model_number,T_UserCar.year,T_UserCar.created_date from T_UserCar left join T_PairCarApp on T_PairCarApp.user_car_id = T_UserCar.user_car_id left join T_User on T_User.user_id = T_UserCar.user_id where T_UserCar.user_id = '.$user_id);

         return $q->result();
    }


    public function checkUserAndAccessToken($user_id,$access_token){
        $q = $this->db->query('select T_User.access_token from T_UserCar left join T_PairCarApp on T_PairCarApp.user_car_id = T_UserCar.user_car_id left join T_User on T_User.user_id = T_UserCar.user_id where T_UserCar.user_id = '.$user_id. ' AND T_User.access_token ='.'"'.$access_token.'"');

        return $q->result();
    }

    public function checkInNotification($user_notification_id,$access_token){

        $q = $this->db->query('select T_UserNotification.user_id,T_User.user_id,T_User.access_token from T_UserNotification left join T_User on T_UserNotification.user_id = T_UserNotification.user_id 
            where T_UserNotification.user_notification_id = '.$user_notification_id. ' AND T_User.access_token ='.'"'.$access_token.'"');


        return $q->result();

    }

    public function authByPairCarAppId($pair_car_app_id,$access_token){

        // print_r('select T_User.user_id,T_User.access_token from T_UserCar left join T_PairCarApp on T_PairCarApp.user_car_id = T_UserCar.user_car_id left join T_User on T_User.user_id = T_UserCar.user_id where T_PairCarApp.pair_car_app_id = '.$pair_car_app_id .' AND T_User.access_token ='.'"'.$access_token.'"');die;

        $q = $this->db->query('select T_User.user_id,T_User.access_token from T_UserCar left join T_PairCarApp on T_PairCarApp.user_car_id = T_UserCar.user_car_id left join T_User on T_User.user_id = T_UserCar.user_id where T_PairCarApp.pair_car_app_id = '.$pair_car_app_id .' AND T_User.access_token ='.'"'.$access_token.'"');

        // return $this->db->last_query();die;

        return $q->result();        
    }

    // public function findUserByPairCarId($pair_car_app_id){

        
    //     $q = $this->db->query('select T_User.user_id from T_UserCar left join T_PairCarApp on T_PairCarApp.user_car_id = T_UserCar.user_car_id left join T_User on T_User.user_id = T_UserCar.user_id where T_PairCarApp.pair_car_app_id ='.$pair_car_app_id);

    //     // return $this->db->last_query();die;

    //     return $q->result();        
    // }



    public function authForLocation($user_id,$access_token){

                // print_r('select T_User.user_id,T_User.access_token from T_UserLocation left join T_User on T_UserLocation.user_id = T_User.user_id where T_UserLocation.user_id ='.$user_id.' AND T_User.access_token ='.'"'.$access_token.'"');die;


        $q = $this->db->query('select T_User.user_id,T_User.access_token from T_UserLocation left join T_User on T_UserLocation.user_id = T_User.user_id where T_UserLocation.user_id ='.$user_id.' AND T_User.access_token ='.'"'.$access_token.'"');



        // return $this->db->last_query();
        return $q->result();

    }

    public function select_data($table_name, $where_arr = '', $order_by = '', $limit1 = '', $limit2 = '') {

        $this->db->select('*');
        $this->db->from($table_name);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        if (is_array($order_by)) {
            foreach ($order_by as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($limit1 != '') {
            $this->db->limit($limit2, $limit1);
        }
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $data = $result->result();

            return $data;
        } else {
            return $result->num_rows();
        }
    }

    public function update($table, $wherearr, $updatearr) {

        $this->db->where($wherearr);
        $this->db->update($table, $updatearr);
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    
    public function delete($table, $wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete($table);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    
     public function insert_multiple($table_array, $post_array){
        $i = 0;
        
        foreach ($table_array as $key) {
            # code...
            $table = $table_array[$i];
            $ins = $this->db->insert($table, $post_array);
            $i++;
        }
        return $ins;
     }


}

//  main class end
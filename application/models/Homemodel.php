<?php
header("Access-Control-Allow-Origin: *");
class Homemodel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function checkUsername($username)
    {
        $this->db->select('id');
        $this->db->from('wp_users');
        $this->db->where('user_login', $username);
        $result = $this->db->get()->num_rows();
        return $result;
    }

    public function registration($data)
    {
        $this->db->insert('wp_users', $data);
        return $this->db->insert_id();
    }

    public function verifyLogin($username, $password)
    {

        $query = $this->db->query("select * from user_admin WHERE `username` =  '$username' AND `password` =  '$password' ");

        if ($query->num_rows() == 1) {
            return "Success";
        } else {
            return "Failed";
        }
    }


    public function addUser($data)
    {
        $this->db->insert('wp_users', $data);
        return $this->db->insert_id();
    }

    public function deletUser($id)
    {
    }

    public function updateUser($data)
    {
        return $data;
    }

    public function getUserById($id)
    {
        return $this->db->select('*')->from('wp_users')->where("ID = '$id'")->get()->result_array();
    }

    public function searchUser($offset, $limit, $filter, $search, $date)
    {
        // return $this->db->get_where('wp_users', array($filter => $search))->result();        
        if ($filter == '' ||  $search == '') {
            if ($date != '') {
                return $this->db->select('*')->from('wp_users')->where("user_registered LIKE '%$date%'")->limit($limit, $offset)->get()->result_array();
            } else {
                return $this->db->select('*')->from('wp_users')->limit($limit, $offset)->get()->result_array();
            }
        } else if ($filter != '' &&  $search != '') {
            if ($date != '') {
                return $this->db->select('*')->from('wp_users')->where($filter . " LIKE '%$search%' AND user_registered LIKE '%$date%'")->limit($limit, $offset)->get()->result_array();
            } else {
                return $this->db->select('*')->from('wp_users')->where($filter . " LIKE '%$search%'")->limit($limit, $offset)->get()->result_array();
            }
        }
    }


    public function searchUserCount($filter, $search, $date)
    {
        // return $this->db->get_where('wp_users', array($filter => $search))->result();
        if ($filter == '' ||  $search == '') {
            if ($date != '') {
                return $this->db->select('count(*) as total')->from('wp_users')->where("user_registered LIKE '%$date%'")->get()->result_array();
            } else {
                return $this->db->select('count(*) as total')->from('wp_users')->get()->result_array();
            }
        } else if ($filter != '' &&  $search != '') {
            if ($date != '') {
                return $this->db->select('count(*) as total')->from('wp_users')->where($filter . " LIKE '%$search%' AND user_registered LIKE '%$date%")->get()->result_array();
            } else {
                return $this->db->select('count(*) as total')->from('wp_users')->where($filter . " LIKE '%$search%'")->get()->result_array();
            }
        }
    }

    public function getTrekLeaders()
    {
        return $this->db->select('*')->from('trek_leader')->get()->result_array();
    }

    public function getEmailTemplates()
    {
        return $this->db->select('email_id, email_name')->from('email_template')->get()->result_array();
    }

    //This method will no longer be in use, as new logic will search the products tour_booking_periods
    public function getTreksByDate($date)
    {
        return $this->db->select('*')->from('wp_posts')->where("post_type = 'product' AND post_modified_gmt LIKE '%$date%'")->get()->result_array();
    }

    public function getBookingTreks()
    {
        return $this->db->select('*')->from('wp_postmeta')->where("meta_key = 'tour_booking_periods'")->get()->result_array();
    }
    public function getBookingTreksName($trekIDList)
    {
        $idQueryList = '';
        foreach ($trekIDList as $values) {
            $idQueryList = $idQueryList . "'" . $values . "',";
        }
        $idQueryList = rtrim($idQueryList, ',');
        return $this->db->select('*')->from('wp_postmeta')->where("post_id IN( $idQueryList )")->get()->result_array();


        $idQueryList = '';
        foreach ($trekIDList as $values) {
            $idQueryList = $idQueryList . "'" . $values . "',";
        }
        $idQueryList = rtrim($idQueryList, ',');

        echo $query = "SELECT * FROM `wp_postmeta` where meta_value != '' and meta_key = 'tour_booking_periods' and meta_key = 'name' and post_id in (10050)";
        die();

        return $this->db->select('*')->from('wp_postmeta')->where("post_id IN( $idQueryList )")->get()->result_array();
    }
    public function getEmailText($email_id)
    {
        return $this->db->select('email_text')->from('email_template')->where("email_id = '$email_id'")->get()->result_array();
    }
    public function getTrekById($id)
    {
        return $this->db->select('*')->from('wp_posts')->where("ID = '$id'")->get()->result_array();
    }

    public function getTrekInfo($productId)
    {
        return $this->db->select('*')->from('wp_postmeta')->where("post_id = $productId")->get()->result_array();
    }
    // public function getTrekPaidCustomer($productId)
    // {

    //     $query = "select id,post_status FROM ptudev.wp_posts where ID in 
    //     (SELECT distinct(post_id) FROM ptudev.wp_postmeta where  post_id IN(select order_id FROM ptudev.wp_woocommerce_order_items where order_item_id in
    //     (SELECT order_item_id FROM ptudev.wp_woocommerce_order_itemmeta where  meta_value =$productId))) AND post_status = 'wc-completed'";

    //     $result = $this->db->query($query);

    //     return $result->result_array();
    // }
    // public function getBillingInfo($productId)
    // {
    //     $query = "SELECT * FROM wp_postmeta where post_id in(select order_id FROM wp_woocommerce_order_items where order_item_id in(SELECT order_item_id FROM wp_woocommerce_order_itemmeta where  meta_value =$productId))";
    //     $result = $this->db->query($query);

    //     return $result->result_array();
    // }
    public function getBillingInfo($productId)
    {
        $query = "select p.ID, p.post_status, pm.meta_key, pm.meta_value from wp_posts p JOIN wp_postmeta pm where p.ID = pm.post_id AND 
        post_id IN (select order_id FROM wp_woocommerce_order_items where order_item_id in
        (SELECT order_item_id FROM wp_woocommerce_order_itemmeta where  meta_value =$productId)) 
        AND post_status = 'wc-completed'";        

        $result = $this->db->query($query);

        return $result->result_array();
    }
}

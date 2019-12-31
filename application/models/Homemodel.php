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
        $this->db->select('*');
        $this->db->from('wp_users');
        $this->db->where('user_login', $username);
        $result = $this->db->get();
        $numrows = $result->num_rows();
        if ($numrows == 1) {
            return $username;
            $row = $result->row();
            if (password_verify($password, $row->password)) {
                return $row;
            } else {
                return "WRONG_PASSWORD";
            }
        } else {
            return "NO_USER_FOUND";
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

    public function editUser($data)
    {
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
        return $this->db->select('leader_id, name')->from('trek_leader')->get()->result_array();
    }

    public function getEmailTemplates()
    {
        return $this->db->select('email_id, email_name')->from('email_template')->get()->result_array();
    }

    public function getTreksByDate($date)
    {
        return $this->db->select('*')->from('wp_posts')->where("post_type = 'product' AND post_modified_gmt LIKE '%$date%'")->get()->result_array();
    }

    public function getEmailText($email_id)
    {
        return $this->db->select('email_text')->from('email_template')->where("email_id = '$email_id'")->get()->result_array();
    }
}

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
}

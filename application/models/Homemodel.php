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

    public function getUsers()
    {
        $query = $this->db->query("SELECT * from wp_users LIMIT 0,10");
        return $query->result();
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

<<<<<<< HEAD
    public function searchUser($data)
    {
=======
    public function searchUser($filter, $search)
    {
        // return $this->db->get_where('wp_users', array($filter => $search))->result();

        return $this->db->select('*')->from('wp_users')->where($filter . " LIKE '%$search%'")->get()->result_array();
>>>>>>> ec1805f5193bb40b50d33030ee62a1a5104e54be
    }
}

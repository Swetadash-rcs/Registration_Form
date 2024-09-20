<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Make sure to load the database library
    }

    /**
     * Insert user data into the database
     *
     * @param array $data
     * @return bool
     */
    public function insert($data)
    {
        // Ensure that the `user` table exists and is correctly set up
        return $this->db->insert('user', $data);
    }
   
    

    public function get_user_by_email($email)
{
    $this->db->where('emailId', $email);
    $query = $this->db->get('user');
    return $query->row_array(); // Return the user data
}

public function update_password($email, $hashed_password)
{
    
    $this->db->where('email_id', $email);
    $this->db->update('user', ['password' => $hashed_password]);
}
}

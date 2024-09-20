<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/entity/User_entity.php';

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('User_entity');
        $this->load->library('session');
        $this->load->model('User_model', 'user_model');
        $this->load->helper('form');
    }

    public function signup()
    {
        // Retrieve form data
        $email_id = $this->input->post('email');
        $password = $this->input->post('password');

        // Debugging output
        echo 'Email ID: ' . $email_id . '<br>';
        echo 'Password: ' . $password . '<br>';

        // Check if the data is present
        if (empty($email_id) || empty($password)) {
            show_error('Required fields are missing.');
            return; // Prevent further execution
        }

        // Prepare user data
        $userData = array(
            'emailId' => $email_id,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        );

        // Insert user data into the database
        $inserted = $this->user_model->insert($userData);
        
        if ($inserted) {
            echo 'User registered successfully.';
        } else {
            show_error('Failed to register user.');
        }
    }

    public function login()
    {
       
        if ($this->input->post()) {
            $email_id = $this->input->post('email');
            $password = $this->input->post('password');
    
            $user = $this->user_model->get_user_by_email($email_id);
    
            // Check if user exists and verify password
            if ($user && password_verify($password, $user['password'])) {
                // Set session data or other login logic here
                $this->session->set_userdata('emailId', $user['emailId']);
                // Redirect to the Dashboard
                $this->load->view('Dashboard');
            } else {
                $this->load->view('Login_view', ['error' => 'Invalid login credentials.']);
            }
        } else {
            $this->load->view('From_view');
        }
    }

    public function forgot_password()
    {
        $this->load->view('forgot_password_view');
    }

    public function reset_password()
    {
        if ($this->input->post()) {
            $email_id = $this->input->post('email');
            $user = $this->user_model->get_user_by_email($email_id);

            if ($user) {
                // Generate a password reset token and send email (this part is skipped for simplicity)
                echo "A password reset link has been sent to your email.";
            } else {
                $this->load->view('forgot_password_view', ['error' => 'No user found with that email address.']);
            }
        }
    }
}

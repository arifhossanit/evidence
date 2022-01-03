<?php
class user extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library(array('session', 'form_validation', 'email'));
        $this->load->database();
        $this->load->model('user_model');
    }
    
    function index()
    {
        $Data="dfgdfg";
        echo $Data;
        //set validation rules
        $this->form_validation->set_rules('fname', 'First Name', 'trim|required|alpha|min_length[3]|max_length[30]|xss_clean');
        $this->form_validation->set_rules('lname', 'Last Name', 'trim|required|alpha|min_length[3]|max_length[30]|xss_clean');
        $this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|md5');
        
        //validate form input
        if ($this->form_validation->run() == FALSE)
        {
            // fails
            $this->load->view('user_registration_view');
        }
        else
        {
            //insert the user registration details into database
            $data = array(
                'fname' => $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password')
            );
            
            // insert form data into database
            if ($this->user_model->insertUser($data))
            {
                // on successfull form data insert to database
                $this->session->set_flashdata('msg2','<div class="alert alert-success text-center">Success! Registration complete successfully.</div>');
                redirect('user');
            }
            else
            {
                // error
                $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error.  Please try again later!!!</div>');
                redirect('user');
            }
        }
    }

    
    function login()
    {   

        //set validation rules
        $this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('user_login_view');
        }
        else 
        {
            $data = array(
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password')
            );
    
            if ($r = $this->user_model->loginUser($data))
                {
                    //successfull form data insert to database
                    echo '<div class="alert alert-success text-center">Hurray! Login Successfull</div>';
                }
            else
            {
                // error
                $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error.  Please try again later!!!</div>');
                redirect('user/login');
            }
        }
        
    }
}
?>
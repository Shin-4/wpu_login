<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
	{
        $data['title'] = 'Login';
        $this->load->view('Templates/Auth_Header', $data);
        $this->load->view('Auth/Login');
        $this->load->view('Templates/Auth_Footer');
    }

    public function registrasion()
	{
        # Code ini untuk memberikan rules pada kolom masing masing register
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|matches[password2]|min_length[3]',
        [
            'matches' => 'Password dont match!',
            'min_length' => 'Password to shoort'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        # data yang akan ditampilkan 
        if($this->form_validation->run() == false){
            $data['title'] = 'Registrasion';
            $this->load->view('Templates/Auth_Header', $data);
            $this->load->view('Auth/Registrasion');
            $this->load->view('Templates/Auth_Footer');
        } else {
            $data = [
                ## untuk menginput data ke database.
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'date_created' => time()
            ];

            # insert ke database
            $this->db->insert('user', $data);
            # membuat session yang akan dikirimkan ke login page
            $this->session->set_flashdata('message', '
            <div class="alert alert-success" role="alert">
            Congratulation! your account has been created. Please login!
            </div>');
            # jika selesai register auto ke login pake
            redirect('Auth');
        }
    }

        public function lostpassword()
	{
        $data['title'] = 'Lost Password';
        $this->load->view('Templates/Auth_Header', $data);
        $this->load->view('Auth/LostPassword');
        $this->load->view('Templates/Auth_Footer');
    }

}
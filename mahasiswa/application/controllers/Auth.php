<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {   

        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        // $this->load->model('m_login');
        $this->load->library('form_validation');
        $this->load->helper(array('url'));

    }

	public function index() {
		if ($this->session->userdata('username')){
			redirect('user');
		}
		$this->form_validation->set_rules('email', 'Email/Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'Login';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/login');
			$this->load->view('templates/auth_footer');
		} else {

			$this->_login();
		}
	}

	private function _login(){
		$username = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->db->get_where('tb_users',['email' => $username])->row_array();
		// print_r($user);
		// die;

		//if user exist
		if ($user){
			//if user activated
			if ($user['is_active'] == 1){
				//check password
				if (password_verify($password, $user['password'])){
					// echo 'success';
					$data =[
						'username' => $user['email'],
						'role_id' => $user['role_id']
					];
					$this->session->set_userdata($data);
					redirect('user');	
					if ($user['role_id'] == 1){
						redirect('user');
					}
					if ($user['role_id'] == 2){
						redirect('user');						
					}
				} else {
		            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Wrong password!.</div>');
					redirect('auth');

				}


			} else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">This username has not been activated!.</div>');
			redirect('auth');
			}

		} else {			
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Username is not registered!.</div>');
			redirect('auth');
		}
	}

	public function logout(){
        // $this->session->sess_destroy();
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('role_id');
        $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">You had been logged out!.</div>');
		redirect('auth');
	}

	public function registration() {
		if ($this->session->userdata('username')){
			redirect('user');
		}
		$this->form_validation->set_rules('fname', 'First Name', 'required|trim');
		$this->form_validation->set_rules('lname', 'Last Name', 'required|trim');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[tb_users.username]',['is_unique' => 'Username already exist!']);
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[tb_users.email]',['is_unique' => 'Email already exist!']);
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[5]|matches[cpassword]',['min_length'=>'Password too short!','matches'=>'Password dont match!']);
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]',['matches'=>'Password dont match!']);

		if ($this->form_validation->run() == false) {
		$data['title'] = 'Registration';
		$this->load->view('templates/auth_header', $data);
		$this->load->view('auth/registration');
		$this->load->view('templates/auth_footer');
		} else{
			$username = $this->input->post('username',true);
			$data = [
				'fname' => htmlspecialchars($this->input->post('fname',true)),
				'lname' => htmlspecialchars($this->input->post('lname',true)),
				'username' => htmlspecialchars($username),
				'email' => htmlspecialchars($this->input->post('email',true)),
				'image' => 'user.png',
				'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
				'role_id' => 2,
				'is_active' => 0,
				'date_created' => time(),
				'status_login' => 'no'
			];
			//token
			$token = base64_encode(random_bytes(32));
			$user_token = [
				'username' => $username,
				'token' => $token,
				'date_create()' => time()
			];

			$this->db->insert('tb_users',$data);
			$this->db->insert('tb_users_token',$user_token);
			$this->_sendEmail($token, 'verify');
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Succcess! your account has been created. Please check your email to activate.</div>');
			redirect('auth');
		}
	}

	private function _sendEmail($token, $type){
		$config = [
			'protocol'  	=> '',
			'smtp_host' 	=> '',
			'smtp_user' 	=> '',
			'smtp_password' => '',
			'smtp_port' 	=> 465,
			'mail_type' 	=> 'html',
			'charset' 		=>'utf-8',
			'newline' 		=>"\r\n"
		];
		$this->load->library('email',$config);

		$this->email->from('noreplay@nsahebat.co.id', 'NSA Team');
		$this->email->to($this->input->post('email'));
		if ($type == 'verify'){
		$this->email->subject('Account verification');
		$this->email->message('Click this link to verify your account : <a href="'.base_url() . 'auth/verify?username=' . $this->input->post('username') . '&token=' . urlencode($token) . '">Activate</a>');		
		} else if ($type == 'forgot'){
			$this->email->subject('Reset password');
			$this->email->message('Click this link to reset your account : <a href="'.base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset password</a>');		
		}
		if ($this->email->send()) {
			return true;
		} else {
			echo $this->email->print_debugger();
		}
	}

	public function verify(){
		$username = $this->input->get('username');
		$token = $this->input->get('token');

		$user = $this->db->get_where('tb_users', ['username' => $username])->row_array();

		if ($user) {
			$user_token = $this->db->get_where('tb_users_token', ['token' => $token])->row_array();
			if ($user_token) {
				if (time() - $user_token['date_create'] < (60*60*24)) {
					$this->db->set('is_active', 1);
					$this->db->where('username', $username);
					$this->db->update('tb_users');
					$this->db->delete('tb_users_token', ['$username' => $username]);
		            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">'.$username.' has been activated! Please login.</div>');
					redirect('auth');
				} else {
					$this->db->delete('tb_users', ['$username' => $username]);
					$this->db->delete('tb_users_token', ['$username' => $username]);
		            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Token expired! Please to sign up again.</div>');
					redirect('auth');
				}
				
			} else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Account activation failed! Token invalid.</div>');
			redirect('auth');
			}
			
		} else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Account activation failed! Wrong username.</div>');
			redirect('auth');
		}
		
	}

	public function blocked(){
		$this->load->view('auth/blocked');
	}

	public function forgotPassword(){
		$email = $this->input->post('email');

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'Forgot password';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/forgot-password');
			$this->load->view('templates/auth_footer');
		} else {
			$user = $this->db->get_where('tb_users', ['email' =>$email])->row_array();
			if ($user) {
			//token
			$token = base64_encode(random_bytes(32));
			$user_token = [
				'email' => $email,
				'token' => $token,
				'date_create()' => time()
			];
			$this->db->insert('tb_users_token',$user_token);
			$this->_sendEmail($token, 'forgot');
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Please check your email to reset password.</div>');
			redirect('auth/forgotpassword');

			} else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Email is not registered or activated.</div>');
			redirect('auth/forgotpassword');
			}			
		}						
	}

	public function resetPassword(){
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->db->get_where('tb_users', ['email' => $email])->row_array();
		if ($user) {
			$user_token = $this->db->get_where('tb_users_token', ['token' => $token])->row_array();
			if ($user_token) {
				$this->session->set_userdata('reset_email', $email);
				$this->changepassword();
			} else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong token.</div>');
			redirect('auth/forgotpassword');
			}			
		} else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong email.</div>');
			redirect('auth/forgotpassword');
		}		
	}

	public function changePassword(){

		if(!$this->session->userdata('reset_email')){
			redirect('auth');			
		}

		$this->form_validation->set_rules('npassword', 'New Password', 'required|trim|min_length[5]|matches[cpassword]',['min_length'=>'Password too short!','matches'=>'Password dont match!']);
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[npassword]',['matches'=>'Password dont match!']);

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Forgot password';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/change-password');
			$this->load->view('templates/auth_footer');
		} else {
			$password = (password_hash($this->input->post('npassword'), PASSWORD_DEFAULT));
			$email = $this->session->userdata('reset_email');

			$this->db->set('password', $password);
			$this->db->where('email', $email);
			$this->db->update('tb_users');

			$this->session->unset_userdata('reset_email');
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Password has been changed! Please login.</div>');
			redirect('auth');
		}
		
	}
}

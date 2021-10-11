<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() {   

        parent::__construct();
        is_logged_in();
    }

	public function index(){		
		$data['title'] = 'Dashboard';
		$data ['user'] = $this->db->get_where('tb_users',['email' => $this->session->userdata('username')])->row_array(); 
		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('templates/topbar',$data);
		$this->load->view('admin/index',$data);
		$this->load->view('templates/footer');
	}

	public function role(){		
		$data['title'] = 'Role';
		$data ['user'] = $this->db->get_where('tb_users',['email' => $this->session->userdata('username')])->row_array(); 

		$data['role'] = $this->db->get('tb_users_role')->result_array();

		$this->form_validation->set_rules('role','Role','required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header',$data);
			$this->load->view('templates/sidebar',$data);
			$this->load->view('templates/topbar',$data);
			$this->load->view('admin/role',$data);
			$this->load->view('templates/footer');
		} else {
			$this->db->insert('tb_users_role',['role' => $this->input->post('role')]);
		    $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">New role added!.</div>');
			redirect('admin/role');
		}
	}

	public function roleAccess($role_id){
		$data['title'] = 'Role';
		$data ['user'] = $this->db->get_where('tb_users',['email' => $this->session->userdata('username')])->row_array(); 

		$data['role'] = $this->db->get_where('tb_users_role',['id' =>$role_id])->row_array();
		$this->db->where('id !=',1);
		$data['menu'] = $this->db->get('tb_users_menu')->result_array();


		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('templates/topbar',$data);
		$this->load->view('admin/role-access',$data);
		$this->load->view('templates/footer');

	}

	public function changeAccess(){
		$menu_id = $this->input->post('menuId');
		$role_id = $this->input->post('roleId');

		$data = [
			'role_id' => $role_id,
			'menu_id' => $menu_id
		];

		$result = $this->db->get_where('tb_users_access_menu', $data);

		if ($result->num_rows() < 1){
			$this->db->insert('tb_users_access_menu', $data);
		} else {
			$this->db->delete('tb_users_access_menu', $data);
		}
		$this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Access changed!.</div>');

	}

	// public function profile(){		
	// 	$data['title'] = 'Dashboard';
	// 	$data ['user'] = $this->db->get_where('tb_users',['username' => $this->session->userdata('username')])->row_array(); 
	// 	$this->load->view('templates/header',$data);
	// 	$this->load->view('templates/sidebar',$data);
	// 	$this->load->view('templates/topbar',$data);
	// 	$this->load->view('user/profile',$data);
	// 	$this->load->view('templates/footer');
	// }

}
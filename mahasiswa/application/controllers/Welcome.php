<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {   

        parent::__construct();
    }

	public function index(){		
		$data['data'] = $this->db->get('table_siswa',)->result_array(); 
		$this->load->view('index', $data);
	}

	public function filterdata_fak(){
		$fak = $this->input->post('faculty');
		// echo $fak;
		// die();
		$data['data']=$this->db->get_where('table_siswa', ['Fakultas'=> $fak])->result_array();		
		$this->load->view('index', $data);
	}

	public function filterdata_prodi(){
		$prodi = $this->input->post('prodi');
		// echo $fak;
		// die();
		$data['data']=$this->db->get_where('table_siswa', ['Program_study'=> $prodi])->result_array();		
		$this->load->view('index', $data);
	}

	public function about(){		
		$this->load->view('about');
	}

	public function delete_data($id){
		$this->db->where('Id', $id);
		$this->db->delete('table_siswa');
		
		redirect('Welcome');
	}

	public function input_data(){

		$NIM = $this->input->post('NIM');
		$full_name = $this->input->post('full_name');
		$gender = $this->input->post('gender');
		$faculty = $this->input->post('faculty');
		$prodi = $this->input->post('prodi');
		$data=[
			'NIM' => $NIM,
			'Full_Name' => $full_name,
			'Gender' => $gender,
			'Fakultas' => $faculty,
			'Program_study' => $prodi
		];
		//echo $data;
		$this->db->insert('table_siswa',$data);
		redirect('Welcome');
		
		// $data['data'] = $this->db->get('table_siswa',)->result_array(); 
		// $this->load->view('index', $data);
	}

}
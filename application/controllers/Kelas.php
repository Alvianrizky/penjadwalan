<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {

	protected $page_header = 'Kelas Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Kelas_model'=>'kelas'));
		$this->load->library(array('ion_auth', 'form_validation', 'template'));
		$this->load->helper('bootstrap_helper');
	}

	public function index()
	{  
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Kelas List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Kelas';

		$this->template->theme('kelas_v', $data);
	}

	public function get_kelas()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$list = $this->kelas->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) { 
			$id = $field->id;

			$url_view   = 'view_data('.$id.');';
			$url_update = 'update_data('.$id.');';
			$url_delete = 'delete_data('.$id.');';

			$no++;
			$row = array();
			$row[] = ajax_button($url_view, $url_update, $url_delete);
			$row[] = $no;
			$row[] = $field->namaKelas;
			$row[] = $field->semester;

			$data[] = $row;
		}
		
		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->kelas->count_rows(),
			"recordsFiltered" => $this->kelas->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function view()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$id = $this->input->post('idKelas');

		$query = $this->kelas->where('id', $id)->get();

		$data = array();
		if($query){
			$data = array(
				'namaKelas' => $query->namaKelas,
				'semester'   => $query->semester
			);
		}

		echo json_encode($data);
	}

	public function form_data()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$row = array();
		if($this->input->post('idKelas')){
			$id      = $this->input->post('idKelas');
			$query   = $this->kelas->where('id', $id)->get(); 
			if($query){
				$row = array(
					'idKelas'       => $query->id,
					'namaKelas'     => $query->namaKelas,
					'semester'     => $query->semester,
				);
			}
			$row = (object) $row;
		}

		$data = array('hidden'=> form_hidden('idKelas', !empty($row->idKelas) ? $row->idKelas : ''),
			'namaKelas' => form_input(array('name' => 'namaKelas', 'id' => 'namaKelas', 'class' => 'form-control', 'value' => !empty($row->namaKelas) ? $row->namaKelas : '')),
			'semester' => form_input(array('name' => 'semester', 'id' => 'semester', 'class' => 'form-control', 'value' => !empty($row->semester) ? $row->semester : '')),
		);

		echo json_encode($data);
	}

	public function save_kelas()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$rules = array(
			'insert' => array(
				array('field' => 'namaKelas', 'label' => 'Nama Kelas', 'rules' => 'trim|required|max_length[100]'),
				array('field' => 'semester', 'label' => 'Semester', 'rules' => 'trim|required|max_length[11]')
			),
			'update' => array(
				array('field' => 'idKelas', 'label' => 'idKelas', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'namaKelas', 'label' => 'Nama Kelas', 'rules' => 'trim|required|max_length[100]'),
				array('field' => 'semester', 'label' => 'Semester', 'rules' => 'trim|required|max_length[11]')
			)
		);

		$row = array(
			'namaKelas' => $this->input->post('namaKelas'),
			'semester' => $this->input->post('semester')
		);

		$code = 0;

		if ($this->input->post('idKelas') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->kelas->insert($row);

				$error =  $this->db->error();
				if ($error['code'] <> 0) {
					$code = 1;
					$title = 'Warning!';
					$notifications = $error['code'] . ' : ' . $error['message'];
				} else {
					$title = 'Insert!';
					$notifications = 'Success Insert Data';
				}
			} else {
				$code = 1;
				$title = 'Warning!';
				$notifications = validation_errors(' ', ' ');
			}
		} else {

			$this->form_validation->set_rules($rules['update']);

			if ($this->form_validation->run() == true) {

				$id = $this->input->post('idKelas');

				$this->kelas->where('id', $id)->update($row);

				$error =  $this->db->error();
				if ($error['code'] <> 0) {
					$code = 1;
					$title = 'Warning!';
					$notifications = $error['code'] . ' : ' . $error['message'];
				} else {
					$title = 'Update!';
					$notifications = 'Success Update Data';
				}
			} else {
				$code = 1;
				$title = 'Warning!';
				$notifications = validation_errors(' ', ' ');
			}
		}

		$notif = ($code == 0) ? json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code)) : json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
		
		echo $notif;
	}

	public function delete()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$code = 0;

		$id = $this->input->post('idKelas');

		$this->kelas->where('id', $id)->delete();

		$error =  $this->db->error();
		if($error['code'] <> 0){
			$code = 1;
			$notifications = $error['code'].' : '.$error['message'];
		}
		else{
			$notifications = 'Success Delete Data';
		}
		
		$notif = ($code == 0) ? json_encode(array('icon' => 'success', 'message' => $notifications, 'code' => $code)) : json_encode(array('icon' => 'error', 'message' => $notifications, 'code' => $code));
		
		echo $notif;
	}

}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dosen extends CI_Controller {

	protected $page_header = 'Dosen Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Dosen_model'=>'dosen'));
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
		$data['panel_heading'] = 'Dosen List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Dosen';

		$this->template->theme('dosen_v', $data);
	}

	public function get_dosen()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$list = $this->dosen->get_datatables();
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
			$row[] = $field->nip;
			$row[] = $field->namaDosen;

			$data[] = $row;
		}
		
		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->dosen->count_rows(),
			"recordsFiltered" => $this->dosen->count_filtered(),
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

		$id = $this->input->post('idDosen');

		$query = $this->dosen->where('id', $id)->get();

		$data = array();
		if($query){
			$data = array(
				'nip' => $query->nip,
				'namaDosen' => $query->namaDosen,
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
		if($this->input->post('idDosen')){
			$id      = $this->input->post('idDosen');
			$query   = $this->dosen->where('id', $id)->get(); 
			if($query){
				$row = array(
					'idDosen'       => $query->id,
					'nip'       => $query->nip,
					'namaDosen'       => $query->namaDosen,
				);
			}
			$row = (object) $row;
		}

		$data = array('hidden'=> form_hidden('idDosen', !empty($row->idDosen) ? $row->idDosen : ''),
			'nip' => form_input(array('name' => 'nip', 'id' => 'nip', 'class' => 'form-control', 'value' => !empty($row->nip) ? $row->nip : '')),
			'namaDosen' => form_input(array('name' => 'namaDosen', 'id' => 'namaDosen', 'class' => 'form-control', 'value' => !empty($row->namaDosen) ? $row->namaDosen : '')),
		);

		echo json_encode($data);
	}

	public function save_dosen()
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
				array('field' => 'nip', 'label' => 'NIP', 'rules' => 'trim|required|max_length[20]'),
				array('field' => 'namaDosen', 'label' => 'Nama Dosen', 'rules' => 'trim|required|max_length[100]'),
			),
			'update' => array(
				array('field' => 'idDosen', 'label' => 'idDosen', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'nip', 'label' => 'NIP', 'rules' => 'trim|required|max_length[20]'),
				array('field' => 'namaDosen', 'label' => 'Nama Dosen', 'rules' => 'trim|required|max_length[100]'),
			)
		);

		$row = array(
			'nip' => $this->input->post('nip'),
			'namaDosen' => $this->input->post('namaDosen'),
		);


		$code = 0;

		if ($this->input->post('idDosen') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->dosen->insert($row);

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

				$id = $this->input->post('idDosen');

				$this->dosen->where('id', $id)->update($row);

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

		$id = $this->input->post('idDosen');

		$this->dosen->where('id', $id)->delete();

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

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Akademik extends CI_Controller {

	protected $page_header = 'Tahun Akademik Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Akademik_model'=>'akademik'));
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
		$data['panel_heading'] = 'Tahun Akademik List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Tahun Akademik';

		$this->template->theme('akademik_v', $data);
	}

	public function get_akademik()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$list = $this->akademik->get_datatables();
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
			$row[] = $field->namaTahun;

			$data[] = $row;
		}
		
		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->akademik->count_rows(),
			"recordsFiltered" => $this->akademik->count_filtered(),
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

		$id = $this->input->post('idTahun');

		$query = $this->akademik->where('id', $id)->get();

		$data = array();
		if($query){
			$data = array('namaTahun' => $query->namaTahun,
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
		if($this->input->post('idTahun')){
			$id      = $this->input->post('idTahun');
			$query   = $this->akademik->where('id', $id)->get(); 
			if($query){
				$row = array(
					'idTahun'       => $query->id,
					'namaTahun'       => $query->namaTahun,
				);
			}
			$row = (object) $row;
		}

		$data = array('hidden'=> form_hidden('idTahun', !empty($row->idTahun) ? $row->idTahun : ''),
			'namaTahun' => form_input(array('name' => 'namaTahun', 'id' => 'namaTahun', 'class' => 'form-control', 'value' => !empty($row->namaTahun) ? $row->namaTahun : '')),
		);

		echo json_encode($data);
	}

	public function save_akademik()
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
				array('field' => 'namaTahun', 'label' => 'Nama Tahun Akademik', 'rules' => 'trim|required|max_length[100]')
			),
			'update' => array(
				array('field' => 'idTahun', 'label' => 'idTahun', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'namaTahun', 'label' => 'Nama Tahun Akademik', 'rules' => 'trim|required|max_length[100]')
			)
		);

		$row = array(
			'namaTahun' => $this->input->post('namaTahun'),
		);


		$code = 0;

		if ($this->input->post('idTahun') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->akademik->insert($row);

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

				$id = $this->input->post('idTahun');

				$this->akademik->where('id', $id)->update($row);

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

		$id = $this->input->post('idTahun');

		$this->akademik->where('id', $id)->delete();

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

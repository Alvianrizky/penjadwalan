<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Makul extends CI_Controller {

	protected $page_header = 'Mata Kuliah Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Makul_model'=>'makul'));
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
		$data['panel_heading'] = 'Mata Kuliah List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Mata Kuliah';

		$this->template->theme('makul_v', $data);
	}

	public function get_makul()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$list = $this->makul->get_datatables();
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
			$row[] = $field->namaMakul;
			$row[] = $field->jenis;
			$row[] = $field->jumlahSks;

			$data[] = $row;
		}
		
		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->makul->count_rows(),
			"recordsFiltered" => $this->makul->count_filtered(),
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

		$id = $this->input->post('idMakul');

		$query = $this->makul->where('id', $id)->get();

		$data = array();
		if($query){
			$data = array(
				'namaMakul' => $query->namaMakul,
				'jenis' => $query->jenis,
				'jumlahSks' => $query->jumlahSks,
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

		$opt_ruang = ['Teori' => 'Teori', 'Praktikum' => 'Praktikum'];

		$row = array();
		if($this->input->post('idMakul')){
			$id      = $this->input->post('idMakul');
			$query   = $this->makul->where('id', $id)->get(); 
			if($query){
				$row = array(
					'idMakul'       => $query->id,
					'namaMakul'       => $query->namaMakul,
					'jenis'       => $query->jenis,
					'jumlahSks'       => $query->jumlahSks,
				);
			}
			$row = (object) $row;
		}

		$data = array('hidden'=> form_hidden('idMakul', !empty($row->idMakul) ? $row->idMakul : ''),
			'namaMakul' => form_input(array('name' => 'namaMakul', 'id' => 'namaMakul', 'class' => 'form-control', 'value' => !empty($row->namaMakul) ? $row->namaMakul : '')),
			'jumlahSks' => form_input(array('name' => 'jumlahSks', 'id' => 'jumlahSks', 'class' => 'form-control', 'value' => !empty($row->jumlahSks) ? $row->jumlahSks : '')),
			'jenis' => form_dropdown('jenis', $opt_ruang, !empty($row->jenis) ? $row->jenis : '', 'class="form-control chosen-select"'),
		);

		echo json_encode($data);
	}

	public function save_makul()
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
				array('field' => 'namaMakul', 'label' => 'Nama Mata Kuliah', 'rules' => 'trim|required|max_length[100]'),
				array('field' => 'jumlahSks', 'label' => 'Jumlah SKS', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'jenis', 'label' => 'Jenis', 'rules' => 'trim|required|max_length[11]'),
			),
			'update' => array(
				array('field' => 'idMakul', 'label' => 'idMakul', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'namaMakul', 'label' => 'Nama Mata Kuliah', 'rules' => 'trim|required|max_length[100]'),
				array('field' => 'jumlahSks', 'label' => 'Jumlah SKS', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'jenis', 'label' => 'Jenis', 'rules' => 'trim|required|max_length[11]'),
			)
		);

		$row = array(
			'namaMakul' => $this->input->post('namaMakul'),
			'jenis' => $this->input->post('jenis'),
			'jumlahSks' => $this->input->post('jumlahSks'),
		);


		$code = 0;

		if ($this->input->post('idMakul') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->makul->insert($row);

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

				$id = $this->input->post('idMakul');

				$this->makul->where('id', $id)->update($row);

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

		$id = $this->input->post('idMakul');

		$this->makul->where('id', $id)->delete();

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

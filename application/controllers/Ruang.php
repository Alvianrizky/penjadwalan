<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ruang extends CI_Controller {

	protected $page_header = 'Ruang Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Ruang_model'=>'ruang'));
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
		$data['panel_heading'] = 'Ruang List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Ruang';

		$this->template->theme('ruang_v', $data);
	}

	public function get_ruang()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$list = $this->ruang->get_datatables();
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
			$row[] = $field->namaRuang;
			$row[] = $field->kapasitas;
			$row[] = $field->jenis;

			$data[] = $row;
		}
		
		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->ruang->count_rows(),
			"recordsFiltered" => $this->ruang->count_filtered(),
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

		$id = $this->input->post('idRuang');

		$query = $this->ruang->where('id', $id)->get();

		$data = array();
		if($query){
			$data = array(
				'namaRuang' => $query->namaRuang,
				'kapasitas' => $query->kapasitas,
				'jenis' => $query->jenis,
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
		if($this->input->post('idRuang')){
			$id      = $this->input->post('idRuang');
			$query   = $this->ruang->where('id', $id)->get(); 
			if($query){
				$row = array(
					'idRuang'       => $query->id,
					'namaRuang'       => $query->namaRuang,
					'kapasitas'       => $query->kapasitas,
					'jenis'       => $query->jenis,
				);
			}
			$row = (object) $row;
		}

		$data = array('hidden'=> form_hidden('idRuang', !empty($row->idRuang) ? $row->idRuang : ''),
			'namaRuang' => form_input(array('name' => 'namaRuang', 'id' => 'namaRuang', 'class' => 'form-control', 'value' => !empty($row->namaRuang) ? $row->namaRuang : '')),
			'kapasitas' => form_input(array('name' => 'kapasitas', 'id' => 'kapasitas', 'class' => 'form-control', 'value' => !empty($row->kapasitas) ? $row->kapasitas : '')),
			'jenis' => form_dropdown('jenis', $opt_ruang, !empty($row->jenis) ? $row->jenis : '', 'class="form-control chosen-select"'),
		);

		echo json_encode($data);
	}

	public function save_ruang()
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
				array('field' => 'namaRuang', 'label' => 'Nama Ruang', 'rules' => 'trim|required|max_length[100]'),
				array('field' => 'kapasitas', 'label' => 'Kapasitas', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'jenis', 'label' => 'Jenis', 'rules' => 'trim|required|max_length[11]'),
			),
			'update' => array(
				array('field' => 'idRuang', 'label' => 'idRuang', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'namaRuang', 'label' => 'Nama Ruang', 'rules' => 'trim|required|max_length[100]'),
				array('field' => 'kapasitas', 'label' => 'Kapasitas', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'jenis', 'label' => 'Jenis', 'rules' => 'trim|required|max_length[11]'),
			)
		);

		$row = array(
			'namaRuang' => $this->input->post('namaRuang'),
			'kapasitas' => $this->input->post('kapasitas'),
			'jenis' => $this->input->post('jenis'),
		);


		$code = 0;

		if ($this->input->post('idRuang') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->ruang->insert($row);

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

				$id = $this->input->post('idRuang');

				$this->ruang->where('id', $id)->update($row);

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

		$id = $this->input->post('idRuang');

		$this->ruang->where('id', $id)->delete();

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

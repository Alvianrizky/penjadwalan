<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slotwaktu extends CI_Controller {

	protected $page_header = 'Slot Waktu Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Slot_model'=>'slot', 'Ruang_model' => 'ruang'));
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
		$data['panel_heading'] = 'Slot Waktu List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Slot Waktu';

		$this->template->backend('slot_v', $data);
	}

	public function get_slot()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$list = $this->slot->get_datatables();
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
			$row[] = $field->hari;
			$row[] = $field->sesi;
			$row[] = $field->namaRuang;

			$data[] = $row;
		}
		
		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->slot->count_rows(),
			"recordsFiltered" => $this->slot->count_filtered(),
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

		$id = $this->input->post('idSlot');

		$query = $this->slot
		->with_ruang('fields:namaRuang')
		->where('id', $id)->get();

		$data = array();
		if($query){
			$data = array(
				'hari' => $query->hari,
				'sesi' => $query->sesi,
				'idRuang' => $query->ruang->namaRuang,
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

		$opt_hari = [
			'Senin' => 'Senin', 
			'Selasa' => 'Selasa', 
			'Rabu' => 'Rabu', 
			'Kamis' => 'Kamis', 
			'Jumat' => 'Jumat'];
		$opt_sesi = [
			'07.00 - 07.50' => '07.00 - 07.50', 
			'07.50 - 08.40' => '07.50 - 08.40', 
			'08.40 - 09.30' => '08.40 - 09.30', 
			'09.30 - 10.20' => '09.30 - 10.20', 
			'10.20 - 11.10' => '10.20 - 11.10', 
			'11.10 - 12.00' => '11.10 - 12.00', 
			'13.00 - 13.50' => '13.00 - 13.50', 
			'13.50 - 14.40' => '13.50 - 14.40', 
			'14.40 - 15.30' => '14.40 - 15.30', 
			'15.30 - 16.20' => '15.30 - 16.20'
		];
		$opt_ruang = $this->ruang->as_dropdown('namaRuang')->get_all();

		$row = array();
		if($this->input->post('idSlot')){
			$id      = $this->input->post('idSlot');
			$query   = $this->slot
			->with_ruang('fields:namaRuang')
			->where('id', $id)->get(); 
			if($query){
				$row = array(
					'idSlot'       => $query->id,
					'hari' => $query->hari,
					'sesi' => $query->sesi,
					'idRuang' => $query->ruang->namaRuang,
				);
			}
			$row = (object) $row;
		}

		$data = array('hidden'=> form_hidden('idSlot', !empty($row->idSlot) ? $row->idSlot : ''),
			'hari' => form_dropdown('hari', $opt_hari, !empty($row->hari) ? $row->hari : '', 'class="form-control chosen-select"'),
			'sesi' => form_dropdown('sesi', $opt_sesi, !empty($row->sesi) ? $row->sesi : '', 'class="form-control chosen-select"'),
			'idRuang' => form_dropdown('idRuang', $opt_ruang, !empty($row->idRuang) ? $row->idRuang : '', 'class="form-control chosen-select"'),
		);

		echo json_encode($data);
	}

	public function save_slot()
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
				array('field' => 'hari', 'label' => 'Hari', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'sesi', 'label' => 'sesi', 'rules' => 'trim|required|max_length[20]'),
				array('field' => 'idRuang', 'label' => 'Nama Ruang', 'rules' => 'trim|required|max_length[11]'),
			),
			'update' => array(
				array('field' => 'idSlot', 'label' => 'idSlot', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'hari', 'label' => 'Hari', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'sesi', 'label' => 'sesi', 'rules' => 'trim|required|max_length[20]'),
				array('field' => 'idRuang', 'label' => 'Nama Ruang', 'rules' => 'trim|required|max_length[11]'),
			)
		);

		$row = array(
			'hari' => $this->input->post('hari'),
			'sesi' => $this->input->post('sesi'),
			'idRuang' => $this->input->post('idRuang'),
		);


		$code = 0;

		if ($this->input->post('idSlot') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->slot->insert($row);

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

				$id = $this->input->post('idSlot');

				$this->slot->where('id', $id)->update($row);

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

		$id = $this->input->post('idSlot');

		$this->slot->where('id', $id)->delete();

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

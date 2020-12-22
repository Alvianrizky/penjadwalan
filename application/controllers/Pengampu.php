<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pengampu extends CI_Controller {

	protected $page_header = 'Pengampu Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Makul_model'=>'makul', 'Dosen_model' => 'dosen', 'Akademik_model' => 'akademik', 'kelas_model' => 'kelas', 'Pengampu_model' => 'pengampu'));
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
		$data['panel_heading'] = 'Pengampu List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Pengampu';

		$this->template->theme('pengampu_v', $data);
	}

	public function get_pengampu()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$list = $this->pengampu->get_datatables();
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
			$row[] = $field->namaDosen;
			$row[] = $field->namaKelas;
			$row[] = $field->namaTahun;

			$data[] = $row;
		}
		
		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->pengampu->count_rows(),
			"recordsFiltered" => $this->pengampu->count_filtered(),
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

		$id = $this->input->post('idPengampu');

		$query = $this->pengampu
		->with_matakuliah( 'fields:namaMakul')
		->with_dosen( 'fields:namaDosen')
		->with_kelas('fields:namaKelas')
		->with_tahunakademik('fields:namaTahun')
		->where('id', $id)->get();

		$data = array();
		if($query){
			$data = array(
				'idMakul' => $query->matakuliah->namaMakul,
				'idDosen' => $query->dosen->namaDosen,
				'idKelas' => $query->kelas->namaKelas,
				'idTahun' => $query->tahunakademik->namaTahun,
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

		$opt_makul = $this->makul->as_dropdown('namaMakul')->get_all();
		$opt_dosen = $this->dosen->as_dropdown('namaDosen')->get_all();
		$opt_kelas = $this->kelas->as_dropdown('namaKelas')->get_all();
		$opt_akademik = $this->akademik->as_dropdown('namaTahun')->get_all();

		$row = array();
		if($this->input->post('idPengampu')){
			$id      = $this->input->post('idPengampu');
			$query   = $this->pengampu
			->with_matakuliah('fields:namaMakul')
			->with_dosen('fields:namaDosen')
			->with_kelas('fields:namaKelas')
			->with_tahunakademik('fields:namaTahun')
			->where('id', $id)->get(); 
			if($query){
				$row = array(
					'idPengampu'       => $query->id,
					'idMakul' => $query->idMakul,
					'idDosen' => $query->idDosen,
					'idKelas' => $query->idKelas,
					'idTahun' => $query->tahunakademik->namaTahun,
				);
			}
			$row = (object) $row;
		}

		$data = array('hidden'=> form_hidden('idPengampu', !empty($row->idPengampu) ? $row->idPengampu : ''),
			'idMakul' => form_dropdown('idMakul', $opt_makul, !empty($row->idMakul) ? $row->idMakul : '', 'class="form-control chosen-select"'),
			'idDosen' => form_dropdown('idDosen', $opt_dosen, !empty($row->idDosen) ? $row->idDosen : '', 'class="form-control chosen-select"'),
			'idKelas' => form_dropdown('idKelas', $opt_kelas, !empty($row->idKelas) ? $row->idKelas : '', 'class="form-control chosen-select"'),
			'idTahun' => form_dropdown('idTahun', $opt_akademik, !empty($row->idTahun) ? $row->idTahun : '', 'class="form-control chosen-select"'),
		);

		echo json_encode($data);
	}

	public function save_pengampu()
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
				array('field' => 'idMakul', 'label' => 'Nama Mata Kuliah', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'idDosen', 'label' => 'Nama Dosen', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'idKelas', 'label' => 'Nama Ruang', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'idTahun', 'label' => 'Tahun Akademik', 'rules' => 'trim|required|max_length[11]'),
			),
			'update' => array(
				array('field' => 'idPengampu', 'label' => 'idPengampu', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'idMakul', 'label' => 'Nama Mata Kuliah', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'idDosen', 'label' => 'Nama Dosen', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'idKelas', 'label' => 'Nama Ruang', 'rules' => 'trim|required|max_length[11]'),
				array('field' => 'idTahun', 'label' => 'Tahun Akademik', 'rules' => 'trim|required|max_length[11]'),
			)
		);

		$row = array(
			'idMakul' => $this->input->post('idMakul'),
			'idDosen' => $this->input->post('idDosen'),
			'idKelas' => $this->input->post('idKelas'),
			'idTahun' => $this->input->post('idTahun'),
		);


		$code = 0;

		if ($this->input->post('idPengampu') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->pengampu->insert($row);

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

				$id = $this->input->post('idPengampu');

				$this->pengampu->where('id', $id)->update($row);

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

		$id = $this->input->post('idPengampu');

		$this->pengampu->where('id', $id)->delete();

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

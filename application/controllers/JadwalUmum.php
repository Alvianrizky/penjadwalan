<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class JadwalUmum extends CI_Controller {

	protected $page_header = 'Jadwal Kuliah';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Jadwal_model'=>'jadwal', 'Ruang_model' => 'ruang','Makul_model' => 'makul', 'Dosen_model' => 'dosen', 'Kelas_model' => 'kelas', 'Pengampu_model' => 'pengampu'));
		$this->load->library(array('ion_auth', 'form_validation', 'template', 'pdf2'));
		$this->load->helper('bootstrap_helper');
	}

	public function index()
	{  
		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Jadwal List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Jadwal';

		$this->template->umum('jadwal_umum_v', $data);
	}

	public function table()
	{
		$hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
		$jam = ['07.00', '07.50', '08.40', '09.30', '10.20', '11.10', '12.00', '13.00', '13.50', '14.40', '15.30', '16.20'];

		$ruang = $this->ruang->as_array()->get_all();
		$kromosom = $this->jadwal();

		for ($j = 0; $j < (count($jam) - 1); $j++) {
			$waktu[] = [$j, $j + 1];
		}

		set_table(true);

		if(!empty($kromosom)) {
			$color = 'bg-primary';

			for ($i = 0; $i < count($hari); $i++) {
				$color = ($color == 'bg-primary') ? 'bg-success' : ($color == 'bg-success' ? 'bg-info' : ($color == 'bg-info' ? 'bg-warning' : 'bg-primary'));

				$in_hari_jam = [
					'data' => 'Hari / Jam <br> Ruang',
					'rowspan' => 2,
					'class' => 'font-weight-bold bg ' . $color . '',
					'style' => 'vertical-align : middle; text-align : center; width: 130px'
				];

				$in_hari = [
					'data' => $hari[$i],
					'colspan' => 12,
					'class' => 'font-weight-bold bg ' . $color . ''
				];

				$in_back = [
					'data' => 'istirahat',
					'class' => 'bg bg-secondary'
				];

				$in_waktu = ['07.00 - 07.50', '07.50 - 08.40', '08.40 - 09.30', '09.30 - 10.20', '10.20 - 11.10', '11.10 - 12.00', '12.00 - 13.00', '13.00 - 13.50', '13.50 - 14.40', '14.40 - 15.30', '15.30 - 16.20'];

				for ($x = 0; $x < count($waktu); $x++) {
					$sub_waktu[$x] = [
						'data' => $in_waktu[$x],
						'style' => 'width: 250px!important;',
						'class' => 'font-weight-bold bg ' . $color . ''
					];
				}

				$this->table->add_row($in_hari_jam, $in_hari);
				$this->table->add_row($sub_waktu[0], $sub_waktu[1], $sub_waktu[2], $sub_waktu[3], $sub_waktu[4], $sub_waktu[5], $sub_waktu[6], $sub_waktu[7], $sub_waktu[8], $sub_waktu[9], $sub_waktu[10]);

				for ($j = 0; $j < count($ruang); $j++) {
					$ruang_in = $kromosom[$hari[$i]][$ruang[$j]['namaRuang']];
					if ($ruang_in !== 'kosong') {
						$in_ruang = [
							'data' => $ruang[$j]['namaRuang'],
							'rowspan' => count($ruang_in),
							'class' => 'font-weight-bold bg ' . $color . '',
							'style' => 'vertical-align : middle; text-align : center;'
						];

						for ($l = 0; $l < count($waktu); $l++) {
							if ($ruang_in[0][$l] == 'kosong') {
								$in_waktu_1[$l] = '';
							} else {
								$pengampu = $ruang_in[0][$l];
								$makul = $this->makul->where('id', $pengampu['idMakul'])->get();
								// $kelas = $this->kelas->where('id', $pengampu['idKelas'])->get();
								$dosen = $this->dosen->where('id', $pengampu['idDosen'])->get();

								$search_array = strpos($pengampu['idKelas'], ",");

								if(empty($search_array)) {
									$pisah = $pengampu['idKelas'];
								} else {
									$pisah = explode(",", $pengampu['idKelas']);
								}

								if (is_array($pisah) == 1) {
									$kelas1 = $this->kelas->where('id', $pisah[0])->get();
									$kelas2 = $this->kelas->where('id', $pisah[1])->get();

									if (!empty($ruang_in[0][$l]['nilai'])) {
										$in_data = [
											'data' => $makul->namaMakul .
												'<br>' . $kelas1->namaKelas . ', ' . $kelas2->namaKelas .
												'<br>' . $dosen->namaDosen,
											'class' => 'bg bg-danger text-white',
											

										];
										$in_waktu_1[$l] = $in_data;
									} else {

										$in_data = [
											'data' => $makul->namaMakul .
												'<br>' . $kelas1->namaKelas . ', ' . $kelas2->namaKelas .
												'<br>' . $dosen->namaDosen,
											// 'class' => 'bg ' . $color . '',
											

										];
										$in_waktu_1[$l] = $in_data;
									}
								} else {
									$kelas = $this->kelas->where('id', $pisah)->get();

									if (!empty($ruang_in[0][$l]['nilai'])) {
										$in_data = [
											'data' => $makul->namaMakul .
												'<br>' . $kelas->namaKelas .
												'<br>' . $dosen->namaDosen,
											'class' => 'bg bg-danger text-white',
											

										];
										$in_waktu_1[$l] = $in_data;
									} else {
										$in_data = [
											'data' => $makul->namaMakul .
												'<br>' . $kelas->namaKelas .
												'<br>' . $dosen->namaDosen,
											// 'class' => 'bg ' . $color . '',
											

										];
										$in_waktu_1[$l] = $in_data;
									}
								}
							}
						}

						$this->table->add_row($in_ruang, $in_waktu_1[0], $in_waktu_1[1], $in_waktu_1[2], $in_waktu_1[3], $in_waktu_1[4], $in_waktu_1[5], $in_back, $in_waktu_1[7], $in_waktu_1[8], $in_waktu_1[9], $in_waktu_1[10]);

						if (count($ruang_in) > 1) {

							for ($k = 1; $k < count($ruang_in); $k++) {
								for ($m = 0; $m < count($waktu); $m++) {
									if ($ruang_in[$k][$m] == 'kosong') {
										$in_waktu_2[$m] = '';
									} else {
										$pengampu = $ruang_in[$k][$m];
										$makul = $this->makul->where('id', $pengampu['idMakul'])->get();
										// $kelas = $this->kelas->where('id', $pengampu['idKelas'])->get();
										$dosen = $this->dosen->where('id', $pengampu['idDosen'])->get();

										$search_array = strpos($pengampu['idKelas'], ",");

										if (empty($search_array)) {
											$pisah = $pengampu['idKelas'];
										} else {
											$pisah = explode(",", $pengampu['idKelas']);
										}

										if (is_array($pisah)) {
											$kelas1 = $this->kelas->where('id', $pisah[0])->get();
											$kelas2 = $this->kelas->where('id', $pisah[1])->get();
											if (!empty($ruang_in[$k][$m]['nilai'])) {
												$in_data = [
													'data' => $makul->namaMakul .
														'<br>' . $kelas1->namaKelas . ', ' . $kelas2->namaKelas .
														'<br>' . $dosen->namaDosen,
													'class' => 'bg bg-danger text-white',
													

												];
												$in_waktu_2[$m] = $in_data;
											} else {
												$in_data = [
													'data' => $makul->namaMakul .
														'<br>' . $kelas1->namaKelas . ', ' . $kelas2->namaKelas .
														'<br>' . $dosen->namaDosen,
													// 'class' => 'bg ' . $color . '',
													

												];
												$in_waktu_2[$m] = $in_data;
											}
										} else {
											$kelas = $this->kelas->where('id', $pisah)->get();

											if (!empty($ruang_in[$k][$m]['nilai'])) {
												$in_data = [
													'data' => $makul->namaMakul .
														'<br>' . $kelas->namaKelas .
														'<br>' . $dosen->namaDosen,
													'class' => 'bg bg-danger text-white',
													

												];
												$in_waktu_2[$m] = $in_data;
											} else {
												$in_data = [
													'data' => $makul->namaMakul .
														'<br>' . $kelas->namaKelas .
														'<br>' . $dosen->namaDosen,
													// 'class' => 'bg ' . $color . '',
													

												];
												$in_waktu_2[$m] = $in_data;
											}
										}
									}
								}
								$this->table->add_row($in_waktu_2[0], $in_waktu_2[1], $in_waktu_2[2], $in_waktu_2[3], $in_waktu_2[4], $in_waktu_2[5], $in_back, $in_waktu_2[7], $in_waktu_2[8], $in_waktu_2[9], $in_waktu_2[10]);
							}
						}
					} else {
						$in_ruang1 = [
							'data' => $ruang[$j]['namaRuang'],
							'class' => 'font-weight-bold bg ' . $color . ''
						];


						for ($n = 0; $n < count($waktu); $n++) {
							$in_waktu_3[$n] = '';
						}

						$this->table->add_row($in_ruang1, $in_waktu_3[0], $in_waktu_3[1], $in_waktu_3[2], $in_waktu_3[3], $in_waktu_3[4], $in_waktu_3[5], $in_back, $in_waktu_3[7], $in_waktu_3[8], $in_waktu_3[9], $in_waktu_3[10]);
					}
				}
			}

			$hasil = $this->table->generate();

			$data['hasil'] = $hasil;
		} else {
			$data['hasil'] = '<h4 class="text-center">Data jadwal terbaru belum ada, silahkan generate jadwal terlebih dahulu</h4';
		}

		echo json_encode($data);
	}

	public function form_data()
	{
		$opt_hari = [
			'Senin' => 'Senin',
			'Selasa' => 'Selasa',
			'Rabu' => 'Rabu',
			'Kamis' => 'Kamis',
			'Jumat' => 'Jumat'
		];
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
		$ruang = $this->ruang->as_array()->get_all();

		$opt_ruang = [];
		for($i = 0; $i < count($ruang); $i++) {
			$opt_ruang[$ruang[$i]['namaRuang']] = $ruang[$i]['namaRuang'];
		}
		

		$row = array();
		if ($this->input->post('id')) {
			$id      = $this->input->post('id');
			$jadwal = $this->jadwal->where('idpengampu', $id)->get();
			$query   = $this->pengampu
				->with_dosen('fields:namaDosen')
				->with_matakuliah('fields:namaMakul')
				->where('id', $id)->get();
			if ($query) {
				$row = array(
					'id'       => $query->id,
					'idDosen' => $query->dosen->namaDosen,
					'idMakul' => $query->matakuliah->namaMakul,
				);
			}
			$row = (object) $row;
		}

		$data = array(
			'hidden' => form_hidden('id', !empty($row->id) ? $row->id : ''),
			'hari' => form_dropdown('hari', $opt_hari, !empty($jadwal->hari) ? $jadwal->hari : '', 'class="form-control chosen-select"'),
			'sesi' => form_dropdown('sesi', $opt_sesi, !empty($jadwal->waktu) ? $jadwal->waktu : '', 'class="form-control chosen-select" onchange="cek(this.value, '. $row->id.')"'),
			'idRuang' => form_dropdown('idRuang', $opt_ruang, !empty($jadwal->ruang) ? $jadwal->ruang : '', 'class="form-control chosen-select"'),
			'idDosen' => form_input(array('name' => 'idDosen', 'id' => 'idDosen', 'class' => 'form-control', 'value' => !empty($row->idDosen) ? $row->idDosen : '')),
			'idMakul' => form_input(array('name' => 'idMakul', 'id' => 'idMakul', 'class' => 'form-control', 'value' => !empty($row->idMakul) ? $row->idMakul : '')),
			'waktu' => form_input(array('name' => 'waktu', 'id' => 'waktu', 'class' => 'form-control', 'value' => '')),
		);

		echo json_encode($data);
	}

	public function jadwal()
	{
		$in_waktu = ['07.00 - 07.50', '07.50 - 08.40', '08.40 - 09.30', '09.30 - 10.20', '10.20 - 11.10', '11.10 - 12.00', '12.00 - 13.00', '13.00 - 13.50', '13.50 - 14.40', '14.40 - 15.30', '15.30 - 16.20'];
		$hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

		$jadwal = $this->jadwal->as_array()->get_all();
		$ruang = $this->ruang->as_array()->get_all();

		if(!empty($jadwal)) {
			for ($i = 0; $i < count($hari); $i++) {
				$hari_index = $hari[$i];
				$hari_cek = [];
				for ($j = 0; $j < count($jadwal); $j++) {
					if ($jadwal[$j]['hari'] == $hari_index) {
						$hari_cek[] = $j;
						// $hari_cek[] = $jadwal[$j];
					}
				}

				$ruang_cek = [];
				for ($l = 0; $l < count($hari_cek); $l++) {
					$ruang_kromosom = $jadwal[$hari_cek[$l]]['ruang'];
					for ($m = 0; $m < count($ruang); $m++) {
						if ($ruang[$m]['namaRuang'] == $ruang_kromosom) {
							$ruang_gen = $hari_cek[$l];
							// $ruang_cek[$ruang[$m]['namaRuang']][] = $ruang_gen;
							$ruang_cek[$ruang[$m]['namaRuang']][] = $jadwal[$ruang_gen];
						}
					}
				}

				for ($m = 0; $m < count($ruang); $m++) {
					$search_ruang = array_key_exists($ruang[$m]['namaRuang'], $ruang_cek);
					if ($search_ruang == 1) {
						$ruang_waktu = $ruang_cek[$ruang[$m]['namaRuang']];

						$grub = [];

						for ($s = 0; $s < count($ruang_waktu); $s++) {
							// $where = [
							// 	'idDosen' => $ruang_waktu[$s]['idDosen'], 
							// 	'idMakul' => $ruang_waktu[$s]['idMakul']
							// ];
							// $cek = $this->jadwal->where($where)->get_all();

							$grub[$ruang_waktu[$s]['idMakul']][] = $ruang_waktu[$s];
						}

						$urutan = array_values($grub);

						$ruangan = [];
						for ($k = 0; $k < count($urutan); $k++) {

							$waktu_cek = [];
							for ($n = 0; $n < count($in_waktu); $n++) {
								for ($q = 0; $q < count($urutan[$k]); $q++) {
									if ($urutan[$k][$q]['waktu'] == $in_waktu[$n]) {
										$waktu_cek[] = $urutan[$k][$q];
										break;
									} else {
										if (count($urutan[$k]) == ($q + 1)) {
											$waktu_cek[] = 'kosong';
										}
									}
								}
							}

							$ruangan[] = $waktu_cek;
						}

						$hasil_ruang_waktu[$ruang[$m]['namaRuang']] = $ruangan;
					} else {
						$hasil_ruang_waktu[$ruang[$m]['namaRuang']] = 'kosong';
					}
				}

				$hari_array[$hari_index] = $hasil_ruang_waktu;
			}
		} else {
			$hari_array = 0;
		}

		// echo "<pre>";
		// print_r($hari_array);

		return $hari_array;
	}

	public function cek()
	{
		$in_waktu = ['07.00 - 07.50', '07.50 - 08.40', '08.40 - 09.30', '09.30 - 10.20', '10.20 - 11.10', '11.10 - 12.00', '12.00 - 13.00', '13.00 - 13.50', '13.50 - 14.40', '14.40 - 15.30', '15.30 - 16.20'];

		$awal = $this->input->post('akhir');
		$id = $this->input->post('id');

		$pengampu = $this->pengampu->where('id', $id)->get();
		$makul = $this->makul->where('id', $pengampu->idMakul)->get();

		$search_index = array_search($awal, $in_waktu);

		$sks = $makul->jumlahSks;

		$tambah_sks = $search_index + ($sks - 1);

		$data = array(
			'waktu' => form_input(array('name' => 'waktu', 'id' => 'waktu', 'class' => 'form-control', 'value' => $in_waktu[$tambah_sks])),
		);

		echo json_encode($data);
	}

	public function save_update()
	{
		$in_waktu = ['07.00 - 07.50', '07.50 - 08.40', '08.40 - 09.30', '09.30 - 10.20', '10.20 - 11.10', '11.10 - 12.00', '12.00 - 13.00', '13.00 - 13.50', '13.50 - 14.40', '14.40 - 15.30', '15.30 - 16.20'];

		$rules = array(
			array('field' => 'idDosen', 'label' => 'idDosen', 'rules' => 'trim|required'),
			array('field' => 'idMakul', 'label' => 'idMakul', 'rules' => 'trim|required'),
			array('field' => 'hari', 'label' => 'hari', 'rules' => 'trim|required'),
			array('field' => 'sesi', 'label' => 'sesi', 'rules' => 'trim|required'),
			array('field' => 'waktu', 'label' => 'waktu', 'rules' => 'trim|required'),
			array('field' => 'idRuang', 'label' => 'idRuang', 'rules' => 'trim|required'),
		);

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == true) {

			$makul = $this->makul->where('namaMakul', $this->input->post('idMakul'))->get();
			$pengampu = $this->pengampu->where('id', $this->input->post('id'))->get();
			$jadwal = $this->jadwal->as_array()->get_all();
			$awal = $this->input->post('sesi');
			$akhir = $this->input->post('waktu');

			$in_awal = array_search($awal, $in_waktu);
			$in_akhir = array_search($akhir, $in_waktu);

			$waktu_range = range($in_awal, $in_akhir);

			$nilai = 0;
			$code = 0;

			for ($j = 0; $j < count($jadwal); $j++) {

				$in_jadwal = $jadwal[$j];
				$in_search_waktu = array_search($in_jadwal['waktu'], $in_waktu);
				for ($k = 0; $k < count($waktu_range); $k++) {
					if (in_array($in_search_waktu, $waktu_range)) {
						$status_waktu = 'Ada';
						break;
					} else {
						$status_waktu = 'Tidak Ada';
					}
				}

				if ($in_jadwal['idDosen'] == $pengampu->idDosen && $in_jadwal['ruang'] == $this->input->post('idRuang') && $in_jadwal['hari'] == $this->input->post('hari') && $status_waktu == 'Ada' || $in_jadwal['idKelas'] == $pengampu->idKelas && $in_jadwal['hari'] == $this->input->post('hari') && $status_waktu == 'Ada' || $in_jadwal['idDosen'] == $pengampu->idDosen && $in_jadwal['hari'] == $this->input->post('hari') && $status_waktu == 'Ada' || $in_jadwal['hari'] == $this->input->post('hari') && $status_waktu == 'Ada' && $in_jadwal['ruang'] == $this->input->post('idRuang')) {
					if($in_jadwal['nilai'] == 0) {
						$nilai = 10;
						break;
					}
				}
			}

			for ($i = 0; $i < count($waktu_range); $i++) {
				$row[$i] = [
					'idPengampu' => $this->input->post('id'),
					'idDosen' => $pengampu->idDosen,
					'idMakul' => $makul->id,
					'idKelas' => $pengampu->idKelas,
					'hari' => $this->input->post('hari'),
					'waktu' => $in_waktu[$waktu_range[$i]],
					'ruang' => $this->input->post('idRuang'),
					'nilai' => $nilai,
				];
			}

			$this->jadwal->where('idPengampu', $this->input->post('id'))->delete();

			$this->jadwal->insert($row);

			$error =  $this->db->error();
			if ($error['code'] <> 0) {
				$code = 1;
				$title = 'Warning!';
				$notifications = $error['code'] . ' : ' . $error['message'];
			} else {
				$title = 'Insert!';
				$notifications = 'Berhasil Update Jadwal';
			}
		} else {
			$code = 1;
			$title = 'Warning!';
			$notifications = validation_errors(' ', ' ');
		}

		$notif = ($code == 0) ? json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code)) : json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));

		echo $notif;
	}

	public function hapus()
	{
		$code = 0;

		$this->jadwal->truncate();

		$error =  $this->db->error();
		if ($error['code'] <> 0) {
			$code = 1;
			$notifications = $error['code'] . ' : ' . $error['message'];
		} else {
			$notifications = 'Success Delete Data';
		}

		$notif = ($code == 0) ? json_encode(array('icon' => 'success', 'message' => $notifications, 'code' => $code)) : json_encode(array('icon' => 'error', 'message' => $notifications, 'code' => $code));

		echo $notif;
	}

	public function cetak()
	{
		$hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
		$jam = ['07.00', '07.50', '08.40', '09.30', '10.20', '11.10', '12.00', '13.00', '13.50', '14.40', '15.30', '16.20'];

		$ruang = $this->ruang->as_array()->get_all();
		$kromosom = $this->jadwal();

		for ($j = 0; $j < (count($jam) - 1); $j++) {
			$waktu[] = [$j, $j + 1];
		}

		set_table2(true);

		if (!empty($kromosom)) {
			$color = 'bg-primary';

			for ($i = 0; $i < count($hari); $i++) {
				$color = ($color == 'bg-primary') ? 'bg-success' : ($color == 'bg-success' ? 'bg-info' : ($color == 'bg-info' ? 'bg-warning' : 'bg-primary'));

				$in_hari_jam = [
					'data' => 'Hari / Jam <br> Ruang',
					'rowspan' => 2,
					'class' => 'font-weight-bold bg ' . $color . '',
					'style' => 'vertical-align : middle; text-align : center; width: 100px'
				];

				$in_hari = [
					'data' => $hari[$i],
					'colspan' => 12,
					'class' => 'font-weight-bold bg ' . $color . ''
				];

				$in_back = [
					'data' => 'istirahat',
					'class' => 'bg bg-secondary',
					'style' => 'width: 100px;'
				];

				$in_waktu = ['07.00 - 07.50', '07.50 - 08.40', '08.40 - 09.30', '09.30 - 10.20', '10.20 - 11.10', '11.10 - 12.00', '12.00 - 13.00', '13.00 - 13.50', '13.50 - 14.40', '14.40 - 15.30', '15.30 - 16.20'];

				for ($x = 0; $x < count($waktu); $x++) {
					$sub_waktu[$x] = [
						'data' => $in_waktu[$x],
						'style' => 'width: 200px!important;',
						'class' => 'font-weight-bold bg ' . $color . ''
					];
				}

				$this->table->add_row($in_hari_jam, $in_hari);
				$this->table->add_row($sub_waktu[0], $sub_waktu[1], $sub_waktu[2], $sub_waktu[3], $sub_waktu[4], $sub_waktu[5], $sub_waktu[6], $sub_waktu[7], $sub_waktu[8], $sub_waktu[9], $sub_waktu[10]);

				for ($j = 0; $j < count($ruang); $j++) {
					$ruang_in = $kromosom[$hari[$i]][$ruang[$j]['namaRuang']];
					if ($ruang_in !== 'kosong') {
						$in_ruang = [
							'data' => $ruang[$j]['namaRuang'],
							'rowspan' => count($ruang_in),
							'class' => 'font-weight-bold bg ' . $color . '',
							'style' => 'vertical-align : middle; text-align : center;'
						];

						for ($l = 0; $l < count($waktu); $l++) {
							if ($ruang_in[0][$l] == 'kosong') {
								$in_waktu_1[$l] = '';
							} else {
								$pengampu = $ruang_in[0][$l];
								$makul = $this->makul->where('id', $pengampu['idMakul'])->get();
								// $kelas = $this->kelas->where('id', $pengampu['idKelas'])->get();
								$dosen = $this->dosen->where('id', $pengampu['idDosen'])->get();

								$search_array = strpos($pengampu['idKelas'], ",");

								if (empty($search_array)) {
									$pisah = $pengampu['idKelas'];
								} else {
									$pisah = explode(",", $pengampu['idKelas']);
								}

								if (is_array($pisah) == 1) {
									$kelas1 = $this->kelas->where('id', $pisah[0])->get();
									$kelas2 = $this->kelas->where('id', $pisah[1])->get();

									if (!empty($ruang_in[0][$l]['nilai'])) {
										$in_data = [
											'data' => $makul->namaMakul .
												'<br>' . $kelas1->namaKelas . ', ' . $kelas2->namaKelas .
												'<br>' . $dosen->namaDosen,
											'class' => '',
											

										];
										$in_waktu_1[$l] = $in_data;
									} else {

										$in_data = [
											'data' => $makul->namaMakul .
												'<br>' . $kelas1->namaKelas . ', ' . $kelas2->namaKelas .
												'<br>' . $dosen->namaDosen,
											// 'class' => 'bg ' . $color . '',
											

										];
										$in_waktu_1[$l] = $in_data;
									}
								} else {
									$kelas = $this->kelas->where('id', $pisah)->get();

									if (!empty($ruang_in[0][$l]['nilai'])) {
										$in_data = [
											'data' => $makul->namaMakul .
												'<br>' . $kelas->namaKelas .
												'<br>' . $dosen->namaDosen,
											'class' => '',
											

										];
										$in_waktu_1[$l] = $in_data;
									} else {
										$in_data = [
											'data' => $makul->namaMakul .
												'<br>' . $kelas->namaKelas .
												'<br>' . $dosen->namaDosen,
											// 'class' => 'bg ' . $color . '',
											

										];
										$in_waktu_1[$l] = $in_data;
									}
								}
							}
						}

						$this->table->add_row($in_ruang, $in_waktu_1[0], $in_waktu_1[1], $in_waktu_1[2], $in_waktu_1[3], $in_waktu_1[4], $in_waktu_1[5], $in_back, $in_waktu_1[7], $in_waktu_1[8], $in_waktu_1[9], $in_waktu_1[10]);

						if (count($ruang_in) > 1) {

							for ($k = 1; $k < count($ruang_in); $k++) {
								for ($m = 0; $m < count($waktu); $m++) {
									if ($ruang_in[$k][$m] == 'kosong') {
										$in_waktu_2[$m] = '';
									} else {
										$pengampu = $ruang_in[$k][$m];
										$makul = $this->makul->where('id', $pengampu['idMakul'])->get();
										// $kelas = $this->kelas->where('id', $pengampu['idKelas'])->get();
										$dosen = $this->dosen->where('id', $pengampu['idDosen'])->get();

										$search_array = strpos($pengampu['idKelas'], ",");

										if (empty($search_array)) {
											$pisah = $pengampu['idKelas'];
										} else {
											$pisah = explode(",", $pengampu['idKelas']);
										}

										if (is_array($pisah)) {
											$kelas1 = $this->kelas->where('id', $pisah[0])->get();
											$kelas2 = $this->kelas->where('id', $pisah[1])->get();
											if (!empty($ruang_in[$k][$m]['nilai'])) {
												$in_data = [
													'data' => $makul->namaMakul .
														'<br>' . $kelas1->namaKelas . ', ' . $kelas2->namaKelas .
														'<br>' . $dosen->namaDosen,
													'class' => '',
													

												];
												$in_waktu_2[$m] = $in_data;
											} else {
												$in_data = [
													'data' => $makul->namaMakul .
														'<br>' . $kelas1->namaKelas . ', ' . $kelas2->namaKelas .
														'<br>' . $dosen->namaDosen,
													

												];
												$in_waktu_2[$m] = $in_data;
											}
										} else {
											$kelas = $this->kelas->where('id', $pisah)->get();

											if (!empty($ruang_in[$k][$m]['nilai'])) {
												$in_data = [
													'data' => $makul->namaMakul .
														'<br>' . $kelas->namaKelas .
														'<br>' . $dosen->namaDosen,
													'class' => '',

												];
												$in_waktu_2[$m] = $in_data;
											} else {
												$in_data = [
													'data' => $makul->namaMakul .
														'<br>' . $kelas->namaKelas .
														'<br>' . $dosen->namaDosen,
													// 'class' => 'bg ' . $color . '',

												];
												$in_waktu_2[$m] = $in_data;
											}
										}
									}
								}
								$this->table->add_row($in_waktu_2[0], $in_waktu_2[1], $in_waktu_2[2], $in_waktu_2[3], $in_waktu_2[4], $in_waktu_2[5], $in_back, $in_waktu_2[7], $in_waktu_2[8], $in_waktu_2[9], $in_waktu_2[10]);
							}
						}
					} else {
						$in_ruang1 = [
							'data' => $ruang[$j]['namaRuang'],
							'class' => 'font-weight-bold bg ' . $color . ''
						];


						for ($n = 0; $n < count($waktu); $n++) {
							$in_waktu_3[$n] = '';
						}

						$this->table->add_row($in_ruang1, $in_waktu_3[0], $in_waktu_3[1], $in_waktu_3[2], $in_waktu_3[3], $in_waktu_3[4], $in_waktu_3[5], $in_back, $in_waktu_3[7], $in_waktu_3[8], $in_waktu_3[9], $in_waktu_3[10]);
					}
				}
			}

			$hasil = $this->table->generate();

			$data['hasil'] = $hasil;
		} else {
			$data['hasil'] = '<h4 class="text-center">Data jadwal terbaru belum ada, silahkan generate jadwal terlebih dahulu</h4';
		}

		$this->load->view('backend/head', $data);
	}

}

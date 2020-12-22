<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Generate extends CI_Controller {

	protected $page_header = 'Generate Jadwal';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Slot_model'=>'slot', 'Ruang_model' => 'ruang', 'Akademik_model' => 'akademik', 'Pengampu_model' => 'pengampu', 'Makul_model' => 'makul', 'Dosen_model' => 'dosen', 'Kelas_model' => 'kelas', 'Jadwal_model' => 'jadwal'));
		$this->load->library(array('ion_auth', 'form_validation', 'template'));
		$this->load->helper('bootstrap_helper');
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
		$opt_ruang = $this->ruang->as_dropdown('namaRuang')->get_all();

		$row = array();
		if ($this->input->post('id')) {
			$id      = $this->input->post('id');
			$query   = $this->pengampu
				->with_dosen('fields:namaDosen')
				->where('id', $id)->get();
			if ($query) {
				$row = array(
					'id'       => $query->id,
					'idDosen' => $query->dosen->namaDosen,
				);
			}
			$row = (object) $row;
		}

		$data = array(
			'hidden' => form_hidden('id', !empty($row->id) ? $row->id : ''),
			'hari' => form_dropdown('hari', $opt_hari, '', 'class="form-control chosen-select"'),
			'sesi' => form_dropdown('sesi', $opt_sesi, '', 'class="form-control chosen-select"'),
			'idRuang' => form_dropdown('idRuang', $opt_ruang, !empty($row->idRuang) ? $row->idRuang : '', 'class="form-control chosen-select"'),
			'idDosen' => form_input(array('name' => 'idDosen', 'id' => 'idDosen', 'class' => 'form-control', 'value' => !empty($row->idDosen) ? $row->idDosen : ''))
		);

		echo json_encode($data);
	}

	public function save()
	{
		$arr = $this->input->post('arr');

		$jadwal = $this->jadwal->as_object()->get_all();

		$code = 0;

		if(!empty($jadwal)) {
			$code = 1;
			$title = 'Warning!';
			$notifications = 'Data jadwal sudah ada. Jika ingin menambahkan jadwal baru. klik hapus data jadwal terlebih dahulu.';
		} else {

			$this->jadwal->insert($arr);

			$error =  $this->db->error();
			if ($error['code'] <> 0) {
				$code = 1;
				$title = 'Warning!';
				$notifications = $error['code'] . ' : ' . $error['message'];
			} else {
				$title = 'Insert!';
				$notifications = 'Berhasil menambahkan data Jadwal Terbaru';
			}
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


	public function index()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}
		
		$hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
		$jam = ['07.00', '07.50', '08.40', '09.30', '10.20', '11.10', '12.00', '13.00', '13.50', '14.40', '15.30', '16.20'];

		$ruang = $this->ruang->as_array()->get_all();
		$jadwal = $this->jadwal();
		$kromosom = $jadwal['jadwal'];

		for ($j = 0; $j < (count($jam) - 1); $j++) {
			$waktu[] = [$j, $j + 1];
		}

		set_table(true);

		$color = 'bg-primary';

		for ($i = 0; $i < count($hari); $i++) {
			$color = ($color == 'bg-primary') ? 'bg-success' : ($color == 'bg-success' ? 'bg-info' : ($color == 'bg-info' ? 'bg-warning' : 'bg-primary'));

			$in_hari_jam = [
				'data' => 'Hari / Jam <br> Ruang',
				'rowspan' => 2,
				'class' => 'font-weight-bold bg '.$color.'',
				'style' => 'vertical-align : middle; text-align : center; width: 130px'
			];
			
			$in_hari = [
				'data' => $hari[$i],
				'colspan' => 12,
				'class' => 'font-weight-bold bg '.$color.''
			];

			$in_back = [
				'data' => 'istirahat',
				'class' => 'bg bg-secondary'
			];

			$in_waktu = ['07.00 - 07.50', '07.50 - 08.40', '08.40 - 09.30', '09.30 - 10.20', '10.20 - 11.10', '11.10 - 12.00', '12.00 - 13.00', '13.00 - 13.50', '13.50 - 14.40', '14.40 - 15.30', '15.30 - 16.20'];

			for($x = 0; $x < count($waktu); $x++) {
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
						if($ruang_in[0][$l] == 'kosong') {
							$in_waktu_1[$l] = '';
						} else {
							$pengampu = $ruang_in[0][$l];
							$makul = $this->makul->where('id', $pengampu['idMakul'])->get();
							$kelas = $this->kelas->where('id', $pengampu['idKelas'])->get();

							if(is_array($pengampu['idKelas']) == 1) {
								$kelas1 = $this->kelas->where('id', $pengampu['idKelas'][0])->get();
								$kelas2 = $this->kelas->where('id', $pengampu['idKelas'][1])->get();

								if (!empty($ruang_in[0][$l]['Nilai'])) {
									$in_data = [
										'data' => $makul->namaMakul .
											'<br>' . $kelas1->namaKelas.', '.$kelas2->namaKelas.
											'<br>' . $ruang_in[0][$l]['Dosen'],
										'class' => 'bg bg-danger text-white',
																		
									];
									$in_waktu_1[$l] = $in_data;
								} else {

									$in_data = [
										'data' => $makul->namaMakul .
											'<br>' . $kelas1->namaKelas . ', ' . $kelas2->namaKelas .
											'<br>' . $ruang_in[0][$l]['Dosen'],
										// 'class' => 'bg ' . $color . '',
										
									];
									$in_waktu_1[$l] = $in_data;
								}
							} else {
								if (!empty($ruang_in[0][$l]['Nilai'])) {
									$in_data = [
										'data' => $makul->namaMakul .
											'<br>' . $kelas->namaKelas .
											'<br>' . $ruang_in[0][$l]['Dosen'],
										'class' => 'bg bg-danger text-white',
																				
									];
									$in_waktu_1[$l] = $in_data;
								} else {
									$in_data = [
										'data' => $makul->namaMakul .
											'<br>' . $kelas->namaKelas .
											'<br>' . $ruang_in[0][$l]['Dosen'],
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
								if($ruang_in[$k][$m] == 'kosong') {
									$in_waktu_2[$m] = '';
								} else {
									$pengampu = $ruang_in[$k][$m];
									$makul = $this->makul->where('id', $pengampu['idMakul'])->get();
									$kelas = $this->kelas->where('id', $pengampu['idKelas'])->get();

									if(is_array($pengampu['idKelas'])) {
										$kelas1 = $this->kelas->where('id', $pengampu['idKelas'][0])->get();
										$kelas2 = $this->kelas->where('id', $pengampu['idKelas'][1])->get();
										if (!empty($ruang_in[$k][$m]['Nilai'])) {
											$in_data = [
												'data' => $makul->namaMakul .
													'<br>' . $kelas1->namaKelas.', '.$kelas2->namaKelas.
													'<br>' . $ruang_in[$k][$m]['Dosen'],
												'class' => 'bg bg-danger text-white',
												
												
											];
											$in_waktu_2[$m] = $in_data;
										} else {
											$in_data = [
												'data' => $makul->namaMakul .
													'<br>' . $kelas1->namaKelas . ', ' . $kelas2->namaKelas .
													'<br>' . $ruang_in[$k][$m]['Dosen'],
												// 'class' => 'bg ' . $color . '',
												

											];
											$in_waktu_2[$m] = $in_data;
										}
									} else {
										if (!empty($ruang_in[$k][$m]['Nilai'])) {
											$in_data = [
												'data' => $makul->namaMakul .
													'<br>' . $kelas->namaKelas .
													'<br>' . $ruang_in[$k][$m]['Dosen'],
												'class' => 'bg bg-danger text-white',
												
												
											];
											$in_waktu_2[$m] = $in_data;
										} else {
											$in_data = [
												'data' => $makul->namaMakul .
													'<br>' . $kelas->namaKelas .
													'<br>' . $ruang_in[$k][$m]['Dosen'],
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

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Jadwal Hasil Generate';
		$data['page']         = '';
		$data['breadcrumb']         = 'Generate Jadwal';
		$data['hasil'] = $hasil;
		$data['kromosom'] = $jadwal['kromosom'];

		$this->template->theme('table_v', $data);
	}

	public function jadwal()
	{
		$jam = ['07.00', '07.50', '08.40', '09.30', '10.20', '11.10', '12.00', '13.00', '13.50', '14.40', '15.30', '16.20'];
		$in_waktu = ['07.00 - 07.50', '07.50 - 08.40', '08.40 - 09.30', '09.30 - 10.20', '10.20 - 11.10', '11.10 - 12.00', '12.00 - 13.00', '13.00 - 13.50', '13.50 - 14.40', '14.40 - 15.30', '15.30 - 16.20'];
		$hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
		$ruang = $this->ruang->as_array()->get_all();

		$hasil_mutasi = $this->fitnes_mutasi();

		$max_fitnes = max($hasil_mutasi['fitnes']);
		$search_index = array_search($max_fitnes, $hasil_mutasi['fitnes']);

		$kromosom = $hasil_mutasi['kromosom'][$search_index];

		$jan = [];
		for($s = 0; $s < count($kromosom); $s++) {
			if(!empty($kromosom[$s]['Nilai'])) {
				$jan[] = $s;
			}
		}

		for($j = 0; $j < (count($jam) - 1); $j++) {
			$waktu[] = [$j, $j + 1];
		}

		$save_kromosom = [];
		for($i = 0; $i < count($hari); $i++) {
			$hari_index = $hari[$i];
			$hari_cek = [];
			for($k = 0; $k < count($kromosom); $k++) {
				// if (empty($kromosom[$k]['Nilai'])) {
					if ($kromosom[$k]['Hari'] == $hari_index) {
						$hari_cek[] = $k;
					}
				// }
			}

			$ruang_cek = [];
			for($l = 0; $l < count($hari_cek); $l++) {
				$ruang_kromosom = $kromosom[$hari_cek[$l]]['Ruang'];
				for($m = 0; $m < count($ruang); $m++) {
					if($ruang[$m]['namaRuang'] == $ruang_kromosom) {
						$ruang_gen = $hari_cek[$l];
						$ruang_cek[$ruang[$m]['namaRuang']][] = $ruang_gen;
					}
				}
			}

			for($n = 0; $n < count($ruang); $n++) {
				$search_ruang = array_key_exists($ruang[$n]['namaRuang'], $ruang_cek);

				if($search_ruang == 1) {
					$ruang_waktu = $ruang_cek[$ruang[$n]['namaRuang']];

					$ruangan = [];
					for ($o = 0; $o < count($ruang_waktu); $o++) {
						$ruang_krom = $ruang_waktu[$o];
						// if(empty($kromosom[$ruang_krom]['Nilai'])) {
							$waktu_krom = $kromosom[$ruang_krom];

							$awal_index = array_search($waktu_krom['Mulai'], $jam);
							$akhir_index = array_search($waktu_krom['Selesai'], $jam);

							$waktu_range = range($awal_index, $akhir_index);

							$waktu_cek = [];
							for ($p = 0; $p < count($waktu); $p++) {
								$in_awal = in_array($waktu[$p][0], $waktu_range);
								$in_akhir = in_array($waktu[$p][1], $waktu_range);
								if ($in_awal == 1 && $in_akhir == 1) {
									$waktu_cek[$p] = $kromosom[$ruang_krom];

									if(is_array($kromosom[$ruang_krom]['idKelas'])) {
										$kelas_kro = $kromosom[$ruang_krom]['idKelas'];
										$kelas_arr = $kelas_kro[0].','.$kelas_kro[1];
									} else {
										$kelas_arr = $kromosom[$ruang_krom]['idKelas'];
									}

									if(!empty($kromosom[$ruang_krom]['Nilai'])) {
										$nilai = $kromosom[$ruang_krom]['Nilai'];
									} else {
										$nilai = 0;
									}

									$save_sem = [
										'idPengampu' => $kromosom[$ruang_krom]['id'],
										'idDosen' => $kromosom[$ruang_krom]['idDosen'],
										'idMakul' => $kromosom[$ruang_krom]['idMakul'],
										'idKelas' => $kelas_arr,
										'hari' => $kromosom[$ruang_krom]['Hari'],
										'ruang' => $kromosom[$ruang_krom]['Ruang'],
										'waktu' => $in_waktu[$p],
										'nilai' => $nilai,
									];
									$save_kromosom[] = $save_sem;

								} else {
									$waktu_cek[$p] = 'kosong';
								}
							}

							$ruangan[$o] = $waktu_cek;
						// }
						
					}

					$hasil_ruang_waktu[$ruang[$n]['namaRuang']] = $ruangan;
					// $hasil_ruang_waktu[$ruang[$n]['namaRuang']] = 'ada';
				} else {
					$hasil_ruang_waktu[$ruang[$n]['namaRuang']] = 'kosong';

				}

			}



			$hari_array[$hari_index] = $hasil_ruang_waktu;
			// break;
			// $hari_1[$hari_index] = $ruang_cek;
			// $hari_2[$hari_index] = $hari_cek;
		}


		
		

		// echo "<pre>";
		// print_r($jan);
		// echo "<pre>";
		// print_r($hasil_mutasi['fitnes']);
		// echo "<pre>";
		// print_r($max_fitnes);
		// echo "<pre>";
		// print_r($search_index);
		// echo "<pre>";
		// print_r($kromosom);
		// echo "<pre>";
		// print_r($hari_array);
		// echo "<pre>";
		// print_r($save_kromosom);
		// echo "<pre>";
		// print_r($hari_2);

		$data = [
			'jadwal' => $hari_array,
			'kromosom' => $save_kromosom
		];

		return $data;
	}

	public function fitnes_mutasi()
	{
		$jam = ['07.00', '07.50', '08.40', '09.30', '10.20', '11.10', '12.00', '13.00', '13.50', '14.40', '15.30', '16.20'];

		$mutasi = $this->mutasi();
		
		$kromosom = [];
		$fitnes = [];
		for($i = 0; $i < $this->totalpengampu(); $i++) {

			$jum = 0;
			$gen = [];
			for($j = 0; $j < $this->totalpengampu(); $j++) {
				$gen_mutasi = $mutasi[$i][$j];
				// $gen_pengampu = $this->pengampu->where('id', $gen_mutasi['idPengampu'])->get();

				if(!empty($gen)) {
					for($k = 0; $k < count($gen); $k++) {
						$hari_data = $gen[$k]['Hari'];
						$ruang_data = $gen[$k]['Ruang'];
						$awal_index = array_search($gen[$k]['Mulai'], $jam);
						$akhir_index = array_search($gen[$k]['Selesai'], $jam);
						$mulai_index = array_search($gen_mutasi['Mulai'], $jam);
						$selesai_index = array_search($gen_mutasi['Selesai'], $jam);

						$waktu_range = range($awal_index, $akhir_index);
						$count_waktu = count($waktu_range) - 1;
						unset($waktu_range[$count_waktu]);

						$rand_waktu_range = range($mulai_index, $selesai_index);
						$count_rand = count($rand_waktu_range) - 1;
						unset($rand_waktu_range[$count_rand]);

						for ($m = 0; $m < count($rand_waktu_range); $m++) {
							$index = $rand_waktu_range[$m];
							if (in_array($index, $waktu_range)) {
								$status = 'Ada';
								break;
							} else {
								$status = 'Tidak Ada';
							}
						}

						$cek_kelas = $this->cekkelas($gen[$k]['idKelas'], $gen_mutasi['idKelas']);

						// $pengampu = $this->pengampu->where('id', $gen[$k]['idPengampu'])->get();

						if ($gen[$k]['idDosen'] == $gen_mutasi['idDosen'] && $ruang_data == $gen_mutasi['Ruang'] && $hari_data == $gen_mutasi['Hari'] && $status == 'Ada' || $cek_kelas == 'Ada' && $hari_data == $gen_mutasi['Hari'] && $status == 'Ada' || $gen[$k]['idDosen'] == $gen_mutasi['idDosen'] && $hari_data == $gen_mutasi['Hari'] && $status == 'Ada' || $hari_data == $gen_mutasi['Hari'] && $status == 'Ada' && $ruang_data == $gen_mutasi['Ruang']) {
							$gen_mutasi['Nilai'] = 10;
							$jum = $jum + 10;
						break;
						}
					}
				}

				$gen[$j] = $gen_mutasi;

			}

			$fitnes[$i] = 1 / (1 + $jum);
			$kromosom[$i] = $gen;

		}

		// echo "<pre>";
		// print_r($mutasi[0][1]);
		// echo "<pre>";
		// print_r($fitnes);
		// echo "<pre>";
		// print_r($kromosom);

		$data = [
			'fitnes' => $fitnes,
			'kromosom' => $kromosom
		];

		return $data;
	}


	public function mutasi()
	{
		$jam = ['07.00', '07.50', '08.40', '09.30', '10.20', '11.10', '12.00', '13.00', '13.50', '14.40', '15.30', '16.20'];
		$hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

		$pm = 0.1;
		$total_gen = $this->totalpengampu() * $this->totalpengampu();
		$jumlah_mutasi = $pm * $total_gen;

		$crossover = $this->crossover();

		for($i = 0; $i < $jumlah_mutasi; $i++) {
			$random[] = rand(1, $total_gen);
		}

		$posisi = [];
		for($j = 0; $j < $this->totalpengampu(); $j++) {
			$angka = $random[$j];
			if ($angka >= $this->totalpengampu()) {
				$gen = ($angka % $this->totalpengampu()) == 0 ? 0 : (($angka % $this->totalpengampu()) - 1);
				$kromosom = floor($angka / $this->totalpengampu());
			} else {
				$gen = $angka - 1;
				$kromosom = 0;
			}

			$hasil_acak = [
				'gen' => $gen,
				'kromosom' => $kromosom
			];

			$posisi[] = $hasil_acak;
		}

		for($k = 0; $k < $this->totalpengampu(); $k++) {
			$gen_posisi = $posisi[$k]['gen'];
			$kromosom = $posisi[$k]['kromosom'];

			// $id = $crossover[$kromosom][$gen_posisi];
			// $pengampu = $this->pengampu->where('id', $id)->get();
			$pengampu = $crossover[$kromosom][$gen_posisi];

			$makul = $this->makul->where('id', $pengampu['idMakul'])->get();
			$dosen = $this->dosen->where('id', $pengampu['idDosen'])->get();
			$gen = $this->ruang->gen($makul->jenis);

			$ran_hari = array_rand($hari);

			$random_waktu = array_rand($jam);
			$slot = $jam[$random_waktu];
			$awal = array_search($slot, $jam);

			if ($makul->jumlahSks > 4) {
				$jumlahsks = 3;
			} else {
				$jumlahsks = $makul->jumlahSks;
			}

			$akhir = $awal + $jumlahsks;

			$cekwaktu = $this->cekwaktu($awal, $akhir);

			if($ran_hari == 4 && $cekwaktu['Akhir'] == 6) {
				$cekwaktu['Akhir'] = $cekwaktu['Akhir'] - 1;
				$cekwaktu['Awal'] = $cekwaktu['Awal'] - 1;
			}

			$populasi_acak['id'] = $pengampu['id'];
			$populasi_acak['idDosen'] = $pengampu['idDosen'];
			$populasi_acak['idMakul'] = $pengampu['idMakul'];
			$populasi_acak['idKelas'] = $pengampu['idKelas'];
			$populasi_acak['Dosen'] = $dosen->namaDosen;
			$populasi_acak['Hari'] = $hari[$ran_hari];
			$populasi_acak['Mulai'] = $jam[$cekwaktu['Awal']];
			$populasi_acak['Selesai'] = $jam[$cekwaktu['Akhir']];
			$populasi_acak['Ruang'] = $gen[0]['namaRuang'];

			$slot_acak[$kromosom][$gen_posisi] = $populasi_acak;
		}

		
		$mutasi = [];
		for($l = 0; $l < $this->totalpengampu(); $l++) {
			$search_kromosom = array_key_exists($l, $slot_acak);
			if(!empty($search_kromosom)) {
				for($m = 0; $m < $this->totalpengampu(); $m++) {
					$search_gen = array_key_exists($m, $slot_acak[$l]);
					if(!empty($search_gen)) {
						$mutasi_gen[$m] = $slot_acak[$l][$m];
					} else {
						$mutasi_gen[$m] = $crossover[$l][$m];
					}
				}

				$mutasi[$l] = $mutasi_gen;
			} else {
				$mutasi[$l] = $crossover[$l];
			}
		}


		// echo "<pre>";
		// print_r($random);
		// echo "<pre>";
		// print_r($posisi);
		// echo "<pre>";
		// print_r($slot_acak);
		// echo "<pre>";
		// print_r($mutasi);
		// echo "<pre>";
		// print_r($crossover[0]);

		return $mutasi;
	}


	public function crossover()
	{
		$pc = 0.25;
		for($m = 0; $m < $this->totalpengampu(); $m++) {
			$random = [];
			for ($i = 0; $i < $this->totalpengampu(); $i++) {
				$random[] = mt_rand(0.00, mt_getrandmax() - 1) / mt_getrandmax();
			}

			$kromosom_induk = [];
			for ($j = 0; $j < $this->totalpengampu(); $j++) {
				if ($random[$j] < $pc) {
					$kromosom_induk[] = $j;
				}
			}

			$count_induk = count($kromosom_induk);
			if($count_induk > 1) {
			break;
			}
		}
			
		$parent = [];
		$gen = [];
		for($k = 0; $k < $count_induk; $k = $k +2) {
			$x = $k + 1;
			if($count_induk % 2 == 0) {
				// $parent[] = $k . '-' . $x;
				$gen[0] = $kromosom_induk[$k];
				$gen[1] = $kromosom_induk[$x];
			} else {
				if($k == $count_induk - 1) {
					$last_count = $count_induk - 2;
					// $parent[] = $last_count . '-' . $k;
					$gen[0] = $kromosom_induk[$last_count];
					$gen[1] = $kromosom_induk[$k];
				} else {
					// $parent[] = $k . '-' . $x;
					$gen[0] = $kromosom_induk[$k];
					$gen[1] = $kromosom_induk[$x];
				}
			}

			$parent[] = $gen;
		}

		$count_parent = count($parent);
		$kromosom = [];
		$populasi = $this->wheel()['populasi'];
		for($l = 0; $l < $count_parent; $l++) {
			$acak = rand(0, 57);
			$index_induk1 = $parent[$l][0];
			$index_induk2 = $parent[$l][1];

			if ($count_induk % 2 == 0) {
				for ($n = 0; $n < $this->totalpengampu(); $n++) {
					if ($n > $acak) {
						$parent_pertama[$n] = $populasi[$index_induk2][$n];
					} else {
						$parent_pertama[$n] = $populasi[$index_induk1][$n];
					}
				}

				for ($n = 0; $n < $this->totalpengampu(); $n++) {
					if ($n > $acak) {
						$parent_kedua[$n] = $populasi[$index_induk1][$n];
					} else {
						$parent_kedua[$n] = $populasi[$index_induk2][$n];
					}
				}

				$kromosom[$index_induk1] = $parent_pertama;
				$kromosom[$index_induk2] = $parent_kedua;
			} else {
				
				if(($count_parent - 1) == $l) {
					for ($n = 0; $n < $this->totalpengampu(); $n++) {
						if ($n > $acak) {
							$parent_akhir[] = $populasi[$index_induk1][$n];
						} else {
							$parent_akhir[] = $populasi[$index_induk2][$n];
						}
					}

					$kromosom[$index_induk2] = $parent_akhir;			
				}
				else {
					for ($n = 0; $n < $this->totalpengampu(); $n++) {
						if ($n > $acak) {
							$parent_pertama[$n] = $populasi[$index_induk2][$n];
						} else {
							$parent_pertama[$n] = $populasi[$index_induk1][$n];
						}
					}

					for ($n = 0; $n < $this->totalpengampu(); $n++) {
						if ($n > $acak) {
							$parent_kedua[$n] = $populasi[$index_induk1][$n];
						} else {
							$parent_kedua[$n] = $populasi[$index_induk2][$n];
						}
					}

					$kromosom[$index_induk1] = $parent_pertama;
					$kromosom[$index_induk2] = $parent_kedua;
				}
			}
			
		}

		$crossover = [];
		for ($i = 0; $i < $this->totalpengampu(); $i++) {
			// $data = $x[$i];
			$data = array_key_exists($i, $kromosom);
			if (!empty($data)) {
				$crossover[] = $kromosom[$i];
			} else {
				$crossover[] = $populasi[$i];
			}
		}

		// echo "<pre>";
		// print_r($crossover);

		return $crossover;
	}

	public function wheel()
	{
		$prob = [];
		$kromosom = $this->inisialisasi();
		for($i = 0; $i < $this->totalpengampu(); $i++) {
			$prob[] = $kromosom['fitnes'][$i] / $kromosom['tot_fitnes'];
		}

		$nilai_kumulatif = 0;
		$hasil_kumulatif = [];
		for ($j = 0; $j < $this->totalpengampu(); $j++) {
			$nilai_kumulatif = $nilai_kumulatif + $prob[$j];
			$hasil_kumulatif[] = $nilai_kumulatif;
		}

		$rand = [];
		$seleksi = [];
		$fitnes_seleksi = [];
		$tot_fitnes_seleksi = 0;
		for($k = 0; $k < $this->totalpengampu(); $k++) {
			$random = mt_rand(0.00, mt_getrandmax() - 1) / mt_getrandmax();
			$rand[] = $random;
			
			for($m = 0; $m < $this->totalpengampu(); $m++) {
				if($random <= $hasil_kumulatif[$m]) {
					$seleksi[] = $kromosom['populasi'][$m];
					$fitnes_seleksi[] = $kromosom['fitnes'][$m];
					$tot_fitnes_seleksi = $tot_fitnes_seleksi + $kromosom['fitnes'][$m];
				break;
				}
			}
		}

		$data = [
			'populasi' => $seleksi,
			'fitnes' => $fitnes_seleksi,
			'tot_fitnes' => $tot_fitnes_seleksi
		];

		// echo "<pre>";
		// print_r($data);		

		return $data;
	}

	public function inisialisasi()
	{
		$populasi = [];
		$fitnes = [];
		$jum = 0;
		for ($k = 0; $k < $this->totalpengampu(); $k++) {
			$kromosom = $this->kromosom();
			$populasi[] = $kromosom[0];
			$fitnes[] = $kromosom[1];
			$jum = $jum + $kromosom[1];
		}

		$data = [
			'populasi' => $populasi,
			'fitnes' => $fitnes,
			'tot_fitnes' => $jum
		];

		// echo "<pre>";
		// print_r($data);

		return $data;
	}

	public function kromosom()
	{
		$jam = ['07.00', '07.50', '08.40', '09.30', '10.20', '11.10', '12.00', '13.00', '13.50', '14.40', '15.30', '16.20'];
		$hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

		$gen_pengampu = $this->gabung();

		$data = [];
		$jum = 0;
		for ($i = 0; $i < $this->totalpengampu(); $i++) {
			$makul = $this->makul->where('id', $gen_pengampu[$i]['idMakul'])->get();
			$dosen = $this->dosen->where('id', $gen_pengampu[$i]['idDosen'])->get();
			$gen = $this->ruang->gen($makul->jenis);

			$ran_hari = array_rand($hari);

			$random = array_rand($jam);
			$slot = $jam[$random];
			$awal = array_search($slot, $jam);

			if($makul->jumlahSks > 4) {
				$jumlahsks = 3;
			} else {
				$jumlahsks = $makul->jumlahSks;
			}
			
			$akhir = $awal + $jumlahsks;

			$cekwaktu = $this->cekwaktu($awal, $akhir);

			if($hari[$ran_hari] == 'Jumat' && $cekwaktu['Akhir'] == 6) {
				$cekwaktu['Akhir'] = $cekwaktu['Akhir'] - 1;
				$cekwaktu['Awal'] = $cekwaktu['Awal'] - 1;
			}

			$data1 = [
				'id' => $gen_pengampu[$i]['id'],
				'idDosen' => $gen_pengampu[$i]['idDosen'],
				'idMakul' => $gen_pengampu[$i]['idMakul'],
				'idKelas' => $gen_pengampu[$i]['idKelas'],
				'Dosen' => $dosen->namaDosen,
				'Hari' => $hari[$ran_hari],
				'Mulai' => $jam[$cekwaktu['Awal']],
				'Selesai' => $jam[$cekwaktu['Akhir']],
				'Ruang' => $gen[0]['namaRuang'],
			];

			if(!empty($data)) {
				for ($j = 0; $j < count($data); $j++) {
					$hari_data = $data[$j]['Hari'];
					$ruang_data = $data[$j]['Ruang'];
					$awal_index = array_search($data[$j]['Mulai'], $jam);
					$akhir_index = array_search($data[$j]['Selesai'], $jam);

					$waktu_range = range($awal_index, $akhir_index);
					$count_waktu = count($waktu_range) - 1;
					unset($waktu_range[$count_waktu]);

					$rand_waktu_range = range($cekwaktu['Awal'], $cekwaktu['Akhir']);
					$count_rand = count($rand_waktu_range) - 1;
					unset($rand_waktu_range[$count_rand]);

					for ($k = 0; $k < count($rand_waktu_range); $k++) {
						$index = $rand_waktu_range[$k];
						if (in_array($index, $waktu_range)) {
							$status_waktu = 'Ada';
							break;
						} else {
							$status_waktu = 'Tidak Ada';
						}
					}

					$cek_kelas = $this->cekkelas($data[$j]['idKelas'], $gen_pengampu[$i]['idKelas']);

					// $pengampu = $this->pengampu->where('id', $data[$j]['id'])->get();

					if($data[$j]['idDosen'] == $gen_pengampu[$i]['idDosen'] && $ruang_data == $gen[0]['namaRuang'] && $hari_data == $hari[$ran_hari] && $status_waktu == 'Ada' || $cek_kelas == 'Ada' && $hari_data == $hari[$ran_hari] && $status_waktu == 'Ada' || $data[$j]['idDosen'] == $gen_pengampu[$i]['idDosen'] && $hari_data == $hari[$ran_hari] && $status_waktu == 'Ada' || $hari_data == $hari[$ran_hari] && $status_waktu == 'Ada' && $ruang_data == $gen[0]['namaRuang']) {
						// $data1['Nilai'] = 10;
						$jum = $jum + 10;
					}
				}
			}

			$data[] = $data1;
		}

		$fitnes = 1 / (1 + $jum);

		$kromosom = [
			$data, $fitnes
		];

		// echo "<pre>";
		// print_r($jum);
		// echo "<pre>";
		// print_r($data);

		return $kromosom;
	}

	public function cekwaktu($awal, $akhir)
	{
		if ($awal <= 6) {
			if ($awal == 1 || $awal == 8) {
				$awal = $awal + 1;
				$akhir = $akhir + 1;
			}
			if ($akhir > 6) {
				$kurang = $akhir - 6;
				$awal = $awal - $kurang;
				$akhir = 6;
			} elseif ($akhir == 5) {
				$kurang = $akhir - 1;
				$awal = $awal - 1;
				$akhir = $kurang;
			}
		} else {
			if ($akhir > 11) {
				$kurang = $akhir - 11;
				$awal = $awal - $kurang;
				$akhir = 11;
			} else {
				$awal;
				$akhir;
			}
		}

		$data = ['Awal' => $awal, 'Akhir' => $akhir];

		return $data;
	}

	public function totalpengampu()
	{
		$gen_pengampu = $this->gabung();

		$count = count($gen_pengampu);

		return $count;
	}

	public function cekkelas($data, $gen_pengampu)
	{
		if (is_array($data) == 1 && is_array($gen_pengampu) == 1) {
			$in_kelas_data = $data;
			$in_kelas = $gen_pengampu;
			if ($in_kelas_data[0] == $in_kelas[0] || $in_kelas_data[1] == $in_kelas[1] || $in_kelas_data[0] == $in_kelas[1] || $in_kelas_data[1] == $in_kelas[0]) {
				$status_kelas = 'Ada';
			} else {
				$status_kelas = 'Tidak Ada';
			}
		} elseif (is_array($data) == 1 && is_array($gen_pengampu) == 0) {
			$in_kelas_data = $data;
			$in_kelas = $gen_pengampu;
			if ($in_kelas_data[0] == $in_kelas || $in_kelas_data[1] == $in_kelas) {
				$status_kelas = 'Ada';
			} else {
				$status_kelas = 'Tidak Ada';
			}
		} elseif (is_array($data) == 0 && is_array($gen_pengampu) == 1) {
			$in_kelas_data = $data;
			$in_kelas = $gen_pengampu;
			if ($in_kelas_data == $in_kelas[0] || $in_kelas_data == $in_kelas[1]) {
				$status_kelas = 'Ada';
			} else {
				$status_kelas = 'Tidak Ada';
			}
		} else {
			$in_kelas_data = $data;
			$in_kelas = $gen_pengampu;
			if ($in_kelas_data == $in_kelas) {
				$status_kelas = 'Ada';
			} else {
				$status_kelas = 'Tidak Ada';
			}
		}

		return $status_kelas;
	}

	public function gabung()
	{
		$pengampu = $this->pengampu->as_array()->get_all();

		$pengampu_data = [];
		for ($i = 0; $i < count($pengampu); $i++) {
			$pengampu_in = [
				'id' => $pengampu[$i]['id'],
				'idDosen' => $pengampu[$i]['idDosen'],
				'idMakul' => $pengampu[$i]['idMakul'],
				'idKelas' => $pengampu[$i]['idKelas']
			];

			if (!empty($pengampu_data)) {
				$cek_data = [];
				for ($k = 0; $k < count($pengampu_data); $k++) {
					$cek_dosen = $pengampu_data[$k]['idDosen'];
					$cek_makul = $pengampu_data[$k]['idMakul'];
					if ($pengampu[$i]['idDosen'] == $cek_dosen && $pengampu[$i]['idMakul'] == $cek_makul) {
						$sub_cek_data[0] = $k;
						$sub_cek_data[1] = $i;
						$cek_data[] = $sub_cek_data;
					}
				}
				$cek_hasil[$i] = $cek_data;
			}

			$pengampu_data[$i] = $pengampu_in;
		}

		$gabung_kelas = [];
		for ($j = 0; $j < count($cek_hasil); $j++) {
			if (!empty($cek_hasil[$j])) {
				$cek_jum = count($cek_hasil[$j]);
				if ($cek_jum == 1) {
					$grub[0] =  $cek_hasil[$j][0][0];
					$grub[1] =  $cek_hasil[$j][0][1];
					$gabung_kelas[] = $grub;
				}
			}
		}

		$pengampu_hasil = [];
		for ($l = 0; $l < count($pengampu_data); $l++) {

			for ($m = 0; $m < count($gabung_kelas); $m++) {
				if ($l == $gabung_kelas[$m][0] || $l == $gabung_kelas[$m][1]) {
					if ($l == $gabung_kelas[$m][0]) {
						$in_kelas = $gabung_kelas[$m][1];
						$in_pengampu = [
							'id' => $pengampu_data[$l]['id'],
							'idDosen' => $pengampu_data[$l]['idDosen'],
							'idMakul' => $pengampu_data[$l]['idMakul'],
							'idKelas' => [
								$pengampu_data[$l]['idKelas'],
								$pengampu_data[$in_kelas]['idKelas']
							]
						];
						$pengampu_hasil[] = $in_pengampu;
						break;
					} else {
						break;
					}
				} else {
					if ($m == count($gabung_kelas) - 1) {
						$pengampu_hasil[] = $pengampu_data[$l];
					}
				}
			}
		}



		// echo "<pre>";
		// print_r($pengampu_data);
		// echo "<pre>";
		// print_r($pengampu_hasil);
		// echo "<pre>";
		// print_r($gabung_kelas);

		return $pengampu_hasil;
	}
	
}

<style type="text/css">
    @media print {

        #printPageButton,
        #back,
        #isi,
        .modal-header {
            display: none;
        }

        .content {
            height: 650px;
        }
    }

    @page {
        size: landscape;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?php echo isset($page_header) ? $page_header : ''; ?></h1>
            </div>
        </div>
    </div><!-- /.container -->
</section>

<section class="content">
    <div class="container-fluid">
        <div id="notifications"></div>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo isset($panel_heading) ? $panel_heading : ''; ?></h3>
                    </div><!-- /.card-header -->

                    <div id="table-data">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <a href="<?php echo site_url('jadwalumum/cetak'); ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-print"></i> Cetak Jadwal</a>
                                    </div>
                                    <?php

                                    $opt_dosen = $this->dosen->as_dropdown('namaDosen')->get_all();
                                    $opt_kelas = $this->kelas->as_dropdown('namaKelas')->get_all();

                                    $semuaDosen = [0 => 'Semua Dosen'];
                                    $semuaKelas = [0 => 'Semua Kelas'];

                                    $opt_dosen = $semuaDosen + $opt_dosen;
                                    $opt_kelas = $semuaKelas + $opt_kelas;

                                    $dosen = $this->dosen->as_object()->get_all();
                                    $kelas = $this->kelas->as_object()->get_all();

                                    ?>
                                    <div class="col-4">
                                        <?php echo form_dropdown('dosen', $opt_dosen, !empty($dosen->id) ? $dosen->id : '', 'class="form-control" id="filterdosen"'); ?>
                                    </div>
                                    <div class="col-4">
                                        <?php echo form_dropdown('kelas', $opt_kelas, !empty($kelas->id) ? $kelas->id : '', 'class="form-control" id="filterkelas"'); ?>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="table-responsive">
                                <div id="hasil"></div>
                            </div>
                            <div align="center">
                                <div id='ajax-wait'>
                                    <img alt='loading...' src='<?php echo base_url()?>/assets/animasi/Rolling-1s-84px.png' />
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>


<script type="text/javascript">
    var site_url = site_url() + 'jadwalumum/';

    $(document).ajaxStart(function() {
        $("#ajax-wait").css({
            left: ($(window).width() - 32) / 2 + "px", // 32 = lebar gambar
            top: ($(window).height() - 32) / 2 + "px", // 32 = tinggi gambar
            display: "block"
        })
    }).ajaxComplete(function() {
        $("#ajax-wait").fadeOut();
    });

    $(document).ready(function() {
        $.ajax({
            url: site_url + 'jadwal/',
            cache: false,
            type: "POST",
            dataType: "json",
            success: function(data) {

                $('#hasil').html(data.hasil);
            }
        });

        $.ajax({
            url: site_url + 'form_data/',
            cache: false,
            type: "POST",
            dataType: "json",
            success: function(data) {
                $('#dosen').html(data.dosen);
                $('#kelas').html(data.kelas);
            }
        });

        $('#filterdosen').change(function(){
            var idDosen = document.getElementById("filterdosen").value;
            var idKelas = document.getElementById("filterkelas").value;
            $.ajax({
                url: site_url + 'jadwal/',
                data: {
                    'dosen': idDosen,
                    'kelas': idKelas
                },
                cache: false,
                type: "POST",
                success: function(data) {
                    data = JSON.parse(data);
                    $('#hasil').html(data.hasil);
                    // $('#hasil').ajax.reload(); 
                }
            });
        });

        $('#filterkelas').change(function(){
            var idDosen = document.getElementById("filterdosen").value;
            var idKelas = document.getElementById("filterkelas").value;
            $.ajax({
                url: site_url + 'jadwal/',
                data: {
                    'dosen': idDosen,
                    'kelas': idKelas
                },
                cache: false,
                type: "POST",
                success: function(data) {
                    data = JSON.parse(data);
                    $('#hasil').html(data.hasil);
                    // $('#hasil').ajax.reload(); 
                }
            });
        });  
    })
</script>
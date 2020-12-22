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
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?php echo isset($page_header) ? $page_header : ''; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active"> <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container -->
</section>

<section class="content">
    <div class="container">
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
                                    <div class="col-lg-6">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="delete_data()"><i class="fas fa-trash-alt ml-2"></i> Hapus Jadwal</button>
                                        <a href="<?php echo site_url('jadwal/cetak'); ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-print"></i> Cetak Jadwal</a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div id="hasil"></div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->

<!-- /.content -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form role="form" method="POST" action="" id="form-data" enctype="multipart/form-data">
                    <div class="card-body">
                        <div id="hidden"></div>
                        <div class="form-group">
                            <label>Dosen</label>
                            <div id="idDosen"></div>
                        </div>
                        <div class="form-group">
                            <label>Makul</label>
                            <div id="idMakul"></div>
                        </div>
                        <div class="form-group">
                            <label>Hari</label>
                            <div id="hari"></div>
                        </div>
                        <div class="form-group">
                            <label>Waktu Awal</label>
                            <div id="sesi"></div>
                        </div>
                        <div class="form-group">
                            <label>Waktu Akhir</label>
                            <div id="waktu"></div>
                        </div>
                        <div class="form-group">
                            <label>Ruang</label>
                            <div id="idRuang"></div>
                        </div>

                        <button type="button" name="submit" id="submit" class="btn btn-primary">Update Data</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var site_url = site_url() + 'jadwal/';

    $(document).ready(function() {
        $.ajax({
            url: site_url + 'table/',
            cache: false,
            type: "POST",
            dataType: "json",
            success: function(data) {

                $('#hasil').html(data.hasil);
            }
        });

        $('#submit').click(function() {
            $.ajax({
                url: site_url + 'save_update/',
                type: "POST",
                data: new FormData($('#form-data')[0]),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#myModal').modal('hide');

                    if (data.code == 1) {
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            showCloseButton: true
                            // timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        load();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Warning!', 'Error adding / update data', 'error');
                }
            });

        });
    })

    function delete_data() {
        Swal.fire({
            title: 'Apa anda yakin ?',
            text: "Data akan dihapus dari database !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve) {
                    $.ajax({
                            url: site_url + 'hapus/',
                            cache: false,
                            type: "POST",
                            dataType: 'json'
                        })
                        .done(function(data) {
                            Swal.fire({
                                icon: data.icon,
                                title: 'Deleted!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            if (data.code == 0) table.draw(false);
                            table_data();
                        })
                        .fail(function() {
                            Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                        });
                });
            },
            allowOutsideClick: false
        });
    }

    function cek(id, id1) {
        $.ajax({
            url: site_url + 'cek/',
            data: {
                'akhir': id,
                'id': id1
            },
            cache: false,
            type: "POST",
            dataType: "json",
            success: function(data) {
                $('#waktu').html(data.waktu);
                $('input[name=waktu]').prop('readonly', true);
            }
        });
    }

    function load() {
        $.ajax({
            url: site_url + 'table/',
            cache: false,
            type: "POST",
            dataType: "json",
            success: function(data) {
                $('#hasil').html(data.hasil);
            }
        });
    }


    function form_data() {
        $('#myModal').modal('show');
    }

    function update_data(id) {
        $.ajax({
            url: site_url + 'form_data/',
            data: {
                'id': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                $(".chosen-select").chosen("destroy");
                form_data();
                $('.modal-title').text('Update Jadwal');

                data = JSON.parse(data);
                $('#hidden').html(data.hidden);
                $('#hari').html(data.hari);
                $('#sesi').html(data.sesi);
                $('#waktu').html(data.waktu);
                $('input[name=waktu]').prop('readonly', true);
                $('#idRuang').html(data.idRuang);
                $('#idDosen').html(data.idDosen);
                $('input[name=idDosen]').prop('readonly', true);
                $('#idMakul').html(data.idMakul);
                $('input[name=idMakul]').prop('readonly', true);

                $(".chosen-select").chosen();
            }
        });
    }
</script>
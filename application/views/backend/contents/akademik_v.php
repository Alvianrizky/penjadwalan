<!-- Content Header (Page header) -->
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

<!-- Main content -->
<section class="content">
    <div class="container">
        <div id="notifications"></div>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"> </h3>
                    </div><!-- /.card-header -->

                    <div id="table-data">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button id="create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Tahun Akademik</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 110px!important;">Action</th>
                                            <th>No</th>
                                            <th>Nama Tahun Akademik</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th>Action</th>
                                            <th>No</th>
                                            <th>Nama Tahun Akademik</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

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
                        <div id="js-config"></div>
                        <div class="form-group">
                            <label>Nama Tahun Akademik</label>
                            <div id="namaTahun"></div>
                        </div>
                        <button type="button" name="submit" id="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>

                <form role="form" method="POST" action="" id="form-view">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Nama Tahun Akademik</label>
                                    <p id="namaTahun"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- /.content -->
<script type="text/javascript">
    var site_url = site_url() + 'akademik/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": site_url + 'get_akademik',
                "type": "POST"
            },

            "columnDefs": [{
                "targets": [0],
                "orderable": false,
            }, ],
        });

        $('#create').click(function() {
            $.ajax({
                url: site_url + 'form_data/',
                cache: false,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $(".chosen-select").chosen("destroy");
                    form_data();
                    $('.modal-title').text('Tambah Tahun Akademik');

                    $('#hidden').html(data.hidden);
                    $('#js-config').html(data.jsConfig);
                    $('#namaTahun').html(data.namaTahun);

                    $(".chosen-select").chosen();
                }
            });
        });

        $('#submit').click(function() {
            $.ajax({
                url: site_url + 'save_akademik/',
                type: "POST",
                data: new FormData($('#form-data')[0]),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.code == 1) {
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        table_data();
                        table.draw(false);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Warning!', 'Error adding / update data', 'error');
                }
            });

        });

        $('#myModal').on('shown.bs.modal', function() {
            $('.chosen-select', this).chosen('destroy').chosen();
        });

    });

    function table_data() {
        $('#table-data').show();
        $('#form-data').hide();
        $('#form-view').hide();

        $('#myModal').modal('hide');
        $('.card-title').text('Tahun Akademik List');
    }

    function form_data() {
        $('#myModal').modal('show');
        $('#form-data').show();
        $('#form-view').hide();
    }

    function form_view() {
        $('#myModal').modal('show');
        $('#form-data').hide();
        $('#form-view').show();

        $('.modal-title').text('View Tahun Akademik');
    }

    function view_data(id) {
        $.ajax({
            url: site_url + 'view/',
            data: {
                'idTahun': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                form_view();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('p#namaTahun').html(data.namaTahun);
            }
        });
    }

    function update_data(id) {
        $.ajax({
            url: site_url + 'form_data/',
            data: {
                'idTahun': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                $(".chosen-select").chosen("destroy");
                form_data();
                $('.modal-title').text('Update Tahun Akademik');

                data = JSON.parse(data);
                $('#hidden').html(data.hidden);
                $('#namaTahun').html(data.namaTahun);

                $(".chosen-select").chosen();
            }
        });
    }

    function delete_data(id) {
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
                            url: site_url + 'delete/',
                            data: {
                                'idTahun': id
                            },
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
</script>
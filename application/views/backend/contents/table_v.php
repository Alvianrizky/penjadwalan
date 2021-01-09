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
                                        <button type="button" class="btn btn-primary btn-sm" id="save"><i class="fas fa-save ml-2"></i> Save Jadwal</button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="delete_data()"><i class="fas fa-trash-alt ml-2"></i> Hapus Jadwal</button>
                                        <button type="button" class="btn btn-info btn-sm" onclick="refresh_data()"><i class="fas fa-sync"></i> Refresh Jadwal</button>
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
<!-- Main content -->

<!-- /.content -->

<script type="text/javascript">
    var site_url = site_url() + 'generate/';

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
            url: site_url + 'table/',
            cache: false,
            type: "POST",
            dataType: "json",
            success: function(data) {

                $('#hasil').html(data.hasil);
            }
        });
    })

    $('#save').click(function() {
        $.ajax({
            url: site_url + 'save/',
            data: {
                'arr': kromosom
            },
            type: "POST",
            dataType: "JSON",
            // contentType: false,
            cache: false,
            // processData: false,
            success: function(data) {
                if (data.code == 1) {
                    Swal.fire({
                        icon: data.icon,
                        title: data.title,
                        text: data.message,
                        showConfirmButton: false,
                        showCloseButton: true
                    });
                } else {
                    Swal.fire({
                        icon: data.icon,
                        title: data.title,
                        text: data.message,
                        showConfirmButton: true,
                        // showCloseButton: true
                        // timer: 1500
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire('Warning!', 'Error adding / update data', 'error');
            }
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

    function refresh_data() {
        location.reload(true);
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
</script>
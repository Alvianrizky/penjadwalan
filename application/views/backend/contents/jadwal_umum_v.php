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
                                    <div class="col-lg-6">
                                        <a href="<?php echo site_url('jadwalumum/cetak'); ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-print"></i> Cetak Jadwal</a>
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


<script type="text/javascript">
    var site_url = site_url() + 'jadwalumum/';

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
</script>
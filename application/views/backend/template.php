<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 3 | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/summernote/summernote-bs4.css">
    <!-- datetimepicker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/datetimepicker/css/bootstrap-datetimepicker.css">
    </link>
    <!-- chosen CSS -->
    <link href="<?php echo base_url() . 'assets/'; ?>plugins/chosen/chosen.min.css" rel="stylesheet">

    <link href="<?php echo base_url() . 'assets/'; ?>plugins/select2/css/select2.min.css" rel="stylesheet">
    <link href="<?php echo base_url() . 'assets/'; ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet">
    <link href="<?php echo base_url() . 'assets/'; ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css" rel="stylesheet">



    <!-- DataTables CSS -->
    <link href="<?php echo base_url() . 'assets/'; ?>plugins/datatables-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

    <link href="<?php echo base_url() . 'assets/'; ?>data/jquery.dataTables.min.css" rel="stylesheet">



    <script src="<?php echo base_url() . 'assets/'; ?>sweetalert2/dist/sweetalert2.min.js"></script>
    <link href="<?php echo base_url() . 'assets/'; ?>sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <style type="text/css" media="screen">
        .h7 {
            font-size: 14px;
        }

        .h8 {
            font-size: 10px;
        }

        .scroll {
            max-height: 400px;
            overflow-y: auto;
        }

        .bg-back {
            background-color: #E5DDD5;
        }

        .bg-chat {
            background-color: #DCF8C6;
        }
    </style>
    <!-- jQuery -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/jquery/jquery.min.js"></script>

    <script type="text/javascript">
        function site_url() {
            return "<?php echo site_url('/'); ?>";
        }

        function ConfirmDelete(url) {
            var agree = confirm("Are you sure you want to delete this item?");
            if (agree)
                return location.href = url;
            else
                return false;
        };
    </script>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <?php echo isset($navbar) ? $navbar : ''; ?>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php echo isset($content) ? $content : '' ?>
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Anything you want
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2020-2021 Seduluran Grub - Alvin</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery UI 1.11.4 -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>




    <script src="<?php echo base_url() . 'assets/'; ?>data/jquery.dataTables.min.js"></script>


    <!-- <script src="<?php echo base_url() . 'assets/'; ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script> -->

    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/moment/moment.min.js"></script>
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#myModal').on('shown.bs.modal', function() {
                $('.chosen-select', this).chosen('destroy').chosen();
            });
        });
        $.widget.bridge('uibutton', $.ui.button);
    </script>


    <!-- Slimscroll -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- Select2 -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/select2/js/select2.full.min.js"></script>
    <!-- Chosen Plugin JavaScript -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/chosen/chosen.jquery.min.js"></script>
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/chosen/chosen.proto.min.js"></script>

    <script src="<?php echo base_url() . 'assets/'; ?>plugins/inputmask/inputmask/jquery.inputmask.js"></script>

    <!-- <script src="<?php echo base_url() . 'assets/'; ?>plugins/sweetalert2/sweetalert2.min.js"></script> -->


    <!-- AdminLTE App -->
    <script src="<?php echo base_url() . 'assets/'; ?>dist/js/adminlte.js"></script>



    <script>
        $(document).ready(function() {
            $('#dataTables').dataTable();

            $('[data-mask]').inputmask();
            $('.select2').select2();

            $('#myModal').on('shown.bs.modal', function() {
                $('.chosen-select', this).chosen('destroy').chosen();
            });
            /*
            //Date picker
            $('#datepicker').datepicker({
              autoclose: true,
              format:'yyyy-mm-dd',
              language:'id'
              //minViewMode: 'years'
            })

            //Date picker
            $('#birthdatepicker').datepicker({
                autoclose: true,
                format:'yyyy-mm-dd',
                startView: 'years',
                viewMode: 'years',
                language:'en'
            })
            */
            $('#datetimepicker').datetimepicker({
                locale: moment.locale('id'),
                format: 'YYYY-MM-DD HH:mm'
                //locale : 'id'
            });


            $('#birthpicker').datetimepicker({
                locale: moment.locale('id'),
                format: 'YYYY-MM-DD',
                viewMode: 'years'
                //locale : 'id'
            });

        });
    </script>
    <script type="text/javascript">
        var config = {
            '.chosen-select': {},
            '.chosen-select-deselect': {
                allow_single_deselect: true
            },
            '.chosen-select-no-single': {
                disable_search_threshold: 10
            },
            '.chosen-select-no-results': {
                no_results_text: 'Oops, nothing found!'
            },
            '.chosen-select-width': {
                width: "150%"
            }
        }
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
    </script>
</body>

</html>
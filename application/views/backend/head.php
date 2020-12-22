<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 3 | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/fontawesome-free/css/all.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>css/bootstrap.css">
    <!-- chosen CSS -->
    <link href="<?php echo base_url() . 'assets/'; ?>plugins/chosen/chosen.min.css" rel="stylesheet">

    <link href="<?php echo base_url() . 'assets/'; ?>plugins/select2/css/select2.min.css" rel="stylesheet">
    <link href="<?php echo base_url() . 'assets/'; ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?php echo base_url() . 'assets/'; ?>plugins/datatables-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

    <link href="<?php echo base_url() . 'assets/'; ?>data/jquery.dataTables.min.css" rel="stylesheet">



    <script src="<?php echo base_url() . 'assets/'; ?>sweetalert2/dist/sweetalert2.min.js"></script>
    <link href="<?php echo base_url() . 'assets/'; ?>sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript">
        function site_url() {
            return "<?php echo site_url('/'); ?>";
        }
    </script>
    <style>
        @page {
            size: landscape;
        }

        @media print {
            #print {
                display: all;
            }
        }

        @media screen {
            #print {
                display: none;
            }
        }
    </style>
</head>

<body id="print">
    <?php echo $hasil; ?>



    <!-- jQuery UI 1.11.4 -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $(document).ready(function() {
            $('#myModal').on('shown.bs.modal', function() {
                $('.chosen-select', this).chosen('destroy').chosen();
            });
        });
        $.widget.bridge('uibutton', $.ui.button);
    </script>

    <script>
        window.print();
    </script>

    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- JQVMap -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/moment/moment.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Select2 -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/select2/js/select2.full.min.js"></script>
    <!-- Chosen Plugin JavaScript -->
    <script src="<?php echo base_url() . 'assets/'; ?>plugins/chosen/chosen.jquery.min.js"></script>

</body>

</html>
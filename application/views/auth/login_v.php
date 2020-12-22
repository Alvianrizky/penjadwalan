<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Administrator | Log in</title>
   <!-- Tell the browser to be responsive to screen width -->
   <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <link href="<?php echo base_url() . 'assets/'; ?>dist/img/logo.png" type="image/x-icon" rel="shortcut icon">
   <!-- Bootstrap 3.3.5 -->
   <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() . 'assets/'; ?>css/bootstrap.min.css">
   <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/fontawesome-free/css/all.min.css">
   <!-- Ionicons -->
   <link rel="stylesheet" href="<?php echo base_url().'assets/';?>plugins/ionicons-2.0.1/css/ionicons.min.css">
   <!-- Theme style -->
   <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>dist/css/adminlte.min.css">
   <!-- iCheck -->
   <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
   <!-- Bootstrap 3.3.5 -->
   <link rel="stylesheet" href="<?php echo base_url().'assets/';?>bootstrap/css/bootstrap.min.css">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="<?php echo base_url().'assets/';?>plugins/font-awesome-4.1.0/css/font-awesome.min.css">
   <!-- Ionicons -->
   <link rel="stylesheet" href="<?php echo base_url().'assets/';?>plugins/ionicons-2.0.1/css/ionicons.min.css">
   <!-- Theme style -->
   <link rel="stylesheet" href="<?php echo base_url().'assets/';?>dist/css/AdminLTE.min.css">
   <!-- iCheck -->
   <link rel="stylesheet" href="<?php echo base_url().'assets/';?>plugins/iCheck/square/blue.css">

   <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
     <![endif]-->
  </head>
  <body class="container-fluid bg-light">
   <div class="card col-4 mx-auto my-5 py-5">
      <div class="login-logo">
         <a href="<?php echo site_url('auth/login');?>"><b>Login</b> Penjadwalan</a>
      </div><!-- /.login-logo -->
      <div class="login-card-body">
         <p class="login-card-msg">
            <?php 
            if (validation_errors()) {
               echo notifications('warning', validation_errors('<p>', '</p>'));
            }
            ?>           
         </p>
         <form action="<?php echo site_url('auth/login');?>" method="post">
            <div class="form-group has-feedback">
               <?php echo lang('login_identity_label', 'identity'); ?>
               <?php echo form_input($identity); ?>
            </div>
            <div class="form-group has-feedback">
               <?php echo lang('login_password_label', 'password'); ?>
               <?php echo form_input($password); ?>
            </div>
            <div class="form-group has-feedback">
               <label>Captcha </label>
               <p><?php echo $image;?></p>
            </div>
            <div class="row">
               <div class="col-xs-8">
                  <?php echo form_input($captcha); ?>
               </div>
            </div>
            <div class="row">
               <div class="col-8">
                  <div class="checkbox icheck">
                     <label>                  
                        <?php echo isset($remember) ? $remember : '';?>
                     </label>
                  </div>
               </div><!-- /.col -->
               <div class="col-4">
                  <button type="submit" class="btn btn-primary float-right">
                     Login
                  </button>
               </div><!-- /.col -->
            </div>
            <a href="forgot_password"><?php echo lang('login_forgot_password');?>
         </form>
      </div><!-- /.login-box-body -->
   </div><!-- /.login-box -->

   <!-- jQuery 2.1.4 -->
   <script src="<?php echo base_url() . 'assets/'; ?>plugins/jquery/jquery.min.js"></script>
   <script src="<?php echo base_url() . 'assets/'; ?>plugins/jquery-ui/jquery-ui.min.js"></script>
   <!-- Bootstrap 3.3.5 -->
   <script src="<?php echo base_url() . 'assets/'; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
   <script src="<?php echo base_url().'assets/';?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
   <!-- Bootstrap 3.3.5 -->
   <script src="<?php echo base_url().'assets/';?>bootstrap/js/bootstrap.min.js"></script>
   <script src="<?php echo base_url().'assets/';?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
   <!-- Bootstrap 3.3.5 -->
   <script src="<?php echo base_url().'assets/';?>bootstrap/js/bootstrap.min.js"></script>
   <!-- iCheck -->
   <script src="<?php echo base_url().'assets/';?>plugins/iCheck/icheck.min.js"></script>
   <script>
      $(function () {
         $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
         });
      });
   </script>
</body>
</html>

<?php

$x  = $this->uri->segment(1);

if ($x == 'users' || $x == 'groups') {
   $users = ' menu-open';
   $userslink = ' active';
} else {
   $users = '';
   $userslink = '';
}
if ($x == 'akademik' || $x == 'dosen' || $x == 'kelas'  || $x == 'makul' || $x == 'ruang') {
   $master = ' menu-open';
   $masterlink = ' active';
} else {
   $master = '';
   $masterlink = '';
}
if ($x == 'slotwaktu' || $x == 'pengampu') {
   $relasi = ' menu-open';
   $relasilink = ' active';
} else {
   $relasi = '';
   $relasilink = '';
}

?>

<!-- Brand Logo -->
<a href="index3.html" class="brand-link">
   <img src="<?php echo base_url() . 'assets/'; ?>dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
   <span class="brand-text font-weight-light">Test Online</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
   <!-- Sidebar user panel (optional) -->
   <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
         <img src="<?php echo base_url() . 'assets/'; ?>img/foto.png" class="img-circle elevation-2 mt-2" alt="User Image">
      </div>
      <div class="info">
         <a href="#" class="d-block"><?php echo $this->session->userdata('first_name'); ?></a>
         <a href="#"><i class="fa fa-circle text-success text-sm"></i> Online</a>
      </div>
   </div>

   <!-- Sidebar Menu -->
   <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->

         <li class="nav-item has-treeview<?php echo $users; ?>">
            <a href="#" class="nav-link<?php echo $userslink; ?>">
               <i class="nav-icon fas fa-copy"></i>
               <p>
                  Users Managament
                  <i class="fas fa-angle-left right"></i>
                  <span class="badge badge-info right">2</span>
               </p>
            </a>
            <ul class="nav nav-treeview">
               <li class="nav-item">
                  <a href="<?php echo site_url('users'); ?>" class="nav-link<?php echo $this->uri->segment(1) == 'users' ? ' active' : ''; ?>">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Users</p>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="<?php echo site_url('groups'); ?>" class="nav-link<?php echo $this->uri->segment(1) == 'groups' ? ' active' : ''; ?>">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Groups</p>
                  </a>
               </li>
            </ul>
         </li>

         <li class="nav-item has-treeview
         <?php echo $master; ?>">
            <a href="#" class="nav-link<?php echo $masterlink; ?>">
               <i class="nav-icon fas fa-copy"></i>
               <p>
                  Data Master
                  <i class="fas fa-angle-left right"></i>
                  <span class="badge badge-info right">5</span>
               </p>
            </a>
            <ul class="nav nav-treeview">
               <li class="nav-item">
                  <a href="<?php echo site_url('dosen'); ?>" class="nav-link<?php echo $this->uri->segment(1) == 'dosen' ? ' active' : ''; ?>">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Dosen</p>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="<?php echo site_url('kelas'); ?>" class="nav-link<?php echo $this->uri->segment(1) == 'kelas' ? ' active' : ''; ?>">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Kelas</p>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="<?php echo site_url('makul'); ?>" class="nav-link<?php echo $this->uri->segment(1) == 'makul' ? ' active' : ''; ?>">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Mata Kuliah</p>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="<?php echo site_url('ruang'); ?>" class="nav-link<?php echo $this->uri->segment(1) == 'ruang' ? ' active' : ''; ?>">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Ruang</p>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="<?php echo site_url('akademik'); ?>" class="nav-link<?php echo $this->uri->segment(1) == 'akademik' ? ' active' : ''; ?>">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Tahun Akademik</p>
                  </a>
               </li>
            </ul>
         </li>

         <li class="nav-item has-treeview<?php echo $relasi; ?>">
            <a href="#" class="nav-link<?php echo $relasilink; ?>">
               <i class="nav-icon fas fa-copy"></i>
               <p>
                  Relasi Data
                  <i class="fas fa-angle-left right"></i>
                  <span class="badge badge-info right">2</span>
               </p>
            </a>
            <ul class="nav nav-treeview">
               <li class="nav-item">
                  <a href="<?php echo site_url('slotwaktu'); ?>" class="nav-link<?php echo $this->uri->segment(1) == 'slotwaktu' ? ' active' : ''; ?>">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Slot Waktu</p>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="<?php echo site_url('pengampu'); ?>" class="nav-link<?php echo $this->uri->segment(1) == 'pengampu' ? ' active' : ''; ?>">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Pengampu</p>
                  </a>
               </li>
            </ul>
         </li>

         <li class="nav-item">
            <a href="<?php echo site_url('coba'); ?>" class="nav-link">
               <i class="nav-icon fas fa-power-off"></i>
               <p>
                  Jadwal
               </p>
            </a>
         </li>

         <li class="nav-header"></li>
         <li class="nav-item">
            <a href="<?php echo site_url('auth/logout'); ?>" class="nav-link">
               <i class="nav-icon fas fa-power-off"></i>
               <p>
                  Logout
               </p>
            </a>
         </li>
      </ul>
   </nav>
   <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
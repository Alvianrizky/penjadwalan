<div class="container">
    <a href="../../index3.html" class="navbar-brand">
        <img src="<?php echo base_url() . 'assets/'; ?>dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Penjadwalan</span>
    </a>

    <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">

            <li class="nav-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Users Managament</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                    <li><a href="<?php echo site_url('users'); ?>" class="dropdown-item">Users</a></li>
                    <li><a href="<?php echo site_url('groups'); ?>" class="dropdown-item">Groups</a></li>
                    <!-- <li class="dropdown-divider"></li> -->
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Data Master</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                    <li><a href="<?php echo site_url('dosen'); ?>" class="dropdown-item">Dosen</a></li>
                    <li><a href="<?php echo site_url('kelas'); ?>" class="dropdown-item">Kelas</a></li>
                    <li><a href="<?php echo site_url('makul'); ?>" class="dropdown-item">Mata Kuliah</a></li>
                    <li><a href="<?php echo site_url('ruang'); ?>" class="dropdown-item">Ruang</a></li>
                    <li><a href="<?php echo site_url('akademik'); ?>" class="dropdown-item">Tahun Akademik</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Relasi Data</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                    <li><a href="<?php echo site_url('pengampu'); ?>" class="dropdown-item">Pengampu</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="<?php echo site_url('generate'); ?>" class="nav-link">Generate Jadwal</a>
            </li>

            <li class="nav-item">
                <a href="<?php echo site_url('jadwal'); ?>" class="nav-link">Jadwal</a>
            </li>



        </ul>
    </div>
    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <li class="nav-item ml-5">
            <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-danger btn-sm text-white mt-1 ">Logout</a>
        </li>
    </ul>
</div>
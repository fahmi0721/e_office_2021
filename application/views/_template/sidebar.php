<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?= base_url('public/img/avatar.png') ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= $this->session->userdata('Nama') ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <?php $aktif = $this->uri->segment(1) == "" ? "class='active'" : ""; ?>
        <li <?= $aktif; ?>><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <?php $aktif = $this->uri->segment(1) == "nota_dinas" ? "class='active'" : ""; ?>
        <li><a href="<?= base_url('nota_dinas/index') ?>"><i class="fa fa-book"></i> <span>Nota Dinas</span></a></li>
        <?php $aktif = $this->uri->segment(1) == "surat_masuk" ? "class='active'" : ""; ?>
        <li><a href="<?= base_url('surat_masuk/index') ?>"><i class="fa fa-file"></i> <span>Surat Masuk</span></a></li>
        <?php $aktif = $this->uri->segment(1) == "surat_keluar" ? "class='active'" : ""; ?>
        <li><a href="<?= base_url('surat_keluar/index') ?>"><i class="fa fa-file"></i> <span>Surat Keluar</span></a></li>
        <!-- <li class="treeview">
          <a href="#">
            <i class="fa fa-file"></i> <span>Persuratan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="../../index.html"><i class="fa fa-circle-o"></i> Surat Masuk</a></li>
            <li><a href="../../index2.html"><i class="fa fa-circle-o"></i> Surat Keluar</a></li>
            <li><a href="../../index2.html"><i class="fa fa-circle-o"></i> Nota Dinas</a></li>
          </ul>
        </li> -->

        <!-- <li class="treeview">
          <a href="#">
            <i class="fa fa-file-o"></i> <span>Persuratan 2019-2020</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="../../index.html"><i class="fa fa-circle-o"></i> Surat Masuk</a></li>
            <li><a href="../../index2.html"><i class="fa fa-circle-o"></i> Surat Keluar</a></li>
            <li><a href="../../index2.html"><i class="fa fa-circle-o"></i> Nota Dinas</a></li>
          </ul>
        </li> -->
        <?php $aktif = $this->uri->segment(1) == "request_surat" ? "class='active'" : ""; ?>
        <li <?= $aktif; ?>><a href="<?= base_url('request_surat/') ?>"><i class="fa fa-envelope"></i> <span>Request Nomor Surat</span></a></li>
        <li><a target='_blank' href="http://intansejahterautama.co.id/sdm"><i class="fa fa-envelope"></i> <span>Persuratan 2019-2020</span></a></li>
        
        <li class="header">Export Surat</li> 
        <?php $aktif = $this->uri->segment(1) == "export_nota_dinas" ? "class='active'" : ""; ?>
        <li><a href="<?= base_url('export_nota_dinas/') ?>"><i class="fa fa-tags"></i> <span>Export Nota Dinas</span></a></li>
        <li><a href="<?= base_url('export_surat_keluar/') ?>"><i class="fa fa-tags"></i> <span>Export Surat Keluar</span></a></li>
        <li><a href="<?= base_url('export_surat_masuk/') ?>"><i class="fa fa-tags"></i> <span>Export Surat Masuk</span></a></li>
          
        <!-- <li><a href="../widgets.html"><i class="fa fa-th"></i> <span>Widgets</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">Hot</small>
            </span>
          </a>
        </li> -->
        <?php if($this->session->userdata('KodeLevel') == 0){ ?>
          <li class="header">MAIN SETTING</li> 
          <?php $aktif = $this->uri->segment(1) == "klasifikasi" ? "class='active'" : ""; ?>
          <li><a href="<?= base_url('klasifikasi/') ?>"><i class="fa fa-tags"></i> <span>Klasifikasi</span></a></li>
          <?php $aktif = $this->uri->segment(1) == "users" ? "class='active'" : ""; ?>
          <li <?= $aktif; ?>><a href="<?= base_url('jenis_surat/index') ?>"><i class="fa fa-tags"></i> <span>Jenis Surat</span></a></li>
          <?php $aktif = $this->uri->segment(1) == "users" ? "class='active'" : ""; ?>
          <li <?= $aktif; ?>><a href="<?= base_url('users/index/') ?>"><i class="fa fa-users"></i> <span>Users</span></a></li>
        <?php } ?>
       
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
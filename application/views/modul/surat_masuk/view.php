
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Modul Surat Masuk
        <small>Data Surat Masuk</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Data Surat Masuk</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Data Surat Masuk</h3>

          <div class="box-tools pull-right">
            <div class='btn-group' id='BtnControl'>
                <?php if($this->session->userdata('KodeLevel') != 2){ ?>
                <a href="<?= base_url('surat_masuk/tambah/'); ?>" class='btn btn-sm btn-primary' title='Tambah Data' data-toggle='tooltip'><i class='fa fa-plus'></i> Tambah</a>
                <?php } ?>
                <button class='btn btn-sm btn-warning btn-flat' onclick="location.reload();" title='Reload' data-toggle='tooltip'><i class='fa fa-refresh'></i></button>
            </div>
          </div>
        </div>
        <div class="box-body">
            <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
            <form id='Filter'>
            <div class='row' style='margin-bottom:5px'>
                <div class='col-sm-1'>
                    <select class='form-control' id='Row' name='Row' onchange="LoadData()">
                        <option value='10'>10</option>
                        <option value='15'>15</option>
                        <option value='25'>25</option>
                        <option value='75'>75</option>
                        <option value='100'>100</option>
                    </select>
                </div>
                <div class='col-sm-6 col-md-4 col-md-offset-7 col-sm-offset-5'>
                    <div class='input-group'>
                        <select class='form-control' name='By' onchange="LoadData()">
                          <option value="NoSurat">No Surat</option>
                          <option value="Perihal">Perihal</option>
                          <option value="Dari">Dari</option>
                        </select>
                        <span class='input-group-addon'>#</span>
                        <input type='text' name='Search' onkeyup="LoadData()" class='form-control' autocomplete='off' placeholder='Entri data' />
                        <span class='input-group-addon'><i class='fa fa-search'></i></span>
                    </div>
                </div>
                
            </div>
            </form>
            <div class='row'><div class='col-sm-12'>
            <div class='table-responsive'>
                <table class='table table-striped table-bordered'>
                    <thead>
                        <tr>
                            <th width='10px' class='text-center'>No</th>
                            <th>No Dokumen</th>
                            <th>Surat Masuk</th>
                            <th>Tanggal</th>
                            <th>Authors</th>
                            <th>File</th>
                            <th width='10%' class='text-center'>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id='ShowData'></tbody>
                </table> 
            </div>
            </div></div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <div id='Paging'></div>
        </div>
        <!-- /.box-footer-->
        <div class="overlay LoadingState" >
            <i class="fa fa-refresh fa-spin"></i>
        </div>

      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<div class='modal fade in' id='modal' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content'>
<div class="modal-header">
    <button type="button" class="close" id="close_modal" data-dismiss="modal">&times;</button>
    <h5 class="modal-title"></h5>
</div>
<div class='modal-body'>

    <div id="ModalDetail"></div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="SubmitDelete()"><i class="fa fa-check-square"></i> &nbsp;Hapus</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="ClearModal()"><i class="fa fa-mail-reply"></i> &nbsp;Batal</button>
    </div>

</div>
</div>
</div>
</div>
<?php $this->load->view('modul/surat_masuk/js'); ?>
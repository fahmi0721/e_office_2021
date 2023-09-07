
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Modul Eksport Nota Dinas
        <small>Export Data</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Export Nota Dinas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Export Nota Dinas</h3>

          <div class="box-tools pull-right">
            <div class='btn-group' id='BtnControl'>
                <a href="<?= base_url('klasifikasi/index/'); ?>" class='btn btn-sm btn-danger' title='Kembali' data-toggle='tooltip'><i class='fa fa-mail-reply'></i> Kembali</a>
            </div>
          </div>
        </div>
        <div class="box-body">
            <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
            <form id="FormData" class="form-horizontal" action="#">
                <div class='row'>
                    <div class='col-sm-3 col-md-4'>
                        <small>Catatan:
                            <ul>
                                <li><span class='text-danger'>*)</span> Wajib diisi!</li>
                            </ul>
                        </small>
                    </div>
                    <div class='col-sm-9 col-md-8'>
                        <div class="form-group"><div class='col-sm-12'><span id='ProsesCrud'></span></div></div>
                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">Dari<span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                                    <input class='form-control FormInput datepicker' type='text' autocomplete='off' placeholder='Dari' name='Dari' id='Dari'>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Sampai<span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                                    <input class='form-control FormInput' type='text' autocomplete='off' placeholder='Sampai' name='Sampai' id='Sampai'>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">Klasifikasi Surat<span class='text-danger'>*</span></label>
                                <select name="master" id="master" class='form-control FormInput'>
                                    <option value="all">All</option>
                                    <?php foreach($master as $dts){ ?>
                                    <option value="<?= $dts->Kode ?>"><?= $dts->Kode ?> - <?= $dts->Nama ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Pilih Aksi <span class='text-danger'>*</span></label>
                                <select name="aksi" id="aksi" class='form-control FormInput'>
                                    <option value="">Pilih Aksi</option>
                                    <option value="print">Cetak Daftar</option>
                                    <option value="export-excel">Export Excel</option>
                                </select>
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class='btn-group'>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-paper-plane"></i> Proses</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
      </div>

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
<?php $this->load->view('modul/eksport_nota_dinas/js'); ?>
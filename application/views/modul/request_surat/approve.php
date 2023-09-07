
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Modul Approve Request Nomor Surat
        <small>Approve Request Nomor Surat</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= base_url('request_surat/index/') ?>"><i class="fa fa-users"></i> Detail Request Nomor Surat</a></li>
        <li class="active">Approve Request Nomor Surat</li>

      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Approve Request Nomor Surat</h3>

          <div class="box-tools pull-right">
            <div class='btn-group' id='BtnControl'>
                <a href="<?= base_url('request_surat/index/'); ?>" class='btn btn-sm btn-danger' title='Kembali' data-toggle='tooltip'><i class='fa fa-mail-reply'></i> Kembali</a>
            </div>
          </div>
        </div>
        <div class="box-body">
            <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
            <form id="FormDataApprove" class="form-horizontal" action="#">
                <input type='hidden'  name='IdRequest' value="<?= $data->Id ?>">
                <input type='hidden'  name='Authorss' value="<?= $data->Authorss ?>">
                <input type='hidden'  name='TglCreate' value="<?= $data->TglCreate ?>">
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
                                <label class="control-label">Kepada<span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-user'></i></span>
                                    <input class='form-control FormInput' value="<?= $data->Kepada ?>" type='text' autocomplete='off' placeholder='Kepada' name='Kepada' id='Kepada'>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                              <label class="control-label">Perihal<span class='text-danger'>*</span></label>
                              <input class='form-control FormInput' value="<?= $data->Perihal ?>" type='text' autocomplete='off' placeholder='Perihal' name='Perihal' id='Perihal'>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">Tanggal<span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <input class='form-control FormInput' value="<?= $data->TglSurat ?>" type='text' autocomplete='off' placeholder='Tanggal Surat' name='TglSurat' id='TglSurat'>
                                    <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                                </div>
                            </div>

                            <div class='col-sm-6'>
                                <label class="control-label">Jenis Surat<span class='text-danger'>*</span></label>
                                <select onchange='GetNomorSurat()' class='form-control FormInput select-jenis' name='KodeJenisSurat' id='KodeJenisSurat'>
                                    <option value=''>..:: Pilih Jenis Surat ::..</option>
                                    <?php foreach($jenis_surat as $js){ ?>
                                        <option value='<?= $js->Kode ?>'><?= $js->Kode ?> - <?= $js->Jenis ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">Nomor Surat<span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-key'></i></span>
                                    <input class='form-control FormInput' readonly  type='text' autocomplete='off' placeholder='Nomor Surat' name='NoSurat' id='NoSurat'>
                                </div>
                            </div>
                            

                            <div class='col-sm-6'>
                                <label class="control-label">File</label>
                                <div class='input-group'>
                                    <input class='form-control FormInput' accept='.pdf' type='file' autocomplete='off' placeholder='File' name='File' id='File'>
                                    <span class='input-group-addon'><i class='fa fa-file'></i></span>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class='col-sm-12'>
                                <label class="control-label">Keterangan</label>
                                <textarea class='form-control FormInput' name='Keterangan' id='Keterangan' rows='5' placeholder=''></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class='btn-group'>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check-square"></i> Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
      </div>
    </section>
  </div>

  <?php $this->load->view('modul/request_surat/js'); ?>

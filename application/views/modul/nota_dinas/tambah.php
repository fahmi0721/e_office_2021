
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Modul Nota Dinas
        <small>Tambah Nota Dinas</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= base_url('nota_dinas/index/') ?>"><i class="fa fa-users"></i> Detail Nota Dinas</a></li>
        <li class="active">Tambah Nota Dinas</li>

      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Tambah Nota Dinas</h3>

          <div class="box-tools pull-right">
            <div class='btn-group' id='BtnControl'>
                <a href="<?= base_url('nota_dinas/index/'); ?>" class='btn btn-sm btn-danger' title='Kembali' data-toggle='tooltip'><i class='fa fa-mail-reply'></i> Kembali</a>
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
                                <label class="control-label">Tanggal Surat<span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <input class='form-control FormInput' type='text' autocomplete='off' placeholder='Tanggal Surat' name='TglSurat' id='TglSurat'>
                                    <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Tahun<span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <select class='form-control FormInput select select-tahun' onchange='getNomorSurat()' name='Tahun' id='Tahun'>
                                        <option value="">Pilih Tahun</option>
                                        <?php foreach($tahun as $th){ ?>
                                        <option value="<?= $th ?>"><?= $th; ?></option>
                                        <?php } ?>

                                    </select>
                                    <span class='input-group-addon'><i class='fa fa-calendar-o'></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">Kepada Yth<span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-user'></i></span>
                                    <select class='form-control FormInput select select-kepada' name='Kepada' id='Kepada'>
                                        <option value="">Pilih Tujuan</option>
                                        <?php foreach($kepada as $kp){ ?>
                                        <option value="<?= $kp->Nama ?>"><?= $kp->Nama; ?></option>
                                        <?php } ?>

                                    </select>
                                    
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Nomor Surat<span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-key'></i></span>
                                    <input readonly  class='form-control FormInput' type='text' autocomplete='off' placeholder='Nomor Surat' name='NoSurat' id='NoSurat'>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">Perihal<span class='text-danger'>*</span></label>
                                <input class='form-control FormInput' type='text' autocomplete='off' placeholder='Perihal' name='Perihal' id='Perihal'>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Konseptor</label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-user'></i></span>
                                    <input readonly value="<?= $this->session->userdata('Nama'); ?>" class='form-control' type='text' autocomplete='off' placeholder='Authorss' name='Authorss' id='Authorss'>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class='col-sm-12'>
                                <label class="control-label">Keterangan</label>
                                <textarea rows='5' type='text' autocomplete=off class='form-control FormInput' name='Keterangan' id='Keterangan' placeholder='Keterangan'></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">File <span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <input  class='form-control FormInput' type='file' accept='.pdf' autocomplete='off' placeholder='File' name='File' id='File'>
                                    <span class='input-group-addon'><i class='fa fa-file-o'></i></span>
                                </div>
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
  <?php $this->load->view('modul/nota_dinas/js'); ?>

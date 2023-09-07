
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Modul Surat Masuk
        <small>Ubah Disposisi Surat Masuk</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= base_url('surat_masuk/index/') ?>"><i class="fa fa-file"></i> Surat Masuk</a></li>
        <li><a target='_blank' href="<?= base_url('load_data/surat_masuk/'.$IdSuratMasuk) ?>"><i class="fa fa-file-o"></i> <?= $NoSurat ?></a></li>
        <li><a href="<?= base_url('surat_masuk/view_disposisi/'.$IdSuratMasuk) ?>"><i class="fa fa-tag"></i> Disposisi</a></li>
        <li class="active">Ubah Disposisi Surat Masuk</li>

      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Ubah Disposisi Surat Masuk</h3>

          <div class="box-tools pull-right">
            <div class='btn-group' id='BtnControl'>
                <a href="<?= base_url('surat_masuk/view_disposisi/'.$IdSuratMasuk) ?>" class='btn btn-sm btn-danger' title='Kembali' data-toggle='tooltip'><i class='fa fa-mail-reply'></i> Kembali</a>
            </div>
          </div>
        </div>
        <div class="box-body">
            <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
            <form id="FormDataDisposisiUpdate" class="form-horizontal" action="#">
                <input value='<?= $data->Id; ?>'  class='form-control' type='hidden'  name='Id'>
                <input value='<?= $this->uri->segment(3); ?>'  class='form-control' type='hidden'  name='IdSuratMasuk'>
                <input value='<?= $data->NoDokumen; ?>'  class='form-control' type='hidden'  name='NoDokumen'>
                <div class='row'>
                    <div class='col-sm-3 col-md-4'>
                        <small>Catatan:
                            <ul>
                                <li><span class='text-danger'>*)</span> Wajib diisi!</li>
                                <li>lihat disposisi suratnya <a class='btn btn-xs btn-success' target='_blank' href="<?= base_url('load_data/disposisi/'.$data->Id) ?>">disini</a></li>
                            </ul>
                        </small>
                    </div>
                    <div class='col-sm-9 col-md-8'>
                        <div class="form-group"><div class='col-sm-12'><span id='ProsesCrud'></span></div></div>
                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">Tanggal Surat</label>
                                <div class='input-group'>
                                    <input value='<?= $datas->TglSurat; ?>' disabled class='form-control FormInput' type='text' autocomplete='off' placeholder='Tanggal Surat' name='TglSurat' id='TglSurat'>
                                    <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Tanggal Masuk Surat</label>
                                <div class='input-group'>
                                    <input value='<?= $datas->TglMasukSurat; ?>' disabled class='form-control FormInput' type='text' autocomplete='off' placeholder='Tanggal Surat' name='TglSurat' id='TglSurat'>
                                    <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">Perihal</label>
                                <input value='<?= $datas->Perihal; ?>' disabled  class='form-control FormInput' type='text' autocomplete='off' placeholder='Nomor Surat' name='NoSurat' id='NoSurat'>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Nomor Surat</label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-key'></i></span>
                                    <input disabled value='<?= $datas->NoSurat; ?>'  class='form-control FormInput' type='text' autocomplete='off' placeholder='Nomor Surat' name='NoSurat' id='NoSurat'>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Kepada Yth</label>
                                
                                <div class='input-group'>
                                    <span class='input-group-addon'>S1</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='DIREKTUR UTAMA'>
                                    <span class='input-group-addon'><input type='checkbox' value='S1' class='Kepada' name='Kepada[]' <?php if(in_array("S1",$Kepada)){ echo "checked"; } ?> ></span>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>S2</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='DIREKTUR OPERASI & KOMERSIAL'>
                                    <span class='input-group-addon'><input type='checkbox' value='S2' class='Kepada' name='Kepada[]' <?php if(in_array("S2",$Kepada)){ echo "checked"; } ?>></span>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>S3</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='DIREKTUR KEUANGAN & MANAJEMEN RISIKO'>
                                    <span class='input-group-addon'><input type='checkbox' value='S3' class='Kepada' name='Kepada[]' <?php if(in_array("S3",$Kepada)){ echo "checked"; } ?>></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Lain-Lain</label>
                                <div class='input-group'>
                                    <span class='input-group-addon'>B1</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='SM KEUANGAN & UMUM'>
                                    <span class='input-group-addon'><input value='B1' type='checkbox' class='Kepada' name='Kepada[]' <?php if(in_array("B1",$Kepada)){ echo "checked"; } ?>></span>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>B2</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='SM SDM & PELATIHAN'>
                                    <span class='input-group-addon'><input value='B2' type='checkbox' class='Kepada' name='Kepada[]' <?php if(in_array("B2",$Kepada)){ echo "checked"; } ?>></span>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>B3</span>
                                    <input disabled  class='form-control FormInput'  type='text' autocomplete='off' value='SM OPERASI & KOMERSIAL ALIH DAYA'>
                                    <span class='input-group-addon'><input type='checkbox' value='B3' class='Kepada' name='Kepada[]' <?php if(in_array("B3",$Kepada)){ echo "checked"; } ?>></span>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>B3</span>
                                    <input disabled  class='form-control FormInput'  type='text' autocomplete='off' value='SM RISIKO, IT & KOMERSIAL ALIH DAYA'>
                                    <span class='input-group-addon'><input type='checkbox' value='B4' class='Kepada' name='Kepada[]' <?php if(in_array("B4",$Kepada)){ echo "checked"; } ?>></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Disposisi</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class='input-group'>
                                    <span class='input-group-addon'>1</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='Untuk diketahui seperlunya'>
                                    <span class='input-group-addon'><input type='checkbox' value='1' class='Disposisi' name='Disposisi[]' <?php if(in_array("1",$Disposisi)){ echo "checked"; } ?>></span>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>2</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='Pelajari, untuk saran/pendapat'>
                                    <span class='input-group-addon'><input type='checkbox' value='2' class='Disposisi' name='Disposisi[]' <?php if(in_array("2",$Disposisi)){ echo "checked"; } ?>></span>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>3</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='Segera konsep jawaban'>
                                    <span class='input-group-addon'><input type='checkbox' value='3' class='Disposisi' name='Disposisi[]' <?php if(in_array("3",$Disposisi)){ echo "checked"; } ?>></span>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>4</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='Proses Sesuai ketentuan'>
                                    <span class='input-group-addon'><input type='checkbox' value='4' class='Disposisi' name='Disposisi[]' <?php if(in_array("4",$Disposisi)){ echo "checked"; } ?>></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class='input-group'>
                                    <span class='input-group-addon'>5</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='Bicarakan dengan Saya'>
                                    <span class='input-group-addon'><input type='checkbox' value='5' class='Disposisi' name='Disposisi[]' <?php if(in_array("5",$Disposisi)){ echo "checked"; } ?>></span>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>6</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='Untuk di jawab dan jelaskan'>
                                    <span class='input-group-addon'><input type='checkbox' value='6' class='Disposisi' name='Disposisi[]' <?php if(in_array("6",$Disposisi)){ echo "checked"; } ?>></span>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>7</span>
                                    <input disabled  class='form-control FormInput' type='text'  autocomplete='off' value='Teliti & Laporkan'>
                                    <span class='input-group-addon'><input type='checkbox' value='7' class='Disposisi' name='Disposisi[]' <?php if(in_array("7",$Disposisi)){ echo "checked"; } ?>></span>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>8</span>
                                    <input disabled  class='form-control FormInput' type='text' autocomplete='off' value='Untuk menjadi perhatian dan pelaksanaan lebih lanjut'>
                                    <span class='input-group-addon'><input type='checkbox' value='8' class='Disposisi' name='Disposisi[]' <?php if(in_array("8",$Disposisi)){ echo "checked"; } ?>></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class='col-sm-12'>
                                <label class="control-label">Catatan</label>
                                <textarea class='form-control FormInputDisposisi' type='text' autocomplete='off' placeholder='Tanggal Surat' rows='5' name='Catatan' id='Catatan'><?= $data->Catatan ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">File</label>
                                <div class='input-group'>
                                    <input  class='form-control FormInputDisposisi' accept='.pdf' type='file' autocomplete='off' placeholder='File Disposisi' name='File' id='File'>
                                    <span class='input-group-addon'><i class='fa fa-file-o'></i></span>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Disposisi Dari</label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-user'></i></span>
                                    <select class='form-control FormInputDisposisi select select-kepada' name='Dari' id='Dari'>
                                        <option value="">Pilih Tujuan</option>
                                        <?php foreach($kepada as $kp){ 
                                            $ck = $kp->Nama == $data->Dari ? "selected" : "";
                                        ?>
                                        <option value="<?= $kp->Nama ?>" <?= $ck; ?>><?= $kp->Nama; ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">Tanggal Disposisi</label>
                                <div class='input-group'>
                                    <input  class='form-control FormInputDisposisi' value="<?= $data->Tgl ?>" type='text' autocomplete='off' placeholder='Tanggal Disposisi' name='Tgl' id='Tgl'>
                                    <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Authors</label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-user'></i></span>
                                    <input readonly  class='form-control' type='text' autocomplete='off' placeholder='File Disposisi' name='Authorss' value="<?= $data->Authorss; ?>">
                                    
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
  <?php $this->load->view('modul/surat_masuk/js'); ?>

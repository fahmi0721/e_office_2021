
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Modul Nota Dinas
        <small>Data Disposisi Nota Dinas</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= base_url('nota_dinas/index') ?>"><i class="fa fa-file"></i> Nota Dinas</a></li>
        <li><a target='_blank' href="<?= base_url('load_data/nota_dinas/'.$IdNotaDinas) ?>"><i class="fa fa-file-o"></i> <?= $NoSurat; ?></a></li>
        <li class="active">Data Disposisi Nota Dinas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Data Disposisi Nota Dinas</h3>
          <div class="box-tools pull-right">
            <div class='btn-group' id='BtnControl'>
                <a href="<?= base_url('nota_dinas/index/'); ?>" class='btn btn-sm btn-danger' title='Kembali' data-toggle='tooltip'><i class='fa fa-mail-reply'></i> Kembali</a>
            </div>
          </div>
        </div>
        <div class="box-body">
            <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
            <div class='row'><div class='col-sm-12'>
            <div class='table-responsive'>
                <table class='table table-striped table-bordered'>
                    <thead>
                        <tr>
                            <th width='10px' class='text-center'>No</th>
                            <th>Dokumen</th>
                            <th>Disposisi Dari</th>
                            <th>Authors</th>
                            <th>File</th>
                            <th width='10%' class='text-center'>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $No=1; foreach($data as $dt){ ?>
                            <tr>
                                <td width='10px' class='text-center'><?= $No ?></td>
                                <td>No Dokumen : <?= $dt->NoDokumen; ?><br>No Dokumen Surat : <?= $dt->NoDokumenSurat; ?></td>
                                <td><?= $dt->Dari ?></td>
                                <td><?= $dt->Authorss ?></td>
                                <td class='text-center'><a target='_blank' class='btn btn-xs btn-success' data-toggle='tooltip' title='Lihat Surat' href="<?= base_url('load_data/disposisi/'.$dt->Id) ?>"><i class='fa fa-eye'></i></a></td>
                                <td width='10%' class='text-center'>
                                    <div class='btn-group'>
                                        <a  class='btn btn-xs btn-warning' data-toggle='tooltip' title='Detail Data Disposisi' href="<?= base_url('nota_dinas/detail_disposisi/'.$IdNotaDinas."/".$dt->Id) ?>"><i class='fa fa-eye'></i></a>
                                        <?php if($this->session->userdata('KodeLevel') != 2){ ?>
                                        <a  class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' href="<?= base_url('nota_dinas/edit_disposisi/'.$IdNotaDinas."/".$dt->Id) ?>"><i class='fa fa-edit'></i></a>
                                        <a onclick="return confirm('Anda yakin menghapus data ini?')" class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' href="<?= base_url('nota_dinas/delete_disposisi/'.$IdNotaDinas."/".$dt->Id) ?>"><i class='fa fa-trash-o'></i></a>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                        <?php $No++; } ?>
                    </tbody>
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
<?php $this->load->view('modul/nota_dinas/js'); ?>
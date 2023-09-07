
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Modul Users
        <small>Ubah Users</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= base_url('users/index/') ?>"><i class="fa fa-users"></i> Detail Users</a></li>
        <li class="active">Ubah Users</li>

      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Ubah Users</h3>

          <div class="box-tools pull-right">
            <div class='btn-group' id='BtnControl'>
                <a href="<?= base_url('users/index/'); ?>" class='btn btn-sm btn-danger' title='Kembali' data-toggle='tooltip'><i class='fa fa-mail-reply'></i> Kembali</a>
            </div>
          </div>
        </div>
        <div class="box-body">
            <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
            <form id="FormDataUpdate" class="form-horizontal" action="#">
                <input type='hidden' value='<?= $data->Id; ?>' name='Id'>
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
                                <label class="control-label">Nama Lengkap<span class='text-danger'>*</span></label>
                                <input class='form-control FormInput' value='<?= $data->Nama ?>' type='text' autocomplete='off' placeholder='Nama Lengkap' name='Nama' id='Nama'>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Direktorat<span class='text-danger'>*</span></label>
                                <select type='text'   class='form-control FormInput select-direktorat' name='Direktorat' id='Direktorat'>
                                    <option value=''></option>
                                    <option value='0' <?php if($data->Direktorat == "0"){ echo "selected"; } ?>>SDM</option>
                                    <option value='1' <?php if($data->Direktorat == "1"){ echo "selected"; } ?>>OPERASI</option>
                                    <option value='2' <?php if($data->Direktorat == "2"){ echo "selected"; } ?>>KEUANGAN</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class='col-sm-6'>
                                <label class="control-label">Username<span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-user'></i></span>
                                    <input readonly type='text' value='<?= $data->Username ?>' autocomplete=off class='form-control FormInput' name='Username' id='Username' placeholder='Username' />
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Password<span class='text-danger'>*</span></label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-key'></i></span>
                                    <input type='text' autocomplete=off class='form-control FormInput' name='Password' id='Password' placeholder='Password' />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">    
                            <div class='col-sm-6'>
                                <label class="control-label">Status <span class='text-danger'>*</span></label></label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><input <?php if($data->Status == "1"){ echo "checked"; } ?> type='radio' name='Status' value='1' id='Status1' checked  /></span>
                                    <input type='text' readonly class='form-control' value='Aktif'  />
                                    <span class='input-group-addon'><input <?php if($data->Status == "0"){ echo "checked"; } ?> type='radio' name='Status' id='Status0' value='0'  /></span>
                                    <input type='text' readonly class='form-control' value='Tidak Aktif'  />
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <label class="control-label">Level<span class='text-danger'>*</span></label>
                                <select type='text'  class='form-control FormInput select-level' name='Level' id='Level'>
                                    <option value=''></option>
                                    <option value='0' <?php if($data->Level == "0"){ echo "selected"; } ?>>Admin</option>
                                    <option value='1' <?php if($data->Level == "1"){ echo "selected"; } ?>>Tata Usaha</option>
                                    <option value='2' <?php if($data->Level == "2"){ echo "selected"; } ?>>Pelaksana</option>
                                </select>
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
  <?php $this->load->view('modul/users/users_js'); ?>

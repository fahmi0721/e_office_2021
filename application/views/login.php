
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>E-OOFICE : Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?= base_url('public/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/css/AdminLTE.min.css') ?>">
  
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>E-</b>OFFICE</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="<?= base_url('auth/proses/') ?>" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="Username" placeholder="Username" required>
        <span class="fa fa-user form-control-feedback" ></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="Password" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="form-group">
        <span class="pull-right"><button class="btn btn-primary btn-flat" type='submit'  name="login"><i class="fa fa-sign-in"></i> Login</button></span>
        <span class="clearfix"></span>
      </div>


    </form>
    <?php if($this->session->flashdata('error') !=''){
            echo '<div class="alert alert-danger" role="alert">';
            echo "<h4><i class='fa fa-warning'></i> Error</h4>";
            echo $this->session->flashdata('error');
            echo '</div>';
        }
    ?>
   
    

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="<?= base_url('public/js/jquery.min.js') ?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?= base_url('public/js/bootstrap.min.js') ?>"></script>

</body>
</html>

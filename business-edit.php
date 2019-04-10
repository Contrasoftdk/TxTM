<?php
session_start();
require_once 'dbconnect.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
  custom_redirect("Location: index.php");
  echo "<meta http-equiv='refresh' content='0; url=index.php' />";
  exit;
}

$id   = @$_GET['bid'];
$sql_select = "select * from business where business_id = " . $id;
$isData  = mysqli_query($conn, $sql_select);
if ($isData) {
  $editData = mysqli_fetch_array($isData);
  // print_r($editData['business_name']); die;
} else {
  custom_redirect("Location: " . HOME_URL . "/business-list.php?error=1&task=edit");
}

if (count($_POST) > 0) {
  $id =  $_POST['business_id'];
  unset($_POST['business_id']);
  $_POST['business_name'] =  mysqli_real_escape_string($conn, $_POST['business_name']);
  $_POST['updated_at'] = date('Y-m-d H:i:s');
  $sql_insert = qry_update('business', $_POST, 'WHERE business_id = ' . $id);
  $do_insert  = mysqli_query($conn, $sql_insert);
  if ($do_insert) {
    custom_redirect("Location: " . HOME_URL . "/business-list.php?success=1");
    die();
  } else {
    custom_redirect("Location: " . HOME_URL . "/business-list.php?error=1&task=add");
    die();
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>First-Transport | Business</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <header class="main-header">
      <!-- Logo -->
      <a href="home.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>FIRST </b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>FIRST</b> Transport</span>
      </a>

      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->


            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo $_SESSION['username']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                  <p>
                    <?php echo $_SESSION['username']; ?>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-right">
                    <a href="logout.php" class="btn btn-default btn-flat">Log ud</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header> <!-- Left side column. contains the logo and sidebar -->

    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php echo $_SESSION['username']; ?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php include('sidebar-admin-business.php'); ?>
        <!-- /.sidebar -->

    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>Business</h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Hjem</a></li>
          <li class="active">Business</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <?php if (@$_GET['success']) : ?>
          <?php if (@$_GET['task'] == 'delete') : ?>
            <p class="alert alert-success">Slettet !</p>
          <?php else : ?>
            <p class="alert alert-success">Gemt !</p>
          <?php endif; ?>
        <?php endif; ?>

        <?php if (@$_GET['error']) : ?>
          <?php if (@$_GET['task'] == 'delete') : ?>
            <p class="alert alert-danger">Kunne ikke slette !</p>
          <?php else : ?>
            <p class="alert alert-danger">Kunne ikke gemme !</p>
          <?php endif; ?>
        <?php endif; ?>

        <div class="row">
          <div class="col-md-12">
            <!-- Edit view -->
            <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="business_id" value="<?php echo @$editData['business_id']; ?>" readonly>
                </div>
                <label class="col-sm-2 control-label">Business Id</label>
              </div>
              <div class="form-group">
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="business_name" value="<?php echo @$editData['business_name']; ?>" placeholder="Business Name">
                </div>
                <label class="col-sm-2 control-label">Business Name</label>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-danger create_user">Gem</button>
                </div>
              </div>
            </form>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <b>Version</b> 2.3.7
      </div>
      <strong>Copyright &copy; 2017 <a href="http://contrasoft.dk">Contrasoft</a>.</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Create the tabs -->
      <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
      </ul>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery 2.2.3 -->
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="plugins/fastclick/fastclick.js"></script>
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

  <!-- App -->
  <script src="dist/js/app.min.js"></script>
  <!-- for demo purposes -->
  <script src="dist/js/demo.js"></script>

  <script>
    $(function() {
      // $('#start_date, #end_date, #driverid, #costumertypeid, #paymenttypeid').on(  'keyup change',function(){
      //   table1.ajax.reload();
      // }); 

      $('.create_user').on('click', function() {
        var business_name = $('input[name="business_name"]');

        if (business_name.val().length == 0) {
          business_name.addClass('error');
          business_name.focus();
          return false;
        }
      });
    });
  </script>
</body>

</html>
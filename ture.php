<?php
session_start();
ob_start();
require_once 'dbconnect.php';

// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
  header('location:index.php');
  //  echo "<meta http-equiv='refresh' content='0; url=index.php' />";
  exit;
}
// select loggedin users detail
$res = mysqli_query($conn, "SELECT * FROM user WHERE userid=" . $_SESSION['user']);
$userRow = mysqli_fetch_array($res);

$sql = "SELECT * FROM payment";
$res = mysqli_query($conn, $sql);
$payments = array();
while ($row = mysqli_fetch_assoc($res)) {
  $payments[] = $row;
}

$sql = "SELECT * FROM user where userlevel = 3 AND business_id = ".$_SESSION['BusinessId'];
$res = mysqli_query($conn, $sql);
$drivers = array();
while ($row = mysqli_fetch_assoc($res)) {
  $drivers[] = $row;
}

$sql = "SELECT * FROM rates";
$res = mysqli_query($conn, $sql);
$rates = $rate_ids = array();
while ($row = mysqli_fetch_assoc($res)) {
  $rates[] = $row;
  $rate_ids[] = $row['rateid'];
}

$sql = "SELECT * FROM payment";
$res = mysqli_query($conn, $sql);
$payments = array();
while ($row = mysqli_fetch_assoc($res)) {
  $payments[] = $row;
}

$method = 'index';
if (isset($_GET['method'])) {
  $method = $_GET['method'];
}

if (count($_POST) > 0) {
  $_POST['business_id'] = $_SESSION['BusinessId'];
  if ($method == 'add') {
    $_POST['tdate']     = date('Y-m-d', strtotime($_POST['tdate']));
    $_POST['starttime'] = date('H:i:s', strtotime($_POST['starttime']));
    $_POST['endtime']   = date('H:i:s', strtotime($_POST['endtime']));
    unset($_POST['hour']);
    unset($_POST['minute']);
    unset($_POST['meridian']);
    $sql_insert = qry_insert('trip', $_POST);
    $do_insert  = mysqli_query($conn, $sql_insert);
    if ($do_insert) {
      custom_redirect("Location: " . HOME_URL . "/ture.php?success=1");
      die();
    } else {
      custom_redirect("Location: " . HOME_URL . "/ture.php?error=1&task=add");
      die();
    }
  }

  if ($method == 'edit') {
    $_POST['tdate']     = date('Y-m-d', strtotime($_POST['tdate']));
    $_POST['starttime'] = date('H:i:s', strtotime($_POST['starttime']));
    $_POST['endtime']   = date('H:i:s', strtotime($_POST['endtime']));
    $tripid = $_GET['id'];
    unset($_POST['tripid']);
    unset($_POST['hour']);
    unset($_POST['minute']);
    unset($_POST['meridian']);

    $sql_insert = qry_update('trip', $_POST, 'WHERE tripid = ' . $tripid);
    // echo $sql_insert;
    // die();
    $do_insert  = mysqli_query($conn, $sql_insert);

    if ($do_insert) {
      custom_redirect("Location: " . HOME_URL . "/ture.php?action=edit&method=edit&id=" . $tripid . "&success=1");
      die();
    } else {
      custom_redirect("Location: " . HOME_URL . "/ture.php?error=1&task=add");
      die();
    }
  }
}

if ($method == 'delete') {
  $id = $_GET['id'];
  $sql_delete = "DELETE FROM trip WHERE tripid =" . $id;
  $do_del = mysqli_query($conn, $sql_delete);
  if ($do_del) {
    custom_redirect("Location: ture.php");
  } else {
    custom_redirect("Location: ture.php?error=1&task=delete");
  }
  die();
}
if ($method == 'edit') {
  $id   = @$_GET['id'];
  if (!$id) {
    custom_redirect("Location: ture.php");
    die();
  }
  $res  = mysqli_query($conn, "SELECT * FROM trip WHERE tripid=" . $id);
  $trips = mysqli_fetch_assoc($res);
  if ($trips) {
    foreach ($trips as $key => $value) {
      if ($key == 'tdate') {
        $value = date('d-m-Y', strtotime($value));
      }
      if ($key == 'starttime' || $key == 'endtime') {
        $value = date('h:i a', strtotime($value));
      }
      $$key = $value;
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>First-Transport | Ture</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/colorpicker/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
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
        <span class="logo-mini"><b><?php echo $_SESSION['BusinessName']; ?> </b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b><?php echo $_SESSION['BusinessName']; ?></b></span>
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
                <img src="<?php echo $_SESSION['ProfilePic']; ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo $_SESSION['username']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="<?php echo $_SESSION['ProfilePic']; ?>" class="img-circle" alt="User Image">
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
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="<?php echo $_SESSION['ProfilePic']; ?>" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php echo $_SESSION['username']; ?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">Kontrolpanel</li>
          <li><a href="home.php"><i class="fa fa-book"></i> <span>Skrivebord</span></a></li>
          <li class="active"><a href="ture.php"><i class="fa fa-cab"></i> <span>Kørsel/Ture</span></a></li>
          <li><a href="takstzoner.php"><i class="fa fa-bar-chart"></i> <span>Takstzoner</span></a></li>
          <li><a href="indstillinger.php"><i class="fa fa-gears"></i> <span>Indstillinger</span></a></li>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Ture
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Hjem</a></li>
          <li class="active">Ture</li>
        </ol>
      </section>
      <!-- Main content -->
      <?php if ($method == 'index') : ?>
        <section class="content">
          <div class="row">
            <div class="col-md-3">
              <div class="box box-danger">
                <div class="box-header">
                  <h3 class="box-title">Vælg Dato:</h3>
                </div>
                <div class="box-body">
                  <!-- Date dd/mm/yyyy -->
                  <div class="form-group">
                    <label>Fra dato:</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control" id="start_date">
                    </div>
                    <!-- /.input group -->
                  </div>
                  <!-- /.form group -->
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <div class="col-md-3">
              <div class="box box-danger">
                <div class="box-header">
                  <h3 class="box-title">Vælg Dato</h3>
                </div>
                <div class="box-body">
                  <!-- Date dd/mm/yyyy -->
                  <div class="form-group">
                    <label>Til dato:</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control" id="end_date">
                    </div>
                    <!-- /.input group -->
                  </div>
                  <!-- /.form group -->
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col (right) -->
            <div class="col-md-2">
              <div class="form-group">
                <label>Chauffør</label>
                <select class="form-control select2" id="driverid" style="width: 100%;">
                  <option value="-1" selected="selected">Alle Chaufføre</option>
                  <?php
                  if (count($drivers) > 0) {
                    foreach ($drivers as $driver) {
                      $driver_id    = $driver['userid'];
                      $driver_name  = $driver['fname'] . ' ' . $driver['lname'];
                      echo '<option value="' . $driver_id . '">' . $driver_name . '</option>';
                    }
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Kundetype</label>
                <select class="form-control select2" id="costumertypeid" style="width: 100%;">
                  <option value="" selected="selected">Privat & Erhverv</option>
                  <option value="0">Privat</option>
                  <option value="1">Erhverv</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Betalingstype</label>
                <select class="form-control select2" id="paymenttypeid" style="width: 100%;">
                  <option value="" selected="selected">Kontant & Kreditkort</option>
                  <option value="1">Kontant</option>
                  <option value="2">Kreditkort</option>
                  <option value="3">Konto</option>
                </select>
              </div>
            </div>
            <div class="col-md-2 ">
              <div class="form-group">
                <label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</label>
                <button class="btn btn-danger form-control" id="search-trip" style="width:170px;"> <i class="fa fa-search"></i> Søg</button>
              </div>
            </div>
          </div>
          <!-- /.row -->

          <div class="raw">
            <div class="col-md-12">
              <h3>Tur data</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <p id="ji-filter" style="display:none">
                Show:
                <select>
                  <option value="">All</option>
                  <?php foreach ($rate_ids as $rate_id) {
                    ?>
                    <option><?php echo $rate_id; ?></option>
                  <?php
                } ?>
                </select>
              </p>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Tur Id</th>
                    <th>Kundetype</th>
                    <th>Betalingstype</th>
                    <th>Dato</th>
                    <th>Start Tid</th>
                    <th>Slut Tid</th>
                    <th>Afstand</th>
                    <th>Læssetid</th>
                    <th>Tur nummer</th>
                    <th>Rate Id</th>
                    <th>Pris</th>
                    <th>Chauffør</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <div>
            <a href="<?php echo HOME_URL; ?>/ture.php?method=add" class="btn btn-primary">Tilføj Ny</a>
          </div>
          <!-- /.col -->
          <!-- /.row -->
        </section>
      <?php endif; ?>

      <?php if ($method == 'add' || $method == 'edit') : ?>
        <section class="content">
          <?php if (@$_GET['success']) : ?>
            <p class="alert alert-success">Gemt !</p>
          <?php endif; ?>
          <?php if (@$_GET['error']) : ?>
            <p class="alert alert-danger">Kunne ikke gemme din handlinger !</p>
          <?php endif; ?>
          <div class="box" style="padding: 10px 0px;">
            <form class="form-horizontal" action="" method="POST">
              <?php if ($method == 'edit') : ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Tur Id</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="tripid" value="<?php echo @$tripid; ?>" readonly>
                  </div>
                </div>
              <?php endif; ?>
              <div class="form-group">
                <label class="col-sm-2 control-label">Kunde Type</label>
                <div class="col-sm-10">
                  <select class="form-control" name="customertype">
                    <option <?php if (@$customertype == 0) {
                              echo 'selected="selected"';
                            } ?> value="0">Privat</option>
                    <option <?php if (@$customertype == 1) {
                              echo 'selected="selected"';
                            } ?> value="1">Erhverv</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Dato</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control date-picker" name="tdate" value="<?php echo @$tdate; ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Start Tid</label>
                <div class="col-sm-10">

                  <div class="input-group bootstrap-timepicker timepicker">

                    <input type="text" class="form-control time-picker" name="starttime" value="<?php echo @$starttime; ?>">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Slut Tid</label>
                <div class="col-sm-10">
                  <div class="input-group bootstrap-timepicker timepicker">
                    <input type="text" class="form-control time-picker" name="endtime" value="<?php echo @$endtime; ?>">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Afstand</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="distance" value="<?php echo @$distance; ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Læssetid</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="waittime" value="<?php echo @$waittime; ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Takst type</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="ratetype" style="width: 100%;">
                    <option value="1">Lille</option>
                    <option value="2">Stor</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Takst Id</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="ratesid" style="width: 100%;">
                    <?php
                    if (count($rates) > 0) {
                      foreach ($rates as $rate) {
                        $rateid    = $rate['rateid'];
                        if (@$ratesid == $rateid) {
                          echo '<option selected="selected" value="' . $rateid . '">' . $rateid . '</option>';
                        } else {
                          echo '<option value="' . $rateid . '">' . $rateid . '</option>';
                        }
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Tur Pris</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="tripprice" value="<?php echo @$tripprice; ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Chauffør</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="tdriverid" style="width: 100%;">
                    <?php
                    if (count($drivers) > 0) {
                      foreach ($drivers as $driver) {
                        $driver_id    = $driver['userid'];
                        $driver_name  = $driver['fname'] . ' ' . $driver['lname'];
                        if (@$tdriverid == $driver_id) {
                          echo '<option selected="selected" value="' . $driver_id . '">' . $driver_name . '</option>';
                        } else {
                          echo '<option value="' . $driver_id . '">' . $driver_name . '</option>';
                        }
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Tur nummer</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="tripnumber" value="<?php echo @$tripnumber; ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Betalings Type</label>
                <div class="col-sm-10">
                  <select class="form-control select2" id="paymenttype" name="paymenttype" style="width: 100%;">
                    <?php
                    if (count($payments) > 0) {
                      foreach ($payments as $payment) {
                        $payment_id    = $payment['paymentid'];
                        $payment_type  = $payment['paymenttype'];

                        if ($paymenttype ==  $payment_id) {
                          echo '<option selected="selected" value="' . $payment_id . '">' . $payment_type . '</option>';
                        } else {
                          echo '<option value="' . $payment_id . '">' . $payment_type . '</option>';
                        }
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <a href="<?php echo HOME_URL; ?>/ture.php" class="btn btn-danger">Annuller</a>
                  <button type="submit" class="btn btn-primary create_trip">Gem</button>
                </div>
              </div>
            </form>
          </div>
        </section>
      <?php endif; ?>

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
  <!-- Select2 -->
  <script src="plugins/select2/select2.min.js"></script>
  <!-- InputMask -->
  <script src="plugins/input-mask/jquery.inputmask.js"></script>
  <script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
  <!-- date-range-picker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap datepicker -->
  <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
  <!-- bootstrap color picker -->
  <script src="plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
  <!-- bootstrap time picker -->
  <script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
  <!-- SlimScroll 1.3.0 -->
  <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
  <!-- iCheck 1.0.1 -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <!-- FastClick -->
  <script src="plugins/fastclick/fastclick.js"></script>
  <!-- App -->
  <script src="dist/js/app.min.js"></script>
  <!-- For demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- Page script -->
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
  <!-- page script -->

  <script>
    $(function() {
      //Initialize Select2 Elements
      $(".select2").select2({
        closeOnSelect: true
      });

      //Datemask dd/mm/yyyy
      $("#start_date").inputmask("dd-mm-yyyy", {
        "placeholder": "dd-mm-yyyy"
      });
      //Datemask2 mm/dd/yyyy
      $("#end_date").inputmask("dd-mm-yyyy", {
        "placeholder": "dd-mm-yyyy"
      });
      //Money Euro
      $("[data-mask]").inputmask();

      //Date range picker
      $('#reservation').daterangepicker();
      //Date range picker with time picker
      $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        format: 'MM/DD/YYYY h:mm A'
      });
      //Date range as a button
      $('#daterange-btn').daterangepicker({
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function(start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
      );

      //Date picker
      $('#datepicker').datepicker({
        autoclose: true
      });

      $('.date-picker').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
      });

      $('.time-picker').timepicker();

      //iCheck for checkbox and radio inputs
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      });
      //Red color scheme for iCheck
      $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
      });
      //Flat red color scheme for iCheck
      $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      });

      //Colorpicker
      $(".my-colorpicker1").colorpicker();
      //color picker with addon
      $(".my-colorpicker2").colorpicker();

      //Timepicker
      $(".timepicker").timepicker({
        showInputs: false
      });

      jQuery("body").on("click", ".btn-delete", function() {
        var confrim = confirm("Er du sikker ? Vil du slette denne tur ?");
        if (confrim) {
          window.location.href = $(this).attr('data-href');
        }
      });

      $('.create_trip').on('click', function() {
        var tdate = $('input[name="tdate"]');
        var starttime = $('input[name="starttime"]');
        var endtime = $('input[name="endtime"]');
        var distance = $('input[name="distance"]');
        var waittime = $('input[name="waittime"]');
        var ratetype = $('input[name="ratetype"]');
        var ratesid = $('select[name="ratesid"]');
        var tripprice = $('input[name="tripprice"]');
        var tdriverid = $('select[name="tdriverid"]');
        var tripnumber = $('input[name="tripnumber"]');
        var paymenttype = $('select[name="paymenttype"]');

        if (tdate.val().length == 0) {
          tdate.addClass('error');
          tdate.focus();
          return false;
        }

        if (starttime.val().length == 0) {
          starttime.addClass('error');
          starttime.focus();
          return false;
        }
        if (endtime.val().length == 0) {
          endtime.addClass('error');
          endtime.focus();
          return false;
        }
        if (distance.val().length == 0) {
          distance.addClass('error');
          distance.focus();
          return false;
        }
        if (waittime.val().length == 0) {
          waittime.addClass('error');
          waittime.focus();
          return false;
        }

        if (ratesid.val().length == 0) {
          ratesid.addClass('error');
          ratesid.focus();
          return false;
        }
        if (tripprice.val().length == 0) {
          tripprice.addClass('error');
          tripprice.focus();
          return false;
        }

        if (tripnumber.val().length == 0) {
          tripnumber.addClass('error');
          tripnumber.focus();
          return false;
        }
      });
    });
  </script>

  <script>
    $(function() {
      var table1 = $("#example1").DataTable({
        "ajax": {
          "url": "ajax_data_trip.php",
          "type": "POST",
          "data": buildSearchData
        },
        "dom": 'l<"table-ji-selection">frtip',
        initComplete: function(settings) {
          var api = new $.fn.dataTable.Api(settings);
          $('.table-ji-selection', api.table().container()).append(
            $('#ji-filter').detach().show()
          );

          $('#ji-filter select').on('change', function() {
            table1.columns(9).search(this.value).draw();
          });
        },
        "bLengthChange": false
      });
      $('#search-trip').on('click', function() {
        $("#example1").dataTable().fnDestroy();
        var table1 = $("#example1").DataTable({
          "ajax": {
            "url": "ajax_data_trip.php",
            "type": "POST",
            "data": buildSearchData
          },
          "dom": 'l<"table-ji-selection">frtip',
          initComplete: function(settings) {
            var api = new $.fn.dataTable.Api(settings);
            $('.table-ji-selection', api.table().container()).append(
              $('#ji-filter').detach().show()
            );

            $('#ji-filter select').on('change', function() {
              table1.columns(9).search(this.value).draw();
            });
          },
          "bLengthChange": false
        });
      });

      function buildSearchData() {
        var dta = {
          'bid': '<?php echo $_SESSION['BusinessId']; ?>',
          'start_date': $('#start_date').val(),
          'end_date': $('#end_date').val(),
          'driverid': $('#driverid').val(),
          'costumertypeid': $('#costumertypeid').val(),
          'paymenttypeid': $('#paymenttypeid').val(),
          'page': 'ture',
        };
        return dta;
      }
      // $('#start_date, #end_date, #driverid, #costumertypeid, #paymenttypeid').on(  'keyup change',function(){
      // table1.ajax.reload();
      // });
    });
  </script>
  <style>
    .table-ji-selection {
      float: left;
    }

    div.dataTables_filter {
      float: right;
    }
  </style>
</body>

</html>
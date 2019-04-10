<?php
session_cache_limiter(FALSE);
ob_start();
session_start();
require_once '../dbconnect.php';

// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
  custom_redirect("Location: " . HOME_URL . "/index.php");
  echo "<meta http-equiv='refresh' content='0; url=index.php' />";
  exit;
}
// select loggedin users detail
$res = mysqli_query($conn, "SELECT * FROM user WHERE userid=" . $_SESSION['user']);
$userRow = mysqli_fetch_array($res);

$action = @$_GET['action'];
$method = @$_GET['method'];

if (count($_POST) > 0) {
  $_POST['business_id'] = $_GET['bid'];
  if ($method == 'edit') {
    $id   = @$_GET['id'];
    $sql_insert = qry_update('rates', $_POST, 'WHERE rateid = ' . $id);
  }
  if ($method == 'add') {
    $sql_insert = qry_insert('rates', $_POST);
  }
  $do_insert  = mysqli_query($conn, $sql_insert);
  if ($do_insert) {
    custom_redirect("Location: " . HOME_URL . "/super-admin/takstzoner.php?action=edit&method=edit&bid=". $_GET['bid'] . "&id=" . $id . "&success=1");
  }
}
switch ($method) {
  case 'edit':
    $id   = @$_GET['id'];
    if (!$id) {
      custom_redirect("Location: " . HOME_URL . "/super-admin/takstzoner.php?bid=". $_GET['bid']);
      die();
    }
    break;

  default: 
    break;
}

switch ($action) {
  case 'delete':
    $id = $_GET['id'];
    $sql_delete = "DELETE FROM rates WHERE rateid =" . $id;
    $do_del = mysqli_query($conn, $sql_delete);
    if ($do_del) {
      custom_redirect("Location: " . HOME_URL . "/super-admin/takstzoner.php?success=1&bid=". $_GET['bid']);
    } else {
      custom_redirect("Location: " . HOME_URL . "/super-admin/takstzoner.php?error=1&task=delete&bid=". $_GET['bid']);
    }
    die();
    break;
  case 'edit':
    $id   = $_GET['id'];
    if (!$id) {
      custom_redirect("Location: " . HOME_URL . "/super-admin/takstzoner.php?bid=". $_GET['bid']);
      die();
    }
    $res  = mysqli_query($conn, "SELECT * FROM rates WHERE rateid=" . $id);
    $rate = mysqli_fetch_assoc($res);
    if ($rate) {
      foreach ($rate as $key => $value) {
        $$key = (int)$value;
      }
    }
  default:
    break;
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>First-Transport | Takstzoner</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
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
        <span class="logo-mini"><b><?php echo $_SESSION['BusinessName']; ?></b></span>
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
                <img src="../dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo $_SESSION['username']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                  <p>
                    <?php echo $_SESSION['username']; ?>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-right">
                    <a href="../logout.php" class="btn btn-default btn-flat">Log ud</a>
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
            <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php echo $_SESSION['username']; ?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">Kontrolpanel</li>
          <li>
            <a href="home.php?bid=<?php echo $_GET['bid']; ?>"><i class="fa fa-book"></i> <span>Desk</span></a>
          </li>
          <li>
            <a href="ture.php?bid=<?php echo $_GET['bid']; ?>"><i class="fa fa-cab"></i> <span>Travel / Tours</span></a>
          </li>
          <li class="active">
            <a href="takstzoner.php?bid=<?php echo $_GET['bid']; ?>"><i class="fa fa-bar-chart"></i> <span>Takstzoner</span></a>
          </li>
          <li>
            <a href="../business-add.php"><i class="fa fa-book"></i> <span>Create Business</span></a>
          </li>
          <li>
            <a href="../business-list.php"><i class="fa fa-gears"></i> <span>Business</span></a>
          </li>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>Håndtere dine takster her</h1>
        <?php
        $error = @$_GET['error'];
        $task = @$_GET['task'];
        if ($error && $task) {
          switch ($task) {
            case 'delete':
              $msg = 'Can not delete record ! Maybe it is using for other parrent record.';
              break;
            default:
              $msg = 'Something wrong !';
              break;
          }
          echo '<p class="alert alert-danger" style="margin-top:10px;">' . $msg . '</p>';
        }
        ?>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Hjem</a></li>
          <li class="active">Takstzoner</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <?php if (@$_GET['success']) : ?>
          <p class="alert alert-success">Gemt !</p>
        <?php endif; ?>
        <?php if (@$_GET['error']) : ?>
          <p class="alert alert-danger">Kunne ikke gemme din handling !</p>
        <?php endif; ?>
        <div class="row">
          <div class="col-md-12">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="<?php if (!$method || $method == 'index') {
                              echo 'active';
                            }; ?>">
                  <a href="<?php echo HOME_URL; ?>/super-admin/takstzoner.php?method=index&bid=<?php echo $_GET['bid'] ?>">Rediger i takster</a>
                </li>
                <li class="<?php if ($method == 'add') {
                              echo 'active';
                            }; ?>">
                  <a href="<?php echo HOME_URL; ?>/super-admin/takstzoner.php?method=add&bid=<?php echo $_GET['bid']; ?>">Opret ny takst</a>
                </li>
                <?php if (@$_GET['id']) : ?>
                  <li class="<?php if ($method == 'edit') {
                                echo 'active';
                              }; ?>">
                    <a href="<?php echo HOME_URL; ?>/super-admin/takstzoner.php?method=edit&bid=<?php echo $_GET['bid']; ?>">Rediger i takst</a>
                  </li>
                <?php endif; ?>
              </ul>
              <div class="tab-content">
                <div class="active tab-pane" id="struck">

                  <?php if (!$method || $method == 'index') : ?>
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Takst Id</th>
                          <th>Takst Type</th>
                          <th>Start Pris</th>
                          <th>Læsse Pris</th>
                          <th>Km Pris</th>
                          <th>30 min Pris</th>
                          <th>Minimum Pris</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  <?php endif; ?>
                  <?php if ($method && $method == 'add' || $method == 'edit') : ?>
                    <form class="form-horizontal" action="" method="POST">
                      <?php if ($method == 'edit') : ?>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Takst id</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" name="rateid" value="<?php echo @$rateid; ?>" readonly>
                          </div>
                        </div>
                      <?php endif; ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label">Takst Type</label>
                        <div class="col-sm-10">
                          <select class="form-control" name="ratetype">
                            <option value="1" <?php if (@$ratetype == 1) {
                                                echo 'slected';
                                              } ?>>Lille</option>
                            <option value="2" <?php if (@$ratetype == 2) {
                                                echo 'slected';
                                              } ?>>Stor</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label">Start Pris</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" name="startprice" value="<?php echo @$startprice; ?>"  step=".01">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-2 control-label">Læsse Pris</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" name="waitprice" value="<?php echo @$waitprice; ?>" step=".01">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-2 control-label">Km Pris</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" name="kmprice" value="<?php echo @$kmprice; ?>"  step=".01">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-2 control-label">30 min. Pris</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" name="halfhourprice" value="<?php echo @$halfhourprice; ?>"  step=".01">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-2 control-label">Minimum Pris</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" name="minimumprice" value="<?php echo @$minimumprice; ?>" step=".01">
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-danger create_rate">Gem takst</button>
                        </div>
                      </div>
                    </form>
                  <?php endif; ?>

                </div>
                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
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
  <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="../bootstrap/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="../plugins/fastclick/fastclick.js"></script>
  <!-- App -->
  <script src="../dist/js/app.min.js"></script>

  <!-- DataTables -->
  <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>


  <!-- for demo purposes -->
  <script src="dist/js/demo.js"></script>

  <script>
    $(function() { 
      var BusinessId = '<?php echo $_GET['bid']; ?>';
      var myKeyVals = {
        BusinessId: BusinessId
      };
      var table1 = $("#example1").DataTable({
        "ajax": {
          "url": "../ajax_data_rates_sadmin.php",
          "type": "POST",
          "data" : myKeyVals
        }
      });

      function buildSearchData() {
        var dta = {
          'start_date': $('#start_date').val(),
          'end_date': $('#end_date').val(),
          'driverid': $('#driverid').val(),
          'costumertypeid': $('#costumertypeid').val(),
          'paymenttypeid': $('#paymenttypeid').val(),
        };
        return dta;
      }
      // $('#start_date, #end_date, #driverid, #costumertypeid, #paymenttypeid').on(  'keyup change',function(){
      //   table1.ajax.reload();
      // });
      jQuery("body").on("click", ".btn-delete", function() {
        var confrim = confirm("Er du sikker? Vil du slette denne takst permanent ?");
        if (confrim) {
          window.location.href = $(this).attr('data-href');
        }
      });

      $('.create_rate').on('click', function() {
        var startprice = $('input[name="startprice"]');
        var waitprice = $('input[name="waitprice"]');
        var kmprice = $('input[name="kmprice"]');
        var halfhourprice = $('input[name="halfhourprice"]');
        var minimumprice = $('input[name="minimumprice"]');

        if (startprice.val().length == 0) {
          startprice.addClass('error');
          startprice.focus();
          return false;
        }

        if (waitprice.val().length == 0) {
          waitprice.addClass('error');
          waitprice.focus();
          return false;
        }

        if (kmprice.val().length == 0) {
          kmprice.addClass('error');
          kmprice.focus();
          return false;
        }
        if (halfhourprice.val().length == 0) {
          halfhourprice.addClass('error');
          halfhourprice.focus();
          return false;
        }
        if (minimumprice.val().length == 0) {
          minimumprice.addClass('error');
          minimumprice.focus();
          return false;
        }
      });
    });
  </script>
</body>

</html>
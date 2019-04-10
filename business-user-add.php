<?php
session_start();
require_once 'dbconnect.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
  custom_redirect("Location: index.php");
  echo "<meta http-equiv='refresh' content='0; url=index.php' />";
  exit;
}

if (count($_POST) > 0) {
  // print_r($_POST); die;
  if (!empty($_POST['password'])) {
    $_POST['password'] = md5($_POST['password']);
  } else {
    unset($_POST['password']);
  }
  // print_r($_POST); die;
  $_POST['business_id'] = $_GET['bid'];
  $target_dir = "dist/img/user/";
  $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  // Check if image file is a actual image or fake image          
  $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
  if ($check !== false) {
    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
      $_POST['profile_pic'] = $_FILES["profile_pic"]["name"];
    } else {
      // echo "Sorry, there was an error uploading your file.";
      custom_redirect("Location: " . HOME_URL . "/business-list.php?error=1&task=add");
    }
  } else {
    // echo "File is not an image.";
    custom_redirect("Location: " . HOME_URL . "/business-list.php?error=1&task=add");
  }
  if (@$_POST['is_admin'] == 'on') {
    $_POST['userlevel'] = 2;
  } else {
    $_POST['userlevel'] = 3;
  }
  unset($_POST['is_admin']);
  $sql_insert = qry_insert('user', $_POST);
  $do_insert  = mysqli_query($conn, $sql_insert);

  if ($do_insert) {
    custom_redirect("Location: " . HOME_URL . "/business-view.php?bid=" . $_GET['bid'] . "&success=1");
  } else {
    custom_redirect("Location: " . HOME_URL . "/business-view.php?bid=" . $_GET['bid'] . "&error=1&task=edit");
    die();
  }
}

$bid = @$_GET['bid'];
$sql_select = "select * from business where business_id = " . $bid;
$isData  = mysqli_query($conn, $sql_select);
if ($isData) {
  $editBData = mysqli_fetch_array($isData);
  // print_r($editData['business_name']); die;
} else {
  custom_redirect("Location: " . HOME_URL . "/business-list.php?error=1&task=view");
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

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <?php include('sidebar-admin-business.php'); ?>
    <!-- /.sidebar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>Business User Add</h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Hjem</a></li>
          <li class="active">
            <a href="business-view.php?bid=<?php echo $editBData['business_id']; ?>">
              <?php echo $editBData['business_name']; ?>
            </a>
          </li>
          <li class="active">User</li>
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
            <!-- View business details view -->

            <!-- Add/Edit view -->
            <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="business_name" value="<?php echo @$editBData['business_name']; ?>" placeholder="Business Name" readonly disabled>
                  <input type="hidden" class="form-control" name="business_id" value="<?php echo @$editBData['business_id']; ?>">
                </div>
                <label class="col-sm-2 control-label">Business Name</label>
              </div>
              <div class="form-group">
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="fname" placeholder="First Name">
                </div>
                <label class="col-sm-2 control-label">First Name</label>
              </div>
              <div class="form-group">
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="lname" placeholder="Last Name">
                </div>
                <label class="col-sm-2 control-label">Last Name</label>
              </div>
              <div class="form-group">
                <div class="col-sm-10">
                  <input type="email" class="form-control" name="email" placeholder="Email">
                </div>
                <label class="col-sm-2 control-label">Email</label>
              </div>
              <div class="form-group">
                <div class="col-sm-10">
                  <textarea class="form-control" name="address"></textarea>
                </div>
                <label class="col-sm-2 control-label">Address</label>
              </div>
              <div class="form-group">
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="phone_no" placeholder="phone number">
                </div>
                <label class="col-sm-2 control-label">Phone No</label>
              </div>
              <div class="form-group">
                <div class="col-sm-10">
                  <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <label class="col-sm-2 control-label">Password</label>
              </div>
              <div class="form-group">
                <div class="col-sm-10">
                  <input type="file" class="form-control" name="profile_pic">
                </div>
                <label class="col-sm-2 control-label">Profile</label>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="is_admin"> Adminstrator rettigheder for denne bruger? <a href="#"></a>
                    </label>
                  </div>
                </div>
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
      $('.create_user').on('click', function() {
        var fname = $('input[name="fname"]');
        var lname = $('input[name="lname"]');
        var email = $('input[name="email"]');
        var address = $('textarea[name="address"]');
        var phone_no = $('input[name="phone_no"]');
        var password = $('input[name="password"]');
        var profile_pic = $('input[name="profile_pic"]');

        if (fname.val().length == 0) {
          fname.addClass('error');
          fname.focus();
          return false;
        }
        if (lname.val().length == 0) {
          lname.addClass('error');
          lname.focus();
          return false;
        }
        if (email.val().length == 0) {
          email.addClass('error');
          email.focus();
          return false;
        }
        if (address.val() == '') {
          address.addClass('error');
          address.focus();
          return false;
        }
        if (phone_no.val().length == 0) {
          phone_no.addClass('error');
          phone_no.focus();
          return false;
        }
        if (password.val().length == 0) {
          password.addClass('error');
          password.focus();
          return false;
        }
        if (profile_pic.val().length == 0) {
          profile_pic.addClass('error');
          profile_pic.focus();
          return false;
        }
      });
    });
  </script>
</body>

</html>
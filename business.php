<?php
  session_start();
  require_once 'dbconnect.php';
  // if session is not set this will redirect to login page
  if( !isset($_SESSION['user']) ) {
    custom_redirect("Location: index.php");
    echo "<meta http-equiv='refresh' content='0; url=index.php' />";
    exit;
  }
  $action = @$_GET['action'];
  $method = @$_GET['method'];

  if( count( $_POST ) > 0 ){
    if( $method == 'edit' ){
      $id =  $_POST['business_id'];
      unset($_POST['business_id']);
      $target_dir = "dist/img/user/";
      if($_FILES["profile_pic"]["name"] != ''){
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]); 
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image          
        $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
        if($check !== false) {          
          if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $_POST['profile_pic'] = $_FILES["profile_pic"]["name"];
          } else {
            // echo "Sorry, there was an error uploading your file.";
            custom_redirect("Location: ".HOME_URL."/business.php?error=1&task=add");
          }
        } else {
          // echo "File is not an image.";
          custom_redirect("Location: ".HOME_URL."/business.php?error=1&task=add");
        }
      }else{
        $_POST['profile_pic'] = $_POST['old_profile_pic'];
      }
      unset($_POST['old_profile_pic']);
      $_POST['updated_at'] = date('Y-m-d H:i:s'); 
      $sql_insert = qry_update('business',$_POST,'WHERE business_id = '.$id);
    }
    else if( $method == 'add' ){
      if( !empty($_POST['password']) ){
        $_POST['password'] = md5($_POST['password']);
      }else{
        unset($_POST['password']);
      }
      $target_dir = "dist/img/user/";
      $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      // Check if image file is a actual image or fake image          
      $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
      if($check !== false) {          
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) { 
          $_POST['profile_pic'] = $_FILES["profile_pic"]["name"];
        } else {
          // echo "Sorry, there was an error uploading your file.";
          custom_redirect("Location: ".HOME_URL."/business.php?error=1&task=add");
        }
      } else {
        // echo "File is not an image.";
        custom_redirect("Location: ".HOME_URL."/business.php?error=1&task=add");
      }
      $sql_insert = qry_insert('business',$_POST);
    }
    else if( $method == 'change' ){
      $business_id = @$_POST['business_id'];
      if( !empty($_POST['password']) ){
        $_POST['password'] = md5($_POST['password']);
      }else{
        unset($_POST['password']);
      }
      unset($_POST['repassword']);
      unset($_POST['business_id']);
      $sql_insert = qry_update('business',$_POST,' WHERE business_id = '.$business_id);
    }
    else if( $method == 'delete' ){
      print_r($_POST); die;
      $business_id = @$_POST['business_id'];
      if( !empty($_POST['password']) ){
        $_POST['password'] = md5($_POST['password']);
      }else{
        unset($_POST['password']);
      }
      unset($_POST['repassword']);
      unset($_POST['business_id']);
      $sql_insert = qry_update('business',$_POST,' WHERE business_id = '.$business_id);
    }
    $do_insert  = mysqli_query($conn, $sql_insert);
    if( $do_insert ){
      if( $method == 'add' ){
        custom_redirect("Location: ".HOME_URL."/business.php?success=1");
      }
      else if( $method == 'change' ){
        custom_redirect("Location: ".HOME_URL."/business.php?success=1");
      }
      else if( $method == 'delete' ){
        custom_redirect("Location: ".HOME_URL."/business.php?success=1");
      }else{
        custom_redirect("Location: ".HOME_URL."/business.php?action=edit&method=edit&id=".$id."&success=1");
      }
      die();
    }else{
      custom_redirect("Location: ".HOME_URL."/business.php?error=1&task=add");
      die();
    }
  }
  
  switch ($method) {
    case 'view': 
      $id   = @$_GET['id'];
      $sql_select = "select * from business where business_id = ".$id; 
      $isData  = mysqli_query($conn, $sql_select);
      if($isData){
        $viewData = mysqli_fetch_array($isData);
        // print_r($editData['business_name']); die;
      }else{
        custom_redirect("Location: ".HOME_URL."/business.php?error=1&task=view");
      }
    case 'edit': 
      $id   = @$_GET['id'];
      $sql_select = "select * from business where business_id = ".$id; 
      $isData  = mysqli_query($conn, $sql_select);
      if($isData){
        $editData = mysqli_fetch_array($isData);
        // print_r($editData['business_name']); die;
      }else{ 
        custom_redirect("Location: ".HOME_URL."/business.php?error=1&task=edit");
      }
    case 'change': 
      $id   = @$_GET['id'];
      $sql_select = "select * from business where business_id = ".$id; 
      $isData  = mysqli_query($conn, $sql_select);
      if($isData){
        $changeData = mysqli_fetch_array($isData);
        // print_r($changeData['business_name']); die;
      }else{ 
        custom_redirect("Location: ".HOME_URL."/business.php?error=1&task=edit");
      }
    default:
        break;
  }

  switch ($action) {
    case 'delete':
        $id = $_GET['id'];
        $sql_delete = "UPDATE `business` SET `is_deleted` = '1' WHERE business_id =".$id; 
        $do_del = mysqli_query($conn, $sql_delete);
        if( $do_del ){
            $redirect = custom_redirect("Location: ".HOME_URL."/business.php?success=1&task=delete");
            die();
        }else{
            $redirect = custom_redirect("Location: ".HOME_URL."/business.php?error=1&task=delete");
            die();
        }
        break;
    case 'edit': 
    case 'change': 
    default:
        break;
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
  </header>  <!-- Left side column. contains the logo and sidebar -->

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
      <ul class="sidebar-menu">
        <li class="header">Kontrolpanel</li>
        <li>
          <a href="business.php?method=add"><i class="fa fa-book"></i> <span>Create Business</span></a>
        </li>
        <li>
          <a href="business.php"><i class="fa fa-gears"></i> <span>Business</span></a>
        </li>
      </ul>
    </section>
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
	  <?php if(@$_GET['success']): ?>
		  <?php if(@$_GET['task'] == 'delete'): ?>
			<p class="alert alert-success">Slettet !</p>
		  <?php else:?>
		  <p class="alert alert-success">Gemt !</p>
		  <?php endif;?>
	  <?php endif;?>

	  <?php if(@$_GET['error']): ?>
		 <?php if(@$_GET['task'] == 'delete'): ?>
			<p class="alert alert-danger">Kunne ikke slette !</p>
		  <?php else:?>
		   <p class="alert alert-danger">Kunne ikke gemme !</p>
		  <?php endif;?>		
	  <?php endif;?>

      <div class="row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="<?php if( !$method || $method == 'index'){ echo 'active'; }; ?>">
                <a href="<?php echo HOME_URL;?>/business.php?method=index" >Business List</a>
              </li>
              <li  class="<?php if($method == 'add'){ echo 'active'; }; ?>">
                <a href="<?php echo HOME_URL;?>/business.php?method=add">Create Business</a>
              </li>
              <?php if(@$_GET['id']):?>
              <li  class="<?php if($method == 'change'){ echo 'active'; }; ?>">
                <a href="<?php echo HOME_URL;?>/business.php?method=change&action=change&id=<?php echo $_GET['id'];?> ">Change Password</a>
              </li>
              <li  class="<?php if($method == 'edit'){ echo 'active'; }; ?>">
                <a href="<?php echo HOME_URL;?>/business.php?method=edit&action=edit&id=<?php echo $_GET['id'];?> ">Edit business</a>
              </li>
              <li  class="<?php if($method == 'view'){ echo 'active'; }; ?>">
                <a href="<?php echo HOME_URL;?>/business.php?method=view&action=view&id=<?php echo $_GET['id'];?> ">View Business</a>
              </li>
              <?php endif;?>
            </ul>

            <div class="tab-content">
              <div class="active tab-pane" id="user">
                <!-- Default view -->
                <?php if( !$method || $method == 'index'): ?>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Business Id</th>
                        <th>Business Name</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Create Date</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>                    
                    </tbody>
                  </table>
                <?php endif;?>

                <!-- View details view -->
                <?php if( $method && $method == 'view'): ?>
                  <h2>Business Details</h2>
                  <hr>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="40%">Title</th>
                        <th>Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th>Business Name</th>
                        <td><?php echo $viewData['business_name']; ?></td> 
                      </tr>
                      <tr>
                        <th>First Name</th>
                        <td><?php echo $viewData['fname']; ?></td>
                      </tr>
                      <tr>
                        <th>Last Name</th>
                        <td><?php echo $viewData['lname']; ?></td>
                      </tr>
                      <tr>
                        <th>E-mail</th>
                        <td><?php echo $viewData['email']; ?></td>
                      </tr> 
                      <tr>
                        <th>Address</th>
                        <td><?php echo $viewData['address']; ?></td>
                      </tr>
                      <tr>
                        <th>Phone Number</th>
                        <td><?php echo $viewData['phone_no']; ?></td>
                      </tr> 
                      <tr>
                        <th>Profile</th>
                        <td><img src="<?php echo 'dist/img/user/'.$viewData['profile_pic']; ?>" height="200"></td>
                      </tr>
                    </tbody>
                  </table> 
                  <h2>Users List</h2>
                  <hr>
                  <table id="BusinessUsers" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>User Id</th>
                        <th>Navn</th>
                        <th>Email</th>
                        <th>Mobil</th>
                        <th>Bruger Niveau</th> 
                      </tr>
                    </thead>
                    <tbody>                    
                    </tbody>
                  </table>
                <?php endif;?>

                <!-- Add/Edit view -->
                <?php if( $method && $method == 'add' || $method == 'edit'): ?>
                  <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
                    <?php if(  $method == 'edit'): ?>
                      <div class="form-group">
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="business_id" value="<?php echo @$editData['business_id'];?>" readonly>
                        </div>
                        <label class="col-sm-2 control-label">Business Id</label>
                      </div>
                    <?php endif;?>
                    <div class="form-group">
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="business_name" value="<?php echo @$editData['business_name'];?>" placeholder="Business Name">
                      </div>
                      <label class="col-sm-2 control-label">Business Name</label>
                    </div> 
                    <div class="form-group">
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="fname" value="<?php echo @$editData['fname'];?>" placeholder="First Name">
                      </div>
                      <label class="col-sm-2 control-label">First Name</label>
                    </div> 
                    <div class="form-group">
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="lname" value="<?php echo @$editData['lname'];?>" placeholder="Last Name">
                      </div>
                      <label class="col-sm-2 control-label">Last Name</label>
                    </div> 
                    <div class="form-group">
                      <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" value="<?php echo @$editData['email'];?>" placeholder="Email">
                      </div>
                      <label class="col-sm-2 control-label">Email</label>
                    </div> 
                    <div class="form-group">
                      <div class="col-sm-10">
                        <textarea class="form-control" name="address"><?php echo @$editData['address'];?></textarea> 
                      </div>
                      <label class="col-sm-2 control-label">Address</label>
                    </div> 
                    <div class="form-group">
                      <div class="col-sm-10">
                        <input type="number" class="form-control" name="phone_no" value="<?php echo @$editData['phone_no'];?>" placeholder="phone number">
                      </div>
                      <label class="col-sm-2 control-label">Phone No</label>
                    </div>
                    <?php if(  $method == 'add'): ?>
                    <div class="form-group">
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="password" value="<?php echo @$password;?>" placeholder="Password">
                      </div>
                      <label class="col-sm-2 control-label">Password</label>
                    </div> 
                    <?php endif;?>
                    <?php if(  $method == 'edit'): ?>
                    <div class="form-group">
                      <div class="col-sm-10"> 
                        <img src="<?php echo 'dist/img/user/'.$editData['profile_pic']; ?>" height="100">
                        <input type="hidden" name="old_profile_pic" value="<?php echo $editData['profile_pic']; ?>">
                      </div>
                      <label class="col-sm-2 control-label">Old Profile</label>
                    </div> 
                    <?php endif;?>
                    <div class="form-group">
                      <div class="col-sm-10">
                        <input type="file" class="form-control" name="profile_pic">
                      </div>
                      <label class="col-sm-2 control-label">Profile</label>
                    </div> 
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-danger create_user">Gem</button>
                      </div>
                    </div>
                  </form>
                <?php endif;?>

                <!-- Change password view -->
                <?php if( $method && $method == 'change'): ?>
                  <form class="form-horizontal" action="" method="POST">
                     <?php if($method == 'change'): ?>
                    <div class="form-group">
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="business_id" value="<?php echo @$changeData['business_id'];?>" readonly>
                      </div>
                      <label class="col-sm-2 control-label">Business Id</label>
                    </div>
                    <?php endif;?>
                    <div class="form-group">
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="business_name" value="<?php echo @$changeData['business_name'];?>"  placeholder="Business Name">
                      </div>
                      <label for="inputName" class="col-sm-2 control-label">Business Navn</label>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-10">
                        <input type="password" class="form-control"  name="password" placeholder="Password">
                      </div>
                      <label for="password" class="col-sm-2 control-label">Adgangskode</label>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-10">
                        <input type="password" class="form-control" name="repassword" placeholder="Retype password">
                      </div>
                      <label for="retype password" class="col-sm-2 control-label">Genindtast Adgangskode</label>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-danger change-password">Skift Adgangskode</button>
                      </div>
                    </div>
                  </form>   
                <?php endif;?>
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
  $(function () {
    var table1 = $("#example1").DataTable({
      "ajax": { 
        "url": "ajax_data_business.php",
        "type": "POST", 
        // "data" : buildSearchData
      },
      "aaSorting": [],
      columnDefs: [ { orderable: false, targets: [4,5] } ]
    });

    var BusinessId = '<?php echo $viewData['business_id']; ?>';
    var myKeyVals = { BusinessId:BusinessId };
    var table1 = $("#BusinessUsers").DataTable({
      "ajax": { 
        "url": "ajax_data_users_of_business.php",
        "type": "POST", 
        "data" : myKeyVals
      },
      "aaSorting": [],
      columnDefs: [ { orderable: false, targets: [] } ]
    });

    // $('#start_date, #end_date, #driverid, #costumertypeid, #paymenttypeid').on(  'keyup change',function(){
    //   table1.ajax.reload();
    // });
    jQuery("body").on("click",".btn-delete", function(){
      var confrim = confirm("Er du sikker? Vil du slette denne bruger permanent? ture udført af denne bruger vil også blive slettet ?");
      if( confrim ){
        window.location.href = $(this).attr('data-href');
      }
    });

    $('.create_user').on('click',function(){
      var business_name        = $('input[name="business_name"]');
      var fname        = $('input[name="fname"]');
      var lname        = $('input[name="lname"]');
      var email        = $('input[name="email"]');
      var address      = $('textarea[name="address"]');
      var phone_no     = $('input[name="phone_no"]');
      var password     = $('input[name="password"]');
      var profile_pic  = $('input[name="profile_pic"]');

      if(business_name.val().length == 0){
        business_name.addClass('error');
        business_name.focus();
        return false;
      }
      if(fname.val().length == 0){
        fname.addClass('error');
        fname.focus();
        return false;
      }
      if(lname.val().length == 0){
        lname.addClass('error');
        lname.focus();
        return false;
      }
      if(email.val().length == 0){
        email.addClass('error');
        email.focus();
        return false;
      }
      if(address.val() == ''){
        address.addClass('error');
        address.focus();
        return false;
      }
      if(phone_no.val().length == 0){
        phone_no.addClass('error');
        phone_no.focus();
        return false;
      }
      if(password.val().length == 0){
        password.addClass('error');
        password.focus();
        return false;
      }
      if(profile_pic.val().length == 0){
        profile_pic.addClass('error');
        profile_pic.focus();
        return false;
      }
    });
    
    $('.change-password').on('click',function(){
      var business_name = $('input[name="business_name"]');
      var password      = $('input[name="password"]');
      var repassword    = $('input[name="repassword"]');

      if(business_name.val().length == 0){
        business_name.addClass('error');
        business_name.focus();
        return false;
      }
      if(password.val().length == 0){
        password.addClass('error');
        password.focus();
        return false;
      }
      if(repassword.val().length == 0){
        repassword.addClass('error');
        repassword.focus();
        return false;
      }
      if( password.val() != repassword.val() ){
        repassword.addClass('error');
        repassword.focus();
        return false;
      }
    });
  });
</script>
</body>
</html>
<?php
ob_start();
session_start();
require_once 'dbconnect.php';

// it will never let you open index(login) page if session is set
if (isset($_SESSION['user']) != "") {
  header("Location: home.php");
  exit;
}
$error = false;
if (isset($_POST['btn-login'])) {
  // prevent sql injections/ clear user invalid inputs
  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);
  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);
  // prevent sql injections / clear user invalid inputs

  if (empty($email)) {
    $error = true;
    $emailError = "Please enter your email address.";
    $_SESSION['Error'] = $emailError;
    custom_redirect("Location: " . HOME_URL . "/index.php?error=1");
    die();
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = true;
    $emailError = "Indtast venligst din emailadresse";
    $_SESSION['Error'] = $emailError;
    custom_redirect("Location: " . HOME_URL . "/index.php?error=1");
    die();
  }

  if (empty($pass)) {
    $error = true;
    $passError = "Indtast venligst din adgangskode";
  }

  // if there's no error, continue to login
  if (!$error) {
    $password = md5($pass); // password hashing using md5
    $res = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    $row = mysqli_fetch_array($res);
    $count = mysqli_num_rows($res); // if uname/pass correct it returns must be 1 row
    if ($count == 1 && $row['password'] == $password && $row['userlevel'] == 1) {
      $_SESSION['user'] = $row['userid'];
      $_SESSION['username'] = $row['fname'] . ' ' . $row['lname'];
      header("Location: business-list.php");
    } else if ($count == 1 && $row['password'] == $password && $row['userlevel'] == 2) {
      $BusinessId = $row['business_id'];
      $bres = mysqli_query($conn, "SELECT * FROM business WHERE business_id='$BusinessId'");
      $brow = mysqli_fetch_array($bres);
      $bcount = mysqli_num_rows($bres);
      if ($bcount == 1) {
        $_SESSION['user'] = $row['userid'];
        $_SESSION['username'] = $row['fname'] . ' ' . $row['lname'];
        if ($row['profile_pic'] != '')
          $_SESSION['ProfilePic'] = "dist/img/user/" . $row['profile_pic'];
        else
          $_SESSION['ProfilePic'] = "dist/img/dummy/user-dummy.png";
        $_SESSION['BusinessName'] = $brow['business_name'];
        $_SESSION['BusinessId'] = $brow['business_id'];
        header("Location: home.php");
      } else {
        $errMSG = "Business not assigned.";
        $_SESSION['Error'] = $errMSG;
        custom_redirect("Location: " . HOME_URL . "/index.php?error=1");
        die();
      }
    } else {
      $errMSG = "Forkert adgangskode eller mail, prøv igen!";
      $_SESSION['Error'] = $errMSG;
      custom_redirect("Location: " . HOME_URL . "/index.php?error=1");
      die();
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>First-Transport | Log in</title>
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
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="home.php"><b>First-Transport</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">Login for at begynde din session</p>

      <?php if (@$_GET['error'] && isset($_SESSION['Error'])) : ?>
        <p class="alert alert-danger"><?php echo $_SESSION['Error']; ?></p>
      <?php
      session_unset();
      session_destroy();
      endif;
      ?>
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="form-group has-feedback">
          <input name="email" type="email" class="form-control" placeholder="Email" required>
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" name="pass" class="form-control" placeholder="Password" required>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
            </div>
          </div>
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat" name="btn-login">Log ind</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery 2.2.3 -->
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <script>
    $(function() {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });
    });
  </script>
</body>

</html>
<?php ob_end_flush(); ?>
<?php
ob_start();
session_start();
require_once 'dbconnect.php';

// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
  header('location:index.php');
  exit;
}
// select loggedin users detail
$res = mysqli_query($conn, "SELECT * FROM user WHERE userid=" . $_SESSION['user']);
$userRow = mysqli_fetch_array($res);

function js_str($s)
{
  return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}
function js_array($array)
{
  $temp = array_map('js_str', $array);
  return '[' . implode(',', $temp) . ']';
}
//trips for the last 12 months, there is 2 types of trips (private, business)
$trips_2_types_sql = "SELECT * FROM trip where business_id = ".$_SESSION['BusinessId']." AND tdate > DATE_SUB(now(), INTERVAL 12 MONTH)";
$trips_2_types = mysqli_query($conn,  $trips_2_types_sql);
$trips_results = array();
while ($row = mysqli_fetch_assoc($trips_2_types)) {
  $trips_results[] = $row;
}
$text_months = array(
  "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
);
$months = array();
$m = 0;
$amount_private_months = array();
$income_private_months = array();
$amount_business_months = array();
$income_business_months = array();
foreach ($text_months as $text_month) {
  $months[$text_month] = array();
  //Set defaul values for months
  $amount_business_months[$m] = 0;
  $income_business_months[$m] = 0;
  $amount_private_months[$m] = 0;
  $income_private_months[$m] = 0;
  $m++;
}

if (count($trips_results)) {
  foreach ($trips_results as $trips_2_types_result) {
    $tdate = $trips_2_types_result['tdate'];
    $tmonth = date('F', strtotime($tdate));

    //Amount Private
    if ($trips_2_types_result['customertype'] == 0) {
      $months[$tmonth]['amount_values']['private'][]          = $trips_2_types_result['tripprice'];
      $months[$tmonth]['income_values']['private'][]          = $trips_2_types_result['tripid'];
    }
    //Amount Business
    if ($trips_2_types_result['customertype'] == 1) {
      $months[$tmonth]['amount_values']['business'][]        = $trips_2_types_result['tripprice'];
      $months[$tmonth]['income_values']['business'][]        = $trips_2_types_result['tripid'];
    }
  }
  $amount_private_months = array();
  $income_private_months = array();
  $amount = 0;
  $i = 0;
  foreach ($months as $month => $month_value) {
    $amount = 0;
    if (isset($month_value['amount_values']['private']) && count($month_value['amount_values']['private']) > 0) {
      foreach ($month_value['amount_values']['private'] as $amount_value) {
        $amount += (int)$amount_value;
      }
      $income_private_months[$i] = count($month_value['income_values']['private']);
    } else {
      $income_private_months[$i] = 0;
    }
    $amount_private_months[$i] = $amount;
    $i++;
  }
  //Bussiness
  $amount_business_months = array();
  $income_business_months = array();
  $amount = 0;
  $i = 0;
  foreach ($months as $month => $month_value) {
    $amount = 0;
    if (isset($month_value['amount_values']['business']) && count($month_value['amount_values']['business']) > 0) {
      foreach ($month_value['amount_values']['business'] as $amount_value) {
        $amount += (int)$amount_value;
      }
      $income_business_months[$i] = count($month_value['income_values']['business']);
    } else {
      $income_business_months[$i] = 0;
    }
    $amount_business_months[$i] = $amount;
    $i++;
  }
}
// chart 3 and chart 4;
$sql   = "SELECT * FROM user";
$res   = mysqli_query($conn, $sql);
$text_users  = array();
$users = array();
$amount_private_users = array();
$income_private_users = array();
$amount_business_users = array();
$income_business_users = array();
$u = 0;
while ($row = mysqli_fetch_assoc($res)) {
  $text_users[$row['userid']] = $row['name'];
  $amount_private_users[$u] = 0;
  $income_private_users[$u] = 0;
  $amount_business_users[$u] = 0;
  $income_business_users[$u] = 0;
  $u++;
}
// $today = '2017-01-30';
$today = date('Y-m-d');
$sql1 = "SELECT * FROM trip where business_id = ".$_SESSION['BusinessId']." AND tdate = '$today'";
$res = mysqli_query($conn, $sql1);
$trips_results = array();
while ($row = mysqli_fetch_assoc($res)) {
  $trips_results[] = $row;
}
$label_users = $text_users;
if (count($trips_results)) {
  $label_users = array();
  foreach ($trips_results as $trips_2_types_result) {
    $tdriverid = $trips_2_types_result['tdriverid'];

    //Amount Private
    if ($trips_2_types_result['customertype'] == 0) {
      $users[$tdriverid]['amount_values']['private'][]          = $trips_2_types_result['tripid'];
      $users[$tdriverid]['income_values']['private'][]          = $trips_2_types_result['tripprice'];
    }
    //Amount Business
    if ($trips_2_types_result['customertype'] == 1) {
      $users[$tdriverid]['amount_values']['business'][]        = $trips_2_types_result['tripid'];
      $users[$tdriverid]['income_values']['business'][]        = $trips_2_types_result['tripprice'];
    }
    $label_users[$tdriverid] =  $text_users[$tdriverid];
  }

  $amount_private_users = array();
  $income_private_users = array();
  $amount = 0;
  $i = 0;
  foreach ($users as $user_id => $user) {
    $income = 0;
    if (isset($user['income_values']['private']) && count($user['income_values']['private']) > 0) {
      foreach ($user['income_values']['private'] as $income_value) {
        $income += (int)$income_value;
      }
    }
    $income_private_users[$user_id]   = $income;
    if (isset($user['amount_values']['private'])) {
      $amount_private_users[$user_id]   = count($user['amount_values']['private']);
    }
    $i++;
  }

  //Bussiness
  $amount_business_users = array();
  $income_business_users = array();
  $amount = 0;
  $i = 0;
  foreach ($users as $user_id => $user) {
    $income = 0;
    if (isset($user['income_values']['business']) &&  count($user['income_values']['business']) > 0) {
      foreach ($user['income_values']['business'] as $amount_value) {
        $income += (int)$income_value;
      }
    }
    $income_business_users[$user_id] = $income;
    if (isset($user['income_values']['business'])) {
      $amount_business_users[$user_id] = count($user['income_values']['business']);
    } else {
      $amount_business_users[$user_id] = 0;
    }
    $i++;
  }
}
ksort($amount_private_users);
ksort($income_private_users);
ksort($amount_business_users);
ksort($income_business_users);
// echo '<pre>';
// print_r($amount_private_users);
//  echo '</pre>';
// print_r($income_private_users);
// print_r($amount_business_users);
// print_r($income_business_users);
//die();
//chart 4
$sql = "SELECT * FROM payment";
$res = mysqli_query($conn, $sql);
$payments = array();
while ($row = mysqli_fetch_assoc($res)) {
  $payments[$row['paymentid']] = $row['paymenttype'];
}
$payment_private_info = $payment_info = $new_payments = array();
$total_erhver = $total_private = 0;
foreach ($payments as $paymentid => $paymenttype) {
  //$payments[$paymentid]['income'] = array();

  foreach ($trips_results as $trips_result) {
    if ($trips_result['paymenttype'] == $paymentid) {
      $new_payments[$paymentid]['income'][]     = $trips_result;
      $new_payments[$paymentid]['count_income'] = count($new_payments[$paymentid]['income']);
      if ($trips_result['customertype'] == 1) {
        $payment_info[$paymentid]['paymenttype'] = $paymenttype;
        if (empty($payment_info[$paymentid]['tripprice'])) {
          $payment_info[$paymentid]['tripprice'] = $trips_result['tripprice'];
        } else {
          $payment_info[$paymentid]['tripprice'] += $trips_result['tripprice'];
        }
        $total_erhver += $trips_result['tripprice'];
      } else {
        $payment_private_info[$paymentid]['paymenttype'] = $paymenttype;
        if (empty($payment_private_info[$paymentid]['tripprice'])) {
          $payment_private_info[$paymentid]['tripprice'] = $trips_result['tripprice'];
        } else {
          $payment_private_info[$paymentid]['tripprice'] += $trips_result['tripprice'];
        }
        $total_private += $trips_result['tripprice'];
      }
    }
  }
}
//info box
$new_payments = array();
foreach ($payments as $paymentid => $paymenttype) {
  //$payments[$paymentid]['income'] = array();
  foreach ($trips_results as $trips_result) {
    $customertype = $trips_result['customertype'];

    if ($customertype == 0) {
      if ($trips_result['paymenttype'] == $paymentid) {
        $new_payments[$paymentid]['private']['income'][] = $trips_result;
      }
    } else {
      if ($trips_result['paymenttype'] == $paymentid) {
        $new_payments[$paymentid]['business']['income'][] = $trips_result;
      }
    }
  }
}

$info_payments = array();
$cash_business = 0;
$cash_private  = 0;
$credit_business = 0;
$credit_private = 0;
foreach ($new_payments as $paymentid => $new_payment) {
  //cash
  if ($paymentid == 1) {
    if (isset($new_payments[$paymentid]['business']['income'])) {

      foreach ($new_payments[$paymentid]['business']['income'] as $bi) {
        $cash_business += (int)$bi['tripprice'];
      }
    }
    if (isset($new_payments[$paymentid]['private']['income'])) {

      foreach ($new_payments[$paymentid]['private']['income'] as $pi) {
        $cash_private += (int)$pi['tripprice'];
      }
    }
  }

  //card
  if ($paymentid == 2) {
    if (isset($new_payments[$paymentid]['business']['income'])) {

      foreach ($new_payments[$paymentid]['business']['income'] as $bi1) {
        $credit_business += (int)$bi1['tripprice'];
      }
    }
    if (isset($new_payments[$paymentid]['private']['income'])) {

      foreach ($new_payments[$paymentid]['private']['income'] as $pi1) {
        $credit_private += (int)$pi1['tripprice'];
      }
    }
  }
}

$data_chart4 = array();
$payment_color = array(
  1 => '#f56954',
  2 => '#00a65a',
);
foreach ($payments as $paymentid => $paymenttype) {
  //Erhver
  if ($paymentid == 1) {

    $data_chart4[1] = '
      {
        value: "' . $total_erhver . '",
        color: "' . $payment_color[$paymentid] . '",
        highlight: "' . $payment_color[$paymentid] . '",
        label: "Erhver"
      }
     ';
  }
  //Private
  if ($paymentid == 2) {
    $data_chart4[2] = '
      {
        value: "' . $total_private . '",
        color: "' . $payment_color[$paymentid] . '",
        highlight: "' . $payment_color[$paymentid] . '",
        label: "Privat"
      }
     ';
  }

  //      $data_chart4[] = '
  //       {
  //         value: '.count($new_payments[$paymentid]['income']).',
  //         color: "'.$payment_color[$paymentid].'",
  //         highlight: "'.$payment_color[$paymentid].'",
  //         label: "'.$paymenttype.'"
  //       }
  //      ';
}
$data_chart4 = implode(',', $data_chart4);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>First-Transport | Skrivebord</title>
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
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

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
        <span class="logo-lg"><?php echo $_SESSION['BusinessName']; ?></span>
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
    </header> <!-- Left side column. contains the logo and sidebar -->
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
          <li class="active"><a href="home.php"><i class="fa fa-book"></i> <span>Skrivebord</span></a></li>
          <li><a href="ture.php"><i class="fa fa-cab"></i> <span>Kørsel/Ture</span></a></li>
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
          Skrivebord
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Hjem</a></li>
          <li class="active">Skrivebord</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-6">
            <!-- BAR CHART -->
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Indkomst for de sidste 12 måneder, Privat og Erhverv</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <div class="box-body">
                <div class="chart">
                  <canvas id="areaChart" style="height:230px"></canvas>
                </div>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- BAR CHART -->
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Dagens ture, Privat og Erhverv</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <div class="box-body">
                <div class="chart">
                  <canvas id="barChart" style="height:230px"></canvas>
                </div>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
          <!-- /.col (LEFT) -->
          <div class="col-md-6">
            <!-- BAR CHART -->
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Antal ture for de sidste 12 måneder, Privat og Erhverv</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <div class="box-body">
                <div class="chart">
                  <canvas id="areaChartIncome" style="height:230px"></canvas>
                </div>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- DONUT CHART -->
            <div class="box box-danger">
              <div class="box-header with-border">
                <h3 class="box-title">Dagens indkomst, Privat og Erhverv</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <div class="box-body">
                <canvas id="pieChart" style="height:250px"></canvas>
              </div>
              <!-- /.box-body -->

              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">Indkomst Privat</div>
                      <div class="panel-body">
                        <?php
                        $total_tripprice_pv = 0;
                        foreach ($payment_private_info as $value) :
                          ?>
                          <div class="row">
                            <label class="col-md-6"><?php echo $value['paymenttype']; ?></label>
                            <div class="col-md-6"><?php echo $value['tripprice']; ?></div>
                          </div>
                          <?php
                          $total_tripprice_pv += $value['tripprice'];
                        endforeach; ?>
                        <div class="row">
                          <label class="col-md-6">Total</label>
                          <div class="col-md-6"><?php echo $total_tripprice_pv; ?></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">Indkomst Erhverv</div>
                      <div class="panel-body">
                        <?php
                        $total_tripprice = 0;
                        foreach ($payment_info as $value) :
                          ?>
                          <div class="row">
                            <label class="col-md-6"><?php echo $value['paymenttype']; ?></label>
                            <div class="col-md-6"><?php echo $value['tripprice']; ?></div>
                          </div>
                          <?php
                          $total_tripprice += $value['tripprice'];
                        endforeach; ?>

                        <div class="row">
                          <label class="col-md-6">Total</label>
                          <div class="col-md-6"><?php echo $total_tripprice; ?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box -->
          </div>
          <!-- /.col (RIGHT) -->
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
      <strong>Copyright &copy; 2017 <a href="http://contrasoft.dk">Contrasoft </a>.</strong>
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
  <!-- ChartJS 1.0.1 -->
  <script src="plugins/chartjs/Chart.min.js"></script>
  <!-- FastClick -->
  <script src="plugins/fastclick/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/app.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- page script -->
  <script>
    $(function() {
      /* ChartJS
       * -------
       * Here we will create a few charts using ChartJS
       */

      //--------------
      //- AREA CHART -
      //--------------

      // Get context with jQuery - using jQuery's .get() method.
      var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
      // This will get the first returned node in the jQuery collection.
      var areaChartAmount = new Chart(areaChartCanvas);

      var areaChartDataAmount = {
        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        datasets: [{
            label: "Private",
            fillColor: "rgba(210, 214, 222, 1)",
            strokeColor: "rgba(210, 214, 222, 1)",
            pointColor: "rgba(210, 214, 222, 1)",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: <?php echo js_array($amount_private_months); ?>
          },
          {
            label: "Business",
            fillColor: "rgba(60,141,188,0.9)",
            strokeColor: "rgba(60,141,188,0.8)",
            pointColor: "#3b8bba",
            pointStrokeColor: "rgba(60,141,188,1)",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(60,141,188,1)",
            data: <?php echo js_array($amount_business_months); ?>
          }
        ]
      };
      var areaChartDataIncome = {
        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        datasets: [{
            label: "Private",
            fillColor: "rgba(210, 214, 222, 1)",
            strokeColor: "rgba(210, 214, 222, 1)",
            pointColor: "rgba(210, 214, 222, 1)",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: <?php echo js_array($income_private_months); ?>
          },
          {
            label: "Business",
            fillColor: "rgba(60,141,188,0.9)",
            strokeColor: "rgba(60,141,188,0.8)",
            pointColor: "#3b8bba",
            pointStrokeColor: "rgba(60,141,188,1)",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(60,141,188,1)",
            data: <?php echo js_array($income_business_months); ?>
          }
        ]
      };

      var areaChartDataDrivers = {
        labels: <?php echo js_array($label_users); ?>,
        datasets: [{
            label: "Private",
            fillColor: "rgba(210, 214, 222, 1)",
            strokeColor: "rgba(210, 214, 222, 1)",
            pointColor: "rgba(210, 214, 222, 1)",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: <?php echo js_array($amount_private_users); ?>
          },
          {
            label: "Business",
            fillColor: "rgba(60,141,188,0.9)",
            strokeColor: "rgba(60,141,188,0.8)",
            pointColor: "#3b8bba",
            pointStrokeColor: "rgba(60,141,188,1)",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(60,141,188,1)",
            data: <?php echo js_array($amount_business_users); ?>
          }
        ]
      };

      var areaChartOptions = {
        //Boolean - If we should show the scale at all
        showScale: true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: false,
        //String - Colour of the grid lines
        scaleGridLineColor: "rgba(0,0,0,.05)",
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,
        //Boolean - Whether the line is curved between points
        bezierCurve: true,
        //Number - Tension of the bezier curve between points
        bezierCurveTension: 0.3,
        //Boolean - Whether to show a dot for each point
        pointDot: false,
        //Number - Radius of each point dot in pixels
        pointDotRadius: 4,
        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth: 1,
        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius: 20,
        //Boolean - Whether to show a stroke for datasets
        datasetStroke: true,
        //Number - Pixel width of dataset stroke
        datasetStrokeWidth: 2,
        //Boolean - Whether to fill the dataset with a color
        datasetFill: true,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true
      };
      //Create the line chart
      areaChartAmount.Line(areaChartDataAmount, areaChartOptions);
      var areaChartIncomeCanvas = $("#areaChartIncome").get(0).getContext("2d");
      // This will get the first returned node in the jQuery collection.
      var areaChartIncome = new Chart(areaChartIncomeCanvas);
      areaChartIncome.Line(areaChartDataIncome, areaChartOptions);
      //-------------
      //- LINE CHART -
      //--------------

      //-------------
      //- PIE CHART -
      //-------------
      // Get context with jQuery - using jQuery's .get() method.
      var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
      var pieChart = new Chart(pieChartCanvas);
      var PieData = [<?php echo $data_chart4; ?>];
      var pieOptions = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: "#fff",
        //Number - The width of each segment stroke
        segmentStrokeWidth: 2,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect
        animationEasing: "easeOutBounce",
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
      };
      //Create pie or douhnut chart
      // You can switch between pie and douhnut using the method below.
      pieChart.Doughnut(PieData, pieOptions);

      //-------------
      //- BAR CHART -
      //-------------
      var barChartCanvas = $("#barChart").get(0).getContext("2d");
      var barChart = new Chart(barChartCanvas);
      var barChartData = areaChartDataDrivers;
      barChartData.datasets[1].fillColor = "#00a65a";
      barChartData.datasets[1].strokeColor = "#00a65a";
      barChartData.datasets[1].pointColor = "#00a65a";
      var barChartOptions = {
        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
        scaleBeginAtZero: true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: true,
        //String - Colour of the grid lines
        scaleGridLineColor: "rgba(0,0,0,.05)",
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,
        //Boolean - If there is a stroke on each bar
        barShowStroke: true,
        //Number - Pixel width of the bar stroke
        barStrokeWidth: 2,
        //Number - Spacing between each of the X value sets
        barValueSpacing: 5,
        //Number - Spacing between data sets within X values
        barDatasetSpacing: 1,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        //Boolean - whether to make the chart responsive
        responsive: true,
        maintainAspectRatio: true
      };

      barChartOptions.datasetFill = false;
      barChart.Bar(barChartData, barChartOptions);
    });
  </script>
</body>

</html>
<?php
require_once 'dbconnect.php';

$page = @$_POST['page'];
$bid = @$_POST['bid'];

$sql = "SELECT * FROM trip AS T";
$sql .= " LEFT JOIN user AS U ON T.tdriverid = U.userid ";
//$sql .= " LEFT JOIN levels AS L ON T.customertype = L.Levelid ";
$sql .= " LEFT JOIN payment AS P ON T.paymenttype = P.paymentid "; 
$sql .= " WHERE T.business_id =".$bid." "; 

if (
	@$_POST['start_date'] != ""
	|| @$_POST['end_date'] != ""
	|| @$_POST['driverid'] > -1
	|| @$_POST['costumertypeid'] > -1
	|| @$_POST['paymenttypeid'] > -1
) {
	$sql .= " and 1 = 1 ";
}

if ($_POST['start_date'] != "") {
	$start_date = strtotime($_POST['start_date']);
	$start_date = date('Y-m-d', $start_date);
	$sql .= " AND  T.tdate >= '$start_date'";
}
if ($_POST['end_date'] != "") {
	$end_date = strtotime($_POST['end_date']);
	$end_date = date('Y-m-d', $end_date);
	$sql .= " AND  T.tdate <= '$end_date'";
}

if ($_POST['driverid'] != "" && $_POST['driverid'] > -1) {
	$tdriverid = $_POST['driverid'];
	$sql .= " AND  T.tdriverid = $tdriverid";
}
if ($_POST['costumertypeid'] != "" && $_POST['costumertypeid'] > -1) {
	$customertype = $_POST['costumertypeid'];
	$sql .= " AND  T.customertype = $customertype";
}
if ($_POST['paymenttypeid'] != "" && $_POST['paymenttypeid'] > -1) {
	$paymenttype = $_POST['paymenttypeid'];
	$sql .= " AND  T.paymenttype = $paymenttype";
}
$sql .= " ORDER BY tripid DESC";
// echo $sql; die;

$res = mysqli_query($conn,  $sql);
$trips_results = array(); 
if($res){
	while ($row = mysqli_fetch_assoc($res)) {
		$trips_results[] = $row;
	}
}
$return = array();
$return['data'] = array();


foreach ($trips_results as $key => $trips_result) {
	$tripid 	  = $trips_result['tripid'];
	$customertype = $trips_result['customertype'];

	if ($customertype == 1) {
		$customertypeTxt = 'Bussiness';
	}
	if ($customertype == 0) {
		$customertypeTxt = 'Private';
	}
	$tdate 		  = $trips_result['tdate'];
	$tdate		  = date('d-m-Y', strtotime($tdate));

	$starttime 	  = $trips_result['starttime'];
	$endtime 	  = $trips_result['endtime'];
	$distance 	  = $trips_result['distance'];
	$waittime 	  = $trips_result['waittime'];
	$ratetype 	  = $trips_result['ratetype'];
	$ratesid 	  = $trips_result['ratesid'];

	$tripprice 	  = $trips_result['tripprice'];
	$tripnumber   = $trips_result['tripnumber'];
	$tdriverid 	  = $trips_result['tdriverid'];
	$tdriverid 	  = $trips_result['name'];
	$paymenttype  = $trips_result['paymenttype'];
	$action = '';

	$link_edit 		= HOME_URL . '/' . $page . '.php?action=edit&method=edit&bid=' . $bid.'&id=' . $tripid;
    $link_delete 	= HOME_URL . '/' . $page . '.php?action=delete&&method=delete&bid=' . $bid.'&id=' . $tripid;
    
	$action = '<a href="' . $link_edit . '" class="btn btn-primary btn-circle btn-edit"><i class="fa fa-pencil"></i></a> ';
	$action .= '<a href="javascript:void(0);" data-href="' . $link_delete . '" class="btn btn-danger btn-circle btn-delete"><i class="fa fa-trash"></i></a>';

	$return['data'][] = array(
		$tripid,
		$customertypeTxt,
		$paymenttype,
		$tdate,
		$starttime,
		$endtime,
		$distance,
		$waittime,
		$tripnumber,
		$ratesid,
		$tripprice,
		$tdriverid,
		$action,
	);
}
echo json_encode($return);
die();

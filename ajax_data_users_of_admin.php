<?php
require_once 'dbconnect.php';
$sql = "SELECT * FROM user AS U ";
$sql .= "LEFT JOIN levels AS L ON U.userlevel = L.Levelid ";
$sql .= "where U.business_id = " . $_POST['BusinessId'];
$sql .= " ORDER BY userid DESC ";
$res = mysqli_query($conn,  $sql);
$results = array();

// print_r($_POST['BusinessId']); die;

while ($row = mysqli_fetch_assoc($res)) {
	$results[] = $row;
}
$return = array();
$return['data'] = array();

foreach ($results as $key => $result) {
	$userid 	  	= $result['userid'];
	$name 	  		= $result['fname'] . ' ' . $result['lname'];
	$email   		= $result['email'];
	$phone 	  		= $result['phone_no'];
	$userlevel 	  	= $result['levelname'];

	$link_edit 		= HOME_URL . '/indstillinger.php?action=edit&method=edit&id=' . $userid;
	$link_change 	= HOME_URL . '/indstillinger.php?action=change&method=change&id=' . $userid;
	$link_delete 	= HOME_URL . '/indstillinger.php?action=delete&id=' . $userid;
	$action = '<a href="' . $link_edit . '" class="btn btn-primary btn-circle btn-edit"><i class="fa fa-pencil"></i></a> ';
	$action .= '<a href="' . $link_change . '" class="btn btn-primary btn-circle"><i class="fa fa-unlock"></i></a> ';
	$action .= '<a href="javascript:void(0);" data-href="' . $link_delete . '" class="btn btn-danger btn-circle btn-delete"><i class="fa fa-trash"></i></a>';

	$return['data'][] = array(
		$userid,
		$name,
		$email,
		$phone,
		$userlevel,
		$action,
	);
}
echo json_encode($return);
die();

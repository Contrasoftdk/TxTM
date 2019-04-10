<?php
require_once 'dbconnect.php';
$BusinessId = $_POST['BusinessId'];
$sql = "SELECT * FROM user AS U ";
$sql .= "LEFT JOIN levels AS L ON U.userlevel = L.Levelid ";
$sql .= "where U.business_id = " . $BusinessId;
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

	$link_view 		= HOME_URL . '/business-user-view.php?bid=' . $BusinessId . '&uid=' . $userid;
	$link_edit 		= HOME_URL . '/business-user-edit.php?bid=' . $BusinessId . '&uid=' . $userid;
	$link_change 	= HOME_URL . '/business-user-change.php?bid=' . $BusinessId . '&uid=' . $userid;
	$link_delete 	= HOME_URL . '/business-view.php?action=delete-user&bid=' . $BusinessId . '&uid=' . $userid;

	$action = '<a href="' . $link_view . '" class="btn btn-primary btn-circle btn-view"><i class="fa fa-eye"></i></a> ';
	$action .= '<a href="' . $link_edit . '" class="btn btn-primary btn-circle btn-edit"><i class="fa fa-pencil"></i></a> ';
	$action .= '<a href="' . $link_change . '" class="btn btn-primary btn-circle"><i class="fa fa-unlock"></i></a> ';
	$action .= '<a href="javascript:void(0);" data-href="' . $link_delete . '" class="btn btn-danger btn-circle btn-delete"><i class="fa fa-trash"></i></a>';

	$return['data'][] = array(
		$userid,
		$name,
		$email,
		$phone,
		$userlevel,
		$action
	);
}
echo json_encode($return);
die();

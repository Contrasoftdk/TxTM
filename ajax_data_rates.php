<?php
require_once 'dbconnect.php';
// $sql = "SELECT * FROM rates AS R ORDER BY rateid DESC";
$BusinessId = $_POST['BusinessId'];
$sql = "SELECT * FROM rates where business_id = ".$BusinessId." ORDER BY rateid DESC";
// echo $sql; die;
$res = mysqli_query($conn,  $sql);
$results = array();
while ($row = mysqli_fetch_assoc($res)) {
	$results[] = $row;
}
$return = array();
$return['data'] = array();
foreach ($results as $key => $result) {
	$rateid 	  = $result['rateid'];
	$ratetype 	  = $result['ratetype'];
	$startprice   = $result['startprice'];
	$waitprice 	  = $result['waitprice'];
	$kmprice 	  = $result['kmprice'];
	$halfhourprice 	  = $result['halfhourprice'];
	$minimumprice 	  = $result['minimumprice'];

	$link_edit 		= HOME_URL . '/takstzoner.php?action=edit&method=edit&id=' . $rateid;
	$link_delete 	= HOME_URL . '/takstzoner.php?action=delete&id=' . $rateid;
	$action = '<a href="' . $link_edit . '" class="btn btn-primary btn-circle btn-edit"><i class="fa fa-pencil"></i></a> ';
	$action .= '<a href="javascript:void(0);" data-href="' . $link_delete . '" class="btn btn-danger btn-circle btn-delete"><i class="fa fa-trash"></i></a>';

	$return['data'][] = array(
		$rateid,
		$ratetype,
		$startprice,
		$waitprice,
		$kmprice,
		$halfhourprice,
		$minimumprice,
		$action
	);
}
echo json_encode($return);
die();

<?php
require_once 'dbconnect.php';
$sql = "SELECT * FROM business where is_deleted = 0 ORDER BY business_id DESC ";
$res = mysqli_query($conn,  $sql);
$results = array();

while ($row = mysqli_fetch_assoc($res)) {
	$results[] = $row;
}
$return = array();
$return['data'] = array();

foreach ($results as $key => $result) {
	$business_id 	  	= $result['business_id'];
	$name 	  		= $result['business_name']; 
	$create_date 	  	= $result['created_at'];

	$link_view 		= HOME_URL . '/business-view.php?bid=' . $business_id;
	$link_edit 		= HOME_URL . '/business-edit.php?bid=' . $business_id;
	// $link_change 	= HOME_URL . '/business.php?action=change&method=change&id=' . $business_id;
	$link_delete 	= HOME_URL . '/business-list.php?action=delete&bid=' . $business_id;

	$action = '<a href="' . $link_view . '" class="btn btn-primary btn-circle btn-view"><i class="fa fa-eye"></i></a> ';
	$action .= '<a href="' . $link_edit . '" class="btn btn-primary btn-circle btn-edit"><i class="fa fa-pencil"></i></a> ';
	// $action .= '<a href="' . $link_change . '" class="btn btn-primary btn-circle"><i class="fa fa-unlock"></i></a> ';
	$action .= '<a href="javascript:void(0);" data-href="' . $link_delete . '" class="btn btn-danger btn-circle btn-delete"><i class="fa fa-trash"></i></a>';

	$return['data'][] = array(
		$business_id,
		$name, 
		$create_date,
		$action
	);
}
echo json_encode($return);
die();

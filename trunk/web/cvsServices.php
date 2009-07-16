<?php
include("services/NARServices.php");

//get the method being called
if($_REQUEST['method']=='getReferenceCodes') {
	$services = new NARServices;
	$data= $services->getReferenceCodes($_REQUEST['code']);
	
	foreach($data as $rec) {
		echo($rec['area_code'].",".$rec['area_name']."\n");
	}
	exit();
} else {
	//Present the documentation of the web service
	require 'libs/Smarty.class.php';
	$smarty = new Smarty;
	$smarty->display('NAR_API_Docs.html');
	
	
}
?>
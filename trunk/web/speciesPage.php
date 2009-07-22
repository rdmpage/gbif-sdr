<?php

require 'services/SDRServices.php';
require 'controller.php';

if (!isset($_REQUEST['id'])) {
   echo("error!"); 
   die();
}


$speciesId=$_REQUEST['id'];
$speciesName=$_REQUEST['n'];




$services = new SDRServices;

//$data =$services->getItemList(10);
$smarty->assign('speciesId', $speciesId);
$smarty->assign('scientificName', $speciesName);
$smarty->assign('comments',$services->getComments($speciesId));




$smarty->display('speciesPage.tpl');

?>
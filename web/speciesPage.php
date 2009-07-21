<?php

require 'services/SDRServices.php';
require 'controller.php';

if (!isset($_REQUEST['species'])) {
   echo("error!"); 
   die();
}


$speciesId=$_REQUEST['species'];




$services = new SDRServices;

//$data =$services->getItemList(10);
$smarty->assign('speciesId', $speciesId);
$smarty->assign('comments',$services->getComments($speciesId));




$smarty->display('speciesPage.tpl');

?>
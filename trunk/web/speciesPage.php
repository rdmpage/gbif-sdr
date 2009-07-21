<?php

require 'services/SDRServices.php';
require 'controller.php';


$services = new SDRServices;

//$data =$services->getItemList(10);
$smarty->assign('speciesId', 10);
$smarty->assign('comments',$services->getComments(10));




$smarty->display('speciesPage.tpl');

?>
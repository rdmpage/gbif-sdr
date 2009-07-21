<?php

require 'libs/Smarty.class.php';
require 'services/SDRServices.php';

$smarty = new Smarty;
$services = new SDRServices;

//$data =$services->getItemList(10);
$smarty->assign('speciesId', 10);
$smarty->display('speciesPage.tpl');

?>
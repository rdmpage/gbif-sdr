<?php

require 'controller.php';
require 'services/SDRServices.php';

$services = new SDRServices;

//$data =$services->getItemList(10);
//$smarty->assign('data', $data);
$smarty->display('searchresult.tpl');
?>
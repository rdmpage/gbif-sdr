<?php

require 'libs/Smarty.class.php';
require 'services/SDRServices.php';

$smarty = new Smarty;
$services = new SDRServices;

//$data =$services->getItemList(10);
//$smarty->assign('data', $data);
$smarty->display('header.tpl');
$smarty->display('searchresult.tpl');
$smarty->display('footer.tpl');
?>
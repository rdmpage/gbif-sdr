<?php

require 'controller.php';
require 'services/SDRServices.php';

$services = new SDRServices;

//$data =$services->getItemList(10);
//$smarty->assign('data', $data);
/*$smarty->display('header.tpl');*/
$smarty->display('headerGbif.tpl');
$smarty->display('register.tpl');
/* $smarty->display('footer.tpl'); */
$smarty->display('footerGbif.tpl');
?>
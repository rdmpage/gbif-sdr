<?php

require 'controller.php';


//$data =$services->getItemList(10);
//$smarty->assign('data', $data);
$smarty->assign('section', 'home');
$smarty->display('home.tpl');
?>
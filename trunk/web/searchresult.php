<?php

require 'controller.php';
require 'services/SDRServices.php';

$services = new SDRServices;

$searchName=$_REQUEST['q'];
if(strlen($searchName)>3) {
    $res=$services->searchForName($searchName,10,1);
    $smarty->assign('results', $res);
    $smarty->assign('queryString',$searchName);
    $smarty->assign('popularSpecies', $services->getMostPopularSpecies(16));
}


//$data =$services->getItemList(10);
//
$smarty->display('searchresult.tpl');
?>
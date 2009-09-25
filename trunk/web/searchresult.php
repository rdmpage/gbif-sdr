<?php

require 'controller.php';
require 'services/SDRServices.php';

$services = new SDRServices;

$searchName=$_REQUEST['q'];
if(strlen($searchName)>3) {
    $smarty->assign('results', $services->searchForName($searchName,10,0));
    $smarty->assign('queryString',$searchName);
    $smarty->assign('popularSpecies', $services->getMostPopularSpecies(16));
}


//$data =$services->getItemList(10);
//
$smarty->display('searchresult.tpl');
?>
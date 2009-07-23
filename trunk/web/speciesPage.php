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

$speciesData = $services->getGbifDetailsByNameId($speciesId);



$smarty->assign('popularSpecies', $services->getMostPopularSpecies(4));
$smarty->assign('speciesId', $speciesId);
$smarty->assign('nub_concept_id', $speciesData['nub_concept_id']);
$smarty->assign('scientificName', $speciesData['scientific_name']);
$smarty->assign('comments',$services->getComments($speciesId));




$smarty->display('speciesPage.tpl');

?>
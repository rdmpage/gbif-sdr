<?php

require 'services/SDRServices.php';
require 'controller.php';

if (!isset($_REQUEST['id'])) {
   echo("error!"); 
   die();
}


$speciesId=$_REQUEST['id'];
@$speciesName=$_REQUEST['n'];

$services = new SDRServices;

$speciesData = $services->getGbifDetailsByNameId($speciesId);

$smarty->assign('sources', $services->getSpeciesDetailsByNameId($speciesId));


$smarty->assign('popularSpecies', $services->getMostPopularSpecies(4));
$smarty->assign('speciesId', $speciesId);
$smarty->assign('nub_concept_id', $speciesId);
$smarty->assign('scientificName', $speciesData->scientificName);
@$smarty->assign('imageURL', $speciesData->imageURL);
$smarty->assign('comments',$services->getComments($speciesId));


$smarty->assign('kingdom', $speciesData->kingdom);
$smarty->assign('phylum', $speciesData->phylum);
$smarty->assign('class', $speciesData->class);
$smarty->assign('order', $speciesData->order);
$smarty->assign('family', $speciesData->family);
$smarty->assign('genus', $speciesData->genus);



$smarty->display('speciesPage.tpl');

?>
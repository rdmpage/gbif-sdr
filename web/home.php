<?php

require 'libs/Smarty.class.php';
require 'services/SDRServices.php';

$smarty = new Smarty;
$services = new SDRServices;

$smarty->display('home.tpl');
?>
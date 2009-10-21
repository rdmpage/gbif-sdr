<?php

require 'services/SDRServices.php';
require 'libs/Smarty.class.php';

$smarty = new Smarty;

$smarty->display('widget.tpl');

?>
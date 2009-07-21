<?php
require 'libs/Smarty.class.php';
$smarty = new Smarty;
session_start();
$smarty->assign('username', $_SESSION['user']['username']);
?>


		
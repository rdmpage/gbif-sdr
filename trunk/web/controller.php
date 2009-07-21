<?php
require 'libs/Smarty.class.php';
$smarty = new Smarty;
session_start();
if(isset($_SESSION['user'])) {
    $smarty->assign('username', $_SESSION['user']['username']);
}

?>


		
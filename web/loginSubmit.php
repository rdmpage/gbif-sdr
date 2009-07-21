<?php
require 'libs/Smarty.class.php';
require 'services/SDRServices.php';
$smarty = new Smarty;
$services = new SDRServices;


//data validation
$loginerror=false;

$email=$_POST['email'];
$pass=$_POST['password'];

try {
    $loginresult= $services->login($email,$pass);
} catch(Exception $e) {
  /*  $smarty->assign('error', $e->getMessage());    
    
    $smarty->display('header.tpl');
    $smarty->display('register.tpl');
    $smarty->display('footer.tpl'); */
    die();
    
}

if($loginresult) {
    header( 'Location: /' ) ;
} else {
    
}
//set the login params
session_start();

$_SESSION['user']=$logineresult;


//everything went fine. We send to the home page


?>
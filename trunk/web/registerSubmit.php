<?php
require 'libs/Smarty.class.php';
require 'services/SDRServices.php';
$smarty = new Smarty;
$services = new SDRServices;


//data validation
$loginerror=false;

$user_name=$_POST['username'];
$project_name=$_POST['project_name'];
$email=$_POST['email'];
$password=$_POST['password'];
$password_confirm=$_POST['confirmpassword'];

try {
    $logineresult= $services->registerUser($user_name,$project_name,$email,$password);
} catch(Exception $e) {
    $smarty->assign('error', $e->getMessage());    
    
    $smarty->display('header.tpl');
    $smarty->display('register.tpl');
    $smarty->display('footer.tpl');
    die();
    
}


//set the login params
session_start();

$_SESSION['user']=$logineresult;


//everything went fine. We send to the home page
header( 'Location: /' ) ;

?>
<?php
	
	/**
	 * JSON gateway
	 */
	
	include("globals.php");
	
	include "core/html/app/Gateway.php";
	
	$gateway = new Gateway();
	
	$gateway->setBaseClassPath($servicesPath);
	
	$gateway->service();
?>
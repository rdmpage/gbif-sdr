<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Species distribution repository</title>

  <!-- Framework CSS -->

  	<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen, projection">
  	<link rel="stylesheet" href="css/typography.css" type="text/css" media="screen, projection">
  	<link rel="stylesheet" href="css/forms.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="css/grid.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="css/print.css" type="text/css" media="print">
<!-- 	<link rel="stylesheet" href="css/style1.css" type="text/css" media="screen, projection"> -->
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen, projection">

	<!--<link rel="stylesheet" href="css/tpl/header.css" type="text/css" media="screen, projection">	
	<link rel="stylesheet" href="css/tpl/footer.css" type="text/css" media="screen, projection">-->
<!-- 	<link rel="stylesheet" href="css/tpl/searchHForm.css" type="text/css" media="screen, projection">	 -->
<!-- 	<link type='text/css' href='css/login.css' rel='stylesheet' media='screen' /> -->
<!-- 	<link type='text/css' href='css/basicLogin.css' rel='stylesheet' media='screen' />	 -->
<!-- 	<link rel="stylesheet" href="css/search.css" type="text/css" media="screen, projection"> -->

	<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
	
	<script src="js/jquery.validate.js" type="text/javascript"></script>
	
	<script src="js/cmxforms.js" type="text/javascript"></script>
	
		
	<script type="text/javascript" src="js/jquery.simplemodal.js"></script>
	<script type="text/javascript" src="js/init.js"></script>
	
	
  <!--[if lt IE 8]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen, projection"><![endif]-->


</head>
<body>
	
	<!-- Login modal window-->
	<div id="login_form" style='display:none'>
		<div class="loginContainer">
			<div id="status" align="left">			
				<div class="span-10 error_msg" id="error_msg">Incorrect username/password combination</div>
				<div class="span-10">
					<form id="login" onkeypress="enterLogin(event)">
						<div class="span-4 login">
							<label class="login">Email or username</label>
							<input id="email" class="login" type="text" name="email">
						</div>
						<div class="span-4  login1 last">
							<label class="login">Password</label>
							<input id="password" class="login" type="password" name="password">
						</div>
					</form>
				</div>
				<div class="span-11 submitLogin">
					<!--<input class="checkbox" type="checkbox"/>
					<p class="submit"> keep me signed in</p>
					<p class="submit"> | </p>
					<a class="last linkForgot" href="#">Forgot password?</a>-->
					<input class="span-3 submitButton" value="Sign in" name="Sign in" id="submit" type="submit"  onclick='login()' />
					<img class="ajax_loading" id="ajax_loading" src="/images/ajax-loader.gif" style="display:none">
				</div>
			 </div>
		</div>	
	</div>
	
	<!-- Complete login modal window-->
	<div id="login" style='display:none'>
		<div class="loginContainer">
			<div id="confirmation" class="span-10 confirmationDialog"></div>
			<p class="confirmationText">Wellcome to Species Distribution, only 2 seconds.</p>
		</div>	
	</div>
	
	<!-- Logout confirmation modal window-->
	<div id="logout" style='display:none'>
		<div class="loginContainer">
			<div id="logoutConfirmation" class="span-10 logoutDialog" align="center">Are you sure you want to log out {$username}?</div>
			<div class="span-10 confirmationButtons">
				<input class="span-3 submitButton" value="Yes" onClick="logout()" type="submit"/>
				<input class="span-3 submitButton" value="No"  onClick="$.modal.close()" type="submit"/>
			</div>	
		</div>	
	</div>


	<div class="container">
		<div class="span-24 last headerContainer">
			<a href="/"><div class="span-1 last headerLogo"></div></a>
			<div class="span-1 last headerList">
				<ul>
  					<li><a href="browse.php">BROWSE2</a></li>
  					<li><a href="contribute.php">CONTRIBUTE</a></li>
  					<li><a href="api.php">API/Developers</a></li>
  					<li><a href="about.php">ABOUT</a></li>
				</ul>
			</div>
			<div class="span-1 last headerSign">
			    <div id="loginDiv" class="login_sign">
    			    {if $username ne ""}
        				{$username} | <a id="logoutRef" onClick="$('#logout').modal()" href="#">Sign out </a>  
    			    {else}
        				<a id="login_link" href="#">Login </a>or
        				<a href="register.php"> Sign up!</a>
    			    {/if}
			    </div>	 
			</div>
		</div>


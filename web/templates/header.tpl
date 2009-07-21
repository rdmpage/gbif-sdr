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
	<link rel="stylesheet" href="css/style1.css" type="text/css" media="screen, projection">

	<link rel="stylesheet" href="css/tpl/header.css" type="text/css" media="screen, projection">	
	<link rel="stylesheet" href="css/tpl/footer.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="css/tpl/searchHForm.css" type="text/css" media="screen, projection">	
	<link type='text/css' href='css/login.css' rel='stylesheet' media='screen' />
	<link type='text/css' href='css/basicLogin.css' rel='stylesheet' media='screen' />	
	<link rel="stylesheet" href="css/search.css" type="text/css" media="screen, projection">

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
				<div class="span-10">
					<form id="login">
						<div class="span-4 login">
							<label class="login">Email or username</label>
							<input id="email" class="login" type="text" name="email">
						</div>
						<div class="span-4  login1 last">
							<label class="login">Password</label>
							<input id="password" class="login" type="password" name="password"
							    onkeydown="if (event.keyCode == 13) login()"
							>
						</div>
					</form>
				</div>
				<div class="span-11 submitLogin">
					<input class="checkbox" type="checkbox"/>
					<p class="submit"> keep me signed in</p>
					<p class="submit"> | </p>
					<a class="last linkForgot" href="#">Forgot password?</a>
					<input class="span-3 last submitButton" value="Sign in" name="Sign in" id="submit" type="submit"  onclick='login()' />
				</div>
			 </div>
		</div>	
	</div>


	<div class="container">
		<div class="span-24 last headerContainer">
			<div class="span-1 last headerLogo"></div>
			<div class="span-1 last headerList">
				<ul>
  					<li><a href="">BROWSE</a></li>
  					<li><a href="">CREATE</a></li>
  					<li><a href="">ABOUT</a></li>
				</ul>
			</div>
			<div class="span-1 last headerSign">
			    {if $username ne ""}
    				<div class="login_sign">
    					{$username} | <a id="login_link" href="#">Sign out </a>
    				</div>			    
			    {else}
    				<div id="loginDiv" class="login_sign">
    					<a id="login_link" href="#">Login </a>or
    					<a href="register.php"> Sign up!</a>
    				</div>	    
			    {/if}
			</div>
		</div>


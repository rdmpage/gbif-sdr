<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Species distribution repository</title>

  	<link rel="stylesheet" href="/css/reset.css" type="text/css" media="screen, projection">
  	<link rel="stylesheet" href="/css/typography.css" type="text/css" media="screen, projection">
  	<link rel="stylesheet" href="/css/forms.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="/css/grid.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="/css/print.css" type="text/css" media="print">
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="/css/gbif.css" type="text/css" media="screen, projection">


	<script type="text/javascript" src="/js/jquery.js"></script>	
	<script type="text/javascript" src="/js/jquery.validate.js" ></script>	
	<script type="text/javascript" src="/js/cmxforms.js"></script>
	<script type="text/javascript" src="/js/jquery.simplemodal.js"></script>
	<script type="text/javascript" src="/js/init.js"></script>
	
	
  	<!--[if lt IE 8]><link rel="stylesheet" href="/css/ie.css" type="text/css" media="screen, projection"><![endif]-->


</head>
<body>
	
	<!-- Login modal window-->
	<div id="login_form" style='display:none'>
		<div class="loginContainer">
			<div id="status" align="left">			
				<div class="span-11">
					<form id="loginForm" action="javascript: void login()">
						<div class="span-4 login">
							<label class="login">Email or username</label>
							<input id="email" class="login" type="text">
						</div>
						<div class="span-4  login1 last">
							<label class="login">Password</label>
							<input id="passwordLogin" class="login" type="password">
						</div>
						<div class="span-11 submitLogin">
							<input id="loginButton" class="span-3 submitButton" value="Sign in" name="Sign in" id="submit" type="submit"/>
							<div class="span-6 error_msg" id="error_msg">Incorrect username/password combination</div>
						</div>
					</form>
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

	<div class="separatorTop"></div>
	<div class="container">
	<div class="span-24" id="wrapper">
		<div class="span-24" id="header">
			<div>
				<div class="span-15 column first">
					<a href="/"><img class="logoImg" src="/images/logogbif.jpg"></a>
				</div>
				<div class="headerSign span-9 column last">
				    <div id="loginDiv" class="login_sign">
	    			    {if $username ne ""}
	        				{$username} | <a id="logoutRef" onClick="$('#logout').modal()" href="#">Sign out</a>  
	    			    {else}
	        				<a id="login_link" href="#" onclick="$('#login_form').modal()">Login </a>or
	        				<a href="/register.php"> Sign up!</a>
	    			    {/if}
				    </div>	 
				</div>
			</div>
			<div class="span-24 menuContainer" id="menu">
				<ul>
					<li><a {if $section eq "home"} class="current"{/if} href="/">Home</a></li>
  					<li><a {if $section eq "browse"} class="current"{/if} href="browse.php">Browse</a></li>
  					<li><a {if $section eq "contribute"} class="current"{/if} href="contribute.php">Contribute</a></li>
  					<li><a {if $section eq "api"} class="current"{/if} href="api.php">API/Developers</a></li>
  					<!--li><a {if $section eq "about"} class="current"{/if} href="about.php">About</a></li -->
				</ul>
			</div>
		</div>


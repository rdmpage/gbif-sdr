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
	
	<script type="text/javascript" src="js/jquery-1.3.2.js"></script>

	
	<script type="text/javascript" src="js/jquery.simplemodal.js"></script>
	<script type="text/javascript" src="js/init.js"></script>

	{literal}
	<script type="text/javascript">
	
	
		function commentAction() {
						
				/*var name = $("#name").val();
				var email = $("#email").val();*/
				var comment = $("#comment").val();
			    var dataString ='&comment=' + comment;
				
				if(comment=='') {
			    	alert('Error, invalid user data');
			    } else {
					$("#flash").show();
					$("#flash").fadeIn(400).html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading">Loading Comment...</span>');
					$.ajax({
						type: "POST",
			 	 		url: "commentajax.php",
			   			data: dataString,
			  			cache: false,
			  			success: function(html){
			  				$("ol#update").append(html);
			  				$("ol#update li:last").fadeIn("slow");
			  				/*document.getElementById('email').value='';
			   				document.getElementById('name').value='';*/
			    			document.getElementById('comment').value='';
							/* $("#name").focus(); */
			  				$("#flash").hide();
			  			}
			 		});
				}
				return false;
		};
			
	</script>
	{/literal}
	
  <!--[if lt IE 8]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen, projection"><![endif]-->


</head>
<body>
	
	<!-- Login modal window Display:none-->
	<div id="login_form" style='display:none'>
		<div class="loginContainer">
			<div id="status" align="left">			
				<div class="span-10">
					<form id="login">
						<div class="span-4 login">
							<label class="login">Email or username</label>
							<input class="login" type="text" name="email">
						</div>
						<div class="span-4  login1 last">
							<label class="login">Password</label>
							<input class="login" type="password" name="password">
						</div>
					</form>
				</div>
				<div class="span-11 submitLogin">
					<input class="checkbox" type="checkbox"/>
					<p class="submit"> keep me signed in</p>
					<p class="submit"> | </p>
					<a class="last linkForgot" href="#">Forgot password?</a>
					<input class="span-3 last submitButton" value="Sign in" name="Sign in" id="submit" type="submit" />
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
				<div class="login_sign">
					<a id="login_link" href="#">Login </a>or
					<a href="#"> Sign up!</a>
				</div>
			</div>
		</div>


<?php /* Smarty version 2.6.22, created on 2009-07-15 17:10:14
         compiled from header.tpl */ ?>
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
	
	
		<?php echo '
		<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
		<script type="text/javascript">
		
		
			function commentAction() {
							
					/*var name = $("#name").val();
					var email = $("#email").val();*/
					var comment = $("#comment").val();
				    var dataString =\'&comment=\' + comment;
					
					if(comment==\'\') {
				    	alert(\'Error, invalid user data\');
				    } else {
						$("#flash").show();
						$("#flash").fadeIn(400).html(\'<img src="ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading">Loading Comment...</span>\');
						$.ajax({
							type: "POST",
				 	 		url: "commentajax.php",
				   			data: dataString,
				  			cache: false,
				  			success: function(html){
				  				$("ol#update").append(html);
				  				$("ol#update li:last").fadeIn("slow");
				  				/*document.getElementById(\'email\').value=\'\';
				   				document.getElementById(\'name\').value=\'\';*/
				    			document.getElementById(\'comment\').value=\'\';
								/* $("#name").focus(); */
				  				$("#flash").hide();
				  			}
				 		});
					}
					return false;
			};
				
		</script>
		'; ?>

	
  <!--[if lt IE 8]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen, projection"><![endif]-->


</head>
<body>
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
					<a href="">Login </a>or
					<a href=""> Sign up!</a>
				</div>
			</div>
		</div>

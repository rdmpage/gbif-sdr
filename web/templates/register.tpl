{literal}
<script type="text/javascript">
	$.validator.setDefaults({
		submitHandler: function() {register();}
	});

	$().ready(function() {
	
		$("#signupForm").validate({
			rules: {
				firstname: "required",
				lastname: "required",
				username: {
					required: true,
					minlength: 5
				},
				password: {
					required: true,
					minlength: 5
				},
				confirm_password: {
					required: true,
					minlength: 5,
					equalTo: "#password"
				},
				mail: {
					required: true,
					email: true,
					minlength: 5
				},
				topic: {
					required: "#newsletter:checked",
					minlength: 2
				},
				agree: "required"
			},
			messages: {
				firstname: "Please enter your firstname",
				lastname: "Please enter your lastname",
				username: {
					required: "Please enter a username",
					minlength: "Your username must consist of at least 5 characters"
				},
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				},
				confirm_password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long",
					equalTo: "Please enter the same password as above "
				},
				mail: "Please enter a valid email address",
				agree: "Please accept our policy",
				minlength: "Your email must be at least 5 characters long"
			}
		});
	
	// propose username by combining first- and lastname
	$("#username").focus(function() {
		var firstname = $("#firstname").val();
		var lastname = $("#lastname").val();
		if(firstname && lastname && !this.value) {
			this.value = firstname + "." + lastname;
		}
	});
	
	// check if confirm password is still valid after password changed
	$("#password").blur(function() {
		$("#confirm_password").valid();
	});

});
</script>
{/literal}

		
		<div class="span-24 ppalContainer">
			<div class="span-18 registerContainer first">
			 	<div class="title_blue">Register now in Species distribution repository, It’s easy and free</div>
				<div class="registerInputs">
					<div class="register_14">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eleifend convallis metus vitae scelerisque. Sed condimentum pellentesque nisi, ac lacinia felis sagittis bibendum. Cras tempus ipsum nec.</div>
					{*{if $error ne ""}
						There has been an error!
						{$error}
					{/if}*}
					<form class="cmxform"  method="get" action="registerSubmit.php"  id="signupForm">
						<div class="span-18 column1">
							<div class="span-9 last">
								<div class="title_campo"><label for="username">User name*</label></div>
								<input class="span-8 text " id="username" name="username"/>
							</div>
							<div class="span-9 last">
								<div class="title_campo"><label for="project">Project name</label></div>
								<input class="span-8 text" id="project_name" name="project_name"/>
							</div>
						</div>
						
		
						<div class="span-9 last column2">
							<div class="title_campo"><label for="mail">e-mail*</label></div>
							<input class="span-8 text" id="mail" name="mail"/>
						</div>
						
						<div class="span-18 column1">
							<div class="span-9 last">
								<div class="title_campo"><label for="password">password*</label></div>
								<input class="span-8 text" name="password" type="password" id="password"/>
							</div>
							<div class="span-9 last">
								<div class="title_campo"><label for="confirm_password">confirm password*</label></div>
								<input class="span-8 text" name="confirm_password" type="password" id="confirm_password"/>
							</div>
						</div>
						<div class="span-18">
							<div class="span-4">
								<input type="submit" class="submit_button" value="submit" id="submitRegister" name="submit"/>
							</div>
							<div class="span-10 registerError" id="registerError">
								
							</div>
						</div> 
						<div class="span-18 requiredLabel">* required fields</div>
					</form>
				</div>
			</div>
			<div class="span-6 last rightColumn">
				<div class="register_info">
				 	<div class="title_blue">Why I should to be registered?</div>
				    <div class="separator_small"></div>
				    <p class="register_12">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eleifend convallis metus vitae scelerisque. Sed condimentum pellentesque nisi, ac lacinia felis sagittis bibendum.</p> 
					<p class="register_12">Cras tempus ipsum nec urna condimentum vehicula. Nulla et nisl at nisi imperdiet fermentum ac sed arcu. Sed et ornare nulla. Pellentesque lobortis commodo ullamcorper.</p>
					<p class="register_12">Nulla vitae aliquam augue. Ut vel nisi nibh, non dictum orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus rhoncus.</p>
				</div>
			</div>
		</div>
		
		<div class="span-24 separator40"></div>

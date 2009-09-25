


// When the form is submitted
function login(){  
  

	$('#error_msg').hide();

    var email = $("#email").val();
    var password = $("#passwordLogin").val();
    
    
    if (email.length == 0 || password.length == 0) {
    	$('#error_msg').html('*There are empty fields.');
    	$('#error_msg').show();
    	return false;
    }
    
    if (email.length < 5 || password.length < 5) {
    	$('#error_msg').html('*Not enough characters...');
    	$('#error_msg').show();
    	return false;
    }
    
    $('#submit').attr("disabled", "true");
    $('#email').attr("disabled", "true");
    $('#passwordLogin').attr("disabled", "true");
    
    $('#loginButton').val('Checking...');
    var dataObj = ({email : email,
        method: 'login',
        password: password
        });
        
    // -- Start AJAX Call --
    $.ajax({
    	type: "POST",
    	url: "ajaxController.php",
    	data: dataObj,
    	cache: false,
    	success: function(result){
            
		if(result=='invalid') {
                //notify the user that the login was wrong
                $("#error_msg").show();
                $('#submit').removeAttr("disabled");
                $('#email').removeAttr("disabled");
    			$('#passwordLogin').removeAttr("disabled");
    			$('#loginButton').val('Sign in');
            } else {
                //login ok. Close the popup and change the login menu in the header
                $.modal.close(); 
               
                $('#confirmation').html('Thank for your logging '+result+'.');
                $('#login').modal();
                 timerID = setTimeout("timerHide()", 2000);
             }
        
    	},
        error:function (xhr, ajaxOptions, thrownError){
                alert('SDR' + xhr.status + "\n" + thrownError);
        }
    });
    return false;
}



//logout function
function logout(){
    var dataObj = ({method: 'logout'});    
    $.ajax({
    	type: "POST",
    	url: "ajaxController.php",
    	data: dataObj,
    	cache: false,
    	success: function(result){},
        error: function (xhr, ajaxOptions, thrownError){
                alert(xhr.responseText + "\n" + thrownError + ajaxOptions);
        }
    });
    
    location.reload();

}  


function timerHide() {
     location.reload();
     clearTimeout(timerID);
}



function checkSearch () {
	$('#lengthError').html('');
	var searchValue = $("#searchText").val();
	if (searchValue.length > 3) {
		window.location.href = 'searchResult.php?q=' + searchValue;
	} else {
		$('#lengthError').html('*Sorry, not enough characters, at least 4.');
	}
}



// Preload Images

img2 = new Image(220, 19);  
img2.src="/images/ajax-loader.gif";

// When DOM is ready

//REGISTER EVENTS
$(document).ready(function(){

    // Launch MODAL BOX if the Login Link is clicked
    $("#login_link").click(function(){
        $('#login_form').modal();
    });

});


// When the form is submitted
function login(){  

    // Hide 'Submit' Button
    //$('#submit').hide();
    $('#submit').attr("disabled", "true");
    $('#email').attr("disabled", "true");
    $('#password').attr("disabled", "true");

    // Show Gif Spinning Rotator
    $('#ajax_loading').show();
	$('#error_msg').hide();

    var email = $("#email").val();
    var password = $("#password").val();
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
            	$('#ajax_loading').hide();
                $('#submit').removeAttr("disabled");
                $('#email').removeAttr("disabled");
    			$('#password').removeAttr("disabled");
            } else {
                //login ok. Close the popup and change the login menu in the header
                $('#ajax_loading').hide();               
                $.modal.close(); 
                $("#loginDiv").html(result + ' | <a id="logoutRef" href="#"> Sign out</a> ');
                $("#logoutRef").click(function(){
	                $('#logout').modal();
	            });
                $("#commentArea").html('<div class="title_gray">Post your comment now</div><textarea class="span-17" name="comment" id="comment"></textarea><input type="button" class="last commentButtonPost" value="Comment now" onclick="commentAction()"/>');
                $('#confirmation').html('Thank for your logging '+result+'.');
                $('#login').modal();
                timerID = setTimeout("timerHide()", 2000);
            }
        
    	},
        error:function (xhr, ajaxOptions, thrownError){
                alert('SDR' + xhr.status + "\n" + thrownError);
        }
    });
  
    // -- End AJAX Call --

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
    	success: function(result){
    		$("#commentArea").html('<div class="span-12 title_logout"><a href="#" onclick="$("#login_form").modal();return false;">Login</a> or <a href="/register.php">register</a> to post your comment</div>');
            $("#loginDiv").html('<a id="login_link" href="#">Login</a> or <a href="/register.php">Sign up!</a>');
            $("#login_link").click(function(){
                $('#login_form').modal();
            });
        
    	},
        error:function (xhr, ajaxOptions, thrownError){
                alert(xhr.status + "\n" + thrownError);
        }
    });
    $.modal.close();

}  


function timerHide() {
     $.modal.close();
     //document.getElementById("login").style.display = "none";
     clearTimeout(timerID);
}

function enterLogin(e) {
	e = e || window.event;
	var unicode=e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
	if (unicode == 13){
 		login();
 	}
}

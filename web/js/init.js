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
    $('#submit').hide();

    // Show Gif Spinning Rotator
    $('#ajax_loading').show();


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
                alert('error');
            } else {
                //login ok. Close the popup and change the login menu in the header                
                $.modal.close(); 
                
                $("#loginDiv").html(result + ' | <a href="register.php"> Sign out</a> ');
                
            }
        
    	},
        error:function (xhr, ajaxOptions, thrownError){
                alert(xhr.status + "\n" + thrownError);
        }
    });
  
    // -- End AJAX Call --

    return false;

}
<?php
require 'services/SDRServices.php';
$services = new SDRServices;

if ($_REQUEST['method'] == 'addComment') {
    
	$name=$_SESSION['user']['username'];
	$email=$_SESSION['user']['email'];
	$comment=$_REQUEST['comment'];
	$date = date("d/m/Y h:i");

	$lowercase = strtolower($email);
	$image = md5( $lowercase );

    $result = $services->addComment($_SESSION['user']['id'],$comment,$_REQUEST['speciesId']);
    
    if ($result) {
        ?>
    	<div class="span-18 comments">
    		<div class="span-2 avatar">
    			<!-- <img src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo $image; ?>"/> -->
    		</div>
    		<div class="span-16 contenedor">
    			<span class="span-16 title_comment">by <span class="title_comment_u last"> <?php echo $name;?> </span> <?php echo $date;?> </span>
    			<div class="span-16 text_comment"><?php echo $comment; ?></div>
    		</div>
    	</div>    
        <?php
    } else {
        ?>
    	<div class="span-18 comments">
    		Sorry, the has been an error.
    	</div>    
        <?php       
    }    
}

if ($_REQUEST['method'] == 'login') {

    try {
        $user= $services->login($_REQUEST['email'],$_REQUEST['password']);
        echo($user['username']);
    } catch(Exception $e) {
        echo('invalid');
    }
     die();
   
}

if ($_REQUEST['method'] == 'logout') {
    $services->logout();
}


?>


		
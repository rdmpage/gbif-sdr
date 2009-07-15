<?php
if($_POST) {
	/* $name=$_POST['name']; */
	$name="jatorre";
	$email="xavijam@gmail.com";
	$comment=$_POST['comment'];
	$date = date("d/m/Y h:i");

	$lowercase = strtolower($email);
	$image = md5( $lowercase );
	
	
	//Service table to registry comments.
	/* mysql_query("INSERT INTO comments (name,email,comment) VALUES ('$name','$email','$comment') "); */
}

else { }

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


		
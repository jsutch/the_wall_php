<?php
session_start();

require_once('mysql.connection.php');

?>
<!DOCTYPE html>
<html lang="en">
   <head>
   	  <meta charset="UTF-8">
   	  <link rel="stylesheet" type="text/css" href="the.wall.css">
      <title>The Wall  - Posts</title>

   </head>
   <div id='header'>
   		<div id="title">
		   	<h2>CodingDojo Wall</h2>
		 </div>
		 <div id='user'>
		 	<h3>Welcome <?= $_SESSION['first_name']?></h3>
		 </div>
		 <div id='logout'>
		 	<a href="the.wall.logout.php">Logout</a>
		 </div>
   </div>
   	<div id='wall_post'>
   		<div id='post_title'>
   			<h2> Post a Message</h2>
   		</div>
   		<div class='message_area'>
   			<div class='messages'>
		   		<div class='post_message'>
			   		<form action="the.wall.post.process.php" method="post">
			   			<input type="hidden" name="origin" value="message">
				   		<textarea name='formMessage' rows="10" cols="150"></textarea>
				   		<input type='hidden' name='action' value='post_message'>
						<input type="submit" value="Submit">
					</form>
				</div>
				<div class="message_display">
				<?php 
					if(isset($_SESSION['postArray'])){
		                 foreach($_SESSION['postArray'] as $key => $value){
		                 	echo $value .'<br>';
		                 }
	                	unset($_SESSION['postArray']);
	                } else { ?>
	                	<h2> There are no messages - Post one!</h2>
	               <?php }
                 ?>
				</div>
			</div>
		</div>

   	</div>
   	<div id='errors'>
	<?php
		if(isset($_SESSION['errors'])){
			foreach($_SESSION['errors'] as $error){ ?>
				<p><?= $error .'<br>'; ?> </p>
	<?php	}
		unset($_SESSION['errors']);
		} ?>
	</div>
   <body>

   </body>

</html>
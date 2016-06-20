<?php
session_start();
require_once('mysql.connection.php');
//require('the.wall.post.process.php');
?>
<!DOCTYPE html>
<html lang="en">
   <head>
   	  <meta charset="UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="author" content="Jeff Sutch">
	    <meta name="description" content="PHP profile page">
   	  <link rel="stylesheet" type="text/css" href="the.wall.css">
      <title>The Wall Main</title>

   </head>
   <body>
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
				<!-- Get messages -->
				<?php get_messages() ?>
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

   </body>
<?php   


function get_messages() {

	// get messages
	$messageQuery="SELECT m.id, concat(u.first_name, ' ', u.last_name) as name, m.message, date_format(m.updated_at, '%M %D %Y'), m.updated_at as mdate FROM users u, messages m WHERE u.id = m.user_id ORDER BY m.updated_at DESC;";

	$message=fetch_all($messageQuery);
		foreach($message as $key => $value){

			$strDate = strtotime($message[$key]['mdate']);
			$formattedDate = date("F jS Y h:i:s A", $strDate);
			$message_id = $message[$key]['id'];

			$messages[] = "<div class='post'>\n" .
							"<h3><b>" . "Message ID ".  $message[$key]['id'] ." " . $message[$key]['name'] . " " .  " - " . $formattedDate . "</b></h3>\n" .
							"<span class='posted'><p>" . $message[$key]['message'] . "</p></span>" .
							"</div>";
		}					
		foreach ($messages as $message) {
			echo $message . '<br>';
		}
}


function display_message() {

	
}
	echo "<hr /><div class='msg-div'><div class='msg-hdr'>{$msg['first_name']} {$msg['last_name']} - {$dateString}";



function get_comments_by_message($message_id) {
		// get messages
	$commentQuery="SELECT m.id as mid, concat(u.first_name, ' ', u.last_name) as name, c.comment,m.id as cmid, c.message_id as ccmid, c.id as cid, date_format(m.updated_at, '%M %D %Y at %H:%m:%s') as mupdate, date_format(c.updated_at, '%M %D %Y at %H:%m:%s') as cupdate FROM users u, comments c, messages m WHERE u.id = c.user_id and c.message_id = {$message_id} ORDER BY m.updated_at DESC;";

	$comment=fetch_all($commentQuery);
		foreach($comment as $key => $value){

			$strDate = strtotime($comment[$key]['cupdate']);
			$formattedDate = date("F jS Y h:i:s A", $strDate);
			$comment_id = $comment[$key]['cid'];
			$cmt_message_id = $comment[$key]['ccmid'];
			$message_id = $comment[$key]['cmid'];

			$comments[] = "<div class='post'>\n" .
							"<h3><b>" . " Comment". $comment_id . " " . " for message" . $message_id ." with Comments_Message_Id ". $cmt_message_id . " " . $comment[$key]['name'] . " " .  " - " . $formattedDate . "</b></h3>\n" .
							"<span class='posted'><p>" . $comment[$key]['comment'] . "</p></span>" .
							"</div>";
		}					
		if ($message_id == $comment_id) {
			foreach ($comments as $comment) {
				echo $comment . '<br>';
			} 
		} else {
				echo  "Message ID" . $message_id . " Comment ID" . $comment_id . '<br>';
		}

}






?>
</html>
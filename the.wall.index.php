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
				<h1> My User ID is <?= $_SESSION['user_id'] ?> </h1>
				<?php get_messages($_SESSION['user_id']) ?>
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


function get_messages($user_id) {

	// get messages
	$messageQuery="SELECT m.id, concat(u.first_name, ' ', u.last_name) as name, m.message, date_format(m.updated_at, '%M %D %Y'), m.updated_at as mdate, m.created_at, m.user_id FROM users u, messages m WHERE u.id = m.user_id ORDER BY m.updated_at DESC;";

	$messages=fetch_all($messageQuery);
	$user_id = $_SESSION['user_id'];

		// foreach ($messages as $message) {
		// 	echo $message['id']. $message['name']. $message['message'] . $message['mdate'] . '<br>';
		// }
		foreach ($messages as $message) 
	    {
	        display_message($message, $user_id);
	    }

}


function display_message($message, $user_id) {
	# Return formated messages in HTML and get associated comments
	$dateTime = strtotime($message['created_at']);
    $dateString = date("F jS, Y - g:i A", $dateTime);
	$user_id = $_SESSION['user_id'];

    echo "<hr /><div class='msg-div'><div class='msg-hdr'>{$message['name']} - {$dateString}";
    $minutesSincePost = (time() - $dateTime) / 60;

    if (    (($message['user_id'] == $user_id) && ($minutesSincePost < 30)))
    {
        echo    "<form class='del-btn-form' action='wall_process.php' method='post'>                
                    <input type='hidden' name='action' value='delete_message'>
                    <input type='hidden' name='record_id' value='{$message['id']}'>
                    <input type='submit' class='btn btn-warning' value='delete message'>
                </form>";
    }
    echo   "</div>";
    echo    "<p class='msg-txt'>{$message['message']}</p>";

    // Get Comments
    get_comments($message, $user_id);
}


function get_comments($message, $user_id) {
	// Get a list of comments to bundle beneath the messages
	echo    "<div class='com-div'>";
	$commentQuery="SELECT c.user_id, u.first_name, u.last_name, c.id, c.created_at, c.comment_txt FROM comments c INNER JOIN users u ON c.user_id = u.id WHERE c.message_id = {$message['id']} ORDER BY c.created_at ASC;";

    $comments = fetch_all($commentQuery); 
    foreach ($comments as $comment)
    {
        display_comment($comment, $user_id);
    }
    echo   "<form class='centered com-form' action='wall_process.php' method='post'>
                <label class='left'><p>Post a comment</p><textarea rows='3' class='com-box' name='comment'></textarea></label>
                <input type='hidden' name='action' value='add_comment'>
                <input type='hidden' name='msg_id' value='{$message['id']}'>
                <br />
                <input class='btn btn-success' type='submit' value='comment'>
            </form>
            </div>
        </div>";
}

function display_comment($comment, $user_id)
{
    $dateTime = strtotime($com['created_at']);
    $dateString = date("F jS, Y - g:i A", $dateTime);
    echo   "<div class='com-hdr'>{$com['name']} - {$dateString}";

    $minutesSinceComment = (time() - $dateTime) / 60;
    if (    (($comment['user_id'] == $user_id) && ($minutesSinceComment < 30))
        ||  ($user_id == ADMIN_ID))
    {
        echo    "<form class='del-btn-form' action='wall_process.php' method='post'>                
                    <input type='hidden' name='action' value='delete_comment'>
                    <input type='hidden' name='record_id' value='{$comment['id']}'>
                    <input type='submit' class='btn btn-warning' value='delete comment'>
                </form>";
    }
    echo "</div><p class='com-txt'>{$com['comment_txt']}</p>";
}




?>
</html>
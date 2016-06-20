<?php 
// form debugger
session_start();
?>

<h3> The message is <?php //echo $_POST['formComment']; ?> </h3>
<?php
	$user_id = $_SESSION['user_id'];
	// echo $user_id .'<br>';
	// $message_id = $_POST['message_id'];
	// echo $message_id .'<br>';
	// $comment = $_POST['formComment'];
	// echo $comment .'<br>';
	// $query = "INSERT INTO comments (user_id, comment, message_id, created_at, updated_at) VALUES ('{$user_id}', '{$comment}', '{$message_id}', NOW(), NOW())";
	// echo $query;
?>
<?php
var_dump($_POST);
var_dump($_SESSION);
?>
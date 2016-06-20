<?php
// session var declare, etc.
	// session_start();
	$_SESSION['errors'] = array();
	require_once('mysql.connection.php');
	// $_SESSION['user_id'] = $user['id'];
	// $user_id = $_SESSION['user_id'];
//


if(empty($_POST['origin']))
	{
		//error
		return_error("Don't tamper with the form");
	} else if ($_POST['origin'] == 'message') {
 		// do message stuff
 		post_message($_POST);
	} else if ($_POST['origin'] == 'reply') {
		//do login stuff
		post_reply($_POST);
	}




function post_message($post_info) {
//
	// var_dump($post_info);
	// die();
	// $message = $_POST['formMessage'];
	// var_dump($_SESSION['user_id']);
	if(empty($post_info['formMessage'])){
		return_error("Message can't be empty");
	}
	$user_id = $_SESSION['user_id'];
	$message = $post_info['formMessage'];
	$query = "INSERT INTO messages (user_id, message, created_at, updated_at) VALUES ('{$user_id}', '{$message}', NOW(), NOW())";
	//
	// echo $query;
	if(isset($_SESSION['user_id'])){
		$post_id = run_mysql_query($query);
		$_SESSION['post_id'] = $post_id;
	// 
	// TOGGLE ON/OFF
	header('Location: the.wall.index.php');
	}
}

function get_messages() {

	// get messages
	$messageQuery="SELECT m.id, concat(u.first_name, ' ', u.last_name) as name, m.message, date_format(m.updated_at, '%M %D %Y'), m.updated_at as mdate FROM users u, messages m WHERE u.id = m.user_id ORDER BY m.updated_at DESC;";

	$message=fetch_all($messageQuery);
	if(isset($message['id'])) {
		foreach($message as $key => $value){

			$strDate = strtotime($message[$key]['created_at']);
			$formattedDate = date("F jS Y h:i:s A", $strDate);
			$name = $message[$key]['first_name'];

			$messages[] = "<div class='post'>\n" .
							"<h3><b>" . $message[$key]['first_name'] . " " . $message[$key]['last_name']  . " - " . $formattedDate . "</b></h3>\n" .
							"<span class='posted'><p>" . $message[$key]['message'] . "</p></span>" .
							"</div>";
		}
		$_SESSION['messages'] = $messages;
	} else {
		return_error("Last name can't be empty");
	}
}


// post a reply 
function post_reply($post_info) {
//

}

// error handling
	function return_error($string){
 	// $_SESSION['errors'][] = $string;
 	//	array_push($_SESSION['errors'], $string);
 	}

// var_dump($post_info);
// var_dump($_SESSION);
// var_dump($_POST);
// echo $message;
?>
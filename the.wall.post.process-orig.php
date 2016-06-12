<?php
// session var declare, etc.
	session_start();
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


	if(empty($_SESSION['postArray'])) { 
	// get replies first
	$replyQuery =	"SELECT users.first_name, users.last_name, comments.* FROM the_wall_php.comments 
										left join users on users.id = comments.user_id
										left join messages on comments.message_id = messages.id
										WHERE comments.message_id = '{$post_id}'
										ORDER BY comments.created_at ASC";

	$replies = fetch_all($replyQuery);

			foreach($comments as $keys => $comment){
			$replyDate = strtotime($comments[$keys]['updated_at']);
			$formattedReplayDate = date("M jS Y h:i:s A", $strCommentDate);

			$replyString .= "<div class='reply'>" . 
									"<h4><br>" . $replies[$keys]['first_name'] . " " . $replies[$keys]['last_name'] . " - " . $formattedReplayDate . "</br></h4>".
									"<span class='replied'><p>" . $comments[$keys]['comment'] . "</p></span>" .
									"</div>";
			}	
	// get messages
	$messageQuery="SELECT users.first_name, users.last_name, messages.message, messages.created_at, messages.id FROM the_wall_php.messages LEFT JOIN users on users.id = messages.user_id ORDER BY messages.id DESC";

		$posts=fetch_all($messageQuery);
		foreach($posts as $key => $value){

			$strDate = strtotime($posts[$key]['created_at']);
			$formattedDate = date("F jS Y h:i:s A", $strDate);
			$name = $posts[$key]['first_name'];

			$postArray[] = "<div class='post'>\n" .
							"<h3><b>" . $posts[$key]['first_name'] . " " . $posts[$key]['last_name']  . " - " . $formattedDate . "</b></h3>\n" .
							"<span class='posted'><p>" . $posts[$key]['message'] . "</p></span>" .
							"</div>" .
							$replyString;

		}

		$_SESSION['postArray'] = $postArray;
	}


	// post a reply 
	function post_reply($post_info) {
	//
	}

// error handling
	function return_error($string){
 	// $_SESSION['errors'][] = $string;
 		array_push($_SESSION['errors'], $string);
 	}

// var_dump($post_info);
var_dump($_SESSION);
// var_dump($_POST);
// echo $message;
?>
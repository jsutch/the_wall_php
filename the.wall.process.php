<?
	session_start();

	// set vars
	if(isset($_POST['password'])) {
			$passlength=strlen($_POST['password']);
	}
	$_SESSION['errors'] = array();

	// include the mysql file
	// SQL working
	require_once('mysql.connection.php');

	// Working
	function extract_numbers($string)
		{
			preg_match_all('/([\d]+)/', $string, $match);
			 array_push($numbers_in_name, "$match[0]");
		}
	// Validate and Route POSTs to correct functions
	if(empty($_POST['origin']))
		{
			// if hidden name doesn't equal "origin", then error
			return_error("Don't tamper with the form");
		} else if ($_POST['origin'] == 'registration') {
	 		// do registration stuff
	 		register($_POST);
		} else if ($_POST['origin'] == 'login') {
			//do login stuff
			login($_POST);
		} else if  ($_POST['origin'] == 'message') {
			post_message($_POST);
		} else if  ($_POST['origin'] == 'comment') {
			post_comment($_POST);
		} else if  ($_POST['origin'] == 'delete_message') {
			delete_message($_POST);
		} else if  ($_POST['origin'] == 'delete_comment') {
			delete_comment($_POST);
		}

	//Working
	function register($post_info) {
		// Working
		// set variables 
		$password_length = 3;
		// var_dump($post_info);
		// Check to see if Success was set in an earlier session and unset
		$_SESSION['success'] = '';
		// all fields present
		if(empty($post_info['first_name'])){
			return_error("First name can't be empty");
		}
		if(empty($post_info['last_name'])){
			return_error("Last name can't be empty");
		}
		// valid email
		if(empty($post_info['email']) || !filter_var($post_info['email'], FILTER_VALIDATE_EMAIL)){
			return_error("Email either invalid or empty");
		}
		// password >= $password_length chars
		if(empty($post_info['password']) || strlen($post_info['password']) < ($password_length)) {
			return_error("Password must be greater than $password_length chars");
		}
		// passwords must match
		if(empty($post_info['password_confirm']) || $post_info['password'] != $post_info['password_confirm']){
			return_error("Passwords don't match");	
		}
		if(!empty($_SESSION['errors'])){
			header('Location: the.wall.login-reg.php');
		} else {
			// proceed with user creation
			$first_name = escape_this_string($post_info['first_name']);
			$last_name = escape_this_string($post_info['last_name']);
			$email = escape_this_string($post_info['email']);
			$password = escape_this_string($post_info['password']);

			// password hashing and salt adding
			$pseudo = bin2hex(openssl_random_pseudo_bytes(22));
			$salt = bin2hex($pseudo);
			$hashed_password = crypt($password, $salt);

			$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) VALUES ('{$first_name}', '{$last_name}', '{$email}', '{$hashed_password}', NOW(), NOW())";
			// echo $query;
			// die();
			// $user_id = run_mysql_query($query);
			// echo $user_id;
			// die();
			if(run_mysql_query($query)){
				$_SESSION['success'] = true;
			}
		}
		header('Location: the.wall.login-reg.php');
	}

	// Working
	function login($post_info) {
		// temp test
		// var_dump($post_info);
		// die();
		if(empty($post_info['email'])){
		return_error("Email can't be empty");
		header('Location: the.wall.login-reg.php');
		die();
		}
		if(empty($post_info['password'])){
		return_error("Password can't be empty");
		header('Location: the.wall.login-reg.php');
		die();
		}
		//$messages = [];
		$email = escape_this_string($post_info['email']);
		$password = escape_this_string($post_info['password']);
		$user_query = "SELECT * from users WHERE email = '{$email}'";
		$user = fetch_record($user_query);
		if($user && (crypt($password, $user['password']) == $user['password'])){
			// do stuff
			// set session variables
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['first_name'] = $user['first_name'];
			// Redirect to the.wall.index
			header('Location: the.wall.index.php');
		}
	}

	// Working
	function post_message($post_info) {
		//
		if(empty($post_info['formMessage'])){
			return_error("Message can't be empty");
			header('Location: the.wall.index.php');
		} else {
			// Do message stuff
			//$message = $_POST['formMessage'];
			$message = $post_info['formMessage'];
			$user_id = $_SESSION['user_id'];
			$query = "INSERT INTO messages (user_id, message, created_at, updated_at) VALUES ('{$user_id}', '{$message}', NOW(), NOW())";
			if(isset($_SESSION['user_id'])){
				$post_id = run_mysql_query($query);
				$_SESSION['post_id'] = $post_id;
			}
		header('Location: the.wall.index.php');
		}
	}

	// Working
	function post_comment($post_info) {
//
	// var_dump($post_info);
	// die();
	// $message = $_POST['formMessage'];
		if(empty($post_info['formComment'])){
			return_error("Comment can't be empty");
		}
		$user_id = $_SESSION['user_id'];
		$message_id = $post_info['message_id'];
		$comment = $post_info['formComment'];
		$query = "INSERT INTO comments (user_id, comment, message_id, created_at, updated_at) VALUES ('{$user_id}', '{$comment}', '{$message_id}', NOW(), NOW());";
		//
			// echo $query;
			if(isset($_SESSION['user_id'])){
				$comment_id = run_mysql_query($query);
				$_SESSION['comment_id'] = $comment_id;
			// 
			// TOGGLE ON/OFF
			// var_dump($_SESSION);
			header('Location: the.wall.index.php');
			}
	}

	// Not Working
	function delete_message($post_info){
		$user_id = $_SESSION['user_id'];
		$message_id = $post_info['message_id'];
		$message_query = "SELECT * FROM messages WHERE id = '{$message_id}'";
		$messagedata = fetch_record($message_query);
		// validate that user id is message id
		if($messagedata['user_id'] == $_SESSION['user_id']) {
			// allow the post to be deleted
			$delete_query = "DELETE FROM messages WHERE id  = '{$message_id}'";
			$deleted_id = run_mysql_query($delete_query);
			$_SESSION['deleted_message_id'] = $deleted_id;
			header('Location: the.wall.index.php');
		} else {
			return_error("This isn't your post.");
			header('Location: the.wall.index.php');
		}
	}

		// Not Working
	function delete_comment($post_info){

	}


 	function return_error($string){
 		// $_SESSION['errors'][] = $string;
 		array_push($_SESSION['errors'], $string);
 	}
 

//var_dump($_SESSION);
?>
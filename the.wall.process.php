<?
	session_start();

	// set vars
	// $_SESSION['success'] = '';
	$passlength=strlen($_POST['password']);
	$_SESSION['errors'] = array();
	$_SESSION['first_name'] = '';

	// include the mysql file
	// SQL working
	require_once('mysql.connection.php');


	function extract_numbers($string)
		{
			preg_match_all('/([\d]+)/', $string, $match);
			 array_push($numbers_in_name, "$match[0]");
		}

	if(empty($_POST['origin']))
		{
			//error
			return_error("Don't tamper with the form");
		} else if ($_POST['origin'] == 'registration') {
	 		// do registration stuff
	 		register($_POST);
		} else if ($_POST['origin'] == 'login') {
			//do login stuff
			login($_POST);
		}

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


	function login($post_info) {
		// temp test
		// var_dump($post_info);
		// die();
		$email = escape_this_string($post_info['email']);
		$password = escape_this_string($post_info['password']);
		// $query = "SELECT * from users WHERE email = '{$email}' AND password = '{$password}'";
		$query = "SELECT * from users WHERE email = '{$email}'";


		$user = fetch_record($query);
		if($user && (crypt($password, $user['password']) == $user['password'])){
			// do stuff
			$_SESSION['user_id'] = $user['id'];

			header('Location: the.wall.index.php');
		}
	}



	if(empty($_SESSION['first_name']))
		{
			$user_id = $_SESSION['user_id'];
			$query = "SELECT first_name FROM users WHERE id = '$user_id'";
			//set first name
			$user = fetch_record($query);
			echo $user;
			$_SESSION['first_name'] = $user['first_name'];
		}


 	function return_error($string){
 		// $_SESSION['errors'][] = $string;
 		array_push($_SESSION['errors'], $string);
 	}
 

var_dump($_SESSION);
?>
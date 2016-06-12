<?php
session_start();
// email *
// first_name *
// last_name *
// password *
// confirm_password *
// birth_date
?>

<!DOCTYPE html>
<html lang="en">
   <head>
   	  <meta charset="UTF-8">
   	  <link rel="stylesheet" type="text/css" href="the.wall.css">
      <title>The Wall Login</title>
   <style>
   
   </style>
   </head>

   <body>
   	<div id="welcome">
   		<h1>Welcome To The Wall</h1>
   	</div>

	<div id='login_form'>
		<h2> Please Login</h2>
		<form action='the.wall.process.php' method='post'>
			 <input type="hidden" name="origin" value="login">
		     Email: <input type='text' name='email'>
		     Password: <input type='password' name='password' size=25 maxlength=25>
	     	 <input type='submit' value='Login'>
		</form>
	</div>

   	<div id="reg_form">
   		<h2> Please Make An Account</h2>
	   	<form action='the.wall.process.php' method='post'>
	   		<input type="hidden" name="origin" value="registration">
		     <input type='text' name='first_name' placeholder="First Name: ">
		     <input type='text' name='last_name' placeholder="Last Name: ">
		     <input type='text' name='email' placeholder="Email: ">
		     <input type='password' name='password' placeholder="Password:">
		     <input type='password' name='password_confirm' placeholder="Confirm Password:">
		     <input type='hidden' name='action' value='register'>
	     	 <input type='submit' value='Register'>
		</form>
	</div>




	<div id='errors'>
		<?php
		if(isset($_SESSION['errors'])){
			foreach($_SESSION['errors'] as $error){ ?>
				<p><?= $error .'<br>'; ?> </p>
		<?php	}
		unset($_SESSION['errors']);
		}
		?>
	<?php if(isset($_SESSION['success'])){ ?>	
		<p> You're now signed up! Please login.</p>
	<?php  $_SESSION['success'] = false; } ?>

	</div>

   </body>


<?php 
// var_dump($_SESSION);
// var_dump($_SESSION['errors']);

?>
</html>
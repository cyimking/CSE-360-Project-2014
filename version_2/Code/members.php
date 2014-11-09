
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
<script src="main.js"></script>	


<?php
/*********************************************************************
 *********************************************************************
 * Members File - Handle all functions for the Member's Page         *
 * Version 1.0.2 (Status - Complete)                                 *
 * Version 1.0.2                                                     *
 		* Form Inputs FORCE users to enter date (required="required")*
		* Added a redirect pause for signing in and registering      *
 *********************************************************************
 *********************************************************************
 */

/* TRUE = We are on the index.php page. 
* False = We are in the members.php page.
*/
$homepage = true; 
require 'process/user_function.php';

/* Check if the session is set, if not then set it and include the header file */
if(!isset($_SESSION)) 
    { 
		$homepage = false;  
		include "process/header.php";
    } 

/* Check for an error session. If found, displayed errors. 
* Error Type == 1 = Registration Error
* Error Type == 2 = Login Error
*/
if(isset($_SESSION['errors']) && isset($_SESSION['error_type'])){
	$errors = $_SESSION['errors'];
	$error_type = $_SESSION['error_type'];}

/* Check if a form was submitted to this page. 
* Register = Check Registration for errors then Register User
* Login = Check Sign Form for errors then Sign In User (Set Session for User)
*/
if(isset($_POST['submit']))
{
	$action =  $_POST['submit'];
	
	switch($action){
		case "Create Account": 
			$username = sanitize($_POST['username']);
			$email  = sanitize($_POST['email'],"email");
			//$r_email = sanitize($_POST['r_email'],"email");
			$password = sanitize($_POST['password']);
			$r_password = sanitize($_POST['password2']);
			registration_check($username,$email,$password,$r_password);
			break;
			
		case "Sign In":
			$email = sanitize($_POST['email']);
			$password = sanitize($_POST['pass']);
			login_check($email,$password);
			break;
			
		default: 
			header("Location: index.php");
			break;
	}
}

/* Check if an action was submitted (www.site.com/members.php?action={something}
* Sign Out = Sign Out the User (Destroy ALL SESSSIONS)
* Default = Redirect to homepage. 
*/
else if(isset($_GET['action']))
{
	$action = $_GET['action'];
	
	switch($action){
		case "signout":
			signout();
			break;
		default:
			header("Location: index.php");
			exit();
			break;
	}
}

/*  
* No reason to be in the members.php, so redirect to homepage
*/
else{
	if($homepage == false){
		header("Location: index.php");
		exit();
	}	

}


/* Display Registration Table
* Display Errors if the Error Type 1 Session is Set
* On Submission, pass the Registration POST key
*/
function display_registration_table()
{
	global $errors;
	global $error_type;
		
	echo'
		<br>
		<div id="create_account">
		<h1>Create Account </h1>
		<hr>

		Please fill the form below to create a new free Account.
		<br>
		';
		
		if($error_type == 1){
			echo "<br><div align='center' style='color:red'>" . $errors . "</div>";
			unset($_SESSION['errors']);
			unset($_SESSION['error_type']);
			}
	
	echo '
		<br>
		<form name="Create Account" method="post" action="members.php"> 
		
		<input type="text" size="70%"  placeholder="Account ID (Username)" class="register_input" id="username" name="username" 
		style="position:relative; margin-left: 10px" required="required">
		<span id="message" style="position:absolute;padding:7px;" ></span><br>

		<input type="email" size="70%"  placeholder="Email" class="register_input" id="email" name="email" 
		style="position:relative; margin-left: 10px" required="required">
		<span id="message_email" style="position:absolute;padding:7px;" ></span><br>

		<!-- <input type="text" size="70%"  placeholder="First Name" class="register_input"><br>
		<input type="text" size="70%"  placeholder="Last Name" class="register_input"><br> -->

		<input type="password" size="70%" placeholder="Password" name ="password" class="register_input" id="password"
		 style="position:relative; margin-left: 10px" required="required">
		<span id="password_strength" style="position:absolute;padding:7px;" ></span><br>

		<input type="password" size="70%" placeholder="Password Confirmation" class="register_input" id="password2" name ="password2"
		 style="position:relative; margin-left: 10px" required="required">
		<span id="validate-status" style="position:absolute;padding: 7px"></span>
		<br>
		<input type="submit" value="Create Account" name="submit" id="register_input_submit_fail">
		<br>
		<span id="submit_t"></span>
		</form>
		</div>
		';
}



/* Display Login Table
* Display Errors if the Error Type 2 Session is Set
* On Submission, pass the Login POST key
*/
function display_login_table()
{
	
	global $errors;
	global $error_type;
	
	echo'
		<br>
		<div id="sign_in">
		<h1>Sign In </h1>
		<hr>

		Please fill the form below to create a new free Account.
		<br>';
		
		if($error_type == 2){
			echo "<br><div align='center' style='color:red'>" . $errors . "</div>";
			unset($_SESSION['errors']);
			unset($_SESSION['error_type']);
			}
	echo'	
		<br>
		<form name="Sign In" method="post" action="members.php"> 
		
		<input type="text" size="70%" name="email" placeholder="Account ID (Email Address)" class="register_input" style="position:relative; margin-left: 10px"
		required="required">
		<span style="position:absolute;padding:7px;">Recover Account ID: <a href="#">Here </a></span><br>
		
		<input type="password" size="70%" name="pass" placeholder="Password" class="register_input" style="position:relative; margin-left: 10px"
		required="required">
		<span style="position:absolute;padding:7px;">Forgot Password? <a href="#">Recover it</a></span><br>
		
		<input type="submit" value="Sign In" name="submit" id="register_input_submit">
		</form>
		</div>

		';
}

/* 
* Check Registration Form for Errors
* @para $username, $email, $r_email (repeat email), $password, $r_password
* Errors = Create new error session then redirect to homepage
*/
function registration_check($username,$email,$password,$r_password)
{
	
	/* Check if all fields are filled in */
	if($username == "" || $email == "" || $password == "" || $r_password == ""){
		$_SESSION['errors'] = "You need to fill out the whole form!";
		$_SESSION['error_type'] = 1;
		header("Location: index.php");
		exit();
		}

	/* Check if both passwords matches */	
	else if($password != $r_password){
		$_SESSION['errors'] = "Passwords do not match!";
		$_SESSION['error_type'] = 1;
		header("Location: index.php");
		exit();}
		
	/* Check if the length of the password is between 6 and 16 characters */	
	else if(strlen($password) < 6 || strlen($password) > 16){
		$_SESSION['errors'] = "Passwords much between 6 to 16 characters!";
		$_SESSION['error_type'] = 1;
		header("Location: index.php");
		exit();}
		
	/* Check if the username is available */ 
	else if(user_exist($username)== true){
		$_SESSION['errors'] = 'Username is already taken';
		$_SESSION['error_type'] = 1;
		header("Location: index.php");
		exit();}

	/* Check if the email is available */ 
	else if(email_exist($email) == true){
		$_SESSION['errors'] = 'Email is already taken';
		$_SESSION['error_type'] = 1;
		header("Location: index.php");
		exit();}
		
	/* YAY! No more errors are found sound now register the user! */ 
	else
	{
		/* Check if the registration was successful or not*/
		if(registration($username,$email,$password) == true){
			unset($_SESSION['errors']);
			unset($_SESSION['error_type']);
			header("refresh:3; url=members.php");
			echo "<br><div align='center'>You have successfully registered. You will be redirected now.</div>";
			exit();}
		else
		{
			$_SESSION['errors'] = 'Registration FAILED';
			$_SESSION['error_type'] = 1;
			header("Location: index.php");
			exit();}
	}
}

/* 
* Register the Member - Add user into the database
* @para $username, $email, $password
* @return True = Successful 
* @return False = Not Successful
*/
function registration($username,$email,$password)
{
	global $connection;
	
	$username = $email;
	
	/* "?", etc.. are special tokens for better SQL INJECTION PROTECTION*/
	$query = "INSERT INTO user (username,email,password,salt)
			  VALUES (?,?,?,?) ";
			  
	/* Salt will prevent users from attacks (brute force attacks and rainbow table attacks)*/
	/* Creating a HASH Method is NOT recommanded, thus I will use a powerful salt / password hash function */
	$salt = dechex(mt_rand(0,2147483647)) . dechex(mt_rand(0, 2147483647));
	
	/* Hash the password with the salt to securely store into database */
	$password = hash('sha256', $password . $salt);
	
	/* Hash the password 65537 times to prevent brute force attacks */
	for($x = 0; $x < 65536; $x++)
	{
		$password = hash('sha256', $password . $salt);
	}
	
	/* Prepare Query */
	$stmt = mysqli_prepare($connection,$query);
	if($stmt){
		
		/* BIND the username, email, pass, and salt to the  ? ? ? ?*/
		mysqli_stmt_bind_param($stmt,"ssss",$username,$email,$password,$salt);
	
		$check = mysqli_stmt_execute($stmt);
	
		if(!$check) return false;
	}
		/* Create Directory*/
		create_directory($username);
		return true;
}


/* 
* Check Sign In Form for Errors
* @para $username, $password
* Errors = Create new error session then redirect to homepage
*/
function login_check($email,$password)
{
	/* Check if all fields are filled */
	if($email == "" || $password == ""){
		$_SESSION['errors'] = "You need to fill out the whole form!";
		$_SESSION['error_type'] = 2;
		header("Location: index.php");
		exit();
		}
	
	/* Check if the username exist in the database*/	
	else if(!email_exist($email)){
		$_SESSION['errors'] = "Email does not exist!";
		$_SESSION['error_type'] = 2;
		header("Location: index.php");
		exit();
		}
	
	/* Attempt to sign in member. If unsuccessful, the username (account id) and pass was not valid */
	else{
		if(login($email,$password) == true){
			header("Location: index.php");
			exit();}
		
		else{
			$_SESSION['errors'] = "Invalid Email ID and Password!";
			$_SESSION['error_type'] = 2;
			header("Location: index.php");
			exit();
		}	
	}
}

/* 
* Sign In the User (Login and Signin is the same thing. Will be fixed in version 1.0.1
* @para $username,$password,
* @return True if we successful logged in a member
* @return False if we are unsuccessful
*/
function login($email,$password)
{
	global $connection;
	
	$login = false;
	
	$query = mysqli_query($connection,"SELECT * FROM user WHERE email = '$email'") or die();
	
	while($row = mysqli_fetch_array($query)){
		
		$check_password = hash('sha256',$password . $row['salt']);
		for($x = 0; $x < 65536; $x++)
		{
			$check_password = hash('sha256',$check_password . $row['salt']);
		}
		
		if($check_password == $row['password']){
			  $login =  true;
			  $user_id = $row['user_id'];
			}
			
	}
		
	if($login)
	{
		unset($row['salt']);
		unset($row['password']);
		$_SESSION['user'] = $email;
		$_SESSION['user_id'] = $user_id;
		return true;
	}
	
	else
		return false;
}

/* 
* Sign Out the User. Destroy all sessions
*/
function signout()
{
	/*DESTROY ALL SESSIONS!! HAHAHAHA - Evil Laugh >:> */
	session_destroy();
	header("Location: index.php");
	exit();
}


?>
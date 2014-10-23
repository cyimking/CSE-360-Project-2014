<?php
/********************************************************************
 ********************************************************************
 * Members File - Handle all functions for the Member's Page        *
 * Version 1.0 (Status - Incomplete)                                *
 ********************************************************************
 ********************************************************************
 */

/* TRUE = We are on the index.php page. 
* False = We are in the members.php page.
*/
$homepage = true; 

/* Check if the session is set, if not then set it and include the config file */
if(!isset($_SESSION)) 
    { 
        session_start();
		$homepage = false;  
		include "process/config.php";
    } 

/* Check for an error session. If found, displayed errors. 
* Error Type == 1 = Registration Error
* Error Type == 2 = Login Error
*/
if(isset($_SESSION['errors']) && isset($_SESSION['error_type'])){
	$errors = $_SESSION['errors'];
	$error_type = $_SESSION['error_type'];}

/* Check if the registration was successful. If yes then display it*/
else if(isset($_SESSION['success']))
	$success = true;


/* Check if a form was submitted to this page. 
* Register = Check Registration for errors then Register User
* Login = Check Sign Form for errors then Sign In User (Set Session for User)
*/
if(isset($_POST['submit']))
{
	$action = $_POST['submit'];
	
	switch($action){
		case "Register": 
			$username = clean_string($_POST['user']);
			$email  = clean_string($_POST['email']);
			$r_email = clean_string($_POST['r_email']);
			$password = clean_string($_POST['pass']);
			$r_password = clean_string($_POST['rpass']);
			registration_check($username,$email,$r_email,$password,$r_password);
			break;
			
		case "Login":
			$username = clean_string($_POST['user']);
			$password = clean_string($_POST['pass']);
			login_check($username,$password);
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
	global $success;
	
	if($error_type == 1)
	echo "<br>" . $errors;
	
	if($success == true)
	echo "<br>" . $_SESSION['success'];
	
	echo'
		<br>
		<table align="center" cellpadding="0" cellspacing="1" border="1px solid black" id="tabs"> 

        <tr> 
        <form name="register" method="post" action="members.php"> 
		<td> 

		<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF"> 
		<tr> 
		<td colspan="3"><strong><center>Registration</center></strong></td> 
		</tr> 

		<tr> 
		<td width="78">Username</td> 
		<td width="6">:</td> 
		<td width="294"><input name="user" type="text" id="user"></td> 
		</tr> 
		
		<tr> 
		<td>Email</td> 
		<td>:</td> 
		<td><input name="email" type="text" id="email"></td> 
		</tr> 
		
		<tr> 
		<td>Repeat Email</td> 
		<td>:</td> 
		<td><input name="r_email" type="text" id="r_email"></td> 
		</tr> 

		<tr> 
		<td>Password</td> 
		<td>:</td> 
		<td><input name="pass" type="password" id="pass"></td> 
		</tr> 

		<tr> 
		<td>Repeat Password</td> 
		<td>:</td> 
		<td><input name="rpass" type="password" id="rpass"></td> 
		</tr> 

		<tr> 
		<td></td> 
		<td></td> 
		<td><input type="submit" name="submit" value="Register"></td> 
		</tr> 

		</table> 
		</td> 
		</form> 
		</tr> 
		</table> 
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
	
	if($error_type == 2)
	echo $errors . "<br>";
	
	echo'
		<table align="center" cellpadding="0" cellspacing="1" border="1px solid black" id="tabs"> 

		<tr> 
		<form name="register" method="post" action="members.php"> 
		<td> 

		<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF"> 

		<tr> 
		<td colspan="3"><strong><center>Sign In</center></strong></td> 
		</tr> 

		<tr> 
		<td width="78">Account ID (Username)</td> 
		<td width="6">:</td> 
		<td width="294"><input name="user" type="text" id="user"></td> 
		</tr> 

		<tr> 
		<td>Password</td> 
		<td>:</td> 
		<td><input name="pass" type="password" id="pass"></td> 
		</tr> 

		<tr> 
		<td></td> 
		<td></td> 
		<td><input type="submit" name="submit" value="Login"></td> 
		</tr> 

		</table> 
		</td> 
		</form> 
		</tr> 
		</table> 
		';
}

/* 
* Check Registration Form for Errors
* @para $username, $email, $r_email (repeat email), $password, $r_password
* Errors = Create new error session then redirect to homepage
*/
function registration_check($username,$email,$r_email,$password,$r_password)
{
	
	/* Check if all fields are filled in */
	if($username == "" || $email == "" || $r_email == "" || $password == "" || $r_password == ""){
		$_SESSION['errors'] = "You need to fill out the whole form!";
		$_SESSION['error_type'] = 1;
		header("Location: index.php");
		exit();
		}
	
	/* Check if both emails matches */	
	else if($email != $r_email){
		$_SESSION['errors'] = "Emails do not match!";
		$_SESSION['error_type'] = 1;
		header("Location: index.php");
		exit();}
		
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
			session_destroy(); //destroy "error / error" type sessions
			session_start(); // Start session to store SUCCESS session
			$_SESSION['success'] = "You are now registered. Please Sign In!";
			header("Location: index.php");
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
	
	$password = encryption($password); //Encrypt Password with a simple MD5 encryption tool
	$query = "INSERT INTO user (username,email,password) VALUES ('$username','$email','$password')";
	$result = mysqli_query($connection,$query);
	
	/* If Result is true then add in a new folder into Profiles Folder 
	* True = We successful added the user into the database
	*/
	if($result){
		create_directory($username);
		return true;
	}
	else return false;
}


/* 
* Check Sign In Form for Errors
* @para $username, $password
* Errors = Create new error session then redirect to homepage
*/
function login_check($username,$password)
{
	/* Check if all fields are filled */
	if($username == "" || $password == ""){
		$_SESSION['errors'] = "You need to fill out the whole form!";
		$_SESSION['error_type'] = 2;
		header("Location: index.php");
		exit();
		}
	
	/* Check if the username exist in the database*/	
	else if(!user_exist($username)){
		$_SESSION['errors'] = "Username does not exist!";
		$_SESSION['error_type'] = 2;
		header("Location: index.php");
		exit();
		}
	
	/* Attempt to sign in member. If unsuccessful, the username (account id) and pass was not valid */
	else{
		$password = encryption($password); //Encrypt Password to Check if the Password matches the pass in the Database
		if(login($username,$password) == true){
			header("Location: index.php");
			exit();}
		
		else{
			$_SESSION['errors'] = "Invalid Account ID and Password!";
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
function login($username,$password)
{
	global $connection;
	
	/* Query to the DB to find a match */
	$query = mysqli_query($connection,"SELECT * FROM user where username ='$username' and password = '$password'");
	$row = mysqli_num_rows($query);
	
	/* A match is found. Create 2 sessions; one for the user's ID and one for the username*/
	if($row == 1){
		while($row = mysqli_fetch_array($query))
			$user_id = $row['user_id'];
		$_SESSION['user_id'] = $user_id;
		$_SESSION['user'] = $username;
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

/******************************************************************************************************************
* HELP FUNCTIONS! WILL BE MOVED ONTO A NEW FILE CALLED FUNCTIONS.PHP THAT WILL HOST ALL SIDE FUNCTIONS IN V 1.0.1 *
*******************************************************************************************************************/

/* Check if the username exist */
function user_exist($username)
{
	global $connection;
	
	$query = mysqli_query($connection,"SELECT * FROM user WHERE username = '$username'") or die("Can't connect to DB");
	$row = mysqli_num_rows($query);
	return ($row == 1)? true : false;
}

/* Check if the email exist */
function email_exist($email)
{
	global $connection;
	
	$query = mysqli_query($connection,"SELECT * FROM user WHERE email = '$email'");
	$row = mysqli_num_rows($query);
	return ($row == 1)? true : false;
}

/* Remove dirty characters to prevent MySQL injections. May not work. Not Tested */
function clean_string($word)
{
	global $connection;
	
	$clean_word = mysqli_real_escape_string($connection,$word);
	return $clean_word;
}

/* Encryption Function. Simple Encryption but if we have time, we can use salt for enhanced encryption */
function encryption($word)
{
	return md5($word);
}

/* Add new directory per registration. Used for retaining user's profile pictures */
function create_directory($username)
{
	global $connection;
	
	$query = mysqli_query($connection,"SELECT * FROM user WHERE username='$username'");
	$row = mysqli_num_rows($query);
	
	if($row == 1){
		while($row = mysqli_fetch_array($query))
			$user_id = $row['user_id'];}
		
	/* Add the directory then copy the image from the main image folder and place it in the user's folder (profile image)*/
	mkdir('profiles/' .$user_id . '_' .$username);
	install_profile_picture($user_id,$username);
}

/*Install default profile picture to the new profile folder*/
function install_profile_picture($user_id,$username)
{
	copy('css/images/default_profile.png','profiles/' .$user_id . '_' .$username.'/default_profile.png');
}

?>
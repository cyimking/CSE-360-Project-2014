<?php

/*
* Function Page. This will include all the global functions. 
* The "user" function page will have include all sign in /sign out functions from the members's file
*/

/* Check to see if the user is signed in or not*/
function check_session()
{
	if(isset($_SESSION['user_id']) && isset($_SESSION['user'])) return true;
	else return false;
}

/* Encryption Function. Simple Encryption but if we have time, we can use salt for enhanced encryption */
function encryption($word)
{
	return md5($word);
}

/* Protect Page so guest can not view certain pages!!*/
function protect_page()
{
	if(check_session() === false) {
	header("Location: 406.php");
	exit();}
}

/* Check if a string is greater than the paramater*/
function clip_string($string,$characters)
{
	if(strlen($string) > $characters){
		$string = substr($string, 0 , $characters);
		$string .= "...";
		return $string;
	}
	return $string;
}

/* Remove dirty characters to prevent MySQL injections. */
function sanitize($data,$type = "")
{
	global $connection;
	
	
	switch($type){
		case "string":
			$new_data = filter_var($data, FILTER_SANITIZE_STRING);
			$new_data = mysqli_real_escape_string($connection,$new_data);
			break;
		case "email":
			$new_data = filter_var($data, FILTER_SANITIZE_EMAIL);
			$new_data = mysqli_real_escape_string($connection,$new_data);
			break;
		case "int":
			$new_data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
			$new_data = mysqli_real_escape_string($connection,$new_data);
			break;
		default:
			$new_data = $data;
			$new_data = mysqli_real_escape_string($connection,$new_data);
			break;
	}
	
	return $data;
}

?>
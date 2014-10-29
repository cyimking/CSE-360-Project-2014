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

?>
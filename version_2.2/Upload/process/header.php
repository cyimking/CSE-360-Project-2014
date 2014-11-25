<?php
/*********************************************************************************************
Header File

Description- 
 * Display on all pages keep things easier. We do not have to keep copying the header files, 
includes, and other behind the scenes functions in every file. We can just include this file
 * Include the config.php (connection to the database)
 * Starts a session. A session is a connection to the database
**********************************************************************************************/

session_start();  // Start session go http://php.net/manual/en/function.session-start.php for more information

/*********************************************************************************************/
/* 									Includes												 */
/*********************************************************************************************/

require 'config.php';
require 'global_function.php';

/*********************************************************************************************/
/* 									Functions 												 */
/*********************************************************************************************/

/**********************************************************************************************
Check to see if the user is logged on. (Check if the user had set a session). 
Return True if session is found.
Return False if session is not found.
**********************************************************************************************/

function display_header()
{
	global $connection;
	
	if(check_session() == false)
	{
		//Header will be LOGO to LEFT, SIGN IN OR CREATE EVENT TO RIGHT
		echo "
			<div id='header'>
			 <span id='primary_links_left_end_logo' style='margin: -10px;position:absolute; left: 0px '><a href='index.php'>
			<img src='http://icons.iconarchive.com/icons/dapino/cute-chicken/48/party-chicken-icon.png'></a></span>			 
			 <span id='primary_links_right_end'><a href='index.php' class='login_button'> Sign In or Create Account </a> </span>  &nbsp;
			 </div>
		      ";
	}
	
	else
	{
		$id = $_SESSION['user_id'];
		$query = mysqli_query($connection, "SELECT username FROM user WHERE user_id = '$id'");
		while($row = mysqli_fetch_array($query)){
		$username = $row['username'];}
		
		
		
		echo "
			<div id='header'>						 
			<span id='primary_links_left_end_logo' style='margin: -10px;position:absolute; left: 0px '><a href='index.php'>
			<img src='css/images/logo.png'></a></span>		
			
			<span id='primary_links_left_end'><a href='events.php'> Explore </a></span>
			
			<span id='primary_links_right_end' class='tooltip' data-tooltip='Sign Out'>
				<a href='members.php?action=signout' id='signoutss'>
					<span class='primary_links_signout'></span>
				</a> 
			</span>
			
			<span id='primary_links_right' class='tooltip' data-tooltip='Setting'>
				<a href='settings.php' id='settingss'>
					<span class='primary_links_setting'></span>
				</a> 
			</span>
		
	
			<span id='primary_links_right' class='tooltip' data-tooltip='Add Event' >
				<a href='create_event.php'>
					<span class='primary_links_event'></span>
				</a> 
			</span> 
			
			<span id='primary_links_right'>
				<a href='".$_SESSION['user']."'>".$_SESSION['user']." </a> 
			</span> 
			
			<span style='float:right; margin-right: 10px; margin-top: -5px'>
				<a href='".$_SESSION['user']."'>
					<img src='profiles/".$_SESSION['user_id']."_".$username."/default_profile.png' height='35px' width='35px' style='border-radius: 3px;'>
				</a>
			</span>
			

			</div>
		      ";
	}
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/main.css" />
<link rel="icon" type="image/ico" href="favico.ico"/>

<?php display_header(); ?>

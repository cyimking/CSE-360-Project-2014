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
	if(check_session() == false)
	{
		//Header will be LOGO to LEFT, SIGN IN OR CREATE EVENT TO RIGHT
		echo "
			<div id='header'>
			 <span id='primary_links_left'><a href='index.php'><img src='css/images/logo.png'></a></span>			 
			 <span id='primary_links_right_end'><a href='index.php' class='login_button'> Sign In or Create Account </a> </span>  &nbsp;
			 </div>
		      ";
	}
	
	else
	{
		echo "
			<div id='header'>						 
			<span id='primary_links_left'><a href='index.php'><img src='css/images/logo.png'></a></span>		
			<span id='primary_links_right_end'><a href='members.php?action=signout'> Sign Out </a> </span> &nbsp;
			<span id='primary_links_right'><a href='settings.php'> Setting </a> </span> &nbsp;
			<span id='primary_links_right'><a href='profile.php?id=".$_SESSION['user_id']."'> Profile </a> </span> &nbsp;
			<span id='primary_links_right'><a href='events.php?action=create_event'> Create Event </a> </span> &nbsp;
			<span id='primary_links_right'><a href='events.php'> Directory </a></span> 
			<span id='primary_links_right'><a href='index.php'> Home </a></span> &nbsp;
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

<?php display_header(); ?>

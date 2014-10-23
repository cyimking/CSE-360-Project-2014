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

/*********************************************************************************************/
/* 									Functions 												 */
/*********************************************************************************************/

/**********************************************************************************************
Check to see if the user is logged on. (Check if the user had set a session). 
Return True if session is found.
Return False if session is not found.
**********************************************************************************************/
function check_session()
{
	if(isset($_SESSION['user_id']) && isset($_SESSION['user'])) return true;
	else return false;
}

function display_header()
{
	if(check_session() == false)
	{
		//Header will be LOGO to LEFT, SIGN IN OR CREATE EVENT TO RIGHT
		echo "
			<div id='header' style='border-bottom: thin solid #6487c0; margin-left: 10px; margin:10px;padding: 10px;'>
			 <span style='float: left; margin-left: 10px;'> LOGO </span>			 
			 <span style='float: right; margin-right: 10px;'> Login </span>  &nbsp;
			 <span style='float: right; margin-right: 10px'><a href='index.php'> Home </a> </span> &nbsp;
			 </div>
		      ";
	}
	
	else
	{
		echo "
			<div id='header' style='border-bottom: thin solid #6487c0; margin: 10px; padding: 10px'>
			 <span style='float: left; margin-left: 10px'> LOGO </span> 
			 <span style='float: right; margin-right: 10px'><a href='members.php?action=signout'> Sign Out </a> </span> &nbsp;
			 <span style='float: right; margin-right: 10px'><a href='profile.php?id=".$_SESSION['user_id']."'> Profile </a> </span> &nbsp;
			 <span style='float: right; margin-right: 10px'> Create Event </span> &nbsp;
			 <span style='float: right; margin-right: 10px'><a href='index.php'> Home </a></span> &nbsp;
			 </div>
		      ";
	}
}



display_header();

?>


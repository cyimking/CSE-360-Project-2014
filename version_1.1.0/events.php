<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/main.css" />


<?php
$homepage = true; //By default we are on the "index" page

/* Check if the session is set, if not then set it and include the header file */
if(!isset($_SESSION)) 
    { 
		$homepage = false;
		include "process/header.php";
    } 


?>

<!-- These functions will be moved onto the JS function! -->
<!-- Load jQuery from Google's CDN -->
    <!-- Load jQuery UI CSS  -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    
    <!-- Load jQuery JS -->
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <!-- Load jQuery UI Main JS  -->
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    
<script>
  $(document).ready(
  
  /* This is the function that will get executed after the DOM is fully loaded */
  function () {
    $( "#datepicker" ).datepicker({
      changeMonth: true,//this option for allowing user to select month
      changeYear: true //this option for allowing user to select from year range
    });
  } 
);

</script>
</head>


<div id="body_base">
<div id="wrapper">

<?php

//Event Page

/********************************************************************
 ********************************************************************
 * Event File - Handle all functions for the Member's Page        *
 * Version 1.0 (Status - Incomplete)                                *
 ********************************************************************
 ********************************************************************
 */

/* Check for an error session. If found, displayed errors. 
* Error Type == 3 = Add Event Error
*/
if(isset($_SESSION['errors']) && isset($_SESSION['error_type'])){
	$errors = $_SESSION['errors'];
	$error_type = $_SESSION['error_type'];}



/* Check to see if the user POST an Action */
if(isset($_POST['submit']))
{
	$action = $_POST['submit'];
	
	switch($action){
		case "Create Event":
			$title = clean_string($_POST['title']);
			$type = clean_string($_POST['type']);
			$venue = clean_string($_POST['venue']);
			$date = clean_string($_POST['date']);
			$time = clean_string($_POST['time']);
			$max_tickets = clean_string($_POST['tickets']);
			check_add_event($title,$type,$venue,$date,$time,$max_tickets);
			break;
		
		default:
			echo "Invalid Link";
			break;
			}
}

/* Check to see if the user GET as an Action */
else if(isset($_GET['action']))
{

	$action = $_GET['action'];
	
	switch($action){
		case "create_event":
			display_add_event_table();
			break;
		
		case "purchase";
			display_purchase_table($_GET['do']);
			break;
				
		default:
			header("Location: index.php");
			exit();
			break;
	}
}


else
{
	//Display our events :))))))
	if($homepage == true)
		display_events();
	
	else{ 
		protect_page();
		display_events();}
}

/* Displays all the events */
function display_events()
{
	/* Check if the DB is empty*/
	global $connection;
	
	$query = mysqli_query($connection, "SELECT * FROM events");
	$row = mysqli_num_rows($query);
	
	if($row == 0)
		echo "There are no events! Please create one today!";
		
	else
	{
		echo "  <br><table id='event_table' align ='center'>
				<tr>
				<th>Type</th>
				<th>Title</th>
				<th>Venue</th>
				<th>Date</th>
				<th>Time</th>
				<th>Buy</th>
				<tr>
			 ";
			 
		while($row = mysqli_fetch_array($query))
		{
			/* Check if the user can purchase a ticket!*/
			$check_tickets = $row['max_tickets'] - $row['tickets_bought'];
			if($check_tickets != 0) $purchase = "<a href='events.php?action=purchase&do=".$row['event_id']."'>Buy</a>";
			else $purchase = "Sold Out!";
			
			echo 
			"
				<tr>
				<td>".$row['type']."</td>
				<td>".$row['title']."</td>
				<td>".$row['venue']."</td>
				<td>".date('m/d/y',$row['date_of_event'])."</td>
				<td>".date('h:i A',$row['date_of_event'])."</td>
				<td>".$purchase."</td>
				</tr>
			";
			
		}
		
		echo "</table>";
		
	}
	
}

/* Display the Create an Event Table*/
function display_add_event_table()
{
	global $error_type;
	global $errors;
	
	if($error_type == 3)
	echo "<br><div align='center' style='color: red'>" . $errors . "</div>";
	
	echo'
		<br>
		Please follow the formats or you will receive an error! 
		<br><br>Date = MM/DD/YYYY (10/27/2014)
		<br><br>Time = HH:MM PM or AM (12:00 PM) (12:00 AM). AM/PM can be in lower case!
		<br>
		<br>
		<table align="center" cellpadding="0" cellspacing="1" border="1px solid black" id="tabs"> 

        <tr> 
        <form name="Create Event" method="post" action="events.php"> 
		<td> 

		<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF"> 
		<tr> 
		<td colspan="3"><strong><center>Create Event</center></strong></td> 
		</tr> 

		<tr> 
		<td width="78">Title</td> 
		<td width="6">:</td> 
		<td width="294"><input name="title" type="text" id="title"></td> 
		</tr> 
		
		<tr> 
		<td>Type</td> 
		<td>:</td> 
		<td><input name="type" type="text" id="type"></td> 
		</tr> 
		
		<tr> 
		<td>Venue</td> 
		<td>:</td> 
		<td><input name="venue" type="text" id="venue"></td> 
		</tr> 

		<tr> 
		<td>Date</td> 
		<td>:</td> 
		<td><input name="date" type="text" id="datepicker"></td> 
		</tr> 

		<tr> 
		<td>Time</td> 
		<td>:</td> 
		<td><input name="time" type="text" id="time"></td> 
		</tr> 
		
		<tr> 
		<td>Total Tickets</td> 
		<td>:</td> 
		<td><input name="tickets" type="text" id="tickets"></td> 
		</tr> 

		<tr> 
		<td></td> 
		<td></td> 
		<td><input type="submit" name="submit" value="Create Event"></td> 
		</tr> 

		</table> 
		</td> 
		</form> 
		</tr> 
		</table> 
		';
}

/* Check if the data from the ADD EVENT table is valid*/
function check_add_event($title,$type,$venue,$date,$time,$max_tickets){
	/* Check if the form was filled all the way! */
	if($type == "" || $type == "" || $venue == "" || $date == "" || $time == "" || $max_tickets == ""){
		$_SESSION['errors'] = "You need to fill out the whole form!";
		$_SESSION['error_type'] = 3;
		header("Location: events.php?action=create_event");
		exit();
		}
		
	/* Check if the user into a string that's less than 32 letters! */
	else if((strlen($title) > 32 || strlen($title) < 4|| strlen($type) > 32 || strlen($type) < 4 || strlen($venue) > 32) || strlen($venue) < 4){
		$_SESSION['errors'] = "The maximum amount of letters for your Title, Type or Venue must be less than 4 - 32 Letter!";
		$_SESSION['error_type'] = 3; 
		header("Location: events.php?action=create_event");
		exit();
	}
	
	/* Check if the Tickets are integers! */
	else if(!ctype_digit($max_tickets))
	{
		$_SESSION['errors'] = "Please enter an integer for the total amount of tickets!";
		$_SESSION['error_type'] = 3;
		header("Location: events.php?action=create_event");
		exit();
	}
	
	/* Check if the date is valid!*/
	else if(!check_date($date))
	{
		$_SESSION['errors'] = "Please use the correct format for the date!";
		$_SESSION['error_type'] = 3;
		header("Location: events.php?action=create_event");
		exit();
	} 
	
	/* Check if the date is before the current date */
	else if(strtotime($date) < strtotime('now'))
	{
		$_SESSION['errors'] = "Your event time must be after the today's date!";
		$_SESSION['error_type'] = 3;
		header("Location: events.php?action=create_event");
		exit();
	}
	
	/* Check if the time entered is in the correct form */
	else if(!check_valid_time($time))
	{
		$_SESSION['errors'] = "Please enter a valid time for your event";
		$_SESSION['error_type'] = 3;
		header("Location: events.php?action=create_event");
		exit();
	}
	
	else
		if(add_event($title,$type,$venue,$date,$time,$max_tickets)){
			header("refresh:5; url=events.php");
			echo "<br><h1>You have successfully added an event! You will be redirected now.</h1>";
			unset($_SESSION['errors']);
			unset($_SESSION['error_type']);
			exit();
		}
		
		else
			echo "Sorry, we are unable to add in your event. Please try again";
		
}

/* 
* Add the event to DB
* @para $title,$type,$venue,$date,$time,$max_tickets
* @return True if we successful added the event to the DB
* @return False if we are unsuccessful
*/
function add_event($title,$type,$venue,$date,$time,$max_tickets)
{
	global $connection;
	
	$user_id = $_SESSION['user_id'];
	$new_date = $date . " " . $time;
	$new_date = strtotime($new_date);
	
	$query = mysqli_query($connection, "INSERT INTO events (user_id,title,type,venue,date_of_event,date_of_creation,max_tickets,tickets_bought)
	VALUES ('$user_id','$title','$type','$venue','$new_date',NOW(),'$max_tickets','0')");
	
	if($query)
		return true;
	else
		return false;
}

/* FUNCTION NOT COMPLETED */
function display_purchase_table($event_id)
{
	
	//First check if the event id is valid
	if(!check_event_id($event_id))
	{
		echo "<br><h1>Sorry the event you enter does not exist!</br>";
	}
	
	else{
		echo "Working";
	}
}

/***************************************************************************
*      Helper Functions. Will be moved to a "event_function" file          *
***************************************************************************/

/* Check if the event id from the Purchase Function is valid */
function check_event_id($id)
{
	global $connection;
	
	$query = mysqli_query($connection,"SELECT * FROM events WHERE event_id='$id'");
	$row = mysqli_num_rows($query);
	
	return $row == 1 ? true: false;
}

/* Check if the date entered in the Create Event Function Valid */
function check_date($date)
{
	if(false === strtotime($date) )
		return false;
		
	else{
		list($month, $day, $year) = explode('/',$date);
		if(!checkdate($month, $day, $year)) return false;
	}
	
	return true;
}


/* Check if the time entered in the Create Event Function Valid */
function check_valid_time($time)
{
	$delimiters = '/^(0?\d|1[0-2]):[0-5]\d\s(am|pm)$/i';
	if(preg_match($delimiters, $time)) return true;
	else return false;
}
?>


<br />
<br />
</div>
</div>
</html>
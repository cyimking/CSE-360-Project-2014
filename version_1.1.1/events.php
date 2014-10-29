<?php
/*********************************************************************
 *********************************************************************
 * Event File - Handle all functions for the Events's Page           *
 * Version 1.0.0 (Status - Complete)                                 *
 	 * All functions are located "process / event_functions.php      *
 *********************************************************************
 *********************************************************************
 */
?>


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
	
include "process/event_functions.php";

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


<body>
<div id="wrapper">

<?php

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
		
		case "Purchase":
			$tickets = clean_string($_POST['tickets']);
			$event_id = clean_string($_POST['event_id']);
			purchase_event($tickets,$event_id);
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
?>


<br />
<br />
</div>
</body>
</html>
<?php
/*********************************************************************
 *********************************************************************
 * Event File - Handle all functions for the Events's Page           *
 * Version 1.0.0 (Status - Complete)                                 *
 	 * All functions are located "process / event_functions.php      *
 *********************************************************************
 *********************************************************************
 */
 
$homepage = true; //By default we are on the "index" page

/* Check if the session is set, if not then set it and include the header file */
if(!isset($_SESSION)) 
    { 
		$homepage = false;
		include "process/header.php";
    } 
	
include "process/event_functions.php";

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
		case "Purchase":
			$tickets = sanitize($_POST['tickets'],"int");
			$event_id = sanitize($_POST['event_id'],"int");
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
		case "edit";
			display_edit($_GET['id']);
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

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" href="css/events.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="js/main.js"></script>	
    <script src="js/events.js"></script>
</head>

<body>
<div class='space'>
</div>
</body>
<div id="footer">
<div class='footer_text'>
Created by Lamar, Bikram and Ian. Enjoy!
</div>
</div>

</html>
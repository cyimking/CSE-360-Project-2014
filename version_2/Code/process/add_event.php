<?php

include "header.php";

if(isset($_POST['submit']))
{
	$action = $_POST['submit'];
	
	switch($action){
		case "Create Event":
			$title = sanitize($_POST['title'],"string");
			$type = sanitize($_POST['type'],"string");
			$venue = sanitize($_POST['venue'],"string");
			$description = sanitize($_POST['description']);
			$date = sanitize($_POST['date']);
			$start_time = sanitize($_POST['start_time']);
			$end_time = sanitize($_POST['end_time']);
			$ticket_name =  NULL;//sanitize($_POST['ticket_name']); Coming Soon
			$max_tickets = sanitize($_POST['max_tickets'],"int");
			$max_tickets_pp = NULL; // sanitize($_POST['max_tickets_purchase'],"int"); Coming Soon 
			$upload_check = 1;
			$image_name = $_FILES['image']['name'];
   		    $image_type = $_FILES['image']['type'];
    		$image_size = $_FILES['image']['size'];
    		$image_tmp_name = $_FILES['image']['tmp_name'];
			$target_dir = "../css/images/events/" .$image_name;
			$target_file = $target_dir . basename($_FILES["image"]["name"]);	
			$imageFileType = explode('.',$image_name);
			$imageFileType = strtolower(end($imageFileType));
			$all_ext = array('png','jpeg','gif','jpg');
			

	//ADD EVENT
	
	if($description == NULL)
		$description = "No Description for Event";
		
	if($ticket_name == NULL)
		$ticket_name = "Default Tickets";
	
	if($max_tickets_pp == NULL)	
		$max_tickets_pp = $max_tickets;

	
	if($image_name == '') {
            echo "<br><script>alert('please select the image!!')</script>";
			die("Must enter an image! Please try again on the settings page");
        }		
			
    //Check if the file is a image or not
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	
    if($check !== false) {
        $upload_check = 1;
    } 
	else {
        $_SESSION['errors'] = "File isn't an image!";
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();
    }

	if(in_array($imageFileType,$all_ext) != true)
	{
		$_SESSION['errors'] = "Incorrect File Extension!";
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();
	}
       
	else if ($_FILES["image"]["size"] > 500000) {
    	$_SESSION['errors'] = "Sorry your file is too big!";
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();	
		}

	else if($upload_check == 0)
		{
			$_SESSION['errors'] = "Sorry we can not upload your image!";
			$_SESSION['error_type'] = 3;
			header("Location: ../events.php?action=create_event");
			exit();
		}
	
		
	/* Check if the form was filled all the way! */
	else if($type == "" || $type == "" || $venue == "" || $date == "" || $start_time == "" || $end_time == ""  || $max_tickets == "" || $max_tickets_pp == ""){
		$_SESSION['errors'] = "You need to fill out the whole form!";
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();
		}
		
	/* Check if the user into a string that's less than 32 letters! */
	else if((strlen($title) > 32 || strlen($title) < 4|| strlen($type) > 32 || strlen($type) < 4 || strlen($venue) > 32) || strlen($venue) < 4 ){
		$_SESSION['errors'] = "The maximum amount of letters for your Title, Type or Venue must be less than 4 - 32 Letter!";
		$_SESSION['error_type'] = 3; 
		header("Location: ../events.php?action=create_event");
		exit();
	}
	
	/* Check if the Tickets are integers! */
	else if(!ctype_digit($max_tickets))
	{
		$_SESSION['errors'] = "Please enter an integer for the total amount of tickets!";
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();
	}
	
	/* Check if the Tickets are integers! */
	else if(!ctype_digit($max_tickets_pp))
	{
		$_SESSION['errors'] = "Please enter an integer for the total amount of tickets that can be purchased per transaction!";
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();
	}
	
	/* Check if the date is valid!*/
	else if(!check_date($date))
	{
		$_SESSION['errors'] = "Please use the correct format for the date!";
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();
	} 
	
	/* Check if the date is before the current date */
	else if(strtotime($date) < strtotime('now'))
	{
		$_SESSION['errors'] = "Your event time must be after the today's date!";
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();
	}
	
	/* Check if the time entered is in the correct form */
	else if(!check_valid_time($start_time))
	{
		$_SESSION['errors'] = $start_time;
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();
	}
	
	else if(!check_valid_time($end_time))
	{
		$_SESSION['errors'] = "Please enter a valid end time for your event";
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();
	}
	
	else if(strtotime($start_time) > strtotime($end_time))
	{
		$_SESSION['errors'] = "Please enter a valid ending date. We do not allow overnight events.";
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();
	}
	
	else if($max_tickets_pp > $max_tickets)
	{
		$_SESSION['errors'] = "Maximum Tickets per Purchase can not be greater than the Tickets Available";
		$_SESSION['error_type'] = 3;
		header("Location: ../events.php?action=create_event");
		exit();
	}

	else
		$image_name = substr(md5(time()),0,15) . "." .$imageFileType;
		if(add_event($title,$type,$venue,$description,$date,$start_time,$end_time,$ticket_name,$max_tickets,$max_tickets_pp,$image_name) == TRUE)
		{	
			 move_uploaded_file($image_tmp_name, "../css/images/events/" .$image_name);
			 header("Location: ../index.php");
		}
		
		else
		{
			$_SESSION['errors'] = "Unable to make this request. Try Again Later!";
			$_SESSION['error_type'] = 3;
			header("Location: ../events.php?action=create_event");
			exit();
		}

	break;
		
		default:
			header("Location: ../index.php");
			break;
	     }
		
	}
	
	
function add_event($title,$type,$venue,$description,$date,$start_time,$end_time,$ticket_name,$max_tickets,$max_tickets_pp,$image_name)
{
	global $connection;
	
	$user_id = $_SESSION['user_id'];
	$new_date = $date . " " . $start_time;
	$new_date = strtotime($new_date);
	$end_time = strtotime($end_time);
	
	
	$query = mysqli_query($connection, "INSERT INTO events (user_id,event_logo,title,event_description,	
												type,venue,date_of_event,event_ending_time,date_of_creation,
												ticket_name,max_tickets,max_tickets_per_purchase,tickets_bought)
										VALUES 
											('$user_id','$image_name','$title','$description','$type','$venue',
											'$new_date','$end_time',NOW(),'$ticket_name',
											'$max_tickets','$max_tickets_pp','0')");
	
	if($query)
		return true;
	else
		return false;
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
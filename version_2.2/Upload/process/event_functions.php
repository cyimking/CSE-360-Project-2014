<?php
/*********************************************************************/
/*********************************************************************/
/*						Display Events Functions                     */
/* FIX - Fix Purchase Bug (Now Functioning)                          */
/*********************************************************************/
/*********************************************************************/
function display_events()
{
	/* Check if the DB is empty*/
	global $connection;
	
	$query = mysqli_query($connection, "SELECT * FROM events");
	$row = mysqli_num_rows($query);
	
	echo "<title>Explore</title>
		<div style='margin-left: 25%'>";
	
	if($row == 0)
		echo "There are no events! Please create one today!";
		
	else
	{
		
			 
		while($row = mysqli_fetch_array($query))
		{
			/* Check if the user can purchase a ticket!*/ 
			// note to self! Check if the current date is greater than the DB date. 
			$puchase_c = 0;
			
			$check_tickets = $row['max_tickets'] - $row['tickets_bought'];
			if($check_tickets != 0) $purchase = "<a href='events.php?action=purchase&do=".$row['event_id']."'>Buy</a>";
			else{ 
				$purchase = "Sold Out!";
				
			}
			
			if(time() > $row['date_of_event'])
			{
				 $purchase = "Event Passed";
				$puchase_c = 1;
			}
			
			if($puchase_c == 0){
			
			echo 
			"
				
				<br>
				<div class='events' id='event_".$row['event_id']."'>
    				<div class='left'><img src='css/images/events/".$row['event_logo']."' height='150px' width='150px'></div>
   				    <div class='right'>
    					<p class='title_event'>".clip_string($row['title'], 50)."</p>
				        <p class='type_event'>" .clip_string($row['type'], 20)." by ".clip_string($row['venue'], 20)."</p>
        				<p class='date_event'>
							<img src='http://png-3.findicons.com/files/icons/987/niome/16/calendar.png'>  " . date('F j, Y',$row['date_of_event'])." ".date('h:i A',$row['date_of_event'])."</p>
        				<p class='location_event'>".$purchase."</p>
    				</div>
    				</div>
				<br>
				
			";
			
		}
		}
		echo "</table></div>";
		
	}
	
}

/*********************************************************************/
/*********************************************************************/
/*						Create Events Functions                      */
/*********************************************************************/
/*********************************************************************/


/* 
* Check if the form is valid
* @para $title,$type,$venue,$date,$time,$max_tickets
*/
function check_add_event($title,$type,$venue,$description,$date,$start_time,$end_time,$ticket_name,$max_tickets,$max_tickets_pp){
	if($description == NULL)
		$description = "No Description for Event";
		
	if($ticket_name == NULL)
		$ticket_name = "Default Tickets";
	
	if($max_tickets_pp == NULL)	
		$max_tickets_pp = $max_tickets;
	
	/* Check if the form was filled all the way! */
	if($type == "" || $type == "" || $venue == "" || $date == "" || $start_time == "" || $end_time == ""  || $max_tickets == "" || $max_tickets_pp == ""){
		$_SESSION['errors'] = "You need to fill out the whole form!";
		$_SESSION['error_type'] = 3;
		header("Location: events.php?action=create_event");
		exit();
		}
		
	/* Check if the user into a string that's less than 32 letters! */
	else if((strlen($title) > 32 || strlen($title) < 4|| strlen($type) > 32 || strlen($type) < 4 || strlen($venue) > 32) || strlen($venue) < 4 ){
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
	
	/* Check if the Tickets are integers! */
	else if(!ctype_digit($max_tickets_pp))
	{
		$_SESSION['errors'] = "Please enter an integer for the total amount of tickets that can be purchased per transaction!";
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
	else if(!check_valid_time($start_time))
	{
		$_SESSION['errors'] = $start_time;
		$_SESSION['error_type'] = 3;
		header("Location: events.php?action=create_event");
		exit();
	}
	
	else if(!check_valid_time($end_time))
	{
		$_SESSION['errors'] = "Please enter a valid end time for your event";
		$_SESSION['error_type'] = 3;
		header("Location: events.php?action=create_event");
		exit();
	}
	
	else if(strtotime($start_time) > strtotime($end_time))
	{
		$_SESSION['errors'] = "Please enter a valid ending date. We do not allow overnight events.";
		$_SESSION['error_type'] = 3;
		header("Location: events.php?action=create_event");
		exit();
	}
	
	else if($max_tickets_pp > $max_tickets)
	{
		$_SESSION['errors'] = "Maximum Tickets per Purchase can not be greater than the Tickets Available";
		$_SESSION['error_type'] = 3;
		header("Location: events.php?action=create_event");
		exit();
	}

	else
		if(add_event($title,$type,$venue,$description,$date,$start_time,$end_time,$ticket_name,$max_tickets,$max_tickets_pp)){
			header("refresh:3; url=events.php");
			echo "<br><h1>You have successfully added an event! You will be redirected now.</h1>";
			unset($_SESSION['errors']);
			unset($_SESSION['error_type']);
			return true;
		}
		
		else
			die("System Failure. It rarely happens but when it do - it our fault.");
	}
		


/* 
* Add the event to DB
* @para $title,$type,$venue,$date,$time,$max_tickets
* @return True if we successful added the event to the DB
* @return False if we are unsuccessful
*/
function add_event($title,$type,$venue,$description,$date,$start_time,$end_time,$ticket_name,$max_tickets,$max_tickets_pp)
{
	global $connection;
	
	$user_id = $_SESSION['user_id'];
	$new_date = $date . " " . $start_time;
	$new_date = strtotime($new_date);
	$end_time = strtotime($end_time);
	
	
	$query = mysqli_query($connection, "INSERT INTO events (user_id,title,event_description,	
												type,venue,date_of_event,event_ending_time,date_of_creation,
												ticket_name,max_tickets,max_tickets_per_purchase,tickets_bought)
										VALUES 
											('$user_id','$title','$description','$type','$venue','$new_date','$end_time',NOW(),'$ticket_name',
											'$max_tickets','$max_tickets_pp','0')");
	
	if($query)
		return true;
	else
		return false;
}

/* 
* Display the Add Event Table
*/
function display_add_event_table()
{
	global $error_type;
	global $errors;
	
	
	echo"
		<title>Event Creation</title>
		<div id='content_top'>
		<span style='color: white; font-size:72px;'>Event Creation Tool</span>
		</div>

		<div>
		<div id='wrapper'>
		<div id='create_event'>

		<br><br>
		<form name='Create Event' method='post' action='process/add_event.php' enctype='multipart/form-data'> 
	";
	
	if($error_type == 3){
		echo "<br><div align='center' style='color: red'>" . $errors . "</div><br>";
		unset($_SESSION['errors']);
		unset($_SESSION['error_type']);
		}
	echo "
	<div id='event_details'>
		<span id='step1_design'>1</span>
		<br>
		<br>
		<hr style='margin-top: -2px; background-color: #D2D2D2'>
		<h1 style='margin-top: -50px; text-align: left; font-weight: lighter; font-size: 24px; padding: 6px'>
		<span style='margin-left: 5px;'>Event Details</span></h1>
		<p style='font-size:20px'>Event Logo</p>

		<img id='blah' src='http://www.laboratoires-tbc.net/sites/default/files/default_images/default_1.jpg' alt='your image' />
		<br>
		<div id='uploadBtn' class='file_event' style='margin: 0 auto;'>
		<span class='uploadBtn1'>Browse</span>
		<input type='file' class='file_button' name='image' id='image' onchange='readURL(this);'  accept='image/gif, image/png,image/jpeg'>
		</div>

		<p style='font-size:20px'>Type of Event *</p>
		<select name='type' required='required' placeholder='Select an event type!' style='padding: 15px; font-size: 16px'  class='register_input'> 
			<option value='Arts'> Arts </option>
			<option value='Business'> Business </option>
			<option value='Charity'> Charity </option>
			<option value='Community'> Community </option>
			<option value='Family & Education'> Family & Education</option>
			<option value='Food & Drink'> Food & Drink </option>
			<option value='Gaming'> Gaming </option>
			<option value='Health'> Health </option>
			<option value='Music'> Music </option>
			<option value='Other'> Other </option>
			<option value='Science & Tech'> Science & Tech </option>
			<option value='Spirituality'> Spirituality </option>
			<option value='Sports & Fitness'> Sports & Fitness </option>
 		</select>
		<br>

		<p style='font-size:20px'>Title of your Event *</p>
		<input type='text' size='90%' class='register_input' name='title' maxlength='32'
			placeholder='What is the name of your event?' required='required' style='padding: 15px; font-size: 16px'>
		<br>

		<p style='font-size:20px'>Venue Spot *</p>
		<input name='venue' type='text'  size='90%' class='register_input' maxlength='32' 
			placeholder='What is the venue of your event?' required='required' style='padding: 15px; font-size: 16px'>
		<br>

		<p style='font-size:20px'>Details for your Event (Optional)</p>
		<textarea cols='90%' rows='7' class='register_input' placeholder='Provide a short description of your event! This is fully optional' 	name='description' style='padding: 15px; font-size: 16px'></textarea>
	</div> 

<br><br>

	<div id='tickets_details'>
		<span id='step2_design'>2</span>
		<br>
		<br>
		<hr style='margin-top: -3px; background-color: #D2D2D2'>
		<h1 style='margin-top: -50px; text-align: left; font-weight: lighter; font-size: 24px; padding: 6px'>
		<span style='margin-left: 5px;'>Date Information</span></h1>
		<p style='color:red'>Please use this format for the starting time / ending time HH:MM AM or PM</p>
		<span style='margin-right: 15%;font-size:20px'>Event's Starting Time *</span>
		<span style='font-size:20px'>Event's Ending Time *</span>
		<br>
		<input type='text' required='required' name='start_time' style='margin-right: 20%; padding: 10px;width: 100px'  class='register_input_date'> 
		<input type='text' required='required' name='end_time' style='padding: 10px;width: 100px' class='register_input_date'> 
		<br>
		<span style='font-size:20px'>Event's Date *</span><br>
		<input name='date' type='text' id='datepicker' required='required' style='padding: 10px; text-align:center' class='register_input_date'>
	</div>

<br><br>

	<div id='confirm_details'>
		<span id='step3_design'>3</span>
		<br>
		<br>
		<hr style='margin-top: -3px; background-color: #D2D2D2'>
		<h1 style='margin-top: -50px; text-align: left; font-weight: lighter; font-size: 24px; padding: 6px'>
		<span style='margin-left: 5px;'>Tickets Information *</span></h1>
		<p style='font-size:20px;'>Ticket Name (Optional)</p>
		<input type='text' size='90%' name='ticket_name' maxlength='16' class='register_input' placeholder='Name of your Ticket...' style='padding: 15px; font-size: 16px'>
		<p style='font-size:20px;'>Ticket Available *</p>
		<input type='text' size='10%' name='max_tickets' class='register_input' required='required' placeholder='Max Tickets' style='padding: 15px; font-size: 16px;text-align: center'>
		<p style='font-size:20px' title='How many tickets can someone purchase?' >Maximum Tickets Per Purchase *</p>
		<input type='text' name='max_tickets_purchase' size='10%' class='register_input' placeholder='Max Tickets' style='padding: 15px; font-size: 16px;text-align: center'>
	</div>

<br><br>

	<div id='confirm_details'>
		<span id='step4_design'>4</span>
		<br>
		<br>
		<hr style='margin-top: -3px; background-color: #D2D2D2'>
		<h1 style='margin-top: -50px; text-align: left; font-weight: lighter; font-size: 24px; padding: 6px'>
		<span style='margin-left: 5px;'>Confirm Event</span></h1>

		<div style=''>
			<span style='font-size:20px'>Is Everything Correct?</span><br>
			<input type='submit' value='Add New Event' name='submit' class='file_event' style='width: 	auto;background-color:#00a300;border:#6d96b8;cursor:pointer;padding: 10px;'>
		</div>
		
	</div>
<br>
</form>
</div>
</div>
		";
}


function display_edit($id)
{
	global $error_type;
	global $errors;
	global $connection;
	$user_id = $_SESSION['user_id'];
	//Get Data 
	
	$query = mysqli_query($connection,"SELECT * FROM events WHERE event_id = '$id' AND user_id = '$user_id'") or die("This event does not exist!");

	$rows = mysqli_num_rows($query);
	
	if($rows === 0)
	{
		header("refresh:3; url=events.php");
        echo "You do not have permission to edit this event!. <br>You are being redirected now...";
        exit();
	}
	
	else{
		
		while($row = mysqli_fetch_array($query)){
		$start_date = date('h:i A',$row['date_of_event']);;
		$end_date =  date('h:i A',$row['event_ending_time']);
		$date = date('d/m/Y',$row['date_of_event']);
		
	echo"
		<title>Event Creation</title>
		<div id='content_top'>
		<span style='color: white; font-size:72px;'>Event Creation Tool</span>
		</div>

		<div>
		<div id='wrapper'>
		<div id='create_event'>

		<br><br>
		<form name='Create Event' method='post' action='process/add_event.php' enctype='multipart/form-data'> 
	";
	
	if($error_type == 3){
		echo "<br><div align='center' style='color: red'>" . $errors . "</div><br>";
		unset($_SESSION['errors']);
		unset($_SESSION['error_type']);
		}
	echo "
	<div id='event_details'>
		<span id='step1_design'>1</span>
		<br>
		<br>
		<hr style='margin-top: -2px; background-color: #D2D2D2'>
		<h1 style='margin-top: -50px; text-align: left; font-weight: lighter; font-size: 24px; padding: 6px'>
		<span style='margin-left: 5px;'>Event Details</span></h1>
		<p style='font-size:20px'>Event Logo</p>

		<img id='blah' src='css/images/events/".$row['event_logo']."' alt='your image' width='460px' height='280px' />
		<br>
		<div id='uploadBtn' class='file_event' style='margin: 0 auto;'>
		<span class='uploadBtn1'>Browse</span>
		<input type='file' class='file_button' name='image' id='image' onchange='readURL(this);' >
		</div>

		<p style='font-size:20px'>Type of Event *</p>
		<select name='type' required='required' placeholder='Select an event type!' style='padding: 15px; font-size: 16px'  class='register_input'> 
			<option value='Arts'> Arts </option>
			<option value='Business'> Business </option>
			<option value='Charity'> Charity </option>
			<option value='Community'> Community </option>
			<option value='Family & Education'> Family & Education</option>
			<option value='Food & Drink'> Food & Drink </option>
			<option value='Gaming'> Gaming </option>
			<option value='Health'> Health </option>
			<option value='Music'> Music </option>
			<option value='Other'> Other </option>
			<option value='Science & Tech'> Science & Tech </option>
			<option value='Spirituality'> Spirituality </option>
			<option value='Sports & Fitness'> Sports & Fitness </option>
 		</select>
		<br>

		<p style='font-size:20px'>Title of your Event *</p>
		<input type='text' size='90%' class='register_input' name='title' maxlength='32'
			value='".$row['title']."' required='required' style='padding: 15px; font-size: 16px; color: black'>
		<br>

		<p style='font-size:20px'>Venue Spot *</p>
		<input name='venue' type='text'  size='90%' class='register_input' maxlength='32' 
			value='".$row['venue']."' required='required' style='padding: 15px; font-size: 16px;color: black''>
		<br>

		<p style='font-size:20px'>Details for your Event (Optional)</p>
		<textarea cols='90%' rows='7' class='register_input' placeholder='".$row['event_description']."'	name='description' style='padding: 15px; font-size: 16px'></textarea>
	</div> 

<br><br>

	<div id='tickets_details'>
		<span id='step2_design'>2</span>
		<br>
		<br>
		<hr style='margin-top: -3px; background-color: #D2D2D2'>
		<h1 style='margin-top: -50px; text-align: left; font-weight: lighter; font-size: 24px; padding: 6px'>
		<span style='margin-left: 5px;'>Date Information</span></h1>
		<p style='color:red'>Please use this format for the starting time / ending time HH:MM AM or PM</p>
		<span style='margin-right: 15%;font-size:20px'>Event's Starting Time *</span>
		<span style='font-size:20px'>Event's Ending Time *</span>
		<br>
		<input type='text' required='required' name='start_time' style='margin-right: 20%; padding: 10px;width: 100px'  class='register_input_date' value='".$start_date."'> 
		<input type='text' required='required' name='end_time' style='padding: 10px;width: 100px' class='register_input_date' value='".$end_date."'> 
		<br>
		<span style='font-size:20px'>Event's Date *</span><br>
		<input name='date' type='text' id='datepicker' required='required' style='padding: 10px; text-align:center;color: black' class='register_input_date' value='".$date."'>
	</div>

<br><br>

	<div id='confirm_details'>
		<span id='step3_design'>3</span>
		<br>
		<br>
		<hr style='margin-top: -3px; background-color: #D2D2D2'>
		<h1 style='margin-top: -50px; text-align: left; font-weight: lighter; font-size: 24px; padding: 6px'>
		<span style='margin-left: 5px;'>Tickets Information *</span></h1>
		<p style='font-size:20px;'>Ticket Name (Optional)</p>
		<input type='text' size='90%' name='ticket_name' maxlength='16' class='register_input' value='".$row['ticket_name']."' style='padding: 15px; font-size: 16px;color: black' >
		<p style='font-size:20px;'>Ticket Available *</p>
		<input type='text' size='10%' name='max_tickets' class='register_input' required='required' value='".$row['max_tickets']."' style='padding: 15px; font-size: 16px;text-align: center;color:black;'>
		<p style='font-size:20px' title='How many tickets can someone purchase?' >Maximum Tickets Per Purchase *</p>
		<input type='text' name='max_tickets_purchase' size='10%' class='register_input' value='".$row['max_tickets_per_purchase']."' style='padding: 15px; font-size: 16px;text-align: center;color:black'>
	</div>

<br><br>

	<div id='confirm_details'>
		<span id='step4_design'>4</span>
		<br>
		<br>
		<hr style='margin-top: -3px; background-color: #D2D2D2'>
		<h1 style='margin-top: -50px; text-align: left; font-weight: lighter; font-size: 24px; padding: 6px'>
		<span style='margin-left: 5px;'>Confirm Event</span></h1>

		<div style=''>
			<span style='font-size:20px'>Is Everything Correct?</span><br>
			<input type='submit' value='Update Event' name='submit' class='file_event' style='width: 	auto;background-color:#00a300;border:#6d96b8;cursor:pointer;padding: 10px;'>
		</div>
		
	</div>
<br>
</form>
</div>
</div>";

		}
		}

}

/*********************************************************************/
/*********************************************************************/
/*						Purchase Events Functions                    */
/*********************************************************************/
/*********************************************************************/
function display_purchase_table($event_id)
{
	
	//First check if the event id is valid
	if(!check_event_id($event_id))
	{
		echo "<br><h1>Sorry the event you enter does not exist!</br>";
	}
	
	else{
		display_purchase($event_id); 
	}
}

function purchase_event($tickets,$event_id)
{
	$user_id = $_SESSION['user_id'];
	
	/* Check if the ticket input is a number*/
	if(!ctype_digit($tickets))
	{
		$_SESSION['errors'] = "Please enter a valid integer";
		$_SESSION['error_type'] = 4;
		header("Location: events.php?action=purchase&do=".$event_id."");
	}
	
	/* Check if the amount of tickets are able for purchasing!*/
	else if((getTickets($event_id,"max_tickets") - getTickets($event_id,"tickets_bought") < $tickets))
	{
		$_SESSION['errors'] = "Please view the tickets bought section!";
		$_SESSION['error_type'] = 4;
		header("Location: events.php?action=purchase&do=".$event_id."");
	}
	
	/* Check if the amount of tickets are able for purchasing (max tickets)!*/
	else if((getTickets($event_id,"max_tickets_per_purchase") < $tickets))
	{
		$_SESSION['errors'] = "Please view the tickets bought section!";
		$_SESSION['error_type'] = 4;
		header("Location: events.php?action=purchase&do=".$event_id."");
	}

	/* Add into DB!*/
	else 
	{
		$old_tickets = getTickets($event_id,"tickets_bought");

		if(add_purchase_event($event_id,$tickets,$old_tickets,$user_id))
		{
			header("refresh:3; url=events.php");
			echo "<br><h1>You have successfully purchased tickets to this event!</h1>";
			unset($_SESSION['errors']);
			unset($_SESSION['error_type']);
			exit();
		}
		
		else
			echo "Error in adding into DB! Contact an administrator asap!";
		
	}
	
}

/* 
* Add purchase event to DB
* @para $event_id, $tickets
* @return True if we successful added the purchase events to the DB
* @return False if we are unsuccessful
*/
function add_purchase_event($event_id,$tickets,$old_tickets,$user_id)
{
	global $connection;
	
	
	$query = "UPDATE events SET tickets_bought = '$tickets' + '$old_tickets' WHERE event_id = '$event_id';";
	$query .= "INSERT INTO events_booked (event_id,user_id,tickets_bought,time_bought) 
				VALUES ('$event_id','$user_id','$tickets',NOW());";
	
	return (mysqli_multi_query($connection,$query))? true : false;
	
}

/* Display the Purchase Event Table
* @para $event_id is the event id of the event. Used to get certain data from the DB
*/
function display_purchase($event_id)
{
	global $connection;
	global $error_type;
	global $errors;
	
	/* Get DATA First */
	$query = mysqli_query($connection, "SELECT * FROM events WHERE event_id='$event_id'") or die("Can not make purchase! Contact the administrator");
	$row = mysqli_num_rows($query);
	
	echo "<div style='margin-top: 10px'><title>Purchasing an Event..</title>";
	
	if($row == 0) die("Can not make purchase! Contact the administrator!");
	else {
		while($row = mysqli_fetch_array($query)){
			$title = $row['title'];
			$type = $row['type'];
			$venue = $row['venue'];
			$date = date('m/d/y',$row['date_of_event']);
			$time = date('h:i A',$row['date_of_event']);
			$tickets_left = $row['max_tickets'] - $row['tickets_bought'];
			$end_time = date('h:i A',$row['event_ending_time']);
			$max_tickets = $row['max_tickets_per_purchase'];
			if(time() > $row['date_of_event']) die("Event Passed!");
			
			}
		
		
	
	if($error_type == 4){
	echo "<br><div align='center' style='color: red;'>" . $errors . "</div>";
	unset($_SESSION['errors']);
	unset($_SESSION['error_type']);
	}
		
		echo '
		
		
		<br>
		
			<table align="center" cellpadding="0" cellspacing="1" border="1px solid black" id="tabs"> 

        	<tr> 
        	<form name="Purchase Event" method="post" action="events.php"> 
			<td> 

			<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF"> 
			<tr> 
			<td colspan="3"><strong><center>Purchase Event</center></strong></td> 
			</tr> 

			<tr> 
			<td width="78">Title</td> 
			<td width="6">:</td> 
			<td width="294">'.$title.'</td> 
			</tr> 
		
			<tr> 
			<td>Type</td> 
			<td>:</td> 
			<td>'.$type.'</td> 
			</tr> 
		
			<tr> 
			<td>Venue</td> 
			<td>:</td> 
			<td>'.$venue.'</td> 
			</tr> 

			<tr> 
			<td>Date</td> 
			<td>:</td> 
			<td>'.$date.'</td> 
			</tr> 

			<tr> 
			<td>Start Time</td> 
			<td>:</td> 
			<td>'.$time.'</td> 
			</tr> 
			
			<tr> 
			<td>Ending Time</td> 
			<td>:</td> 
			<td>'.$end_time.'</td> 
			</tr> 
		
			<tr> 
			<td>Total Tickets Available</td> 
			<td>:</td> 
			<td>'.$tickets_left.'</td> 
			</tr> 
			
			<tr> 
			<td>Total Tickets You can Purchase</td> 
			<td>:</td> 
			<td>'.$max_tickets.'</td> 
			</tr> 
			
			<input type="hidden" name="event_id" value="'.$event_id.'">
			
			<tr> 
			<td>How many tickets do you want to purchase? </td> 
			<td>:</td> 
			<td><input type="number" min="1" max="'.$max_tickets.'" name="tickets" required="required" id="ticketss"></td> 
			</tr> 

			<tr> 
			<td></td> 
			<td></td> 
			<td><input type="submit" name="submit" value="Purchase" id="event_purchase"></td> 
			</tr> 

			</table> 
			</td> 
			</form> 
			</tr> 
			</table> 
			</div>
	';}
}

/*********************************************************************/
/*********************************************************************/
/*						Helper Functions                             */
/*********************************************************************/
/*********************************************************************/

/* 
* Return the amount of tickets based on ticket type
* @para $ticket_type = max_tickets OR tickets_bought
* @return total tickets
*/
function getTickets($event_id,$ticket_type)
{
	global $connection;
	$query = mysqli_query($connection, "SELECT * FROM events WHERE event_id='$event_id'") or die("Can not make purchase! Contact the administrator");
	$row = mysqli_num_rows($query);
		
	while($row = mysqli_fetch_array($query))
	{
		$ticket = $row[''.$ticket_type.''];
	}
	
	return $ticket;
}



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


/*
	?>  //Good practice to omit ending tag 
*/
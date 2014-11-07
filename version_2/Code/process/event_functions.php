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
	
	if($row == 0)
		echo "There are no events! Please create one today!";
		
	else
	{
		echo "  <br><table id='event_table' align ='center'>
				<tr>
				<th>Title</th>
				<th>Type</th>
				<th>Venue</th>
				<th>Date</th>
				<th>Time</th>
				<th>Buy</th>
				<tr>
			 ";
			 
		while($row = mysqli_fetch_array($query))
		{
			/* Check if the user can purchase a ticket!*/ 
			// note to self! Check if the current date is greater than the DB date. 
			$check_tickets = $row['max_tickets'] - $row['tickets_bought'];
			if($check_tickets != 0) $purchase = "<a href='events.php?action=purchase&do=".$row['event_id']."'>Buy</a>";
			else $purchase = "Sold Out!";
			
			if(time() > $row['date_of_event']) $purchase = "Event Passed";
			
			echo 
			"
				<tr>
				<td>".clip_string($row['title'], 20)."</td>
				<td>".clip_string($row['type'], 20)."</td>
				<td>".clip_string($row['venue'], 20)."</td>
				<td>".date('m/d/y',$row['date_of_event'])."</td>
				<td>".date('h:i A',$row['date_of_event'])."</td>
				<td>".$purchase."</td>
				</tr>
			";
			
		}
		
		echo "</table>";
		
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
			exit();
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
	
	if($error_type == 3){
	echo "<br><div align='center' style='color: red'>" . $errors . "</div>";
	unset($_SESSION['errors']);
	unset($_SESSION['error_type']);
	}
	
	echo"
		<div id='content_top'>
		<span style='color: white; font-size:72px;'>Event Creation Tool</span>
		</div>

		<div>
		<div id='wrapper'>
		<div id='create_event'>

		<br><br>
		<form name='Create Event' method='post' action='events.php'> 

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
		<input type='file' class='file_button' onchange='readURL(this);' >
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
		<input type='text' name='max_tickets_purchase' size='10%' class='register_input' placeholder='Max Purchase' style='padding: 15px; font-size: 16px;text-align: center'>
	</div>

<br><br>

	<div id='confirm_details'>
		<span id='step4_design'>4</span>
		<br>
		<br>
		<hr style='margin-top: -3px; background-color: #D2D2D2'>
		<h1 style='margin-top: -50px; text-align: left; font-weight: lighter; font-size: 24px; padding: 6px'>
		<span style='margin-left: 5px;'>Confirm Event</span></h1>

		<div style='float: left;margin-left: 25%'>
			<span style='font-size:20px'>Is Everything Correct?</span><br>
			<input type='submit' value='Create Event' name='submit' class='file_event' style='width: 	auto;background-color:#00a300;border:#00a300;cursor:pointer;padding: 10px;'>
		</div>
		<div>
			<span style='font-size:20px'>Wanna Preview?</span><br>
			<input type='submit' value='Coming Soon' name='submit' class='file_event' style='width:auto;background-color:#0178B8;border:#0178B8;cursor:pointer;padding: 10px' disabled>
		</div>
	</div>
<br>
</form>
</div>
</div>
		";
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
	
	if($row == 0) die("Can not make purchase! Contact the administrator!");
	else {
		while($row = mysqli_fetch_array($query)){
			$title = $row['title'];
			$type = $row['type'];
			$venue = $row['venue'];
			$date = date('m/d/y',$row['date_of_event']);
			$time = date('h:i A',$row['date_of_event']);
			$tickets_left = $row['max_tickets'] - $row['tickets_bought'];
			}
		
		
	
	if($error_type == 4){
	echo "<br><div align='center' style='color: red'>" . $errors . "</div>";
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
			<td>Time</td> 
			<td>:</td> 
			<td>'.$time.'</td> 
			</tr> 
		
			<tr> 
			<td>Total Tickets Available</td> 
			<td>:</td> 
			<td>'.$tickets_left.'</td> 
			</tr> 
			
			<input type="hidden" name="event_id" value="'.$event_id.'">
			
			<tr> 
			<td>How many tickets do you want to purchase? </td> 
			<td>:</td> 
			<td><input type="text" name="tickets" required="required"></td> 
			</tr> 

			<tr> 
			<td></td> 
			<td></td> 
			<td><input type="submit" name="submit" value="Purchase"></td> 
			</tr> 

			</table> 
			</td> 
			</form> 
			</tr> 
			</table> 
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
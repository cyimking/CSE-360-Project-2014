<?php
/*********************************************************************/
/*********************************************************************/
/*						Display Events Functions                     */
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
			header("refresh:3; url=events.php");
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

/* 
* Display the Add Event Table
*/
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
		<td width="294"><input name="title" type="text" id="title" required="required"></td> 
		</tr> 
		
		<tr> 
		<td>Type</td> 
		<td>:</td> 
		<td>
		<select name="type" id="type" required="required"> 
		<option value="Arts"> Arts </option>
		<option value="Business"> Business </option>
		<option value="Charity"> Charity </option>
		<option value="Community"> Community </option>
		<option value="Family & Education"> Family & Education</option>
		<option value="Food & Drink"> Food & Drink </option>
		<option value="Gaming"> Gaming </option>
		<option value="Health"> Health </option>
		<option value="Music"> Music </option>
		<option value="Other"> Other </option>
		<option value="Science & Tech"> Science & Tech </option>
		<option value="Spirituality"> Spirituality </option>
		<option value="Sports & Fitness"> Sports & Fitness </option>
    	</select></td> 
		</tr> 
		
		<tr> 
		<td>Venue</td> 
		<td>:</td> 
		<td><input name="venue" type="text" id="venue" required="required"></td> 
		</tr> 

		<tr> 
		<td>Date</td> 
		<td>:</td> 
		<td><input name="date" type="text" id="datepicker" required="required"></td> 
		</tr> 

		<tr> 
		<td>Time</td> 
		<td>:</td> 
		<td><input name="time" type="text" id="time" required="required"></td> 
		</tr> 
		
		<tr> 
		<td>Total Tickets</td> 
		<td>:</td> 
		<td><input name="tickets" type="text" id="tickets" required="required"></td> 
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
		if(add_purchase_event($event_id,$tickets))
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
function add_purchase_event($event_id,$tickets)
{
	global $connection;
	
	$user_id = $_SESSION['user'];
	$query = "UPDATE events SET tickets_bought = '$tickets' WHERE event_id = '$event_id';";
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
		
		
	
	if($error_type == 4)
	echo "<br><div align='center' style='color: red'>" . $errors . "</div>";
		
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
			<td><input type="text" name="tickets" id="tickets" required="required"></td> 
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
?>
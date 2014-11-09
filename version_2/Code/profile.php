<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/main.css" />
<title>CSE 310 Assignment</title>

<?php include "process/header.php"?>

<div id="body_base">
<div id="wrapper">

<?php


/* You are not signed in so please sign in :-)*/
if(check_session() == false){
	echo "You must be signed in to view this page. Please <a href='index.php'>Sign In </a>Here ";
}

else{

	//Get ID From URL
	if(isset($_GET['id'])){
		$id = $_GET['id'];

		if($id == $_SESSION['user_id'])
		{
			global $connection;

			$query = mysqli_query($connection, "SELECT * FROM user");
			
			//You have to run a LOOP when fetching the array! 
			while($row = mysqli_fetch_array($query)){
			$username = $row['username'];
			$email = $row['email'];
			}

			echo "You are now viewing ".$username."'s profile page!<br>";
			//display the user's profile picture
			echo "<img src='profiles/".$id."_".$username."/default_profile.png' height='150px' width='150px'><br>";
			//display the user's username & email address
			echo $username." (".$email.")<br>";
			//link to settings page (change password & account picture)
			echo "<a href='settings.php'>Settings</a><br><hr>";
			//display a table of purchased tickets (allow users to cancel tickets)
			echo "Purchases:<br>";
			display_purchases($id);
		}
		else{
			//Check if the member's exist
			$query = mysqli_query($connection, "SELECT * FROM user WHERE user_id = '$id'");
			$row = mysqli_num_rows($query);
			if($row == 1)
				echo "You are viewing someone's else profile page!";
			else
				echo "User does not exist!";
		}
	}

	else
	{
		header("Location: index.php");
		exit();
	}
}

/* Display purchased tickets */
function display_purchases($user_id)
{
	/* Global means it's already a function ELSE WHERE. $connection is the only connection variable initailed. 
	* You ONLY need one connection variable not two. 
	* You can perform either a JOIN function to have two queries because you are checking for events booked by the user
	*  and checking to see what event id did the user bought and make sure that the event ID = eventbooked ID 
	*  INNER JOIN only returns the results for User's Booked Events (ID) that Matches the Events ID. I hope that makes sense!
	*/
	global $connection;	

	$query = mysqli_query($connection,"SELECT events_booked.event_id, events_booked.user_id, events_booked.tickets_bought,
										events.event_id, events.user_id, events.title, events.type, events.venue, events.date_of_event
										FROM events_booked
										INNER JOIN events
										ON events_booked.user_id = '$user_id' 
										AND events_booked.event_id = events.event_id ");

	$row1 = mysqli_num_rows($query);
	if($row1 == 0)
		echo "You have no upcomming events! To buy tickets, click <a href='events.php'>HERE</a>.<br>";
	
	else
	{
		echo "  <table id='purchases_table' align ='center'>
				<tr>
				<th>Type</th>
				<th>Title</th>
				<th>Venue</th>
				<th>Date</th>
				<th>Time</th>
				<th>Quantity</th>
				<th> </th>
				<tr>
			 ";

		//display all the user's purchases
		while($row1 = mysqli_fetch_array($query))
		{
			//if the tickets were purchased by this user, display them
			echo "	<tr>
						<td>".$row1['type']."</td>
						<td>".$row1['title']."</td>
						<td>".$row1['venue']."</td>
						<td>".date('m/d/y',$row1['date_of_event'])."</td>
						<td>".date('h:i A',$row1['date_of_event'])."</td>
						<td>".$row1['tickets_bought']."</td>
						<td>Sell</td>
						</tr>
					";
			
		}

		echo "</table>";
	}
}
?>

<br />
<br/>
</div>
</div>
</html>
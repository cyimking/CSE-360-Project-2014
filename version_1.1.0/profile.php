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
			echo "You are now viewing your own profile page!";
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

?>

</div>
</div>
</html>
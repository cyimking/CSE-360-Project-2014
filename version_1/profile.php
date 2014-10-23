<html>

<style>
body
{
	text-align: center;
}
</style>

<?php


//Profile Page not finished at all. Just wanted to have this page on the server

include "process/header.php";

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

</html>
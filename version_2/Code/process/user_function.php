<?php


/*
Global Functions for the Member's File
*/


/* Check if the username exist */
function user_exist($username)
{
	global $connection;
	
	$query = mysqli_query($connection,"SELECT * FROM user WHERE username = '$username'") or die("Can't connect to DB");
	$row = mysqli_num_rows($query);
	return ($row == 1)? true : false;
}

/* Check if the email exist */
function email_exist($email)
{
	global $connection;
	
	$query = mysqli_query($connection,"SELECT * FROM user WHERE email = '$email'");
	$row = mysqli_num_rows($query);
	return ($row == 1)? true : false;
}


/* Add new directory per registration. Used for retaining user's profile pictures */
function create_directory($username)
{
	global $connection;
	
	$query = mysqli_query($connection,"SELECT * FROM user WHERE username='$username'");
	$row = mysqli_num_rows($query);
	
	if($row == 1){
		while($row = mysqli_fetch_array($query))
			$user_id = $row['user_id'];}
		
	/* Add the directory then copy the image from the main image folder and place it in the user's folder (profile image)*/
	mkdir('profiles/' .$user_id . '_' .$username);
	install_profile_picture($user_id,$username);
}

/*Install default profile picture to the new profile folder*/
function install_profile_picture($user_id,$username)
{
	copy('css/images/default_profile.png','profiles/' .$user_id . '_' .$username.'/default_profile.png');
}

?>
<?php


$DB_Server = "localhost";  //Server Location or IP Address. Should be localhost for offline developement (WAMP, LAMP, or MAMP)
$DB_User = "root";         //Database Username. Should be "root" unless you modified it or using shared hosting
$DB_Pass = "password";     //Database Password. Read Documentation 
$DB_Name = "cse310";       //Database Name. I name it CSE310 but you can name it whatever you like. 

$connection = mysqli_connect($DB_Server,$DB_User,$DB_Pass,$DB_Name); // Connection to database using MySQLi Connect

class Member extends PHPUnit_Framework_TestCase
{
	
	
	/* TEST MEMBERS LOGIN */
    public function testmemberfile()
    {
        global $connection;
		
		/* SUICCESSFUL LOGINS */		
		$successful_login1 = login("james@test.com","Testing24");
		$successful_login2 = login("joe@test.com","Testing24");
		$successful_login3 = login("mike@test.com","Testing24");

		
		/* SUCCESSFUL REGISTRATIONS CHECKS */
		$successful_registration_check1 =  registration_check("Jerry","jerry@test.com","Testing24","Testing24"); 
		$successful_registration_check2 =  registration_check("COD_HACKER","cod@testing.com","Testing24","Testing24");
		
		/* SUCCESSFUL REGISTRATIONS */
		$successful_registration = registration("Jackie","jackie@test.com","Testing24");
		
		
		/* SUCCESSFUL USERNAME MATCHES */
		$successful_user_match1 = user_exist("Cyimking");
		$successful_user_match2 = user_exist("James");
		
	
		/* SUCCESSFUL EMAIL MATCHES */
		$successful_email_match1 =  email_exist("cyimking@yahoo.com");
		$successful_email_match2 =  email_exist("joe@test.com");
		
		
		/* UNSUCCESSFUL LOGINS */		
		$unsuccessful_login1 = login("james@test.com","Random");
		$unsuccessful_login2 = login("joe@test.com","Not\Going!To~Work");
		$unsuccessful_login3 = login("mike@test.com","Mikey Tow");
		
		/* UNSUCCESSFUL REGISTRATIONS */
		$unsuccessful_registration1 =  registration_check("","","",""); //NULL INPUTS
		$unsuccessful_registration2 =  registration_check("","HUH@testing.com","Testing24","Testing24");  //NULL USERNAME
		$unsuccessful_registration3 =  registration_check("James","huh@testing.com","Testing24","Testing24");  //Username Taken
		$unsuccessful_registration4 =  registration_check("Tom","joe@test.com","Testing24","Testing24"); //Email Taken
		$unsuccessful_registration5 =  registration_check("Tom","tom@test.com","Testing24","Testing");  //Incorrect Passwords (No Match)
		$unsuccessful_registration6 =  registration_check("Tom","tom@test.com","t","t");  //Short Password
		$unsuccessful_registration7 =  registration_check("Tom","tom@test.com","tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt","tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt"); //Long Passwords


		/* UNSUCCESSFUL USERNAME MATCHES */
		$unsuccessful_user_match1 = user_exist("CheckMate");
		$unsuccessful_user_match2 = user_exist("I Hate You");
		
		/* UNSUCCESSFUL EMAIL MATCHES */
		$unsuccessful_email_match1 =  email_exist("keith@yahoo.com");
		$unsuccessful_email_match2 =  email_exist("germ@test.com");
       
	    /* True Assertions */
        $this->assertEquals(true, $successful_login1);
		$this->assertEquals(true, $successful_login2);
		$this->assertEquals(true, $successful_login3);
		$this->assertEquals(true, $successful_registration_check1);
		$this->assertEquals(true, $successful_registration_check2);
		$this->assertEquals(true, $successful_registration);
		$this->assertEquals(true, $successful_user_match1);
		$this->assertEquals(true, $successful_user_match2);
		$this->assertEquals(true, $successful_email_match1);
		$this->assertEquals(true, $successful_email_match2);
		
		/* False Assertions */
		$this->assertEquals(false, $unsuccessful_login1);
		$this->assertEquals(false, $unsuccessful_login2);
		$this->assertEquals(false, $unsuccessful_login3);
		$this->assertEquals(false, $unsuccessful_registration1);
		$this->assertEquals(false, $unsuccessful_registration2);
		$this->assertEquals(false, $unsuccessful_registration3);
		$this->assertEquals(false, $unsuccessful_registration4);
		$this->assertEquals(false, $unsuccessful_registration5);
		$this->assertEquals(false, $unsuccessful_registration6);
		$this->assertEquals(false, $unsuccessful_registration7);
		$this->assertEquals(false, $unsuccessful_user_match1);
		$this->assertEquals(false, $unsuccessful_user_match2);
		$this->assertEquals(false, $unsuccessful_email_match1);
		$this->assertEquals(false, $unsuccessful_email_match2);
    }
	
	
	
	
   			

}
	



/************************************************************************************** 
*
* Had to place functions or rewrite some functions to test each part in phpunit.
* Since I will not rewrite the members.php file, I will place them here (below)
***************************************************************************************/



function login($email,$password)
{
	global $connection;
	
	$login = false;
	
	$query = mysqli_query($connection,"SELECT * FROM user WHERE email = '$email'") or die();
	
	while($row = mysqli_fetch_array($query)){
		
		$check_password = hash('sha256',$password . $row['salt']);
		for($x = 0; $x < 65536; $x++)
		{
			$check_password = hash('sha256',$check_password . $row['salt']);
		}
		
		if($check_password == $row['password']){
			  $login =  true;
			  $user_id = $row['user_id'];
			  $username = $row['username'];
			}
			
	}
		
	if($login)
	{
		return true;
	}
	
	else
		return false;
}


//MODIFIED 
function registration_check($username,$email,$password,$r_password)
{
	
	/* Check if all fields are filled in */
	if($username == "" || $email == "" || $password == "" || $r_password == ""){
			return false;
		}

	/* Check if both passwords matches */	
	else if($password != $r_password){
			return false;
		}
		
	/* Check if the length of the password is between 6 and 16 characters */	
	else if(strlen($password) < 6 || strlen($password) > 16){
			return false;
		}
		
	/* Check if the username is available */ 
	else if(user_exist($username)== true){
			return false;
		}

	/* Check if the email is available */ 
	else if(email_exist($email) == true){
			return false;
		}
		
	/* YAY! No more errors are found sound now register the user! */ 
	else
	{
		return true;
	}
}


function registration($username,$email,$password)
{
	global $connection;
	
	/* "?", etc.. are special tokens for better SQL INJECTION PROTECTION*/
	$query = "INSERT INTO user (username,email,password,salt)
			  VALUES (?,?,?,?) ";
			  
	/* Salt will prevent users from attacks (brute force attacks and rainbow table attacks)*/
	/* Creating a HASH Method is NOT recommanded, thus I will use a powerful salt / password hash function */
	$salt = dechex(mt_rand(0,2147483647)) . dechex(mt_rand(0, 2147483647));
	
	/* Hash the password with the salt to securely store into database */
	$password = hash('sha256', $password . $salt);
	
	/* Hash the password 65537 times to prevent brute force attacks */
	for($x = 0; $x < 65536; $x++)
	{
		$password = hash('sha256', $password . $salt);
	}
	
	/* Prepare Query */
	$stmt = mysqli_prepare($connection,$query);
	if($stmt){
		
		/* BIND the username, email, pass, and salt to the  ? ? ? ?*/
		mysqli_stmt_bind_param($stmt,"ssss",$username,$email,$password,$salt);
	
		$check = mysqli_stmt_execute($stmt);
	
		if(!$check) return false;
	}

		return true;
}


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

<?php
/*********************************************************************************************
* Purpose of this file is to set up the database. 
* Please edit file to ensure that this correctly connects to your database.
*********************************************************************************************
Database Password Documentation - By Default the DB Password is set to " " however WAMP will 
not allow you to use a password that is null or empty, so you must add in a password. 

To do this:
 1. WAMP Folder -> apps -> phpmyadmin -> OPEN config.inc.php
 2. On line 33, add in a password. 
 3. OPTIONAL. On line 32, you can modify your username.
 4. MAKE SURE that line 39 is "$cfg['Servers'][$i]['extension'] = 'mysqli';" 
**********************************************************************************************/


/********************************************************************************************** 
* Change the variables in this location only to match your database settings
* Define connection variables
**********************************************************************************************/
$DB_Server = "localhost";  //Server Location or IP Address. Should be localhost for offline developement (WAMP, LAMP, or MAMP)
$DB_User = "root";         //Database Username. Should be "root" unless you modified it or using shared hosting
$DB_Pass = "password";     //Database Password. Read Documentation 
$DB_Name = "cse310";       //Database Name. I name it CSE310 but you can name it whatever you like. 

/***********************************************************************************************
* Create the connection to the database
* $Connection Variable stores the Connection
* If it fails display the error (connect_error())
***********************************************************************************************/
$connection = mysqli_connect($DB_Server,$DB_User,$DB_Pass,$DB_Name); // Connection to database using MySQLi Connect

if(mysqli_connect_errno())
{
	echo "Connection to DB failed: " . mysqli_connect_error(); // Display Error
}

 
?>
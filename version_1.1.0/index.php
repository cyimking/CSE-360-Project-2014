<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/main.css" />
<title>CSE 310 Assignment</title>

<?php
include 'process/header.php';
?>

</head>

<div id="body_base">
<div id="wrapper">
<?php
/*
* Include the Header File and Members File.
********************************************
* If the user is signed in, display the EVENTS PAGE
* Else display Login Table and Registration Table from the Members File
*/

if(check_session()){
	include 'events.php';
	//display_events();
}
else{
	include 'members.php';
	display_login_table();
	display_registration_table();}

?>
</div>
</div>
</html>
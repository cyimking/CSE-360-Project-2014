<html>

<style>
body
{
	text-align: center;
}
</style>

<?php
/*
* Include the Header File and Members File.
********************************************
* If the user is signed in, display the EVENTS PAGE
* Else display Login Table and Registration Table from the Members File
*/
include 'process/header.php';
include 'members.php';

if(check_session()){
	echo ' You are logged in. This page should now display the all the events!!';
}
else{
	echo '<br><br><br><br>';
	display_login_table();
	display_registration_table();}


?>


</html>
<?php
include 'process/header.php';
?>

</head>

<body>
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
<br />
<br />
</div>
</body>

<div id="footer">
Copyright
</div>


</html>
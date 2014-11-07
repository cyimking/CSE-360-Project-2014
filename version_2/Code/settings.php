


<?php
/*********************************************************************
 *********************************************************************
 * Setting File - upload the pictures file           *
 * Version 1.0.0 (Status - Complete)                                 *
 	 * All functions are located "process / event_functions.php      *
 *********************************************************************
 *********************************************************************
 */
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/main.css" />


<?php
$homepage = true; //By default we are on the "index" page

/* Check if the session is set, if not then set it and include the header file */
if(!isset($_SESSION))
    {
		$homepage = true;
		include "process/header.php";
    }

include "process/event_functions.php";

?>

<!-- These functions will be moved onto the JS function! -->
<!-- Load jQuery from Google's CDN -->
    <!-- Load jQuery UI CSS  -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

    <!-- Load jQuery JS -->
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <!-- Load jQuery UI Main JS  -->
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script>
  $(document).ready(

  /* This is the function that will get executed after the DOM is fully loaded */
  function () {
    $( "#datepicker" ).datepicker({
      changeMonth: true,//this option for allowing user to select month
      changeYear: true //this option for allowing user to select from year range
    });
  }
);

</script>
</head>


<body>
<div id="wrapper">

<?php

/* Check for an error session. If found, displayed errors.
* Error Type == 3 = Add Event Error
*/
if(isset($_SESSION['errors']) && isset($_SESSION['error_type'])){
	$errors = $_SESSION['errors'];
	$error_type = $_SESSION['error_type'];}

?>
you can upload your images here

<form action="upload.php" method="post" enctype="multipart/form-data">
    <p><p>Select image to upload:<p></p>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

<img src="default_profile.png" alt="" style="width:204px;height:228px">



<br />
<br />
</div>
</body>
</html>
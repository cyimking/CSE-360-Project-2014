<?php
/*********************************************************************
 *********************************************************************
 * Event File - Handle all functions for the Events's Page           *
 * Version 1.0.0 (Status - Complete)                                 *
 	 * All functions are located "process / event_functions.php      *
 *********************************************************************
 *********************************************************************
 */
 
$homepage = true; //By default we are on the "index" page

/* Check if the session is set, if not then set it and include the header file */
if(!isset($_SESSION)) 
    { 
		$homepage = false;
		include "process/header.php";
    } 
	
include "process/event_functions.php";

/* Check for an error session. If found, displayed errors. 
* Error Type == 3 = Add Event Error
*/
if(isset($_SESSION['errors']) && isset($_SESSION['error_type'])){
	$errors = $_SESSION['errors'];
	$error_type = $_SESSION['error_type'];}

/* Check to see if the user POST an Action */
if(isset($_POST['submit']))
{
	$action = $_POST['submit'];
	
	switch($action){
		case "Purchase":
			$tickets = sanitize($_POST['tickets'],"int");
			$event_id = sanitize($_POST['event_id'],"int");
			purchase_event($tickets,$event_id);
			break;
		
		default:
			echo "Invalid Link";
			break;
			}
}

/* Check to see if the user GET as an Action */
else if(isset($_GET['action']))
{

	$action = $_GET['action'];
	
	switch($action){
		case "create_event":
			display_add_event_table();
			break;
		
		case "purchase";
			display_purchase_table($_GET['do']);
			break;
				
		default:
			header("Location: index.php");
			exit();
			break;
	}
}


else
{
	//Display our events :))))))
	if($homepage == true)
		display_events();
	
	else{ 
		protect_page();
		display_events();}
}

?>

<style>
.float-left_event {
float:left;
margin-top: -3px;
min-height: 155px;
background-color: #FAFAFA;
width: 30%; // or 33% for equal width independent of parent width
z-index: 0;
position: relative;
border-left: #E9E9E9 solid 1px;
border-right: #E9E9E9 solid 1px;
border-bottom: #E9E9E9 solid 1px;
}

.float-left_event_m {
float:left;
margin-top: -3px;
margin-right: 20px;
margin-left: 20px;
min-height: 155px;
background-color: #FAFAFA;
width: 30%; // or 33% for equal width independent of parent width
z-index: 0;
position: relative;
border-left: #E9E9E9 solid 1px;
border-right: #E9E9E9 solid 1px;
border-bottom: #E9E9E9 solid 1px;
}

h1{
	font-weight: normal;
}

.test {
	margin-top: 10px;
	font-size: 24px;
}

#something {
	background: #2b5797;
	min-height: 50px;
	clear: both;
	margin: 0px;
}

#wrapper_test{
	background-color: white;
	clear: both;
	width: 80%;
	margin: 0 auto;
	min-height: 500px;
}

#tickets{
	padding: 5px;
	border: #2d89ef solid 1px;
	color: white;
	background: #2d89ef;
	border-radius: 3px;
}

#tickets_sold{
	padding: 5px;
	border: #CACACA solid 1px;
	color: white;
	background: #CACACA;
	border-radius: 3px;
}

#footer
{
	border-top: #797979 thick solid;
	text-align: center;
	background-color: #1d1d1d;
	color: #787878;
	height: 30px;
	clear: both;
	padding: 10px;
}

#homepage_event_button
{
	padding: 5px;
	border: #6C9 solid 1px;
	color: white;
	background: #6C9;
	border-radius: 3px;
	clear: both;
	width: 100%;
}

.register_input{
	padding: 5px;
	margin: 5px;
	border: #C0C0C0 1px solid;
	border-radius: 3px;
	color: #ABABAB;
}

.register_input_date{
	padding: 5px;
	margin: 5px;
	border: #C0C0C0 1px solid;
	border-radius: 3px;
	color: black;
	font-family: Segoe UI;
	font-weight: bold;
	font-size: 16px;
}

.register_input:active{
	color: black;
}


#create_account_check_button_success{
	padding-left: 5px;
	padding-right: 5px;
	padding-top: 3px;
	padding-bottom: 3px;
	/*border: #00a300  solid 1px;
	color: white;
	background: #00a300;*/
	border-radius: 3px;
	margin-top: 2px;
}

#create_account_check_button_fail{
	padding-left: 5px;
	padding-right: 5px;
	padding-top: 3px;
	padding-bottom: 3px;
	/*border: #ee1111 solid 1px;
	color: white;
	background: #ee1111;*/
	border-radius: 3px;
	margin-top: 2px;
}

#create_account_check_good_pass
{
	padding-left: 5px;
	padding-right: 5px;
	padding-top: 3px;
	padding-bottom: 3px;
	border: #00aba9 solid 1px;
	color: white;
	background: #00aba9;
	border-radius: 3px;
	margin-top: 2px;
}

#register_input_submit
{
	color: white;
	background-color: #00a300;
	border: 1px solid #00a300;
	border-radius: 3px;
	padding: 7px;
	margin: 5px;
	width: 100px;
	height: 30px;
}


#register_input_submit_fail
{
	color: white;
	background-color: #ee1111;
	border: 1px solid #ee1111;
	border-radius: 3px;
	padding: 7px;
	margin: 5px;
}

#step1_design{
	background-color: #00aba9;
	color: white;
	padding-top: 10px;
	padding-left: 20px;
	padding-right: 20px;
	padding-bottom: 10px;
	float: left;
}

#step2_design{
	background-color: #ffc40d;
	color: white;
	padding-top: 10px;
	padding-left: 20px;
	padding-right: 20px;
	padding-bottom: 10px;
	float: left;
}

#step3_design{
	background-color: #ff0097;
	color: white;
	padding-top: 10px;
	padding-left: 20px;
	padding-right: 20px;
	padding-bottom: 10px;
	float: left;
}

#step4_design{
	background-color: #2B8D0E;
	color: white;
	padding-top: 10px;
	padding-left: 20px;
	padding-right: 20px;
	padding-bottom: 10px;
	float: left;
}

.file_event{
	position: relative;
	overflow: hidden;
	margin: auto 0;
	width: 75px;
	color: white;
	background-color: #D5D5D5;
	border: 1px solid #D5D5D5;
	border-radius: 3px;
	padding: 5px;
	margin: 5px;
}

.file_event input.file_button{
	position:absolute;
	top: 0;
	right: 0;
	margin: 0;
	padding: 0;
	opacity: 0;
	filter: alpha(opacity=0);
	cursor:pointer; 
}

input.file_button{
	z-index: 10;
}

#content_top{
	/*-webkit-box-shadow: inset 0px 5px 5px 0px #bfccdf;
	-moz-box-shadow:    inset 0px 5px 5px 0px #bfccdf;
	box-shadow:         inset 0px 5px 5px 0px #bfccdf; */
	border-bottom: #E3E3E3 1px solid; 
	height: 150px;
	-webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
	
	background: url("css/images/cse.png");
}
</style>

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
function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(460)
                        .height(287);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
		
</script>
<script src="main.js"></script>	
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

<script>

</script>

</head>


<body>




<br />
<br />
</div>
</body>
</html>
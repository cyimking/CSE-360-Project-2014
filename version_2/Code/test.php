<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include 'process/header.php'; // include the library for database connection ?>
<style>
.float-left {
	float:left;
	margin: 15px;
	min-height: 275px;
	background-color: white;
	width: 30%; // or 33% for equal width independent of parent width
	overflow: hidden;
}

.float-left: hover{
	border-bottom: #BDBDBD solid 1px;
}

.test {
	margin-top: 10px;
	font-size: 24px;
}

#something {
	min-height: 50px;
	clear: both;
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
h1{
	font-weight: normal;
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
</style>
</head>
 
<body>



    <?php 
	function rand_color() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
	function display(){
		for($x = 0; $x < 9; $x++)
	{
		$rand = rand(0,2);
		if($rand === 0) $rand = "<span id='tickets'>Buy Tickets</span>";
		else if($rand === 1) $rand = "<span id='tickets_sold'>Tickets are sold out!</span>";
		else $rand = "<span id='tickets_sold'>Events already passed</span>";
		echo '
			<div align="center" class="float-left" style="border-top: 5px '.rand_color().' solid;">
			<img src="css/images/default_profile.png" style="height: 100px; width=100px" /><br><br>
			<span style="color: #a5a3cc">CONTENT OF COLUMN '.$x.' GOES HERE</span>
			<br><br>
			<span style="font-size: 100%">December 7, 2014 1:00pm</span>
			<br>
			<span>Venue will be at ASU</span>
			<br><br>
			'.$rand.'
	         <br></div>';	
	}}
	?>

<div id='something'>
<h1>"The world's largest event application!"</h1>
</div>
    

<div style="margin-top: -10px">
<div id="wrapper">

<div align="center" class="test">Recent Events Added</div>
<?php display() ?>
</div>


<br>
<br><br>

<div style="clear: both; padding: 10px">
<span id="homepage_event_button">Create Event</span>
</div>
</div>
<br>
</div>


</body>

<div id="footer">
Copyright
</div>

</html>


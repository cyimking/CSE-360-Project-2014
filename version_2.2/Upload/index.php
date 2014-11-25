<?php
include 'process/header.php';
?>
<title>Homepage</title>
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
	?>
 

    		
            <br>
           <h2 style='font-weight: normal;text-align:left'>Popular Categories</h2>
            
            <div class="bigtile">
            	<div class="tile_text">ARTS</div>
            </div>
            
            
            
            <div class="secondtile">
            	<div class="tile_text">GAMING</div>
            </div>
            
            <br>
            
            <div class="thirdtile">
            	<div class="tile_text">MUSIC</div>
            </div>
            
            <br style='clear: both'>
            <br>
            
            <div class="forthtile">
            	<div class="tile_text">PARTIES</div>
            </div>
                      
            <div class="fivetile">
            	<div class="tile_text">FOOD</div>
            </div>
            
            <div class="sixtile">
            	<div class="tile_text">NETWORKING</div>
            </div>
            
            
            <br style="clear:both">
          
           <br />
           <div style='clear:both'>
            <div id='home-bottom'> </div>
        
        <div style='position:absolute; top: 115%; left: 30%;'>
        <h2 style="font-weight: normal;text-align: center;color:black;">Create an Event</h2>
            <p style='text-align: center'>Creating events on MyEvents is as simple as using a computer. <br />
            We provide you everything you need to host the grand event of the year!<br /><br />
            <a href="create_event.php" style="padding: 10px; border-radius: 3px; color: white; background: #F93;text-decoration:none;">Create Event</a>
            </p>
       </div> </div></div>
<?php


}
else{
	include 'members.php';
	display_login_table();
	display_registration_table();}

?>


</div>
 </body>

<div id="footer">
<div class='footer_text'>
Created by Lamar, Bikram and Ian. Enjoy!
</div>
</div>


</html>
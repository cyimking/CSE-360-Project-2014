<?php 

include 'process/header.php'; 
	
/* You are not signed in so please sign in :-)*/

if(check_session() == false){
	echo "You must be signed in to view this page. Please <a href='index.php'>Sign In </a>Here ";
}


if(isset($_POST['upload'])) {
		$old_pass = $_POST['old_pass'];
		$new_pass = $_POST['password'];
		$r_pass = $_POST['password2'];
		$email = $_SESSION['email'];
		$user = $_SESSION['user'];
	    $pass_check = false;
	   
	   //Check if the form is filled
	   if(empty($old_pass) || empty($new_pass) || empty($r_pass)){
		    header("refresh:3; url=p_settings.php");
	   		echo "<br><div align='center'>Fill out the whole form!.<br>You are being redirected now...</div>";
	   		exit();
	   }
	   
	   else if($new_pass !== $r_pass){
		    header("refresh:3; url=p_settings.php");
	   		echo "<br><div align='center'>New Password and Repeat Password doesn't match!.<br>You are being redirected now...</div>";
	   		exit();
	   }
	   
	   else{
	   		//Check if the current password is valid
	   		$query = mysqli_query($connection,"SELECT * FROM user WHERE email = '$email' AND username = '$user'") or die();
	
	  		 while($row = mysqli_fetch_array($query)){
		
				$check_password = hash('sha256',$old_pass . $row['salt']);
				for($x = 0; $x < 65536; $x++)
				{
					$check_password = hash('sha256',$check_password . $row['salt']);
				}
		
				if($check_password == $row['password']){
			  		$pass_check =  true;
					}
				}
		
				/* Password Doesn't Match Current Password */
				if(!$pass_check)
				{
					header("refresh:3; url=p_settings.php");
	   				echo "<br><div align='center'>Current Password doesn't match our records!.<br>You are being redirected now...</div>";
	   				exit();
				}
					/* Update Password */
				
					/* Salt will prevent users from attacks (brute force attacks and rainbow table attacks)*/
					/* Creating a HASH Method is NOT recommanded, thus I will use a powerful salt / password hash function */
					$salt = dechex(mt_rand(0,2147483647)) . dechex(mt_rand(0, 2147483647));
	
					/* Hash the password with the salt to securely store into database */
					$password = hash('sha256', $new_pass . $salt);
	
					/* Hash the password 65537 times to prevent brute force attacks */
					for($x = 0; $x < 65536; $x++)
					{
						$password = hash('sha256', $password . $salt);
					}	
										
					/* Update Password */
					$query = mysqli_query($connection,"UPDATE user SET password = '$password', salt = '$salt'
	   										WHERE username = '$user'") or die("DB Error");
											
					/* Redirect to Password Settings */
					header("refresh:3; url=p_settings.php");
	   				echo "<br><div align='center'>You had successfully updated your password!.<br>You are being redirected now...</div>";
	   				exit();
	   }
    }
	
?>


	<link rel="stylesheet" href="css/settings.css" />
    <title>Password Settings</title>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    
    
    </head>
    <body>
    
    <div id='wrap'>
    		<div id='sidebar'>
   				 
                 <nav class='sidebar_menu'>
                 	<h3 id='sidebar_header'>User Control Panel</h3>
                 	<a href='#' class='sidebar_menu_item'>
                    	<img src="http://icons.iconarchive.com/icons/glyphish/glyphish/24/178-city-icon.png" style='float: left'>
                         My Events 
                    </a>
                    <a href='a_settings.php' class='sidebar_menu_item'>
                    	<img src="http://icons.iconarchive.com/icons/glyphish/glyphish/24/152-rolodex-icon.png" style='float: left'> Account Settings
                    </a>
                    <a href='settings.php' class='sidebar_menu_item'>
                    	<img src="http://icons.iconarchive.com/icons/glyphish/glyphish/24/157-wrench-icon.png" style='float: left'> Profile Settings
                    </a>
                    <a href='p_settings.php' class='sidebar_menu_item'  id='sidebar_bar_active'>
                    	<img src="http://icons.iconarchive.com/icons/glyphish/glyphish/24/196-radiation-icon.png" style='float: left'> Password Settings
                    </a>
    		</div>
    
    		<div id='main'>
            	<h3 id='main_header'>Password Setting</h3>
                <div id='main_content'>
        			<form action="p_settings.php" method="post" enctype="multipart/form-data">

       				
         			<div style='float:left; padding-left: 5%;'>
     			 	<p style='font-size:20px'>Current Password *  </p>
        			<input type='password' required='required' class='setting_input' name='old_pass' >
  					
  			    	<p style='font-size:20px'>New Password *</p>
        			<input type='password' required='required' class='setting_input' name='password' id="password" >
                    <span id="password_strength" style="position:absolute;top: 34%;right:15%;padding: 7px;" ></span>
                    
                    <p style='font-size:20px'>Repeat Password *</p>
        			<input type='password' required='required' class='setting_input' id="password2" name ="password2" >
                    <span id="validate-status" style="position: absolute; top: 46%;right:15%;padding: 7px;"></span>
        			
                    <br>
					<br>
					<input type="submit" name="upload" value="Update Password" id='p_settings_upload'>
                    </div>
    				</form>
                    
                    <br style='clear:both'>
    			</div>
   			 </div>
             
             <br>
		    <br>
    	</div>

    
    </body>
    
    <div id="footer">
	<div class='footer_text'>
		Created by Lamar, Bikram and Ian. Enjoy!
	</div>
	</div>
    
    </html>

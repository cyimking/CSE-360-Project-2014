<?php 

include 'process/header.php'; 
	
/* You are not signed in so please sign in :-)*/

if(check_session() == false){
	echo "You must be signed in to view this page. Please <a href='index.php'>Sign In </a>Here ";
}

$username = $_SESSION['user'];
if(isset($_POST['upload'])) {
		$username = $_SESSION['user'];
		$new_username = $_POST['username'];
	    $user = false; // Username Exist
	   
	   //Check if the username exist
	   $query = mysqli_query($connection,"SELECT username FROM user WHERE username = '$new_username'");
	   $rows = mysqli_num_rows($query);
	   if($rows === 1) $user = false;
	   else $user = true;
	   
	   
	   if(!$user){
		   header("refresh:3; url=a_account.php");
	   	   echo "<br><div align='center'>This username already exist!<br>You are being redirected now...</div>";
	   	   exit();
	   }
	   
	   else{
	  		//Update the database!
	   		$query = mysqli_query($connection,"UPDATE user SET username = '$new_username'
	   										WHERE username = '$username'") or die("DB Error");
	  		 mysqli_close($connection);
	   		
			//Update Profile Folder
			rename("profiles/".$_SESSION['user_id']."_".$username,"profiles/".$_SESSION['user_id']."_".$new_username);
			
			//Update Sessions
			unset($_SESSION['user']); 
			$_SESSION['user'] = $new_username;
			
			
	   		header("refresh:3; url=a_account.php");
	   		echo "<br><div align='center'>You have successfully updated your username!<br>You are being redirected now...</div>";
	   		exit();
	   }
    }
	
?>


	<link rel="stylesheet" href="css/settings.css" />
    <title>Account Settings</title>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    
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
                    <a href='a_settings.php' class='sidebar_menu_item' id='sidebar_bar_active'>
                    	<img src="http://icons.iconarchive.com/icons/glyphish/glyphish/24/152-rolodex-icon.png" style='float: left'> Account Settings
                    </a>
                    <a href='settings.php' class='sidebar_menu_item'>
                    	<img src="http://icons.iconarchive.com/icons/glyphish/glyphish/24/157-wrench-icon.png" style='float: left'> Profile Settings
                    </a>
                    <a href='p_settings.php' class='sidebar_menu_item'>
                    	<img src="http://icons.iconarchive.com/icons/glyphish/glyphish/24/196-radiation-icon.png" style='float: left'> Password Settings
                    </a>
    		</div>
    
    		<div id='main'>
            	<h3 id='main_header'>Account Setting</h3>
                <div id='main_content'>
        			<form action="a_account.php" method="post" enctype="multipart/form-data">

       				
         			<div style='float:left; padding-left: 5%;'>
     			 	<p style='font-size:20px'>Current Username  </p>
        			<input type='text' required='required' class='setting_input' value="<?php echo $username ?>"  disabled>
  					
  					
                    
  			    	<p style='font-size:20px'>New Username *</p>
        			<input type='text' required='required' class='setting_input' name='username' >
        			
                    <br>
					<br>
					<input type="submit" name="upload" value="Update Username" id='settings_upload'>
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

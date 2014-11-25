<?php 

include 'process/header.php'; 
	
/* You are not signed in so please sign in :-)*/

if(check_session() == false){
	echo "You must be signed in to view this page. Please <a href='index.php'>Sign In </a>Here ";
}

$username = $_SESSION['user'];
$query = mysqli_query($connection,"SELECT * FROM user WHERE username= '$username'");
	
	while($row = mysqli_fetch_array($query))
	{
		$username = $row['username'];
		$email = $row['email'];
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
	}

	
if(isset($_POST['upload'])) {
	
	$username = $_SESSION['user'];
	$query = mysqli_query($connection,"SELECT * FROM user WHERE username= '$username'");
	
	$user_id = $_SESSION['user_id'];
	$image_name = $_FILES['image']['name'];
    $image_type = $_FILES['image']['type'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
	$target_dir = "profiles/".$user_id. "_".$username . "/" .$image_name;
	$target_file = $target_dir . basename($_FILES["image"]["name"]);	
	$imageFileType = explode('.',$image_name);
	$imageFileType = strtolower(end($imageFileType));
	$all_ext = array('png','jpeg','gif','jpg');
	$first_name = $_POST['first_name'];	
	$last_name = $_POST['last_name'];
		
	//Check if the user entered an image.. if not, then do nothing just update the DB		
	if($image_name !== '') {
      	
    //Check if the file is a image or not
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	
    if($check !== false) {
        $upload_check = 1;
    } 
	
	else {
		header("refresh:3; url=settings.php");
        echo "File is not an image. <br>You are being redirected now...";
        exit();
    }

	if(in_array($imageFileType,$all_ext) != true)
	{
		header("refresh:3; url=settings.php");
		echo "Incorrect File Extension! <br>You are being redirected now...";
		exit();
	}
       
	else if ($_FILES["image"]["size"] > 5000000) {
			header("refresh:3; url=settings.php");
    		echo "<br>Sorry, your file is too large. <br>You are being redirected now...";
    		exit();
		}
		
    else
		{	
			$image_name = "default_profile.png";
        	move_uploaded_file($image_tmp_name, "profiles/".$user_id. "_".$username . "/" .$image_name);
		}
	   }
	   
	   //Update the database!
	   $query = mysqli_query($connection,"UPDATE user SET first_name = '$first_name', last_name = '$last_name' 
	   										WHERE username = '$username'") or die("DB Error");
	   mysqli_close($connection);
	   
	   header("refresh:3; url=settings.php");
	   echo "<br><div align='center'>You have successfully updated your settings!<br>You are being redirected now...</div>";
	   exit();
	   
    }
	
?>
	<link rel="stylesheet"  href="css/settings.css" />

    <title>Profile Settings</title>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        
    <script>
	function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#settings_profile_avatar')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(150);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
	</script>
    
    </head>
    
    
    <body>
    
    <div id='wrap'>
    		<div id='sidebar'>
   				 
                 <nav class='sidebar_menu'>
                 	<h3 id='sidebar_header'>User Control Panel</h3>
                 	<a href='#' class='sidebar_menu_item'>
                    	<img src="http://icons.iconarchive.com/icons/glyphish/glyphish/24/178-city-icon.png" style='float: left'>
                         <span style='display: inline-block'> My Events </span>
                    </a>
                    <a href='a_settings.php' class='sidebar_menu_item' >
                    	<img src="http://icons.iconarchive.com/icons/glyphish/glyphish/24/152-rolodex-icon.png" style='float: left'> Account Settings
                    </a>
                    <a href='settings.php' class='sidebar_menu_item'  id='sidebar_bar_active'>
                    	<img src="http://icons.iconarchive.com/icons/glyphish/glyphish/24/157-wrench-icon.png" style='float: left'> Profile Settings
                    </a>
                    <a href='p_settings.php' class='sidebar_menu_item'>
                    	<img src="http://icons.iconarchive.com/icons/glyphish/glyphish/24/196-radiation-icon.png" style='float: left'> Password Settings
                    </a>
    		</div>
    
    		<div id='main'>
            	<h3 id='main_header'>Profile Setting</h3>
                <div id='main_content'>
        			<form action="settings.php" method="post" enctype="multipart/form-data">
                    <div style='float: left;'>
      				<p style='font-size:20px;padding-bottom: 5px;'>Current Profile Avatar</p>
       				<img id='settings_profile_avatar'  src='profiles/<?php echo $_SESSION['user_id'] ?>_<?php echo $username ?>/default_profile.png' >
                    <br>
        			<div id='uploadBtn' class='file_event'  style='text-align: center;width: 170px; margin: 0'>
						<span class='uploadBtn1'>Upload New Picture</span>
						<input type='file' class='file_button' name='image' id='image' onchange='readURL(this);' >
                        
					</div></div>
                    
                    
       				
         			<div style='float:left; padding-left: 5%;'>
     			 	<p style='font-size:20px'>First Name * </p>
        			<input type='text' required='required' class='setting_input' name='first_name' value="<?php echo $first_name ?>" >
  					
  					
                    
  			    	<p style='font-size:20px'>Last Name *</p>
        			<input type='text' required='required' class='setting_input' name='last_name' value="<?php echo $last_name ?>" >
        			
                    <br>
					<br>
					<input type="submit" name="upload" value="Update Profile" id='settings_upload'>
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

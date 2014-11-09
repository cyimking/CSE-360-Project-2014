<?php 

include 'process/header.php'; 
	
/* You are not signed in so please sign in :-)*/

if(check_session() == false){
	echo "You must be signed in to view this page. Please <a href='index.php'>Sign In </a>Here ";
}


	
if(isset($_POST['upload'])) {
	
	$username = $_SESSION['user'];
	$query = mysqli_query($connection,"SELECT username FROM user WHERE email= '$username'");
	
	while($row = mysqli_fetch_array($query))
	{
		$username = $row['username'];
	}
	
	$upload_check = 1;
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
			
	if($image_name == '') {
            echo "<br><script>alert('please select the image!!')</script>";
			die("Must enter an image! Please try again on the settings page");
        }		
			
    //Check if the file is a image or not
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	
    if($check !== false) {
        $upload_check = 1;
    } 
	else {
        echo "File is not an image.";
        $upload_check = 0;
    }

	if(in_array($imageFileType,$all_ext) != true)
	{
		echo "Incorrect File Extension!";
		$upload_check = 0;
	}
       
	else if ($_FILES["image"]["size"] > 500000) {
    		echo "<br>Sorry, your file is too large.";
    		$upload_check = 0;	
		}

	if($upload_check == 0)
		{
			echo "<br>Sorry we can not upload the file :-/";
		}
		
    else
		{	
			$image_name = "default_profile.png";
        	move_uploaded_file($image_tmp_name, "profiles/".$user_id. "_".$username . "/" .$image_name);
			echo "<br>image Uploaded Successfully. <br><br> here is your Image <br>";
		}
    }
	
?>






<html>
    <head>
        <title>Settings</title>
    </head>
    <body>

    <form action="settings.php" method="post" enctype="multipart/form-data">
        <br><b>Please select the image:<br> 
        <input type="file" name="image" id="image">
        <input type="submit" name="upload" value="Upload Now">
    </form>
 

    </body>
    </html>

<?php
 if(isset($_POST['email'])){
  $con = mysqli_connect("localhost","root","password","cse310") or die();
 

	 $email = $_POST['email'];
	 $query = mysqli_query($con,"SELECT * from user where email='$email' ") or die();
  
 
  $find = mysqli_num_rows($query);
 
  echo $find;
 }
?>
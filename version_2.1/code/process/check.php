<?php
 if(isset($_POST['username'])){
  $con = mysqli_connect("localhost","root","password","cse310") or die();
 

	 $username = $_POST['username'];
	 $query = mysqli_query($con,"SELECT * from user where username='$username' ") or die();
  
 
  $find = mysqli_num_rows($query);
 
  echo $find;
 }
?>
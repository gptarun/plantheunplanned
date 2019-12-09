<?php
$con = mysqli_connect("localhost","root","root","service_provider");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
$userid=$_GET['userid'];


$res = mysqli_query($con,"Update T_User Set email_activation = 1 Where id = $userid ") ;
if($res){
	$history = mysqli_query($con,"INSERT INTO T_UserHistory (user_id, operation_perform) VALUES ('".$userid."', 'update')");
	if($history){
		echo "Email activation successful";
	}
}
else{
	echo "Email activation failed";
}


?>
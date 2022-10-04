<?php
session_start();
    require_once("movie_dbtool.php");
	$link = create_connect();
	$C_UID =   $_SESSION["UID"];
	$sql = "SELECT UID, Email_User, Password, Nickname, Birth, Sex, Area, Phone, Source, Level, How_Pay, Order_Time FROM a_user";
	$result =execute_sql($link,"funmovie",$sql);
	if(mysqli_num_rows($result) > 0){
	   $mydata = array();
	   while($row = mysqli_fetch_assoc($result)){
	   $mydata [] = $row;
	  // echo json_encode($mydata);
	}
    // $row = mysqli_fetch_assoc($result);
		echo '{"state" : true, "data":'.json_encode($mydata).',"message" : "資料讀取成功!" }';
	}else{
		echo '{"state" : false, "message" : "讀取資料失敗" }';
		
	}
	mysqli_close($link);
?>
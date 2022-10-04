<?php	
session_start();
    require_once("movie_dbtool.php");

    $conn = create_connect();
    $C_UID =   "bf68da0";
	// 抓子帳號我的片單中的影片(這支api只是測試，可以用來修改成其他"讀取"的api)
	$sql = "SELECT a_user_love.Love FROM a_user_love WHERE M_ID IN (SELECT ID FROM a_user WHERE UID = '$C_UID')";
	
	$result =execute_sql($conn,"funmovie",$sql);
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


	 
	 mysqli_close($conn);

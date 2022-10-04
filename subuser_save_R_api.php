<?php	
session_start();
    require_once("movie_dbtool.php");
    $conn = create_connect();
    $C_UID =   $_SESSION["UID"];
	$sub_id = $_SESSION["sub_id"];
	// 抓子帳號我的片單中的影片(下面的sql還未改過，依然是抓主帳號的我的片單資料，之後會改)
	$sql = "SELECT c_movie.Moviename,c_movie.Image FROM c_movie INNER JOIN (SELECT b_user_save.Save, b_user_save.Time FROM b_user INNER JOIN b_user_save ON b_user.ID = b_user_save.Sub_ID WHERE (SELECT b_user.ID FROM b_user INNER JOIN a_user ON a_user.ID = b_user.M_ID WHERE a_user.UID = '$C_UID' AND b_user.Sub_User='$sub_id') = b_user_save.Sub_ID  ORDER BY b_user_save.Time DESC)B WHERE Movie_ID = B.Save ORDER BY B.Time DESC";
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

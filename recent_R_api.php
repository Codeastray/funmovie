<?php
session_start();
    require_once("movie_dbtool.php");
    $conn = create_connect();
    $C_UID =   $_SESSION["UID"];
	// 抓主帳號a_user_recent中，該主帳號所有10內最近觀賞的片單
	$sql = "SELECT c_movie.Moviename,c_movie.Image FROM c_movie INNER JOIN (SELECT a_user_recent.Recent, a_user_recent.Time FROM a_user INNER JOIN a_user_recent ON a_user.ID = a_user_recent.M_ID WHERE (SELECT ID FROM a_user WHERE UID = '$C_UID') = a_user_recent.M_ID  ORDER BY a_user_recent.Time DESC)B WHERE Movie_ID = B.Recent ORDER BY B.Time DESC ";
	$result =execute_sql($conn,"funmovie",$sql);
	if(mysqli_num_rows($result) > 0){
	   $mydata = array();
	   while($row = mysqli_fetch_assoc($result)){
	   $mydata [] = $row;
	  // echo json_encode($mydata);
	}
    // $row = mysqli_fetch_assoc($result);
		echo '{"state" : true, "data":'.json_encode($mydata).',"message" : "最近觀賞讀取成功!" }';
	}else{
		echo '{"state" : false, "message" : "最近觀賞讀取資料失敗" }';
	}
	 mysqli_close($conn);
?>
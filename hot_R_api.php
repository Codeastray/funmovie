<?php	
    require_once("movie_dbtool.php");
    $conn = create_connect();
	// 到資料庫中抓c_movie中Hot欄位的值，由大到小排列，並限制只抓前十部影片
	$sql = "SELECT  c_movie.Moviename FROM c_movie ORDER BY c_movie.Hot DESC LIMIT 10;";
	$result =execute_sql($conn,"funmovie",$sql);
	if(mysqli_num_rows($result) > 0){
	   $mydata = array();
	   while($row = mysqli_fetch_assoc($result)){
	   $mydata [] = $row;
	  // echo json_encode($mydata);
	}
		echo '{"state" : true, "data":'.json_encode($mydata).',"message" : "資料讀取成功!" }';
	}else{
		echo '{"state" : false, "message" : "讀取資料失敗" }';
	}
	 mysqli_close($conn);	
?>
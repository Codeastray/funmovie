<?php
	$data = file_get_contents("php://input", "r");
	$mydata = array();
	$mydata = json_decode($data, true);
    require_once("movie_dbtool.php");
    $conn = create_connect();
	$sql = "SELECT c_movie.Moviename FROM c_movie WHERE Love = 4";
	$result =execute_sql($conn,"funmovie",$sql);
	if(mysqli_num_rows($result) > 0){
	   $mydata = array();
	   while($row = mysqli_fetch_assoc($result)){
	   $mydata [] = $row;
	}
		echo '{"state" : true, "data":'.json_encode($mydata).',"message" : "中國影片讀取成功!" }';
	}else{
		echo '{"state" : false, "message" : "讀取資料失敗" }';
	}
	 mysqli_close($conn);
?>
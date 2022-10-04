<?php
	require_once("movie_dbtool.php");
	$link = create_connect();
	$sql = "SELECT * FROM c_movie";

	$result = execute_sql($link,"funmovie", $sql);
	$mydata = array();
	if(mysqli_num_rows($result) > 0){
	   while($row = mysqli_fetch_assoc($result)){
	   $mydata [] = $row;
	}
		echo '{"state" : true, "data":'.json_encode($mydata).',"message" : "資料讀取成功!" }';
	}else{
		echo '{"state" : false, "message" : "讀取資料失敗" }';
	}
	 mysqli_close($link);
?>
<?php
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);
if (isset($mydata["moviename"])) {
	if ($mydata["moviename"] != "" ) {
		$moviename = $mydata["moviename"];
		require_once("movie_dbtool.php");
		$conn = create_connect();
		// 用a=a+1的語法在資料庫欄位上重複計數
		$sql = "UPDATE c_movie SET c_movie.Hot= c_movie.Hot + 1 WHERE c_movie.Moviename ='$moviename' ";
		if (execute_sql($conn, "funmovie", $sql)) {
			echo '{"state" : true, "message" : "點擊計次累計成功!" }';
		} else {
			echo '{"state" : false, "message" : "累計失敗!:' . mysqli_error($conn) . $sql . ' "}';
		}
		mysqli_close($conn);
	} else {
		echo '{"state" : false, "message" : "欄位不得為空白!" }';
	}
} else {
	echo '{"state" : false, "message" : "欄位錯誤!" }';
}

<?php
session_start();
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);
$C_UID = $_SESSION["UID"];
$moviename = $mydata["moviename"];
// 先過濾前端點擊影片後，有沒有值傳進來
if (isset($mydata["moviename"])) {
	if ($mydata["moviename"] != "") {
		require_once("movie_dbtool.php");
		$conn = create_connect();
		$sql = "SELECT * FROM a_user_recent WHERE M_ID =( SELECT a_user.ID FROM a_user WHERE UID = '$C_UID')";
		$result = execute_sql($conn, "funmovie", $sql);
		if (mysqli_num_rows($result) < 11) {
			$sql = "INSERT INTO a_user_recent (M_ID, Recent) SELECT ( SELECT a_user.ID FROM a_user WHERE  UID = '$C_UID'), (SELECT c_movie.Movie_ID FROM c_movie WHERE  c_movie.Moviename = '$moviename') WHERE NOT EXISTS (SELECT a_user_recent.Recent FROM a_user_recent WHERE a_user_recent.Recent = (SELECT c_movie.Movie_ID FROM c_movie WHERE  c_movie.Moviename = '$moviename') AND M_ID =( SELECT a_user.ID FROM a_user WHERE  UID = '$C_UID'))";
			$result = execute_sql($conn, "funmovie", $sql);
			echo '{"state" : true, "message" : "最近觀看新增成功!" }';
		} else if(mysqli_num_rows($result) > 10) {
			$sql="UPDATE a_user_recent SET a_user_recent.Recent = (SELECT c_movie.Movie_ID FROM c_movie WHERE c_movie.Moviename = '$moviename'), a_user_recent.Time = CURRENT_TIMESTAMP WHERE a_user_recent.M_ID = (SELECT a_user.ID FROM a_user WHERE UID = '$C_UID') AND Time =  (SELECT B.Time FROM(SELECT MIN(Time)AS Time FROM a_user_recent  WHERE a_user_recent.M_ID = (SELECT a_user.ID FROM a_user WHERE UID = '$C_UID')) AS B)";
			$result = execute_sql($conn, "funmovie", $sql);
			echo '{"state" : true, "message" : "最近觀看更新成功!" }';
		}else{
			echo '{"state" : false, "message" : "最近觀看新增失敗!:' . mysqli_error($conn) . $sql . ' "}';
		}
		mysqli_close($conn);
	} else {
		echo '{"state" : false, "message" : "欄位不得為空白!" }';
	}
} else {
	echo '{"state" : false, "message" : "欄位錯誤!" }';
}

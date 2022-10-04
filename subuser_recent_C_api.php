<?php
session_start();
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);
$C_UID = $_SESSION["UID"];
$sub_id = $_SESSION["sub_id"];
$moviename = $mydata["moviename"];
// 先過濾前端點擊影片後，有沒有值傳進來
if (isset($mydata["moviename"])) {
	if ($mydata["moviename"] != "") {
		require_once("movie_dbtool.php");
		$conn = create_connect();
		// 去找所有該主帳號ID 在資料庫中 最近觀看的所有片單數量
		$sql = "SELECT * FROM b_user_recent WHERE Sub_ID =(SELECT b_user.ID FROM b_user INNER JOIN a_user ON a_user.ID = b_user.M_ID WHERE a_user.UID = '$C_UID' AND b_user.Sub_User='$sub_id');";
		$result = execute_sql($conn, "funmovie", $sql);
		// 先判斷是否10部以下
		if (mysqli_num_rows($result) < 10) {
			//再找從前端傳過來，被點擊的片單，是否在資料庫已存在
			$sql = "SELECT * FROM b_user_recent WHERE b_user_recent.Sub_ID = (SELECT b_user.ID FROM b_user INNER JOIN a_user ON a_user.ID = b_user.M_ID WHERE a_user.UID = '$C_UID' AND b_user.Sub_User='$sub_id') AND b_user_recent.Recent = (SELECT c_movie.Movie_ID FROM c_movie WHERE c_movie.Moviename = '$moviename')";
			$result = execute_sql($conn, "funmovie", $sql);
			// 判斷如已存在，就 == 1
			if (mysqli_num_rows($result)  == 1) {
				// 該片存在就只要更新時間戳記為最新時間即可
				$sql = "UPDATE b_user_recent SET b_user_recent.Time = CURRENT_TIME WHERE b_user_recent.Sub_ID = (SELECT b_user.ID FROM b_user INNER JOIN a_user ON a_user.ID = b_user.M_ID WHERE a_user.UID = '$C_UID' AND b_user.Sub_User='$sub_id') AND b_user_recent.Recent = (SELECT c_movie.Movie_ID FROM c_movie WHERE c_movie.Moviename = '$moviename')";
				$result = execute_sql($conn, "funmovie", $sql);
				echo '{"state" : true, "message" : "最近觀看單筆更新成功!" }';
			} else {
				// 該片不存在就新增一筆新的最近觀看片單
				$sql = "INSERT INTO b_user_recent (Sub_ID, Recent) SELECT (SELECT b_user.ID FROM b_user INNER JOIN a_user ON a_user.ID = b_user.M_ID WHERE a_user.UID = '$C_UID' AND b_user.Sub_User='$sub_id'), (SELECT c_movie.Movie_ID FROM c_movie WHERE  c_movie.Moviename = '$moviename') WHERE NOT EXISTS (SELECT b_user_recent.Recent FROM b_user_recent WHERE b_user_recent.Recent = (SELECT c_movie.Movie_ID FROM c_movie WHERE  c_movie.Moviename = '$moviename') AND Sub_ID =(SELECT b_user.ID FROM b_user INNER JOIN a_user ON a_user.ID = b_user.M_ID WHERE a_user.UID = '$C_UID' AND b_user.Sub_User='$sub_id'))";
				$result = execute_sql($conn, "funmovie", $sql);
				echo '{"state" : true, "message" : "最近觀看單筆新增成功!" }';
			}
			
			//一旦資料庫已滿10部(=10列)
		} else if (mysqli_num_rows($result) == 10) {
			//再找從前端傳過來，被點擊的片單，是否在資料庫已存在
			$sql = "SELECT * FROM b_user_recent WHERE b_user_recent.Sub_ID = (SELECT b_user.ID FROM b_user INNER JOIN a_user ON a_user.ID = b_user.M_ID WHERE a_user.UID = '$C_UID' AND b_user.Sub_User='$sub_id') AND b_user_recent.Recent = (SELECT c_movie.Movie_ID FROM c_movie WHERE c_movie.Moviename = '$moviename')";
			$result = execute_sql($conn, "funmovie", $sql);
			// 判斷如已存在，就 == 1
			if (mysqli_num_rows($result)  == 1) {
				// 該片存在就只要更新時間戳記為最新時間即可
				$sql = "UPDATE b_user_recent SET b_user_recent.Time = CURRENT_TIME WHERE b_user_recent.Sub_ID = (SELECT b_user.ID FROM b_user INNER JOIN a_user ON a_user.ID = b_user.M_ID WHERE a_user.UID = '$C_UID' AND b_user.Sub_User='$sub_id') AND b_user_recent.Recent = (SELECT c_movie.Movie_ID FROM c_movie WHERE c_movie.Moviename = '$moviename')";
				$result = execute_sql($conn, "funmovie", $sql);
				echo '{"state" : true, "message" : "最近觀看單筆更新成功!" }';
			} else {
				// 該片不存在就找出10部裡，最近觀賞時間最早的一列，更新片名和時間
				$sql = "UPDATE b_user_recent SET b_user_recent.Recent = (SELECT c_movie.Movie_ID FROM c_movie WHERE c_movie.Moviename = '$moviename'), b_user_recent.Time = CURRENT_TIMESTAMP WHERE b_user_recent.Sub_ID =(SELECT b_user.ID FROM b_user INNER JOIN a_user ON a_user.ID = b_user.M_ID WHERE a_user.UID = '$C_UID' AND b_user.Sub_User='$sub_id') AND Time =  (SELECT B.Time FROM(SELECT MIN(Time)AS Time FROM b_user_recent  WHERE b_user_recent.Sub_ID =(SELECT b_user.ID FROM b_user INNER JOIN a_user ON a_user.ID = b_user.M_ID WHERE a_user.UID = '$C_UID' AND b_user.Sub_User='$sub_id')) AS B)";
				$result = execute_sql($conn, "funmovie", $sql);
				echo '{"state" : true, "message" : "最近觀看滿10部更新成功!" }';
			}
		} else {
			echo '{"state" : false, "message" : "最近觀看新增失敗!:' . mysqli_error($conn) . $sql . ' "}';
		}
		mysqli_close($conn);
	} else {
		echo '{"state" : false, "message" : "欄位不得為空白!" }';
	}
} else {
	echo '{"state" : false, "message" : "欄位錯誤!" }';
}

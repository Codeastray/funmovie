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
		// 去找所有該主帳號ID 在資料庫中 所有我的片單儲存數量
		$sql = "SELECT * FROM a_user_save WHERE M_ID =( SELECT a_user.ID FROM a_user WHERE UID = '$C_UID')";
		$result = execute_sql($conn, "funmovie", $sql);
		// 先判斷是否30部以下
		if (mysqli_num_rows($result) < 30) {
			// 若30以下，就新增片單，但若點擊資料庫中已有的片單，則不會有任何動作 (因為之後會改掉前端按鈕限制已存片單不能點擊)
			$sql = "INSERT INTO a_user_save (M_ID, Save) SELECT ( SELECT a_user.ID FROM a_user WHERE  UID = '$C_UID'), (SELECT c_movie.Movie_ID FROM c_movie WHERE  c_movie.Moviename = '$moviename') WHERE NOT EXISTS (SELECT a_user_save.Save FROM a_user_save WHERE a_user_save.Save = (SELECT c_movie.Movie_ID FROM c_movie WHERE  c_movie.Moviename = '$moviename') AND M_ID =( SELECT a_user.ID FROM a_user WHERE  UID = '$C_UID'))";
			$result = execute_sql($conn, "funmovie", $sql);
			echo '{"state" : true, "message" : "單筆新增成功!" }';
			//一旦資料庫已滿30部(=30列)
		} else if (mysqli_num_rows($result) == 30) {
			// 點擊後，如果該片從未被存過會被存至我的片單，則自動刪除時間最舊的那部影片
			$sql = "DELETE FROM a_user_save WHERE M_ID =( SELECT a_user.ID FROM a_user WHERE UID = '$C_UID') AND Time = (SELECT B.Time FROM(SELECT MIN(Time)AS Time FROM a_user_save WHERE M_ID =( SELECT a_user.ID FROM a_user WHERE UID = '$C_UID'))AS B)";
			$result = execute_sql($conn, "funmovie", $sql);
            // 然後再新增該片，但是若點擊資料庫中已有的片單，不會有任何的動作，但依然會照上述一樣刪除一部片。(之後會改掉前端按鈕限制已存片單不能點擊))
			$sql = "INSERT INTO a_user_save (M_ID, Save) SELECT ( SELECT a_user.ID FROM a_user WHERE  UID = '$C_UID'), (SELECT c_movie.Movie_ID FROM c_movie WHERE  c_movie.Moviename = '$moviename') WHERE NOT EXISTS (SELECT a_user_save.Save FROM a_user_save WHERE a_user_save.Save = (SELECT c_movie.Movie_ID FROM c_movie WHERE  c_movie.Moviename = '$moviename') AND M_ID =( SELECT a_user.ID FROM a_user WHERE  UID = '$C_UID'))";
			$result = execute_sql($conn, "funmovie", $sql);
			echo '{"state" : true, "message" : "滿30部，最舊一筆刪除成功並新增新的一筆成功!" }';
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
?>

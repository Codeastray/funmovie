<?php
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);
// 找尋資料庫有沒有相同的帳號
if (isset($mydata["email_user"])) {
	if ($mydata["email_user"] != "") {
		$check_username = $mydata["email_user"];
		require_once("movie_dbtool.php");
		$conn = create_connect();
		$sql = "SELECT Email_User FROM a_user WHERE Email_User = '$check_username'";
		$result = execute_sql($conn, "funmovie", $sql);
		if (mysqli_num_rows($result) == 1) {
			echo '{"state" : false, "message" : "帳號存在，不可以使用" }';
		} else {
			echo '{"state" : true, "message" : "帳號不存在，可以使用!" }';
		}
		// $mydata = array();
		// while($row = mysqli_fetch_assoc($result)){
		//   $mydata [] = $row;
		// }
		mysqli_close($conn);
	} else {
		echo '{"state" : false, "message" : "欄位不得為空白!" }';
	}
} else {
	echo '{"state" : false, "message" : "欄位錯誤!" }';
}

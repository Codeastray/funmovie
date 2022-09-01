<?php
session_start(); //用SESSION一定要加
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);
$C_UID =   $_SESSION["UID"];
if (isset($mydata["password"])) {
	if ($mydata["password"] != "") {
		$check_password = $mydata["password"];
		require_once("movie_dbtool.php");
		$conn = create_connect();
		// 雖然只是確認密碼，但是如果不在WHERE中加入條件限制哪個UID，就有可能因為兩個主帳號密碼相同造成錯誤
		$sql = " SELECT  Password FROM a_user WHERE Password ='$check_password' AND UID = '$C_UID' ";
		$result = execute_sql($conn, "funmovie", $sql);
		if (mysqli_num_rows($result) == 1) {
				echo '{"state" : true, "message" : "認證成功" }';
		} else {
			echo '{"state" : false, "message" : "認證失敗" }';
		}
		mysqli_close($conn);
	} else {
		echo '{"state" : false, "message" : "欄位不得為空白!" }';
	}
} else {
	echo '{"state" : false, "message" : "欄位錯誤!" }';
}

<?php
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);
if (isset($mydata["email_user"])) {
	if ($mydata["email_user"] != "") {
		$check_email_user = $mydata["email_user"];
		$check_password = $mydata["password"];
		$UID;
		require_once("movie_dbtool.php");
		$conn = create_connect();
		// 確認帳號密碼正確
		$sql = " SELECT Email_User, Password FROM a_user WHERE Email_User = '$check_email_user' AND Password ='$check_password' ";
		$result = execute_sql($conn, "funmovie", $sql);
		if (mysqli_num_rows($result) == 1) {
			// 產生UID
			$UID = substr(sha1(md5(uniqid(date("l jS \of F Y h:i:s A")))), 5, 7);
			// 更新至資料庫的UID
			$sql = " UPDATE a_user SET UID ='$UID'  WHERE Email_User = '$check_email_user'";
			$result = execute_sql($conn, "funmovie", $sql);
			if ($result) {
				echo '{"state" : true, "message" : "登入成功","UID" :"' . $UID . '" }';
			} else {
				echo '{"state" : false, "message" : "uid更新失敗，登入失敗" }';
			}
		} else {
			echo '{"state" : false, "message" : "登入失敗" }';
		}
		mysqli_close($conn);
	} else {
		echo '{"state" : false, "message" : "欄位不得為空白!" }';
	}
} else {
	echo '{"state" : false, "message" : "欄位錯誤!" }';
}

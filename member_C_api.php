<?php
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);
// 建立帳號確認欄位資料
if (isset($mydata["email_user"]) && isset($mydata["password"]) && isset($mydata["nickname"]) && isset($mydata["birth"]) && isset($mydata["sex"]) && isset($mydata["area"]) && isset($mydata["phone"])  && isset($mydata["love"]) && isset($mydata["source"]) && isset($mydata["level"]) && isset($mydata["how_pay"])) {
	if ($mydata["email_user"] != "" && $mydata["password"] != ""  && $mydata["nickname"] != ""  && $mydata["birth"] != ""  && $mydata["sex"] != ""  && $mydata["area"] != ""  && $mydata["phone"] != ""   && $mydata["love"] != "" && $mydata["source"] != "" && $mydata["level"] != "" && $mydata["how_pay"] != "") {
		$m_email_user = $mydata["email_user"];
		$m_password = $mydata["password"];
		$m_nickname = $mydata["nickname"];
		$m_birth = $mydata["birth"];
		$m_sex = $mydata["sex"];
		$m_area = $mydata["area"];
		$m_phone = $mydata["phone"];
		$m_love = $mydata["love"];
		$m_source = $mydata["source"];
		$m_level = $mydata["level"];
		$m_how_pay = $mydata["how_pay"];
		require_once("movie_dbtool.php");
		$conn = create_connect();
		$sql = "INSERT INTO a_user(Email_User,Password,Nickname,Birth,Sex,Area,Phone,Love,Source,Level,How_Pay)VALUES 
        ('$m_email_user','$m_password','$m_nickname','$m_birth','$m_sex','$m_area','$m_phone','$m_love','$m_source','$m_level','$m_how_pay')";
		if (execute_sql($conn, "funmovie", $sql)) {
			echo '{"state" : true, "message" : "註冊成功!" }';
		} else {
			echo '{"state" : false, "message" : "註冊失敗!:' . mysqli_error($conn) . $sql . ' "}';
		}
		mysqli_close($conn);
	} else {
		echo '{"state" : false, "message" : "欄位不得為空白!" }';
	}
} else {
	echo '{"state" : false, "message" : "欄位錯誤!" }';
}

<?php
session_start();
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);
// 從user_select.html中點選子帳號暱稱後，監聽得到sub_id的值
if (isset($mydata["sub_id"])) {
    if ($mydata["sub_id"] != "") {
		$sub_id = $mydata["sub_id"];
		// 另開一個$_SESSION["sub_id"]存入，上面剛剛從前端使用者點擊暱稱所監聽到的值
        $_SESSION["sub_id"]=$sub_id;
		echo '{"state" : true, "message" : "資料傳送成功" }';
    } else {
        echo '{"state" : false, "message" : "資料傳送失敗" }';
    }
} else {
	require_once("movie_dbtool.php");
	$conn = create_connect();
	$C_UID = $_SESSION["UID"];
	$sub_id= $_SESSION["sub_id"];
	// 利用上面的 $_SESSION["sub_id"]=$sub_id ，到資料庫中確認子帳號是誰後，抓取暱稱，渲染至子帳號首頁
	$sql = "SELECT b_user.Nickname FROM b_user INNER JOIN a_user ON a_user.ID = b_user.M_ID WHERE (SELECT ID FROM a_user WHERE UID = '$C_UID') = b_user.M_ID AND b_user.Sub_User='$sub_id';";
	$result = execute_sql($conn, "funmovie", $sql);
	if (mysqli_num_rows($result) == 1) {
		$row = mysqli_fetch_assoc($result);
		echo '{"state" : true, "message" : "讀取片單成功","data":' . json_encode($row) . ' }';
	} else {
		echo '{"state" : false, "message" : "登入失敗!" }';
	}
}
mysqli_close($conn);
?>
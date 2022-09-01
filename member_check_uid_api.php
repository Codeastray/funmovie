<?php
session_start();
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);
// UID確認
if (isset($mydata["UID"])) {
    if ($mydata["UID"] != "") {
        $C_UID = $mydata["UID"];
        // 從瀏覽器抓取cookie傳過來，順便存進SESSION
        $_SESSION["UID"] = $C_UID ;
        require_once("movie_dbtool.php");
        $conn = create_connect();
        $sql = "SELECT UID,Nickname FROM a_user WHERE UID = '$C_UID' ";
        $result = execute_sql($conn, "funmovie", $sql);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            echo '{"state" : true, "message" : "登入成功","data":' . json_encode($row) . ' }';
        } else {
            echo '{"state" : false, "message" : "登入失敗!" }';
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

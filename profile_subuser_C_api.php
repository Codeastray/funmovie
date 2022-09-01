<?php
session_start();
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);
// 抓取SESSION中的UID
$C_UID = $_SESSION["UID"];
$C_nickname = $mydata["nickname"];
require_once("movie_dbtool.php");
$conn = create_connect();
// 先搜尋資料庫，主帳號中有沒有編號1的子帳號 (子帳號在資料庫中用1 2 3 4 5代替)
// 找b_user.Sub_User欄位，設立條件:b_user.M_ID是主帳號的哪個ID +  b_user.Sub_User = 1
$sql = " SELECT b_user.Sub_User FROM b_user WHERE b_user.M_ID = (SELECT ID FROM a_user WHERE UID = '$C_UID') AND b_user.Sub_User = 1";
$result = execute_sql($conn, "funmovie", $sql);
// 設立條件判斷，如果找到，就創新的(例:找到編號1有了，就往下判斷創建編號2)
if (mysqli_num_rows($result) > 0) {
    $sql = " SELECT b_user.Sub_User FROM b_user WHERE b_user.M_ID = (SELECT ID FROM a_user WHERE UID = '$C_UID') AND b_user.Sub_User = 2";
    $result = execute_sql($conn, "funmovie", $sql);
    if (mysqli_num_rows($result) > 0) {
        $sql = " SELECT b_user.Sub_User FROM b_user WHERE b_user.M_ID = (SELECT ID FROM a_user WHERE UID = '$C_UID') AND b_user.Sub_User = 3";
        $result = execute_sql($conn, "funmovie", $sql);
        if (mysqli_num_rows($result) > 0) {
            $sql = " SELECT b_user.Sub_User FROM b_user WHERE b_user.M_ID = (SELECT ID FROM a_user WHERE UID = '$C_UID') AND b_user.Sub_User = 4";
            $result = execute_sql($conn, "funmovie", $sql);
            if (mysqli_num_rows($result) > 0) {
                $sql = " SELECT b_user.Sub_User FROM b_user WHERE b_user.M_ID = (SELECT ID FROM a_user WHERE UID = '$C_UID') AND b_user.Sub_User = 5";
                $result = execute_sql($conn, "funmovie", $sql);
                if (mysqli_num_rows($result) > 0) {
                    //  最後第5個找到了，設立訊息，可增加成員已滿
                    echo '{"state" : false, "message" : "可增加成員已滿" }';
                } else {
                    if (isset($mydata["nickname"])) {
                        if ($mydata["nickname"] != "") {
                            $C_nickname = $mydata["nickname"];
                            $sql = " INSERT INTO b_user ( Sub_User, Nickname)VALUES (5, '$C_nickname')";
                            $result = execute_sql($conn, "funmovie", $sql);
                            $sql = " UPDATE b_user 
                            SET M_ID =  (SELECT ID FROM a_user WHERE UID = '$C_UID' ) WHERE Sub_User = 5 AND Nickname = '$C_nickname';";
                            $result = execute_sql($conn, "funmovie", $sql);
                            echo '{"state" : true, "message" : "新增成功" }';
                        } else {
                            echo '{"state" : false, "message" : "欄位不得為空白!" }';
                        }
                    } else {
                        echo '{"state" : false, "message" : "欄位錯誤!" }';
                    }
                }
            } else {
                if (isset($mydata["nickname"])) {
                    if ($mydata["nickname"] != "") {
                        $C_nickname = $mydata["nickname"];
                        $sql = " INSERT INTO b_user ( Sub_User, Nickname)VALUES (4, '$C_nickname')";
                        $result = execute_sql($conn, "funmovie", $sql);
                        $sql = " UPDATE b_user 
                        SET M_ID =  (SELECT ID FROM a_user WHERE UID = '$C_UID') WHERE Sub_User = 4 AND Nickname = '$C_nickname';";
                        $result = execute_sql($conn, "funmovie", $sql);
                        echo '{"state" : true, "message" : "新增成功" }';
                    } else {
                        echo '{"state" : false, "message" : "欄位不得為空白!" }';
                    }
                } else {
                    echo '{"state" : false, "message" : "欄位錯誤!" }';
                }
            }
        } else {
            if (isset($mydata["nickname"])) {
                if ($mydata["nickname"] != "") {
                    $C_nickname = $mydata["nickname"];
                    $sql = " INSERT INTO b_user ( Sub_User, Nickname)VALUES (3, '$C_nickname')";
                    $result = execute_sql($conn, "funmovie", $sql);
                    $sql = " UPDATE b_user 
                    SET M_ID =  (SELECT ID FROM a_user WHERE UID = '$C_UID')  WHERE Sub_User = 3 AND Nickname = '$C_nickname';";
                    $result = execute_sql($conn, "funmovie", $sql);
                    echo '{"state" : true, "message" : "新增成功" }';
                } else {
                    echo '{"state" : false, "message" : "欄位不得為空白!" }';
                }
            } else {
                echo '{"state" : false, "message" : "欄位錯誤!" }';
            }
        }
    } else {
        if (isset($mydata["nickname"])) {
            if ($mydata["nickname"] != "") {
                $C_nickname = $mydata["nickname"];
                $sql = " INSERT INTO b_user ( Sub_User, Nickname)VALUES (2, '$C_nickname')";
                $result = execute_sql($conn, "funmovie", $sql);
                $sql = " UPDATE b_user SET M_ID =  (SELECT ID FROM a_user WHERE UID = '$C_UID')  WHERE Sub_User = 2 AND Nickname = '$C_nickname';";
                $result = execute_sql($conn, "funmovie", $sql);
                echo '{"state" : true, "message" : "新增成功" }';
            } else {
                echo '{"state" : false, "message" : "欄位不得為空白!" }';
            }
        } else {
            echo '{"state" : false, "message" : "欄位錯誤!" }';
        }
    }
} else {
    // 找不到，就創建編號1的子帳號(例如:找不到編號1，就在else裡面創建編號1的子帳號)
    if (isset($mydata["nickname"])) {
        if ($mydata["nickname"] != "") {
            $C_nickname = $mydata["nickname"];
            // 在資料庫中b_user的M_ID預設值要null，然後將b_user中的 Sub_User, Nickname新增資料進去
            $sql = " INSERT INTO b_user ( Sub_User, Nickname)VALUES (1, '$C_nickname')";
            $result = execute_sql($conn, "funmovie", $sql);
            // 接著把原本是null的M_ID，用子查詢的方式，更新成主帳號的ID(a_user.ID)
            $sql = " UPDATE b_user SET M_ID =  (SELECT ID FROM a_user WHERE UID = '$C_UID') WHERE Sub_User = 1 AND Nickname = '$C_nickname' ";
            $result = execute_sql($conn, "funmovie", $sql);
            echo '{"state" : true, "message" : "新增成功" }';
        } else {
            echo '{"state" : false, "message" : "欄位不得為空白!" }';
        }
    } else {
        echo '{"state" : false, "message" : "欄位錯誤!" }';
    }
}
mysqli_close($conn);

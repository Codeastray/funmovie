<?php
    session_start();
    $data = file_get_contents("php://input", "r");
    $mydata = array();
    $mydata = json_decode($data, true);
    if(isset($mydata["ID"])){
        if($mydata["ID"] != ""){
            $C_UID = $_SESSION["UID"];
            $C_ID = $mydata["ID"];
            require_once("movie_dbtool.php");
            $conn = create_connect();
            $sql = "DELETE FROM b_user WHERE ID = '$C_ID'";
            $result = execute_sql($conn, "funmovie", $sql);
            if($result){
                if(mysqli_affected_rows($conn) == 1){
                    
                    $sql = "DELETE FROM b_user_save WHERE Sub_ID = '$C_ID'";
                    $result = execute_sql($conn, "funmovie", $sql);
                    if($result){
                        echo '{"state": true, "message":"刪除成員資料成功"}';
                        session_unset();
                       // echo '{"state": true, "message":"刪除成員UID"}';
                    }else{
                        echo '{"state": false, "message":"刪除成員失敗"}';
                    }
                }else{
                    echo '{"state": false, "message":"無此資料!"}';
                }   
            }else{
                echo '{"state": false, "message":"刪除失敗!'.mysqli_error($conn).'"}';
            }
            mysqli_close($conn);
        }else{
            echo '{"state": false, "message":"欄位不得為空白!"}';
        }
    }else{
        echo '{"state": false, "message":"API規定的欄位不存在!"}';
    }
?>
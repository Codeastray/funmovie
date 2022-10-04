<?php
	//接收前端的json 字串資料
	session_start();
	$data = file_get_contents("php://input", "r");
	$mydata = array();
	$mydata = json_decode($data, true);
	$C_UID = $_SESSION["UID"];
	//判斷欄位是否存在
	if(isset($mydata["UID"]) && isset($mydata["Nickname"]) && isset($mydata["Birth"]) && isset($mydata["Sex"]) && isset($mydata["Area"]) && isset($mydata["Phone"])){
		//判斷欄位是否為空白
		if($mydata["UID"] != "" && $mydata["Nickname"] != "" && $mydata["Birth"] != "" && $mydata["Sex"] != "" && $mydata["Area"] != "" && $mydata["Phone"] != ""){
			$U_UID = $mydata["UID"];
			$U_Nickname = $mydata["Nickname"];
			$U_Birth = $mydata["Birth"];
			$U_Sex = $mydata["Sex"];
			$U_Area = $mydata["Area"];
			$U_Phone = $mydata["Phone"];
			require_once("movie_dbtool.php");
			$conn = create_connect();
			$sql = "UPDATE a_user SET Nickname = '$U_Nickname', Birth = '$U_Birth', Sex = '$U_Sex', Area = '$U_Area', Phone = '$U_Phone' WHERE UID = '$C_UID'";
			if(execute_sql($conn, "funmovie", $sql)){
				if(mysqli_affected_rows($conn) == 1){
					echo '{"state": true, "message":"更新成功!"}';
				}else{
					echo '{"state": false, "message":"更新失敗, 語法成功但無此欄位!"}';
				}
			}else{
				echo '{"state": false, "message":"更新失敗!'.mysqli_error($conn).'"}';
			}
			mysqli_close($conn);
		}else{
			echo '{"state": false, "message":"欄位不得為空白!"}';
		}
	}else{
		echo '{"state": false, "message":"API規定的欄位不存在!.U"}';
	}
?>
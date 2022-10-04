<?php
	//接收前端的json 字串資料
	$data = file_get_contents("php://input", "r");
	$mydata = array();
	$mydata = json_decode($data, true);
	//判斷欄位是否存在
	if(isset($mydata["ID"]) && isset($mydata["Nickname"])){
		//判斷欄位是否為空白
		if($mydata["ID"] != "" && $mydata["Nickname"] != ""){
			$U_ID = $mydata["ID"];
			$U_Nickname = $mydata["Nickname"];
			require_once("movie_dbtool.php");
			$conn = create_connect();
			$sql = "UPDATE b_user SET Nickname = '$U_Nickname' WHERE ID = '$U_ID'";
			$result = execute_sql($conn, "funmovie", $sql);
			if($result){
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
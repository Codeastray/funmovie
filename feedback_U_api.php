<?php
	$data = file_get_contents("php://input","r");
	$mydata = array();
	$mydata = json_decode($data, true);

	if(isset($mydata["Feedback"])){
		if($mydata["Feedback"] != ""){
			$u_id = $mydata["UID"];
			$u_FB = $mydata["Feedback"];
			
			require_once("movie_dbtool.php");
			$link = create_connect();
			$sql = "UPDATE a_user SET Feedback = '$u_FB' WHERE UID = '$u_id'";

			if(execute_sql($link,"funmovie",$sql)){
				if(mysqli_affected_rows($link) == 1){
					echo '{"state" : true, "message" : "更新成功!" }';
				}else{
					echo '{"state" : false, "message" : "語法正確，無資料被更新!" }';
				}
			}else{
				echo'{"state" : false, "message" : "更新失敗!"}';
			}
			mysqli_close($link);
		}else{
			echo '{"state": false, "message":"欄位不可為空白!!!"}';
		}
	}else{
		echo '{"state": false, "message":"API規定的欄位不存在!!!"}';
	}	
?>
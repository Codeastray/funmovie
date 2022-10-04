<?php 
	$data = file_get_contents("php://input","r");
	$mydata = array();
	$mydata = json_decode($data, true);

	if(isset($mydata["UID"])){
		if($mydata["UID"] != ""){
			$d_id = $mydata["UID"];
			require_once("movie_dbtool.php");
			$conn = create_connect();
			$sql = "DELETE FROM a_user WHERE UID = '$d_id'";	
			if(execute_sql($conn,"funmovie",$sql)){
				if(mysqli_affected_rows($conn) == 1){
					echo '{"state" : true, "message" : "刪除成功!" }';
				}else{
					echo '{"state" : false, "message" : "語法正確，無資料被刪除!" }';
				}
			}else{
				echo'{"state" : false, "message" : "刪除失敗!"}';
			}
			mysqli_close($conn);
		}else{
			echo '{"state": false, "message":"欄位不可為空白!!!"}';
		}
	}else{
		echo '{"state": false, "message":"API規定的欄位不存在!!!"}';
	}
?>
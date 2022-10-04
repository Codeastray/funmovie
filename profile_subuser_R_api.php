<?php	
session_start();
    require_once("movie_dbtool.php");

    $conn = create_connect();
    $C_UID =   $_SESSION["UID"];
	//  找尋當下主帳號中，所有子帳號的暱稱
	$sql = " SELECT b_user.ID, b_user.Nickname, b_user.Sub_User FROM a_user INNER JOIN b_user  ON a_user.ID = b_user.M_ID WHERE (SELECT ID FROM a_user WHERE UID = '$C_UID') = b_user.M_ID ORDER BY Time ASC" ;
	
	$result =execute_sql($conn,"funmovie",$sql);
	if(mysqli_num_rows($result) > 0){
	   $mydata = array();
	   while($row = mysqli_fetch_assoc($result)){
	   $mydata [] = $row;

    
	  
	  // echo json_encode($mydata);

	}
    // $row = mysqli_fetch_assoc($result);
		echo '{"state" : true, "data":'.json_encode($mydata).',"message" : "成員資料讀取成功!" }';
	}else{
		echo '{"state" : false, "message" : "成員資料讀取失敗" }';
		
	}


	 
	 mysqli_close($conn);
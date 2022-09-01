<?php
session_start();
require_once("movie_dbtool.php");
$conn = create_connect();
$C_UID =   $_SESSION["UID"];
// 先選擇a_user中的Love欄位
$sql = "SELECT a_user.Love FROM a_user WHERE  UID = '$C_UID'";
$result = execute_sql($conn, "funmovie", $sql);
if (mysqli_num_rows($result) > 0) {
	$mydata = array();
	$row = mysqli_fetch_assoc($result);
	//  用explode()函數將"/"拿掉，再存到mydata成為陣列 
	$mydata = explode('/', $row["Love"]);
	//設迴圈，count()等於前端javascript的length，在迴圈中跑switch將剛剛mydata陣列中的數字個別處理
	for ($i = 0; $i < count($mydata); $i++) {
		switch ($mydata[$i]) {
			case 1:
				// 當從mydata[0]中抓到 1 值，就去資料庫抓所有 台灣類別的的片單
				$sql = "SELECT c_movie.Moviename FROM c_movie WHERE c_movie.Love = '$mydata[$i]'";
				$result = execute_sql($conn, "funmovie", $sql);
				//從資料庫抓片名，存到$row
				while ($row = mysqli_fetch_assoc($result)) {
					// 類別1:台灣，存到mydata1的三維陣列，限定存入$mydata1 [0][](mydata1陣列中的第0個陣列)
					$mydata1[0][]  = $row;
				}
				break;
			case 2:
				// 當從mydata[1]中抓到 2 值，就去資料庫抓所有 歐美類別的的片單
				$sql = "SELECT c_movie.Moviename FROM c_movie WHERE c_movie.Love = '$mydata[$i]'";
				$result = execute_sql($conn, "funmovie", $sql);
				//從資料庫抓片名，存到$row
				while ($row = mysqli_fetch_assoc($result)) {
					// 類別2:歐美，存到mydata1的三維陣列，限定存入$mydata1 [1][](mydata1陣列中的第1個陣列)
					$mydata1[1][] = $row;
				}
				break;
			case 3:
				// 當從mydata[2]中抓到 3 值，就去資料庫抓所有 日韓類別的的片單
				$sql = "SELECT c_movie.Moviename FROM c_movie WHERE c_movie.Love = '$mydata[$i]'";
				$result = execute_sql($conn, "funmovie", $sql);
				//從資料庫抓片名，存到$row
				while ($row = mysqli_fetch_assoc($result)) {
					// 類別3:日韓，存到mydata1的三維陣列，限定存入$mydata1 [2][](mydata1陣列中的第2個陣列)
					$mydata1[2][]  = $row;
				}
				break;
			case 4:
				// 當從mydata[3]中抓到 4 值，就去資料庫抓所有 大陸類別的的片單
				$sql = "SELECT c_movie.Moviename FROM c_movie WHERE c_movie.Love = '$mydata[$i]'";
				$result = execute_sql($conn, "funmovie", $sql);
				//從資料庫抓片名，存到$row
				while ($row = mysqli_fetch_assoc($result)) {
					// 類別4:大陸，存到mydata1的三維陣列，限定存入$mydata1 [3][](mydata1陣列中的第3個陣列)
					$mydata1[3][]  = $row;
				}
				break;
		}
	}
	echo '{"state" : true, "data":' . json_encode($mydata1) . ',"message" : "資料讀取成功!" }';
} else {
	echo '{"state" : false, "message" : "讀取資料失敗" }';
}
mysqli_close($conn);

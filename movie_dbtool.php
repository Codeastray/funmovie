<?php
//建立連線
function create_connect(){
		$conn = mysqli_connect("localhost", "movie_user", "654654")
		 or die("連線錯誤!".mysqli_connect_error());
		 mysqli_query($conn, "SET NAMES utf8");
		 return $conn;
		}
//執行sql指令
		function execute_sql($conn,$dbname,$sql){
			mysqli_select_db($conn, $dbname)
			or die ("連線資料庫失敗:".mysqli_error($conn));
			$result = mysqli_query($conn, $sql);
			return $result;
		}
?>
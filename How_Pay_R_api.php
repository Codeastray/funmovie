<?php	
    require_once("movie_dbtool.php");
    $conn = create_connect();
	$sql = "SELECT  COUNT(How_Pay) AS region,How_Pay FROM a_user GROUP BY How_Pay;";
	$result =execute_sql($conn,"funmovie",$sql);

	$mydata = Array();
	while($row = mysqli_fetch_assoc($result)){
		$mydata[] = $row;
	}
	echo json_encode($mydata);
	mysqli_close($conn);	
?>
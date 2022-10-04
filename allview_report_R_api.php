<?php	
    require_once("movie_dbtool.php");
    $conn = create_connect();
	$sql = "SELECT  SUM(Hot) AS totalview FROM c_movie;";
	$result =execute_sql($conn,"funmovie",$sql);

	$mydata = Array();
	while($row = mysqli_fetch_assoc($result)){
		$mydata[] = $row;
	}
	echo json_encode($mydata);
	mysqli_close($conn);	
?>
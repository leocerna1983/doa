<?php
require_once("conexion.php");
session_start();
if (isset($_POST['IdProyecto'])){
	
	$id = $_POST['IdProyecto'];	

	$sql = "SELECT count(*) as cantidad FROM controlhoras where idproyecto = '".$id."'";

	$update = mysqli_query($con, $sql) or die(mysqli_error());
	$listatarea = array();
	
	if(mysqli_num_rows($update) == 0){
		$data['value'] = 0;
	}else{		

		$row = mysqli_fetch_assoc($update);		
		$data['value'] = $row["cantidad"];
	}
     
    array_push($listatarea, $data); 		
	echo json_encode($listatarea);	
}
?>

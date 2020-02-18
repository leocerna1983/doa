<?php
require_once("conexion.php");
session_start();
if (isset($_POST['IdProyecto'])){
	
	$id = $_POST['IdProyecto'];	

	$sql1 = "delete FROM controlhoras where idProyecto = '".$id."'";

	$update = mysqli_query($con, $sql1) or die(mysqli_error());	

	$sql = "delete  from proyectos where idproyecto = '".$id."'";

	$update = mysqli_query($con, $sql) or die(mysqli_error());
	$listatarea = array();
	
	//if(mysqli_num_rows($update) == 0){
		//$data['value'] = 0;
	//}else{		
		//$row = mysqli_fetch_assoc($update);		
		$data['value'] = 1;
	//}     
    array_push($listatarea, $data); 		
	echo json_encode($listatarea);	
}
	
?>

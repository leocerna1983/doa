<?php 
	include("conexion.php");
	$tarea = $_POST['term'];	

	$result= mysqli_query($con,"select distinct idtarea, nombre from tareas where nombre like '%$tarea%'
");	
	
	$listatarea = array();
	while ($ver=mysqli_fetch_row($result)) {
        $data['value'] = $ver[1]; 
        $data['label'] = $ver[1]; 
        array_push($listatarea, $data); 		
	}
	echo json_encode($listatarea);	
?>
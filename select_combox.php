<?php 
	include("conexion.php");
	$tarea = $_POST['tarea'];	

	$result= mysqli_query($con,"select distinct idtarea, nombre from tareas
where nombre like '%$tarea%'");	
	$listatarea = array();
	while ($ver=mysqli_fetch_row($result)) {
        $data['idtarea'] = $ver['nombre']; 
        $data['nombre'] = $ver['nombre']; 
        array_push($listatarea, $data); 		
	}
	echo json_encode($listatarea);	
?>
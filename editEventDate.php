<?php

	require_once("conexion.php");
if (isset($_POST['Event'][0]) && isset($_POST['Event'][1]) && isset($_POST['Event'][2])){
	
	$id = $_POST['Event'][0];
	$fecha_asignacion = $_POST['Event'][1];

	$sql = "UPDATE controlhoras SET  fecha_asignacion = '$fecha_asignacion' WHERE idControlh = $id ";

	$update = mysqli_query($con, $sql) or die(mysqli_error());
	if($update){
		echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido actualizado con éxito.</div>';
	}else{
		echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo actualizar los datos !</div>';
	}
}
  header('Location: '.$_SERVER['HTTP_REFERER']);

	
?>

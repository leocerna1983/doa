<?php 
	include("conexion.php");
	$categoria = $_POST['categoria'];	

	$result= mysqli_query($con,"SELECT * FROM tareas  
	WHERE habilitado = 1 and idCategoria ='$categoria'");

	$cadena=" <select id='tarea' name='tarea'  class='form-control' required>";
	if ($categoria ==0){ 
		$cadena = $cadena."<option value='' selected>Selecciona la tarea</option>";
	}
	while ($ver=mysqli_fetch_row($result)) {
		$cadena=$cadena.'<option value='.$ver[0].'>'.utf8_encode($ver[1]).'</option>';
	}
	echo  $cadena."</select>";
	
?>
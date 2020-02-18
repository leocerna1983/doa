<?php 
	include("conexion.php");
	$continente = $_POST['continente'];

	$result= mysqli_query($con,"SELECT * FROM tareas  
	WHERE habilitado = 1 and idCategoria ='$continente'");

	$cadena="<label>SELECT 2 (paises)</label> 
			<select id='lista2' name='lista2'>";

	while ($ver=mysqli_fetch_row($result)) {
		$cadena=$cadena.'<option value='.$ver[0].'>'.utf8_encode($ver[1]).'</option>';
	}

	echo  $cadena."</select>";
	

?>
<?php 
	include("conexion.php");
	$categoria = $_POST['categoria'];
	if (isset($_POST['idProyecto']) && isset($_POST['fecha_asignacion']) && isset($_POST['categoria']) && isset($_POST['tarea'])&& isset($_POST['idUsuario'])){
		$result= mysqli_query($con,"SELECT horas FROM controlhoras where idproyecto = '".$_POST['idProyecto']."' and idusuario='".$_POST['idUsuario']."' and idcategoria='".$_POST['categoria']."' and idtarea='".$_POST['tarea']."' and fecha_asignacion='".$_POST['fecha_asignacion']."'");
		if($ver=mysqli_fetch_array($result))
		{
			echo $ver['horas'];
		}
		else
		{
			echo "0";
		}
	}
	else
	{
		echo "0";
	}		
?>
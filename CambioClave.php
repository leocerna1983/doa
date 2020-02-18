<?php
require_once("conexion.php");
session_start();
if (isset($_POST['IdUsuario']) && isset($_POST['clave']) ){
	
	$id = $_POST['IdUsuario'];
	$clave = $_POST['clave'];

	$sql = "update usuarios set password = '".md5($clave)."' where idusuario = '".$id."'";

	$update = mysqli_query($con, $sql) or die(mysqli_error());
	if($update){
		echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido actualizado con éxito.</div>';
	}else{
		echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo actualizar los datos !</div>';
	}
}
else
{
	echo "Prueba";
  //header('Location: '.$_SERVER['HTTP_REFERER']);
}

	
?>

<?php
	require_once("conexion.php");
	if (isset($_POST['idUsuarioSueldo']) && isset($_POST['sueldo']) && isset($_POST['filter']) ){		
		$idusuario = $_POST['idUsuarioSueldo'];
		$sueldo = $_POST['sueldo'];
		$filter = $_POST['filter'];

		$resulttarea= mysqli_query($con,"select idSueldoMes from sueldomes
						where idUsuario = '".$idusuario."' and mesanio = '".$filter."'");
		$idSueldoMes = 0;
		if($ver=mysqli_fetch_array($resulttarea))
		{
			//$idSueldoMes = $ver['idSueldoMes'];
			$sql = "UPDATE sueldomes set Sueldo = '".$sueldo."' where idUsuario = '".$idusuario."' and mesanio = '".$filter."'";
			mysqli_query($con, $sql);		
		}
		else
		{
			$sql = "INSERT INTO sueldomes(idUsuario,mesanio,Sueldo)
				VALUES('".$idusuario."', '".$filter."', '".$sueldo."')";
			mysqli_query($con, $sql);
		}
	}	
    echo "<script language='javascript'>window.location='".$_SERVER['HTTP_REFERER']."'</script>"; 	    	
?>

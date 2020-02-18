<?php
//&& isset($_POST['tarea'])
	require_once("conexion.php");
	//echo $_POST['idProyecto'];
	if (isset($_POST['filter']) && isset($_POST['hora']) && isset($_POST['fecha_asignacion']) && isset($_POST['categoria']) && isset($_POST['idUsuario']) && isset($_POST['idControlh'])){		
		$term = $_POST['term'];
		$idProyecto = $_POST['filter'];
		$idControlh = $_POST['idControlh'];
		$hora = $_POST['hora'];
		$fecha_asignacion = $_POST['fecha_asignacion'];
		$idCategoria = $_POST['categoria'];
		//$idTarea = $_POST['tarea'];
		$idUsuario = $_POST['idUsuario'];
		$fecha_actualizacion = date("Y-m-d H:i:s"); 
		$valor_hora = 0 ;
		$total_horas = 0 ;		
		if($term=="")
			$idTarea = 1;
		else
	    {
    		$resulttarea= mysqli_query($con,"select IDTAREA from tareas
							where upper(nombre) = '".strtoupper($term)."'");
			if($ver=mysqli_fetch_array($resulttarea))
			{
				$idTarea = $ver['IDTAREA'];
			}
			else
			{
				$sql = "INSERT INTO tareas(NOMBRE, HABILITADO, IDCATEGORIA)
					VALUES('".$term."', 1, 1)";
				mysqli_query($con, $sql);
				$sql = "select max(idtarea) as id from tareas ";
					
					$result3= mysqli_query($con,$sql);
					if($ver1=mysqli_fetch_array($result3))
					{
						$idTarea =  $ver1['id'];
					}
			}

	    }


		//$result= mysqli_query($con,"SELECT idcontrolh FROM controlhoras where idproyecto = '$idProyecto' and idusuario='".$idUsuario."' and idcategoria='".$idCategoria."' and idtarea='".$idTarea."' and fecha_asignacion='".$fecha_asignacion."'");
		//echo "SELECT idcontrolh FROM controlhoras where idcontrolh = '".$idControlh."'";
		$result= mysqli_query($con,"SELECT idcontrolh FROM controlhoras where idcontrolh = '".$idControlh."'");
		if($ver=mysqli_fetch_array($result))
		{
			$sql = "update controlhoras set horas ='".$hora."', idCategoria = '".$idCategoria."', idTarea = '".$idTarea."' where idcontrolh = '".$ver['idcontrolh']."'";
		}
		else
		{
			$sql = "INSERT INTO controlhoras (idProyecto, idUsuario, idCategoria, idTarea, fecha_actualizacion, fecha_asignacion, horas, valor_hora, total_horas) 
		                values ('".$idProyecto."', '".$idUsuario."', '".$idCategoria."', '".$idTarea."', '".$fecha_actualizacion."', 
								'".$fecha_asignacion."', '".$hora."','".$valor_hora."','".$total_horas."')";	
		}
		//or die(mysqli_error())
		$insert = mysqli_query($con, $sql);
		//echo $sql;
		//header('Location: '.$_SERVER['HTTP_REFERER']);
		/*if($insert){
			echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
		}else{
			echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
		}*/
	}
	
    echo "<script language='javascript'>window.location='".$_SERVER['HTTP_REFERER']."'</script>"; 
	    //header('Location: '.$_SERVER['HTTP_REFERER']);
	
?>

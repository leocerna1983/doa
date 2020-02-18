<?php
include("conexion.php");
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Datos de Proyecto</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">
	<style>
		.content {
			margin-top: 80px;
		}
	</style>
	
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
		<?php include('nav_home.php');?>
	</nav>
	<div class="container">
		<div class="content">
			<nav class="navbar navbar-default" style="margin-top:97px;border-color:#ffffff !important">
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav ">
						<li class="active"><a href="list_proyecto.php">Lista de proyecto</a></li>
						<li ><a href="add_proyecto.php">Agregar datos</a></li>
					</ul>
				</div>
			</nav>
			<h2>Datos del Proyecto &raquo; Detalle</h2>
			<hr />
			
			<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$id = mysqli_real_escape_string($con,(strip_tags($_GET["id"],ENT_QUOTES)));
			
			$consulta = "SELECT ccosto.nombre as centroCosto, estados.nombre as estado,clientes.nombre as cliente, o.nombres as usuarioO,c.nombres as usuarioC,
			proyectos.* FROM proyectos 
			INNER JOIN ccosto   ON ccosto.idCC = proyectos.idCC 
			INNER JOIN estados  ON estados.idEestado = proyectos.idEstado 
			INNER JOIN clientes ON clientes.idcliente = proyectos.idcliente
			INNER JOIN usuarios as o ON o.idUsuario = proyectos.idUsuario_o
			INNER JOIN usuarios as c ON c.idUsuario = proyectos.idUsuario_c 
			WHERE idProyecto='$id'";

			$sql = mysqli_query($con, $consulta);
			if(mysqli_num_rows($sql) == 0){
				header("Location: index.php");
			}else{
				$row = mysqli_fetch_assoc($sql);
			}
			
			if(isset($_GET['aksi']) == 'delete'){
				$delete = mysqli_query($con, $sql ="DELETE FROM proyectos WHERE idProyecto='$id'");
				if($delete){
					header("Location: list_proyecto.php");
					echo '<div class="alert alert-danger alert-dismissable">><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Data Borrada</div>';
				}else{
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Problema al Borrar la Data</div>';
				}
			}

			?>
			
			<table class="table table-striped table-condensed">
				<tr>
					<th width="20%">Id Proyecto</th>
					<td><?php echo $row['idProyecto']; ?></td>
				</tr>
				<tr>
					<th>Nombre del proyecto</th>
					<td><?php echo $row['nombre']; ?></td>
				</tr>
				<tr>
					<th>Descripción</th>
					<td><?php echo $row['descripcion'] ?></td>
				</tr>
				<tr>
					<th>Dirección</th>
					<td><?php echo $row['direccion']; ?></td>
				</tr>
				<tr>
					<th>Ciudad</th>
					<td><?php echo $row['ciudad']; ?></td>
				</tr>
				<tr>
					<th>Fecha Inicio</th>
					<td><?php echo explode("-",explode(" ",$row ['fechainicio'])[0])[2]."/".explode("-",explode(" ",$row ['fechainicio'])[0])[1]."/".explode("-",explode(" ",$row ['fechainicio'])[0])[0]; ?></td>
				</tr>
				<tr>
					<th>Fecha Fin</th>
					<td><?php echo explode("-",explode(" ",$row ['fechafin'])[0])[2]."/".explode("-",explode(" ",$row ['fechafin'])[0])[1]."/".explode("-",explode(" ",$row ['fechafin'])[0])[0]; ?></td>
				</tr>
				<tr>
					<th>Fecha Cierre</th>
					<td><?php echo explode("-",explode(" ",$row ['fecha_cierre'])[0])[2]."/".explode("-",explode(" ",$row ['fecha_cierre'])[0])[1]."/".explode("-",explode(" ",$row ['fecha_cierre'])[0])[0]; ?></td>
				</tr>
				<tr>
				<th>Centro Costo</th>
					<td><?php echo $row['centroCosto']; ?></td>
				</tr>
				<tr>
				<th>Cliente</th>
					<td><?php echo $row['cliente']; ?></td>
				</tr>
				<tr>
					<th>Estado</th>
						<td><?php echo $row['estado']; ?></td>
				</tr>
				
			</table>
			
			<a href="list_proyecto.php" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Regresar</a>
			<a href="edit_proyecto.php?id=<?php echo $row['idProyecto']; ?>" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Editar datos</a>
			<a href="det_proyecto.php?aksi=delete&id=<?php echo $row['idProyecto']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Esta seguro de borrar los datos <?php echo $row['nombre']; ?>')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar</a>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php
	include("conexion.php");
?>

<?php
	session_start();

	if ( isset( $_SESSION['idUsuario'] ) ) {
	?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DOA. Control de Horas</title>

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
	<div class="container">
	<nav class="navbar navbar-default navbar-fixed-top">
		<?php include('nav_home.php');?>
	</nav>
	<div class="content">
			<nav class="navbar navbar-default" style="margin-top:97px;border-color:#ffffff !important">
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav ">
						<li class="active"><a href="list_proyecto.php">Lista de proyecto</a></li>
						<li ><a href="add_proyecto.php">Agregar datos <?php echo $_SESSION['idUsuario']; ?></a></li>
					</ul>
				</div>
			</nav>
			<h2>Lista de proyecto</h2>
			<hr/>
			<?php
			if(isset($_GET['aksi']) == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$id = mysqli_real_escape_string($con,(strip_tags($_GET["id"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM proyectos WHERE idProyecto='$id'");
				if(mysqli_num_rows($cek) == 0){
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
				}else{
					// $delete = mysqli_query($con, "DELETE FROM proyectos WHERE idProyecto='$id'");

					if($delete){
						echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos eliminado correctamente.</div>';
					}else{
						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo eliminar los datos.</div>';
					}
				}
			}
			?>

			<form class="form-inline" method="get">
				<div class="form-group">
					<select  name="filter" class="form-control" onchange="form.submit()">
							<option value="0">Filtros de datos de proyecto</option>
								<?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);  ?>
								<?php				
									$estado = mysqli_query($con,"SELECT * FROM estados");
									while ($row_estado = mysqli_fetch_array ($estado))
									{ ?>
										<option value="<?php echo ($row_estado ['idEestado'])?>" 
										<?php  if($filter == $row_estado ['idEestado'] ){ echo 'selected'; } ?>>
											<?php echo $row_estado ['nombre']; ?>
								   		</option>
								<?php }?>
					</select>
				</div>
			</form>
			<br />
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>
                    <th>No</th>
					<th>Código</th>
					<th>Nombre</th>
					<th>Descripción</th>
                    <th>Ciudad</th>
					<th>Fecha Inicio</th>
					<th>Fecha Fin</th>
					<th>Fecha Cierre</th>
					<th>Centro Costo</th>
					<th>Cliente</th>
					<th>Estado</th>
                    <th>Acciones</th>
				</tr>
				<?php
				if($filter){
					$consulta = "SELECT ccosto.nombre as centroCosto, estados.nombre as estado,clientes.nombre as cliente, o.nombres as usuarioO,c.nombres as usuarioC,
					proyectos.* FROM proyectos 
					INNER JOIN ccosto   ON ccosto.idCC = proyectos.idCC 
					INNER JOIN estados  ON estados.idEestado = proyectos.idEstado 
					INNER JOIN clientes ON clientes.idcliente = proyectos.idcliente
					INNER JOIN usuarios as o ON o.idUsuario = proyectos.idUsuario_o
					INNER JOIN usuarios as c ON c.idUsuario = proyectos.idUsuario_c 
					WHERE idEstado='$filter'
					ORDER BY idProyecto ASC ";
					$sql = mysqli_query($con, $consulta);
				}else{
				  $consulta = " SELECT ccosto.nombre as centroCosto, estados.nombre as estado,clientes.nombre as cliente, o.nombres as usuarioO,c.nombres as usuarioC,
					proyectos.* FROM proyectos 
					INNER JOIN ccosto   ON ccosto.idCC = proyectos.idCC 
					INNER JOIN estados  ON estados.idEestado = proyectos.idEstado 
					INNER JOIN clientes ON clientes.idcliente = proyectos.idcliente
					INNER JOIN usuarios as o ON o.idUsuario = proyectos.idUsuario_o
					INNER JOIN usuarios as c ON c.idUsuario = proyectos.idUsuario_c 
					ORDER BY idProyecto ASC ";
					$sql = mysqli_query($con, $consulta);
				}
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td>'.$no.'</td>
							<td>'.$row['idProyecto'].'</td>
							<td><a href="det_proyecto.php?id='.$row['idProyecto'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$row['nombre'].'</a></td>
                            <td>'.$row['descripcion'].'</td>
                            <td>'.$row['ciudad'].'</td>
							<td>'.explode("-",explode(" ",$row ['fechainicio'])[0])[2]."/".explode("-",explode(" ",$row ['fechainicio'])[0])[1]."/".explode("-",explode(" ",$row ['fechainicio'])[0])[0].'</td>
							<td>'.explode("-",explode(" ",$row ['fechafin'])[0])[2]."/".explode("-",explode(" ",$row ['fechafin'])[0])[1]."/".explode("-",explode(" ",$row ['fechafin'])[0])[0].'</td>
							<td>'.explode("-",explode(" ",$row ['fecha_cierre'])[0])[2]."/".explode("-",explode(" ",$row ['fecha_cierre'])[0])[1]."/".explode("-",explode(" ",$row ['fecha_cierre'])[0])[0].'</td>
							<td>'.$row['centroCosto'].'</td>
							<td>'.$row['cliente'].'</td>
							<td>';
							if($row['idEstado'] == '1'){
								echo '<span class="label label-default">'.$row['estado'].'</span>';
							}
                            else if ($row['idEstado'] == '2' ){
								echo '<span class="label label-info">'.$row['estado'].'</span>';
							}
                            else if ($row['idEstado'] == '3' ){
								echo '<span class="label label-success">'.$row['estado'].'</span>';
							}
							else if ($row['idEstado'] == '4' ){
								echo '<span class="label label-danger">'.$row['estado'].'</span>';
							}
						echo '
							</td>
							<td>
								<a href="edit_proyecto.php?id='.$row['idProyecto'].'" title="Editar datos" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<a href="list_proyecto.php?aksi=delete&id='.$row['idProyecto'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['nombre'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>
		</div>
	</div>
	<center>
	<p>&copy; Sistemas Web <?php echo date("Y");?></p
		</center>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="./js/bootstrap.min.js"></script>
</body>
</html>

<?php
} else {
    // Redirect them to the login page
    header("Location: http://localhost:8090/doa/login.php");
}
?>
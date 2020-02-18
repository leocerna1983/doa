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
						<li ><a href="add_proyecto.php">Agregar Proyecto</a></li>
					</ul>
				</div>
			</nav>
			<h2>Lista de proyecto</h2>
			<hr/>
			<?php
			if(isset($_GET['aksi']) == 'delete'){				
				if($_GET['aksi']=='delete')
				{
					// escaping, additionally removing everything that could be (html/javascript-) code
					$id = mysqli_real_escape_string($con,(strip_tags($_GET["id"],ENT_QUOTES)));
					$cek = mysqli_query($con, "SELECT * FROM proyectos WHERE idProyecto='$id'");
					if(mysqli_num_rows($cek) == 0){
						echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
					}else{
						// $delete = mysqli_query($con, "DELETE FROM proyectos WHERE idProyecto='$id'");

						/*if($_GET['aksi']=='delete'){
							echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos eliminado correctamente.</div>';
						}else{
							echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo eliminar los datos.</div>';
						}*/
					}
				}
			}
			if(isset($_GET['aksi']) == 'asociar'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$id = mysqli_real_escape_string($con,(strip_tags($_GET["id"],ENT_QUOTES)));
				$sql = "insert into proyectousuario
					select p.idproyecto, u.idusuario, p.fechainicio, p.fechafin from usuarios u
					inner join proyectos p
					left join proyectousuario pu on p.idproyecto = pu.idproyecto and pu.idusuario = u.idusuario
					where p.idproyecto = '".$id."' and pu.idusuario is null ";
					//echo "SQL ".$sql;
				$cek = mysqli_query($con,$sql);
				//if(mysqli_num_rows($cek) == 0){
				//	echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" //data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
				//}else{
					// $delete = mysqli_query($con, "DELETE FROM proyectos WHERE idProyecto='$id'");

					/*if($cek	){
						echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Usuarios asociados correctamente.</div>';
					}else{
						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudieron asociar los usuarios al proyecto.</div>';
					}*/
				//}
			}
			?>

			<form class="form-inline" method="get" name="theForm" id="theForm">
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
					<th>Sigla</th>
					<th>Nombre</th>
					<th>Descripción</th>
                    <th>Comuna</th>
					<th>Fecha Inicio</th>
					<th>Entrega a Constructora</th>
					<th>Recepcion Final</th>
					<!--<th>Centro Costo</th>-->
					<th>Cliente</th>
					<th>Estado</th>
					
                    <th>Acciones</th>
				</tr>
				<?php
				if($filter){
					$consulta = "SELECT proyectos.idProyecto, estados.nombre as estado,clientes.nombre as cliente, o.nombres as usuarioO,c.nombres as usuarioC,
					proyectos.fecha, proyectos.ciudad, proyectos.fecha_cierre, proyectos.descripcion, proyectos.sigla,
proyectos.fechainicio, proyectos.idEstado,proyectos.nombre,proyectos.fechafin, count(up.idusuario) as UsuariosAsociados  FROM proyectos 					
					INNER JOIN estados  ON estados.idEestado = proyectos.idEstado 
					INNER JOIN clientes ON clientes.idcliente = proyectos.idcliente
					INNER JOIN usuarios as o ON o.idUsuario = proyectos.idUsuario_o
					INNER JOIN usuarios as c ON c.idUsuario = proyectos.idUsuario_c 
                    left join proyectousuario as pu on pu.idproyecto = proyectos.idproyecto
                    left join usuarios up on up.idusuario = pu.idusuario                    
					WHERE idEstado='$filter'
					group by  estados.nombre ,clientes.nombre , o.nombres ,c.nombres ,
					proyectos.fecha, proyectos.ciudad, proyectos.fecha_cierre, proyectos.descripcion, 
proyectos.fechainicio, proyectos.idEstado,proyectos.fechafin,proyectos.nombre, proyectos.sigla
					ORDER BY proyectos.idProyecto ASC ";
					$sql = mysqli_query($con, $consulta);
				}else{
				  $consulta = " SELECT proyectos.idProyecto, estados.nombre as estado,clientes.nombre as cliente, o.nombres as usuarioO,c.nombres as usuarioC,
					proyectos.fecha, proyectos.ciudad, proyectos.fecha_cierre, proyectos.descripcion, 
proyectos.fechainicio, proyectos.idEstado,proyectos.nombre,proyectos.fechafin, proyectos.sigla, count(up.idusuario) as UsuariosAsociados  FROM proyectos 					
					INNER JOIN estados  ON estados.idEestado = proyectos.idEstado 
					INNER JOIN clientes ON clientes.idcliente = proyectos.idcliente
					INNER JOIN usuarios as o ON o.idUsuario = proyectos.idUsuario_o
					INNER JOIN usuarios as c ON c.idUsuario = proyectos.idUsuario_c 
                    left join proyectousuario as pu on pu.idproyecto = proyectos.idproyecto
                    left join usuarios up on up.idusuario = pu.idusuario                    
					group by estados.nombre ,clientes.nombre , o.nombres ,c.nombres ,
					proyectos.fecha, proyectos.ciudad, proyectos.fecha_cierre, proyectos.descripcion, 
proyectos.fechainicio, proyectos.idEstado,proyectos.fechafin,proyectos.nombre, proyectos.sigla
					ORDER BY proyectos.idProyecto ASC ";
					$sql = mysqli_query($con, $consulta);
				}
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					//<td>'.$row['centroCosto'].'</td>
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td>'.$no.'</td>
							<td>'.$row['idProyecto'].'</td>
							<td>'.$row['sigla'].'</td>
							<td><a href="det_proyecto.php?id='.$row['idProyecto'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$row['nombre'].'</a></td>
                            <td>'.$row['descripcion'].'</td>
                            <td>'.$row['ciudad'].'</td>
							<td>'.explode("-",explode(" ",$row ['fechainicio'])[0])[2]."/".explode("-",explode(" ",$row ['fechainicio'])[0])[1]."/".explode("-",explode(" ",$row ['fechainicio'])[0])[0].'</td>
							<td>'.explode("-",explode(" ",$row ['fechafin'])[0])[2]."/".explode("-",explode(" ",$row ['fechafin'])[0])[1]."/".explode("-",explode(" ",$row ['fechafin'])[0])[0].'</td>
							<td>'.explode("-",explode(" ",$row ['fecha_cierre'])[0])[2]."/".explode("-",explode(" ",$row ['fecha_cierre'])[0])[1]."/".explode("-",explode(" ",$row ['fecha_cierre'])[0])[0].'</td>
							
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
								<a href="#" title="Eliminar" onclick="EliminarProyecto('.$row['idProyecto'].')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
								<a href="list_proyecto.php?aksi=asociar&id='.$row['idProyecto'].'" title="Asociar Usuarios al Proyecto" onclick="return confirm(\'Esta seguro desea asociar usuarios a este proyecto '.$row['nombre'].'?\')" class="btn btn-secondary btn-sm"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
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
	<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="list_proyecto.php" id="cambioclave" name="cambioclave">
			<input id="idUsuario" name="idUsuario" type="hidden" class="form-control" >
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Actualizar Clave</h4>
			  </div>
			  <div class="modal-body">				
				    <div class="form-group"> 
						<div class="col-sm-offset-2 col-sm-8">
						  	<div class="form-group">
					            <input type="password" id="clave" name="clave" class="form-control" placeholder="Clave" required="required">
				        	</div>
				        	<div class="form-group">
				            	<input type="password" id="claveconfirm" name="claveconfirm" class="form-control" placeholder="Clave" required="required">
				        	</div>
						</div>
					</div>
				  <input type="hidden" name="id" class="form-control" id="id">				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-primary">Guardar</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>
	<center>
	<p>&copy; Sistemas Web <?php echo date("Y");?></p
		</center>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<script type="text/javascript">

	function EliminarProyecto(idProyecto){
	    	if(confirm("Confirma que eliminara el proyecto?")==true)
	    	{	
	    		var formData = {
                IdProyecto: idProyecto
                };


				$.ajax({
					type:"POST",
					url:"VerificarProyecto.php",
					data:formData,
					success:function(data){
						var obj = JSON.parse(data); 
						if(obj[0].value>0)
						{
							if(confirm("Confirma que eliminara el proyecto? Tiene registros de horas asociados.")==true)
	    					{
	    						$.ajax({
								type:"POST",
								url:"EliminarProyecto.php",
								data:formData,
								success:function(data1){
									var obj1 = JSON.parse(data1); 
									if(obj1[0].value>0){
										alert("El Proyecto fue Eliminado.");
										document.getElementById("theForm").submit(); 
									}
									else
										alert("El Proyecto no se pudo eliminar.");
								}
							});		
	    					}
	    				}
						else
						{
							$.ajax({
								type:"POST",
								url:"EliminarProyecto.php",
								data:formData,
								success:function(data1){
									var obj1 = JSON.parse(data1); 
									if(obj1[0].value>0){
										alert("La Categoria Fue Eliminada.");
										document.getElementById("theForm").submit(); 
									}
									else
										alert("La categoria no se pudo eliminar.");
								}
							});

						}
					}
				});					

	    		//alert("Eliminar");
	    		}
	    	///list_categoria.php?aksi=delete&id='.$row['idCategoria'].'
	    }

		function CambiarClave(vIdUsuario)
		{
			if(confirm("Desea Cambiar la contraseña del usuario?")==true)
			{
				$('#ModalEdit').modal('show');	
				$('#cambioclave #idUsuario').val(vIdUsuario);				
			}			
		}

		$("#cambioclave").submit(function(e){
			if($("#cambioclave #clave").val()==$("#cambioclave #claveconfirm").val())
			{
				var formData = {
                'IdUsuario': $('#cambioclave #idUsuario').val(), 
                'clave': $('#cambioclave #clave').val()
            	};            	
				$.ajax({
					type:"POST",
					url:"CambioClave.php",
					data:formData,
					success:function(r){				
					}
				});					
				alert("Completado.");
			}
			else
			{
				alert("claves diferentes");
			}			
			header("Location: .$url.list_proyecto.php");
	    });
	</script>
</body>
</html>

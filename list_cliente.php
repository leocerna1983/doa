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
						<li class="active"><a href="list_cliente.php">Lista de Clientes</a></li>
						<li ><a href="add_cliente.php">Agregar Cliente</a></li>
					</ul>
				</div>
			</nav>
			<h2>Clientes</h2>
			<hr/>
			<?php
			if (isset($_GET['aksi']) == 'delete') {
				$id = strip_tags($_GET["id"],ENT_QUOTES);
				$cek = mysqli_query($con, "SELECT * FROM clientes WHERE idCliente=".$id);
				if(mysqli_num_rows($cek) == 0){
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
				}else{
					$sqlstr = "DELETE FROM clientes WHERE idCliente = ".$id;
					if ($con->query($sqlstr) === TRUE) {
						echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Cliente eliminado correctamente.</div>';
					} else {
						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo borrar el cliente.</div>';	
					}
				}
				$con->close;
			}
			?>
			<form class="form-inline" method="get">
				<div class="form-group">
					<select  name="filter" class="form-control" onchange="form.submit()">
							
                            <?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : "1");  ?>
							<option value="1" <?php  if($filter == "1" ){ echo 'selected'; } ?> >Habilitado</option>                            
                            <option value="0" <?php  if($filter == "0" ){ echo 'selected'; } ?> >Deshabilitado</option>                            
							<option value="7" <?php  if($filter == "7" ){ echo 'selected'; } ?> >Todos</option>
					</select>
				</div>
			</form>
			<br />
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>
                    <th>Id</th>
					<th>Nombre</th>
					<th>Rut</th>
					<th>Habilitado</th>
                    <th>Acciones</th>
				</tr>
				<?php
					if($filter!=7){
						$consulta = "SELECT idCliente, rut, nombre, habilitado FROM clientes
						WHERE habilitado='$filter'
						ORDER BY idCliente ASC ";
					}else{
						$consulta = "SELECT idCliente, rut, nombre, habilitado FROM clientes						
						ORDER BY idCliente ASC ";
					}
					$sql = mysqli_query($con, $consulta);
					if(mysqli_num_rows($sql) == 0){
						echo '<tr><td colspan="8">No existen clientes.</td></tr>';
					}else{
						while($row = mysqli_fetch_assoc($sql)){
							echo '
							<tr>
								<td>'.$row['idCliente'].'</td>
								<td>'.$row['nombre'].'</td>
								<td>'.$row['rut'].'</td>
								<td>';
								if($row['habilitado'] == 1){
									echo '<span class="label label-default">'."Si".'</span>';
								} else {
									echo '<span class="label label-info">'."No".'</span>';
								}
							echo '
								</td>
								<td>
									<a href="edit_cliente.php?aksi=save&id='.$row['idCliente'].'" title="Editar Cliente" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
									<a href="list_cliente.php?aksi=delete&id='.$row['idCliente'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['nombre'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
								</td>
							</tr>
							';
						}
					}
				?>
			</table>
			</div>
		</div>

	<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="list_cliente.php" id="cambioclave" name="cambioclave">
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
	</div><center>
	<p>&copy; Sistemas Web <?php echo date("Y");?></p
		</center>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<script type="text/javascript">
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

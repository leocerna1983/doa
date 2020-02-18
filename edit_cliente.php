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
	<link href="css/bootstrap-datepicker.css" rel="stylesheet">
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
						<li><a href="list_cliente.php">Lista de Clientes</a></li>
						<li  class="active"><a href="add_cliente.php">Agregar Cliente</a></li>
					</ul>
				</div>
			</nav>
			<h2>Editar datos de Cliente</h2>
			<hr />

			<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$id = mysqli_real_escape_string($con,(strip_tags($_GET["id"],ENT_QUOTES)));
			$consulta = "SELECT nombre, rut, habilitado FROM clientes WHERE idCliente='$id'";
			$sql = mysqli_query($con, $consulta);
			if(mysqli_num_rows($sql) == 0){
				header("Location: list_cliente.php");
			}else{
				$row = mysqli_fetch_assoc($sql);
			}
			if(isset($_POST['save'])){
				$nombre		     = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$rut   			 = mysqli_real_escape_string($con,(strip_tags($_POST["rut"],ENT_QUOTES)));//Escanpando caracteres 
				$habilitado 	 = mysqli_real_escape_string($con,(strip_tags($_POST["habilitado"],ENT_QUOTES)));//Escanpando caracteres

				$sql = "UPDATE clientes SET nombre ='$nombre', rut ='$rut', habilitado = '$habilitado' where idCliente = ".$id;  
				$update = mysqli_query($con,$sql) or die(mysqli_error());
				if($update){
					echo "<script language='javascript'>window.location='edit_cliente.php?id=".$id."&pesan=sukses'</script>"; 
					//header("Location: edit_cliente.php?id=".$id."&pesan=sukses");
				}else{
					echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, no se pudo guardar los datos.</div>';
				}
			}
			mysqli_close($con);
			if(isset($_GET['pesan']) == 'sukses'){
				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Los datos han sido guardados con éxito.</div>';
			}
			?>
			<form class="form-horizontal" action="" method="post">
				<div class="form-group">
					<label class="col-sm-3 control-label">Nombre</label>
					<div class="col-sm-4">
						<input type="text" name="nombre"  value="<?php echo $row ['nombre']; ?>" class="form-control" placeholder="Nombre" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Rut</label>
					<div class="col-sm-2">
						<input type="text" name="rut" value="<?php echo $row ['rut'];  ?>"   class="form-control" placeholder="Rut" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Habilitado</label>
					<div class="col-sm-1">
						<select name="habilitado" class="form-control">
							<?php 
							   if ($row ['habilitado'] == 0) {
									echo '<option value="0" selected>No</option>';
									echo '<option value="1" >Si</option>';
							   } else {
   									echo '<option value="0" >No</option>';
									echo '<option value="1" selected>Si</option>';
							   }
							?>
						</select> 
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="save" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="list_cliente.php" class="btn btn-sm btn-danger">Cancelar</a>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="edit_cliente.php" id="cambioclave" name="cambioclave">
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
		$('.date').datepicker({
			format: 'dd/mm/yyyy',
		})

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
			header("Location: .$url.edit_proyecto.php");
	    });


		$(document).ready(function() {
			
			
		});
		$("form").submit(function(e){
			if($("select[name=centro_costo]").val()=="")
			{
				alert("Debe seleccionar un centro de costo.");			
				e.preventDefault();
			}
       	 	if($("select[name=cliente]").val()=="")
			{
				alert("Debe seleccionar un Cliente.");			
				e.preventDefault();
			}
    	});
	</script>
</body>
</html>
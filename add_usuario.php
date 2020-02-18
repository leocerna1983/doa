<?php
	include("conexion.php");
	session_start();
	
	$consulta= "SELECT nombres, ap_paterno, ap_materno, idUsuario, idRol  FROM usuarios WHERE idUsuario=".$_SESSION['idUsuario'] ."";
	$sql = mysqli_query($con, $consulta);
	$row = mysqli_fetch_assoc($sql);
	$nombreUsuario= $row['nombres'].' '.$row['ap_paterno'].' '.$row['ap_materno'] ;
	$idUsuario = $row['idUsuario'];		
	$idRol = $row['idRol'];		


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
						<li><a href="list_usuario.php">Lista de usuario</a></li>
						<li  class="active"><a href="add_usuario.php">Agregar usuario</a></li>
					</ul>
				</div>
			</nav>
			<h2>Datos del usuario&raquo; Agregar usuario</h2>
			<hr/>

			<?php
			if(isset($_POST['add'])){
				$nombres		     = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$ap_materno	 = mysqli_real_escape_string($con,(strip_tags($_POST["ap_materno"],ENT_QUOTES)));//Escanpando caracteres 
				$ap_paterno	 = mysqli_real_escape_string($con,(strip_tags($_POST["ap_paterno"],ENT_QUOTES)));//Escanpando caracteres 
				$email			 = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));//Escanpando caracteres 
				$rut	 = mysqli_real_escape_string($con,(strip_tags($_POST["rut"],ENT_QUOTES)));//Escanpando caracteres 
				$habilitado		 = mysqli_real_escape_string($con,(strip_tags($_POST["habilitado"],ENT_QUOTES)));//Escanpando caracteres 
				//$fecha_cierre	 = mysqli_real_escape_string($con,(strip_tags($_POST["fecha_cierre"],ENT_QUOTES)));//Escanpando caracteres 
				if(isset($_POST["rol"]))
					$rol	 = mysqli_real_escape_string($con,(strip_tags($_POST["rol"],ENT_QUOTES)));//Escanpando 
				else
					$rol	 = 1;
					//caracteres 
				$sueldo	 = mysqli_real_escape_string($con,(strip_tags($_POST["sueldo"],ENT_QUOTES)));//Escanpando caracteres 

				$cargo	 = mysqli_real_escape_string($con,(strip_tags($_POST["cargo"],ENT_QUOTES)));//Escanpando caracteres 

				// $centro_costo	 = mysqli_real_escape_string($con,(strip_tags($_POST["centro_costo"],ENT_QUOTES)));//Escanpando caracteres 
				// $cliente		 = mysqli_real_escape_string($con,(strip_tags($_POST["cliente"],ENT_QUOTES)));//Escanpando caracteres 
				// $estado			 = 1;
				// //mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 
				// $Afecha_inicio = explode ( "/",$fecha_inicio  );
				// $vfecha_inicio = $Afecha_inicio[2]."-".$Afecha_inicio[1]."-".$Afecha_inicio[0];

				// $Afecha_fin = explode ( "/" , $fecha_fin);
				// $vfecha_fin = $Afecha_fin[2]."-".$Afecha_fin[1]."-".$Afecha_fin[0];
				$vHabilitado = ($habilitado=='1')?'1':'0';
				$codigo=0000;
				$usuario_o =1;
				$idUsuario_c=1;
				$date = getdate();
				$vfecha = $date["year"]."-".str_pad($date["mon"],2,"0",STR_PAD_LEFT)."-".$date["mday"]." ".str_pad($date["hours"],2,"0",STR_PAD_LEFT).":".str_pad($date["minutes"],2,"0",STR_PAD_LEFT).":".str_pad($date["seconds"],2,"0",STR_PAD_LEFT);				
				$cek = mysqli_query($con, "SELECT * FROM proyectos WHERE idproyecto='$codigo'");
					if(mysqli_num_rows($cek) == 0){
						$sql = "INSERT INTO usuarios(nombres,ap_materno,ap_paterno,e_mail,rut,password,habilitado,idrol,sueldo, idcargo)
						VALUES('$nombres','$ap_materno','$ap_paterno','$email','$rut','','$vHabilitado','$rol','$sueldo','$cargo');";
						//echo $sql;
						// $sql = "INSERT INTO proyectos (idEstado,direccion,fecha,nombre,descripcion,idCC,ciudad,fechainicio,fechafin, idCliente, fecha_cierre, idUsuario_o, idUsuario_c) 
						//       VALUES('$estado','$direccion', '".$vfecha."', '$nombre', '$descripcion', '$centro_costo', '$ciudad', '$vfecha_inicio', '$vfecha_fin', '$cliente', '$fecha_cierre','$usuario_o', '$idUsuario_c' )";					 	
						$insert = mysqli_query($con, $sql) or die(mysqli_error());
						if($insert){
							echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
						}else{
							echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
						}
					 
				}else{
					echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. código exite!</div>';
				}
			}
			?>

			<form class="form-horizontal" action="" method="post">
				<div class="form-group">
					<label class="col-sm-3 control-label">Nombre</label>
					<div class="col-sm-4">
						<input type="text" name="nombre"  class="form-control" placeholder="Nombre" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Apellido Paterno</label>
					<div class="col-sm-4">
						<input type="text" name="ap_paterno" class="form-control" placeholder="Apellido Paterno" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Apellido Materno</label>
					<div class="col-sm-4">
						<input type="text" name="ap_materno" class="form-control" placeholder="Apellido Materno" required>
					</div>
				</div>				
				<div class="form-group">
					<label class="col-sm-3 control-label">Email</label>
					<div class="col-sm-4">
						<input type="text" name="email" class="form-control" placeholder="Email" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Rut</label>
					<div class="col-sm-4">
						<input type="text" name="rut" class="form-control" placeholder="Rut" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Habilitado</label>
					<div class="col-sm-4">
						<?php
							echo '<input type="checkbox" name="habilitado" value="1" checked="checked">';	
						?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label">Rol</label>
					<div class="col-sm-3">
						<select name="rol" id="rol" class="form-control" <?php if ($idRol!=2)  echo "disabled=\"true\"" ; ?> >
							<option value="">- Selecciona estado -</option>							
								<?php				
									//$idCC =  $row ['idCC'];
									$cC= mysqli_query($con,"select idrol, nombre as rolnombre from rol where habilitado = 1");
									while ($row_cC= mysqli_fetch_array ($cC))
									{ ?>

										<option <?php if ($row_cC ['idrol']==1) echo "selected"; ?> value="<?php echo ($row_cC ['idrol'])?>" >
											<?php echo $row_cC ['rolnombre']; ?>
								   		</option>
								<?php }?>
						</select> 
					</div>		
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Cargo</label>
					<div class="col-sm-3">
						<select name="cargo" class="form-control">
							<option value="">- Selecciona cargo -</option>							
								<?php				
									$idCC =  $row ['idCC'];
									$cC= mysqli_query($con,"SELECT idcargo, nombre FROM cargo where habilitado = 1");
									while ($row_cC= mysqli_fetch_array ($cC))
									{ ?>
										<option value="<?php echo ($row_cC ['idcargo'])?>" >
											<?php echo $row_cC ['nombre']; ?>
								   		</option>
								<?php }?>
						</select> 
					</div>		
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label">Sueldo</label>
					<div class="col-sm-4">
						<input type="text" name="sueldo" class="form-control" placeholder="Sueldo" required>
					</div>
				</div>		
				
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="index.php" class="btn btn-sm btn-danger">Cancelar</a>
					</div>
				</div>
			</form>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="add_usuario.php" id="cambioclave" name="cambioclave">
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
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
	$('.date').datepicker({
		format: 'dd/mm/yyyy',
	})
	$(document).ready(function() {			
			var idag = new Date();
			$(".date").datepicker("setDate", idag )
		});
	
	$("form").submit(function(e){
		if($("select[name=centro_costo]").val()=="")
		{
			alert("Debe seleccionar un centro de costo.");					
			e.preventDefault();
			$("select[name=centro_costo]").focus();
			return;
		}
        if($("select[name=cliente]").val()=="")
		{
			alert("Debe seleccionar un Cliente.");			
			e.preventDefault();
			$("select[name=cliente]").focus();
			return;
		}
    });

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
			header("Location: .$url.add_usuario.php");
	    });

	</script>
</body>
</html>

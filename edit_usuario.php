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
						<li class="active"><a href="list_usuario.php">Lista de usuario</a></li>
						<li ><a href="add_usuario.php">Agregar usuario</a></li>
					</ul>
				</div>
			</nav>
			<h2>Datos del usuario &raquo; Editar usuario</h2>
			<hr />
			
			<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$id = mysqli_real_escape_string($con,(strip_tags($_GET["id"],ENT_QUOTES)));

			//$Fecha = new Date(moment(new Date()).format('YYYY-MM-DDT03:00:00Z'));					
			//$Mes = moment($Fecha).format('MM');
			//$Anio = moment($Fecha).format('YYYY');
			$valor = date("m-Y");
			$consulta = "SELECT usuarios.idusuario, nombres, ap_materno, ap_paterno, e_mail, rut, password, usuarios.habilitado, usuarios.idrol, IFNULL(sm.sueldo, 0)  as sueldo, rol.nombre as rolnombre, usuarios.idcargo, cargo.nombre as descargo FROM usuarios inner join rol on usuarios.idrol= rol.idrol
			inner join cargo on cargo.idcargo = usuarios.idcargo
			left join sueldomes sm on usuarios.idusuario = sm.idUsuario
				and sm.mesanio = '$valor'
			WHERE usuarios.idusuario='$id'";
			$sql = mysqli_query($con, $consulta);

///echo $consulta;
			if(mysqli_num_rows($sql) == 0){
				header("Location: index.php");
			}else{
				$row = mysqli_fetch_assoc($sql);
			}
			if(isset($_POST['save'])){
				$nombres		     = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$ap_materno	 = mysqli_real_escape_string($con,(strip_tags($_POST["ap_materno"],ENT_QUOTES)));//Escanpando caracteres 
				$ap_paterno	 = mysqli_real_escape_string($con,(strip_tags($_POST["ap_paterno"],ENT_QUOTES)));//Escanpando caracteres 
				$email			 = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));//Escanpando caracteres 
				$rut	 = mysqli_real_escape_string($con,(strip_tags($_POST["rut"],ENT_QUOTES)));//Escanpando caracteres 
				if(isset($_POST['habilitado']))
					$habilitado		 = mysqli_real_escape_string($con,(strip_tags($_POST["habilitado"],ENT_QUOTES)));//Escanpando caracteres 
				else
					$habilitado		 = "0";//Escanpando caracteres 

				//$fecha_cierre	 = mysqli_real_escape_string($con,(strip_tags($_POST["fecha_cierre"],ENT_QUOTES)));//Escanpando caracteres 
				if(isset($_POST['rol']))
					$rol	 = mysqli_real_escape_string($con,(strip_tags($_POST["rol"],ENT_QUOTES)));//Escanpando caracteres 
				else
					$rol	 ="";

				if(isset($_POST['sueldo']))
					$sueldo	 = mysqli_real_escape_string($con,(strip_tags($_POST["sueldo"],ENT_QUOTES)));//Escanpando caracteres 
				else
					$sueldo	 = 0;//Escanpando caracteres 
				$cargo	 = mysqli_real_escape_string($con,(strip_tags($_POST["cargo"],ENT_QUOTES)));//Escanpando caracteres 
				// $cliente		 = mysqli_real_escape_string($con,(strip_tags($_POST["cliente"],ENT_QUOTES)));//Escanpando caracteres 
				// $estado			 = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 

				// $Afecha_inicio = explode ( "/",$fecha_inicio  );
				// $vfecha_inicio = $Afecha_inicio[2]."-".$Afecha_inicio[1]."-".$Afecha_inicio[0];

				// $Afecha_fin = explode ( "/" , $fecha_fin);
				// $vfecha_fin = $Afecha_fin[2]."-".$Afecha_fin[1]."-".$Afecha_fin[0];

				
				$usuario_o =1;
				$idUsuario_c=1;
				$date = date('yyyy mm dd');
				$vHabilitado = ($habilitado=='1')?'1':'0';
				if($rol=="")
					{
						$sql = "UPDATE usuarios SET nombres = '$nombres', ap_materno = '$ap_materno', ap_paterno = '$ap_paterno', e_mail = '$email', rut = '$rut', habilitado = '$vHabilitado', idcargo = '$cargo' WHERE idUsuario = '$id';";
					}	
					else
					{
				$sql = "UPDATE usuarios SET nombres = '$nombres', ap_materno = '$ap_materno', ap_paterno = '$ap_paterno', e_mail = '$email', rut = '$rut', habilitado = '$vHabilitado', idrol = '$rol',
					idcargo = '$cargo' WHERE idUsuario = '$id';";
					}
				// UPDATE proyectos SET idEstado ='$estado', direccion ='$direccion', nombre ='$nombre' ,descripcion ='$descripcion' ,idCC ='$centro_costo',ciudad ='$ciudad' ,fechainicio ='$vfecha_inicio' ,fechafin ='$vfecha_fin' , idCliente ='$cliente',  idUsuario_o ='$usuario_o' , idUsuario_c ='$idUsuario_c'  
				// WHERE idProyecto='$id'	
				//echo $sql;			
				$update = mysqli_query($con,$sql) or die(mysqli_error());
				if($update){
					echo "<script language='javascript'>window.location='edit_usuario.php?id=".$id."&pesan=sukses'</script>"; 
				 //header("Location: edit_usuario.php?id=".$id."&pesan=sukses");
				// header("Location: list_proyecto.php");
				}else{
					echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, no se pudo guardar los datos.</div>';
				}
			}
			
			if(isset($_GET['pesan']) == 'sukses'){				
				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Los datos han sido guardados con éxito.</div>';
			}
			?>
			<form class="form-horizontal" action="" method="post">
				<div class="form-group">
					<label class="col-sm-3 control-label">Nombre</label>
					<div class="col-sm-4">
						<input type="text" name="nombre"  value="<?php echo $row ['nombres']; ?>" class="form-control" placeholder="Nombre" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Apellido Paterno</label>
					<div class="col-sm-4">
						<input type="text" name="ap_paterno"  value="<?php echo $row ['ap_paterno'];  ?>"  class="form-control" placeholder="Apellido Paterno" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Apellido Materno</label>
					<div class="col-sm-4">
						<input type="text" name="ap_materno" value="<?php echo $row ['ap_materno'];  ?>"   class="form-control" placeholder="Apellido Materno" required>
					</div>
				</div>				
				<div class="form-group">
					<label class="col-sm-3 control-label">Email</label>
					<div class="col-sm-4">
						<input type="text" name="email" value="<?php echo $row ['e_mail'];  ?>" class="form-control" placeholder="Email" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Rut</label>
					<div class="col-sm-4">
						<input type="text" name="rut" value="<?php echo $row ['rut'];  ?>" class="form-control" placeholder="Rut" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Habilitado</label>
					<div class="col-sm-4">
						<?php
							if($row['habilitado']==1)
								echo '<input type="checkbox" name="habilitado" value="1" checked="checked">';
							else
								echo '<input type="checkbox" value="1" name="habilitado">';
						?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label">Rol</label>
					<div class="col-sm-3">
						<select name="rol" class="form-control" <?php if ($idRol!=2)  echo "disabled=\"true\"" ; ?>>
							<option value="">- Selecciona estado -</option>
							<option value="<?php echo ($row ['idrol'])?>" <?php {echo "selected";} ?> >
								     <?php echo $row ['rolnombre']; ?>
								</option>
								<?php				
									$idCC =  $row ['idrol'];
									$cC= mysqli_query($con,"select idrol, nombre as rolnombre from rol where habilitado = 1 and idrol != $idCC");
									while ($row_cC= mysqli_fetch_array ($cC))
									{ ?>
										<option value="<?php echo ($row_cC ['idrol'])?>" >
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
							<option value="<?php echo ($row ['idcargo'])?>" <?php {echo "selected";} ?> >
								     <?php echo $row ['descargo']; ?>
								</option>	
								<?php				
									$idCC =  $row ['idcargo'];
									$cC= mysqli_query($con,"SELECT idcargo, nombre FROM cargo where habilitado = 1 and idcargo != $idCC");
									while ($row_cC= mysqli_fetch_array ($cC))
									{ ?>
										<option value="<?php echo ($row_cC ['idcargo'])?>" >
											<?php echo $row_cC ['nombre']; ?>
								   		</option>
								<?php }?>
						</select> 
					</div>		
				</div>

				<?php if ($idRol==2) {
				echo "<div class=\"form-group\">
					<label class=\"col-sm-3 control-label\">Sueldo</label>
					<div class=\"col-sm-4\">
						<input disabled=\"true\" type=\"text\" name=\"sueldo\" value=\"";?><?php echo $row ['sueldo']."";  ?>
						<?php echo "class=\"form-control\" placeholder=\"Sueldo\" required>
					</div>
				</div>";	
				}
				?>				
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="save" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="index.php" class="btn btn-sm btn-danger">Cancelar</a>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="edit_usuario.php" id="cambioclave" name="cambioclave">
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
			header("Location: .$url.list_usuario.php");
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
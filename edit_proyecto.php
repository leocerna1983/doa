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

	<!--<link rel="stylesheet" href="css/colorpicker.css" type="text/css" />-->
    <link rel="stylesheet" media="screen" type="text/css" href="css/layout.css" />
	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-datepicker.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">

	<!--<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/colorpicker.js"></script>
    <script type="text/javascript" src="js/eye.js"></script>
    <script type="text/javascript" src="js/utils.js"></script>
    <script type="text/javascript" src="js/layout.js?ver=1.0.2"></script>-->

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
						<li ><a href="list_proyecto.php">Lista de proyecto</a></li>
						<li class="active"><a href="add_proyecto.php">Actualizar Datos</a></li>
					</ul>
				</div>
			</nav>
			<h2>Datos del proyectos &raquo; Editar datos</h2>
			<hr />
			
			<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$id = mysqli_real_escape_string($con,(strip_tags($_GET["id"],ENT_QUOTES)));

			$consulta = "SELECT estados.nombre as estado,clientes.nombre as cliente, o.nombres as usuarioO,c.nombres as usuarioC,
			proyectos.* FROM proyectos 			
			INNER JOIN estados  ON estados.idEestado = proyectos.idEstado 
			INNER JOIN clientes ON clientes.idcliente = proyectos.idcliente
			INNER JOIN usuarios as o ON o.idUsuario = proyectos.idUsuario_o
			INNER JOIN usuarios as c ON c.idUsuario = proyectos.idUsuario_c 
			WHERE idProyecto='$id'";

			$sql = mysqli_query($con, $consulta);


			if(mysqli_num_rows($sql) == 0){
				//header("Location: index.php");
				echo "<script language='javascript'>window.location='index.php'</script>";
			}else{
				$row = mysqli_fetch_assoc($sql);
			}
			if(isset($_POST['save'])){
				$nombre		     = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$descripcion	 = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));//Escanpando caracteres 
				$direccion	 = mysqli_real_escape_string($con,(strip_tags($_POST["direccion"],ENT_QUOTES)));//Escanpando caracteres 
				$ciudad			 = mysqli_real_escape_string($con,(strip_tags($_POST["ciudad"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_inicio	 = mysqli_real_escape_string($con,(strip_tags($_POST["fecha_inicio"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_fin		 = mysqli_real_escape_string($con,(strip_tags($_POST["fecha_fin"],ENT_QUOTES)));//Escanpando caracteres 
				//$fecha_cierre	 = mysqli_real_escape_string($con,(strip_tags($_POST["fecha_cierre"],ENT_QUOTES)));//Escanpando caracteres 
				//$centro_costo	 = mysqli_real_escape_string($con,(strip_tags($_POST["centro_costo"],ENT_QUOTES)));//Escanpando caracteres 
				$cliente		 = mysqli_real_escape_string($con,(strip_tags($_POST["cliente"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_recepcion_final		 = mysqli_real_escape_string($con,(strip_tags($_POST["fecha_recepcion_final"],ENT_QUOTES)));//Escanpando caracteres 
				$sigla		 = mysqli_real_escape_string($con,(strip_tags($_POST["sigla"],ENT_QUOTES)));//Escanpando caracteres 

				$estado			 = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 
				$vcolor			 = mysqli_real_escape_string($con,(strip_tags($_POST["dpColor"],ENT_QUOTES)));//Escanpando caracteres 


				$Afecha_inicio = explode ( "/",$fecha_inicio  );
				$vfecha_inicio = $Afecha_inicio[2]."-".$Afecha_inicio[1]."-".$Afecha_inicio[0];

				$Afecha_fin = explode ( "/" , $fecha_fin);
				$vfecha_fin = $Afecha_fin[2]."-".$Afecha_fin[1]."-".$Afecha_fin[0];

				$Afecha_recepcion_final = explode ( "/" , $fecha_recepcion_final);
				$vfecha_recepcion_final = $Afecha_recepcion_final[2]."-".$Afecha_recepcion_final[1]."-".$Afecha_recepcion_final[0];
				
				$usuario_o =1;
				$idUsuario_c=1;
				$date = date('yyyy mm dd');
				$sql = "UPDATE proyectos SET idEstado ='$estado', direccion ='$direccion', nombre ='$nombre' ,descripcion ='$descripcion' ,idCC ='$centro_costo',ciudad ='$ciudad' ,fechainicio ='$vfecha_inicio' ,fechafin ='$vfecha_fin' , idCliente ='$cliente',  idUsuario_o ='$usuario_o' , idUsuario_c ='$idUsuario_c' , sigla = '$sigla' , fecha_recepcion = '$vfecha_recepcion_final', color = '$vcolor'
				WHERE idProyecto='$id'";
				$update = mysqli_query($con,$sql) or die(mysqli_error());
				//echo $sql;
				if($update){
				 //header("Location: edit_proyecto.php?id=".$id."&pesan=sukses");
				 echo "<script language='javascript'>window.location='edit_proyecto.php?id=".$id."&pesan=sukses'</script>"; 
				 //header("Location: list_proyecto.php");
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
						<input type="text" name="nombre"  value="<?php echo $row ['nombre']; ?>" class="form-control" placeholder="Nombre" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Descripción</label>
					<div class="col-sm-4">
						<input type="text" name="descripcion" value="<?php echo $row ['descripcion'];  ?>"   class="form-control" placeholder="Descripción" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Sigla</label>
					<div class="col-sm-4">
						<input type="text" name="sigla" value="<?php echo $row ['sigla'];  ?>" maxlength="20" class="form-control" placeholder="Sigla" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Dirección</label>
					<div class="col-sm-4">
						<input type="text" name="direccion"  value="<?php echo $row ['direccion'];  ?>"  class="form-control" placeholder="Dirección" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Comuna</label>
					<div class="col-sm-4">
						<input type="text" name="ciudad" value="<?php echo $row ['ciudad'];  ?>" class="form-control" placeholder="Comuna" required>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Fecha Inicio</label>
					<div class="col-sm-4">
						<div class='input-group date' >
							<input type="text" name="fecha_inicio"  value="<?php echo explode("-",explode(" ",$row ['fechainicio'])[0])[2]."/".explode("-",explode(" ",$row ['fechainicio'])[0])[1]."/".explode("-",explode(" ",$row ['fechainicio'])[0])[0]; ?>" class="input-group date form-control" date="" data-date-format="dd/mm/yyyy" placeholder="00/00/0000" required>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar">
									</span>
								</span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label">Entrega a Constructora</label>
					<div class="col-sm-4">
						<div class='input-group date' >
						<input type="text" name="fecha_fin"  value="<?php echo explode("-",explode(" ",$row ['fechafin'])[0])[2]."/".explode("-",explode(" ",$row ['fechafin'])[0])[1]."/".explode("-",explode(" ",$row ['fechafin'])[0])[0];  ?>" class="input-group date form-control" date=""  data-date-format="dd/mm/yyyy" placeholder="00/00/0000" required>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar">
									</span>
								</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Recepcion Final</label>
					<div class="col-sm-4">
						<div class='input-group date' >
							<input type="text" name="fecha_recepcion_final"  value="<?php echo explode("-",explode(" ",$row ['fecha_recepcion'])[0])[2]."/".explode("-",explode(" ",$row ['fecha_recepcion'])[0])[1]."/".explode("-",explode(" ",$row ['fecha_recepcion'])[0])[0]; ?>" class="input-group date form-control" date="" data-date-format="dd/mm/yyyy" placeholder="00/00/0000" required>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar">
									</span>
								</span>
						</div>
					</div>
				</div>
				<!-- <div class="form-group">
					<label class="col-sm-3 control-label">Fecha Cierre</label>
					<div class="col-sm-3">
						<div class='input-group date' >
						<input type="text" name="fecha_cierre" 
						value="<?php echo explode("-",explode(" ",$row ['fecha_cierre'])[0])[2]."/".explode("-",explode(" ",$row ['fecha_cierre'])[0])[1]."/".explode("-",explode(" ",$row ['fecha_cierre'])[0])[0];  ?>" class="input-group date form-control" date="" data-date-format="dd/mm/yyyy" placeholder="00-00-0000" required>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar">
									</span>
								</span>
						</div>
					</div>
				</div> -->
				<div class="form-group">
					<label class="col-sm-3 control-label">Color Proyecto</label>
					<div class="col-sm-4 container-fluid">
					    <input type="color" class="col-sm-5" style="margin-right: 10px" id="dpColor" name="dpColor" value="<?php echo $row ['color'];  ?>">	
						<label id="lblColor" class="col-sm-5" style="color:white;background-color:<?php echo $row ['color'];  ?>;">Color</label>							
					</div>
				</div>
				<!--<div class="form-group">
					<label class="col-sm-3 control-label">Centro de Costo</label>
					<div class="col-sm-4">
						<select name="centro_costo" class="form-control">
							<option value="">- Selecciona estado -</option>
							<option value="<?php echo ($row ['idCC'])?>" <?php {echo "selected";} ?> >
								     <?php echo $row ['centroCosto']; ?>
								</option>
								<?php				
									$idCC =  $row ['idCC'];
									$cC= mysqli_query($con,"SELECT * FROM ccosto WHERE habilitado = 1 and idCC != $idCC");
									while ($row_cC= mysqli_fetch_array ($cC))
									{ ?>
										<option value="<?php echo ($row_cC ['idCc'])?>" >
											<?php echo $row_cC ['nombre']; ?>
								   		</option>
								<?php }?>


						</select> 
					</div>		
				</div>-->
				<div class="form-group">
					<label class="col-sm-3 control-label">Cliente</label>
						<div class="col-sm-4">
							 <select name="cliente" class="form-control">
								<option value="<?php echo ($row ['idCliente'])?>" <?php {echo "selected";} ?> >
								     <?php echo $row ['cliente']; ?>
								</option>
								<?php				
									$idCliente =  $row ['idCliente'];
									$cliente = mysqli_query($con,"SELECT * FROM clientes WHERE habilitado = 1 and  idCliente != $idCliente");
									while ($row_cliente = mysqli_fetch_array ($cliente))
									{ ?>
										<option value="<?php echo ($row_cliente ['idCliente'])?>" >
											<?php echo $row_cliente ['nombre']; ?>
								   		</option>
									<?php }?>
							</select>
						</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Estado</label>
					<div class="col-sm-4">
						<select name="estado" class="form-control">
								<option value="<?php echo ($row ['idEstado'])?>" <?php {echo "selected";} ?> >
								     <?php echo $row ['estado']; ?>
								</option>
								<?php				
									$idEstado =  $row ['idEstado'];
									$estado = mysqli_query($con,"SELECT * FROM estados WHERE idEestado != $idEstado");
									while ($row_estado = mysqli_fetch_array ($estado))
									{ ?>
										<option value="<?php echo ($row_estado ['idEestado'])?>" >
											<?php echo $row_estado ['nombre']; ?>
								   		</option>
								<?php }?>
						</select>
					</div>
				</div>
			
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
			<form class="form-horizontal" method="POST" action="edit_proyecto.php" id="cambioclave" name="cambioclave">
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

		/*$('#colorSelector').ColorPicker({
			color: '#0000ff',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#colorSelector div').css('backgroundColor', '#' + hex);
			}
		});*/

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
			

			  muestrario = document.querySelector("#dpColor");
			  //muestrario.value = colorPredeterminado;
			  muestrario.addEventListener("input", actualizarPrimero, false);
			  muestrario.addEventListener("change", actualizarTodo, false);
			  muestrario.select();
			
		});

		function actualizarPrimero(event) {
			//alert("actualizarPrimero");

		}

		function actualizarTodo()
		{
			var p = document.querySelector("#lblColor");		  	 
			if (p) {
			    p.style.background = event.target.value;
			}
		}

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
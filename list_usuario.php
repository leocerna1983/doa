<?php
	require_once("conexion.php");
	session_start();
	$consulta= "SELECT nombres, ap_paterno, ap_materno, idUsuario, idRol  FROM usuarios WHERE idUsuario=".$_SESSION['idUsuario'] ."";
	$sql = mysqli_query($con, $consulta);
	$row = mysqli_fetch_assoc($sql);
	$nombreUsuario= $row['nombres'].' '.$row['ap_paterno'].' '.$row['ap_materno'] ;
	$idUsuario = $row['idUsuario'];		
	$idRol = $row['idRol'];		
	$Sueldo = 0; //$row['sueldo'];	
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
	</style>1

<style>
    body {
        padding-top: 60px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. 
		
        */
    }
	#calendar {
		max-width: 800px;
		margin-bottom: 100px;
	}	

	.col-centered{
		float: none;
		margin: 0 auto;
	}
	.my-custom-scrollbar {
		position: relative;
		height: 400px;
		overflow: auto;
	}
	.table-wrapper-scroll-y {
		display: block;
	}

	ul.ui-autocomplete {
    	z-index: 1100;
	}

	#idTareasImputadas th, td {
		white-space: nowrap;
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
						<li class="active"><a href="list_usuario.php">Lista de Usuarios</a></li>
						<li ><a href="add_usuario.php">Agregar Usuario</a></li>
					</ul>
				</div>
			</nav>
			<h2>Lista de usuario</h2>
			<hr/>
			<?php
			if(isset($_GET['aksi']) == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$id = mysqli_real_escape_string($con,(strip_tags($_GET["id"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM usuarios WHERE idUsuario='$id'");
				if(mysqli_num_rows($cek) == 0){
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
				}else{					
					$UsuarioControlHoras = mysqli_query($con, "SELECT * from controlhoras where idusuario =".$id);		
					//echo mysqli_num_rows($UsuarioControlHoras);
					if(mysqli_num_rows($UsuarioControlHoras)==0)
					{
						
						$delete = mysqli_query($con, "DELETE FROM usuarios WHERE idUsuario=".$id);
						if($delete){
							echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos eliminado correctamente.</div>';
						}else{
							echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo eliminar los datos.</div>';
						}						
					}
					else
					{
					    	echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>El usuario tiene control de horas asociado.</div>';					
					}
				}
			}
			?>

			<!-- <form class="form-inline" method="get">
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
			</form> -->
			<br />
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>
                    <th>No</th>
					<th>Código</th>
					<th>Nombres</th>
					<!--<th>Apellido Materno</th>-->
                    <th>Apellido Paterno</th>
					<!--<th>Email</th>-->
					<th>Rut</th>
					<th>Habilitado</th>
					

					<th>Rol</th> 
					<?php
					if($_SESSION['idrol']==0)
					echo '<th>Sueldo</th>'
					?>
                    <th>Acciones</th>
				</tr>
				<?php
				$filter = NULL;
				if($filter){
					$consulta = "SELECT idusuario, nombres, ap_materno, ap_paterno, e_mail, rut, password, usuarios.habilitado, usuarios.idrol, sueldo, rol.nombre as rolnombre FROM usuarios
						inner join rol on usuarios.idrol= rol.idrol
					WHERE usuarios.idrol='$filter'
					ORDER BY nombres ASC ";
					$sql = mysqli_query($con, $consulta);
				}else{
				  $consulta = " SELECT idusuario, nombres, ap_materno, ap_paterno, e_mail, rut, password, usuarios.habilitado, usuarios.idrol, sueldo, rol.nombre as rolnombre FROM usuarios
				  inner join rol on usuarios.idrol= rol.idrol order by nombres asc";
					$sql = mysqli_query($con, $consulta);
				}
				//<td>'.$row['ap_materno'].'</td>
				//<td>'.$row['e_mail'].'</td>
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td>'.$no.'</td>
							<td>'.$row['idusuario'].'</td>
							<td><a href="det_usuario.php?id='.$row['idusuario'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$row['nombres'].'</a></td>
                            
                            <td>'.$row['ap_paterno'].'</td>							
							<td>'.$row['rut'].'</td>							
							<td>';
							if($row['habilitado']==1)
								echo '<input type="checkbox" name="habilitado" checked="checked" disabled>';
							else
								echo '<input type="checkbox" name="habilitado" disabled>';
							
							echo '</td>
							<td>'.$row['rolnombre'].'</td>';							
							if($_SESSION['idrol']==0)
								echo '<td>'.$row['sueldo'].'</td>';
								
							echo '<td>
								<a href="edit_usuario.php?id='.$row['idusuario'].'" title="Editar datos" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<a onclick="CambiarClave('.$row['idusuario'].')" title="Cambiar Clave" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></a>

								<a href="#" title="Editar Sueldo"'; 
								if($idRol==2)
									echo 'onclick="EditarSueldo('.$row['idusuario'].')" '; 							
								echo 'class="btn btn-primary btn-sm"><span class="glyphicon glyphicon glyphicon-usd" aria-hidden="true"></span></a>

								<a href="list_usuario.php?aksi=delete&id='.$row['idusuario'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['nombres'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>


								
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>


			<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">	
		  <div class="modal-dialog modal-md" role="document">			  
			<div class="modal-content">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				  <li class="nav-item">
				    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Editar Sueldo</a>
				  </li>				  
			</ul>
			<div class="tab-content">

			<div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
			<form class="form-horizontal" method="POST" action="EditarSueldo.php" id="modaldetalle" name="modaldetalle">
			 <!--<input id="idProyecto" name="idProyecto" type="hidden" value="<?php echo  $filter ?>">-->
			 <input id="idUsuarioSueldo" name="idUsuarioSueldo" type="hidden" value="">
			 <input id="idsueldomes" name="idsueldomes" type="hidden" value="">
			  <!--<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar</h4>
			  </div>-->
			  <div class="modal-body">


						<div class="form-group">
						<label for="categoria" class="col-sm-2 control-label">Sueldo</label>
						<div class="col-sm-3">
							<input type="text" name="sueldo" id="sueldo" value="<?php echo $row ['sueldo'];  ?>" class="form-control" placeholder="Sueldo" required>
						</div>
						<label class="col-sm-3 control-label">Mes Año</label>
						<div class="col-sm-3">
							<!--<div class="col-md-2 col-xs-3">-->
								<!--<div class="form-group">-->
									<div class='input-group date'>
										<input type="text" name="filter" id="filter"  autocomplete="off" value="<?php if($filter==""){ 
										$mes = date('m');
										$ano = date('Y');
										echo $mes."-".$ano;
											}else echo $filter; ?>"  class="input-group date form-control" data="02-2012" date-format="mm-yyyy" placeholder="00-0000" required>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar">
												</span>
											</span>
									</div>
								<!--</div>-->
							<!--</div>-->
						</div>									 							
			  		</div>
			  		<div class="table-responsive my-custom-scrollbar" id="idTareasImputadas" name="idTareasImputadas">		</div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-primary">Guardar</button>
			  </div>
			</form>
			</div>
			<!--<div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Tareas Imputadas</h4>
			  	</div>

			  	<div class="modal-body">
				  <div class="form-group">

				<div class="table-responsive my-custom-scrollbar" id="idTareasImputadas" name="idTareasImputadas">			
			
			</div>
			</div>
			</div>
		</div>-->
			</div>
			<!-- fin de div principal tab -->
			</div>


			</div>
		  </div>


		</div>

		<!-- Modal -->
		<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="list_usuario.php" id="cambioclave" name="cambioclave">
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
	<center>
	<p>&copy; Sistemas Web <?php echo date("Y");?></p
		</center>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

	<script src="./js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script type="text/javascript">

	$(document).ready(function() {
		$("#ModalAdd #myTab li:first-child a").tab("show");
		$('.date').datepicker({
			format: "mm-yyyy",
			startView: "months", 
			minViewMode: "months",
			autoclose: true
		})

	});

		function EditarSueldo(vIdUsuario)
		{
			//alert(vIdUsuario);
			$("#modaldetalle #sueldo").val(0);
			$("#ModalAdd #myTab li:first-child a").tab("show");	
			$('#ModalAdd').modal('show');					
			$("#modaldetalle #idUsuarioSueldo").val(vIdUsuario);
			recargarListaImputadas(vIdUsuario);
		}

		function CambiarClave(vIdUsuario)
		{
			if(confirm("Desea Cambiar la contraseña del usuario?")==true)
			{
				$('#ModalEdit').modal('show');	
				$('#cambioclave #idUsuario').val(vIdUsuario);				
			}			
		}


		function SetEdicionSueldo(vidsueldomes,vidusuario,vmesanio,vsueldo)
		{				
			$("#ModalAdd #myTab li:first-child a").tab("show")
			$("#modaldetalle #idUsuarioSueldo").val(vidusuario);
			$("#modaldetalle #idsueldomes").val(vidsueldomes);
			$("#modaldetalle #sueldo").val(vsueldo);
		    $("#modaldetalle #filter").val(vmesanio);
		}

		function recargarListaImputadas(idusuario){
		var formData = {
                'idusuario': idusuario        
            	};
			$.ajax({
				type:"POST",
				url:"EditarSueldoModal.php",
				data:formData,
				success:function(r){								
					$('#idTareasImputadas').html(r);							
				}
			});
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
	</script>
</body>
</html>

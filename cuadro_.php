<?php
	require_once("conexion.php");
	$consulta= "SELECT *  FROM usuarios WHERE idUsuario=1";
	$sql = mysqli_query($con, $consulta);
	$row = mysqli_fetch_assoc($sql);
	$nombreUsuario= $row['nombres'].' '.$row['ap_paterno'].' '.$row['ap_materno'] ;
	$idUsuario = $row['idUsuario'];			 
?>

<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DOA. Control de Horas</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">
	<!-- FullCalendar -->
	<link href='css/fullcalendar.css' rel='stylesheet' />


    <!-- Custom CSS -->
    <style>
    body {
        padding-top: 60px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }
	#calendar {
		max-width: 800px;
	}
	.col-centered{
		float: none;
		margin: 0 auto;
	}
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
<?php 
	$filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL); 
?>

<div class="container">
	<nav class="navbar navbar-default navbar-fixed-top">
		<?php include('nav_home.php');?>
	</nav>
	<div class="content">
			<h2 style="margin-top:57px;">Agendar Horas al proyecto</h2>
		<hr/>
		<form class="form-inline" method="get">
				<div class="row">
					<div class="col-xs-6 col-md-3"><b>Usuario:</b> <?php echo $nombreUsuario  ?></div>
					<div class="col-xs-6 col-md-3">
						<div class="form-group">
							<select  id="filter" name="filter" class="form-control" onchange="form.submit()">
									<option value="0">Selecciona el Proyecto</option>
									
										<?php				
											$proyecto= mysqli_query($con,"SELECT pu.* , p.nombre  FROM proyectousuario pu
											INNER JOIN proyectos as p  ON p.idProyecto = pu.idProyecto 
											WHERE  pu.idUsuario = $idUsuario");
											while ($row_proyecto = mysqli_fetch_array ($proyecto))
											{ ?>
												<option value="<?php echo ($row_proyecto ['idProyecto'])?>" 
												<?php  if($filter == $row_proyecto ['idProyecto'] ){ echo 'selected'; } ?>>
													<?php echo $row_proyecto ['nombre']; ?>
												</option>
										<?php }?>
							</select>
						</div>
					</div>
				</div>
			</form>
			<br/>

		<div class="row">
            <div class="col-lg-12 text-center">
                <h1></h1>
                <p class="lead"></p>
                <div id="calendar" class="col-centered">
                </div>
            </div>
			
        </div>
        <!-- /.row -->

		<!-- Modal -->
		<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="addEvent.php">
			 <input id="idProyecto" name="idProyecto" type="hidden" value="<?php echo  $filter ?>">
			 <input id="idUsuario" name="idUsuario" type="hidden" value="<?php echo  $idUsuario ?>">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar Horas</h4>
			  </div>
			  <div class="modal-body">
				  <div class="form-group">
					<label for="categoria" class="col-sm-2 control-label">Categoria</label>
					<div class="col-sm-10">
						<select  name="categoria" id="categoria"  class="form-control" onchange="" required>
								<option value="" selected>Selecciona la categoria</option>
									<?php				
										$categoria= mysqli_query($con,"SELECT * FROM categorias WHERE habilitado = 1");
										while ($row_categoria = mysqli_fetch_array ($categoria))
											{ ?>
											<option value="<?php echo ($row_categoria ['idCategoria'])?>">
												<?php echo $row_categoria ['nombre']; ?>
											</option>
								<?php }?>

						</select>
					</div>
				  </div>
				  <div class="form-group">
					<label for="tarea" class="col-sm-2 control-label">Tarea</label>
					<div class="col-sm-10">
						<div id="listaTarea"></div>
					</div>
				  </div>
				  <div class="form-group">
					<label for="horas" class="col-sm-2 control-label">Horas</label>
					<div class="col-sm-3">
						<input type="text" name="hora" class="form-control" id="hora" placeholder="Horas" required>
					</div>
				  </div>
				  <div class="form-group">
					<label for="fecha_asignacion" class="col-sm-2 control-label">Fecha Asignacion</label>
					<div class="col-sm-3">
					  <input type="text" name="fecha_asignacion" class="form-control" id="fecha_asignacion" readonly>
					</div>
				  </div>
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-primary">Imputar</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="editEventTitle.php">
			<input id="idProyecto" name="idProyecto" type="hidden" value="<?php echo  $filter ?>">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Editar Hora</h4>
			  </div>
			  <div class="modal-body">
				
				 <!-- <div class="form-group">
					<label for="title" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="title" placeholder="Title">
					</div>
				  </div>
				  <div class="form-group">
					<label for="color" class="col-sm-2 control-label">Color</label>
					<div class="col-sm-10">
					  <select name="color" class="form-control" id="color">
						  <option value="">Choose</option>
						  <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
						  <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
						  <option style="color:#008000;" value="#008000">&#9724; Green</option>						  
						  <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
						  <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
						  <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
						  <option style="color:#000;" value="#000">&#9724; Black</option>
						  
						</select>
					</div>
				  </div> -->
				    <div class="form-group"> 
						<div class="col-sm-offset-2 col-sm-10">
						  <div class="checkbox">
							<label class="text-danger"><input type="checkbox"  name="delete"> Borrar Hora</label>
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
</div>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	
	<!-- FullCalendar -->
	<script src='js/moment.min.js'></script>
	<script src='js/fullcalendar.min.js'></script>
	
	<script>

	$(document).ready(function() {

		$('#calendar').fullCalendar({
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
			dayNamesShort: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
			header: {
				left: 'prev,next today',
				center: 'title',
				//right: 'month,basicWeek,basicDay'
				right: ''
			},
			defaultDate:  new Date(), 
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			selectable: true,
			selectHelper: true,
			select: function(fecha_asignacion) {
				if ($("#filter").val()>0) {
					$('#ModalEdit #id').val(event.id);
					$('#ModalAdd #fecha_asignacion').val(moment(fecha_asignacion).format('YYYY-MM-DD'));
					$('#ModalAdd').modal('show');
				}else{
					alert('Debe seleccionar un proyecto');
				}	
			},
			eventRender: function(event, element) {
				element.bind('dblclick', function() {
					$('#ModalEdit #id').val(event.id);
					$('#ModalEdit #hora').val(event.hora);
					if (event.color !='#FF0000'){
					   $('#ModalEdit').modal('show');
					}	
				});
			},
			eventDrop: function(event, delta, revertFunc) { // si changement de position

				edit(event);

			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

				edit(event);

			},
			events: [
			<?php
				if($filter){
				
						$consulta = "SELECT idControlh, sum(horas) horas, fecha_asignacion FROM controlhoras
								 WHERE idProyecto='$filter' && idUsuario='$idUsuario'
								 GROUP BY fecha_asignacion ";
						$sql = mysqli_query($con, $consulta);
						
						if (mysqli_num_rows($sql) == 0){
							//echo 'No hay datos';
						}else{
							while($event = mysqli_fetch_assoc($sql)){
								$fecha_asignacion = $event['fecha_asignacion'];
								$fecha_asignacion = $event['fecha_asignacion'];

								if($fecha_asignacion[1] == '00:00:00'){
									$fecha_asignacion = $fecha_asignacion[0];
								}else{
									$fecha_asignacion = $event['fecha_asignacion'];
								}		
								?>
									{  // horas
										id: '<?php echo $event['idControlh']; ?>',
										title: '<?php echo 'H: '.$event['horas'].' Hrs';  ?>',
										start: '<?php echo $fecha_asignacion; ?>',
										end: '<?php echo $fecha_asignacion; ?>',
										color: '<?php echo '#0071c5' ?>',
									},
					    <?php 
							}
						}
						 $consulta_total = "SELECT idControlh, sum(horas) horas, fecha_asignacion FROM controlhoras
								  WHERE  idUsuario='$idUsuario'
								  GROUP BY fecha_asignacion ";
						 $sql_total = mysqli_query($con, $consulta_total);
						 
						 if (mysqli_num_rows($sql_total) == 0){
							 //echo 'No hay datos';
						 }else{
							 while($totales = mysqli_fetch_assoc($sql_total)){
								 $fecha_asignacion = $totales['fecha_asignacion'];
								 $fecha_asignacion = $totales['fecha_asignacion'];
 
								 if($fecha_asignacion[1] == '00:00:00'){
									 $fecha_asignacion = $fecha_asignacion[0];
								 }else{
									 $fecha_asignacion = $totales['fecha_asignacion'];
								 }		
								 ?>
									 {  
										 id: '<?php echo $totales['idControlh']; ?>',
										 title: '<?php echo 'HT: '.$totales['horas'].' Hrs';  ?>',
										 start: '<?php echo $fecha_asignacion; ?>',
										 end: '<?php echo $fecha_asignacion; ?>',
										 color: '<?php echo '#FF0000' ?>',
									 },
						 <?php 
								 }
						 }

					};	
					?>
			]
		});
		
		function edit(event){
			fecha_asignacion = event.start.format('YYYY-MM-DD');
		
			id =  event.id;
			
			Event = [];
			Event[0] = id;
			Event[1] = start;
			Event[2] = end;
			
			$.ajax({
			 url: 'editEventDate.php',
			 type: "POST",
			 data: {Event:Event},
			 success: function(rep) {
					if(rep == 'OK'){
						alert('Saved');
					}else{
						alert('Could not be saved. try again.'); 
					}
				}
			});
		}
		
	});

   /// metodos para combox independientes
	$(document).ready(function(){
		$('#categoria').val();
		recargarLista();

		$('#categoria').change(function(){
			recargarLista();
		});
	})
</script>
<script type="text/javascript">
	function recargarLista(){
		$.ajax({
			type:"POST",
			url:"select_combox.php",
			data:"categoria=" + $('#categoria').val(),
			success:function(r){
				$('#listaTarea').html(r);
			}
		});
	}

</script>


</body>

</html>

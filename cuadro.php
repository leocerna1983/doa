<?php
	require_once("conexion.php");
	session_start();
	$consulta= "SELECT *  FROM usuarios WHERE idUsuario=".$_SESSION['idUsuario']."";
	$sql = mysqli_query($con, $consulta);
	$row = mysqli_fetch_assoc($sql);
	$nombreUsuario= $row['nombres'].' '.$row['ap_paterno'].' '.$row['ap_materno'] ;
	$idUsuario = $row['idUsuario'];			 
?>
<?php
	if ( isset( $_SESSION['idUsuario'] ) ) {
	?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DOA. Control de Horas</title>
	<link href = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css"
         rel = "stylesheet">

    <link rel="stylesheet" href="css/colorpicker.css" type="text/css" />
    <link rel="stylesheet" media="screen" type="text/css" href="css/layout.css" />
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">
	<!-- FullCalendar -->
	<link href='css/fullcalendar.css' rel='stylesheet' />

	<script type="text/javascript" src="js/colorpicker.js"></script>
    <script type="text/javascript" src="js/eye.js"></script>
    <script type="text/javascript" src="js/utils.js"></script>
    <script type="text/javascript" src="js/layout.js?ver=1.0.2"></script>
      

    <!-- Custom CSS -->
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
		height: 200px;
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
		<form class="form-inline" method="get" id="idFilter">
				<div class="row">
					<div class="col-xs-6 col-md-3"><b>Usuario:</b> <?php echo $nombreUsuario ?></div>
					
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
		  <div class="modal-dialog modal-lg" role="document">			  
			<div class="modal-content">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				  <li class="nav-item">
				    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Agregar Horas</a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Horas Imputadas</a>
				  </li>
			</ul>
			<div class="tab-content">

			<div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
			<form class="form-horizontal" method="POST" action="addEvent.php" id="modaldetalle" name="modaldetalle">
			 <!--<input id="idProyecto" name="idProyecto" type="hidden" value="<?php echo  $filter ?>">-->
			 <input id="idUsuario" name="idUsuario" type="hidden" value="<?php echo  $idUsuario ?>">
			 <input id="idControlh" name="idControlh" type="hidden" value="">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar Horas</h4>
			  </div>
			  <div class="modal-body">


						<div class="form-group">
						<label for="categoria" class="col-sm-2 control-label">Proyectos</label>
						<div class="col-sm-10">
						<?php 														
						?>						
							<select  id="filter" name="filter" class="form-control" >
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
					<div class="col-sm-3">
						<!--<div id="listaTarea"></div>-->
						<div class = "ui-widget">
							<input id = "term" name="term"/>
							<!--<label for = "term">Tags: </label>-->
						</div>
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
				<button type="submit" class="btn btn-primary">Guardar</button>
			  </div>
			</form>
			</div>
			<div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
		</div>
			</div>
			<!-- fin de div principal tab -->
			</div>


			</div>
		  </div>
		</div>
		<!-- Modal -->
		
	</div>
	<!-- Modal -->
		<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="cuadro.php" id="cambioclave" name="cambioclave">
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

    <!-- jQuery Version 1.11.1 -->

    <!-- Nuevo para autocomplete -->
    <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
    <!-- Viejo para calendar 
    <script src="js/jquery.js"></script>-->



    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	
	<!-- FullCalendar -->
	<script src='js/moment.min.js'></script>
	<script src='js/fullcalendar.min.js'></script>
	
    <script src = "https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>

	function recargarLista(idTarea){				
		$.ajax({
			type:"POST",
			url:"select_combox.php",
			data:"categoria=" +$('#categoria').val(),
			success:function(r){						
				$('#listaTarea').html(r);				
				$('#tarea').change();
				if(idTarea>0)
				{
					$("#tarea option[value='"+idTarea+"']").attr('selected', 'selected');
					$("#tarea").val(idTarea);
					$('#tarea').change();
				}
			}
		});
	}

	function SetPrimeraPestana(idControlh, idproyecto, idCategoria, idTarea, tareadesc, horas)
	{		
		$("#ModalAdd #myTab li:first-child a").tab("show")
		
		//$("#modaldetalle #categoria option[value='"+idCategoria+"']").attr('selected', 'selected');
		$("#modaldetalle #filter").val(idproyecto);
		$("#modaldetalle #categoria").val(idCategoria);
		$("#modaldetalle #term").val(tareadesc);
	    $("#modaldetalle #hora").val(horas);
	    $("#modaldetalle #idControlh").val(idControlh);
		$('#modaldetalle #categoria').change();
		
		recargarLista(idTarea);				
	}

	function EliminarEvento(idControlh, idProyecto)
	{
		if(confirm("Confirmar que desea eliminar hora informada?.")==true)
		{
			var formData = {
                'delete': '1', 'idProyecto': idProyecto,
                'id': idControlh 
            };
				$.ajax({
					type:"POST",
					url:"editEventTitle.php",
					data:formData,//"categoria=" + $('#categoria').val(),
					success:function(r){				
						recargarListaImputadas($("#modaldetalle").find("#idUsuario").val(), idProyecto, $('#ModalAdd #fecha_asignacion').val());
						$("#idFilter").submit();
					}
				});
		}
		else
			alert("Eliminacion No Confirmada.")
	}
	$(document).ready(function() {
		
		$("#ModalAdd #myTab li:first-child a").tab("show");
		var defaultView = (localStorage.getItem("fcDefaultView") !== null ? localStorage.getItem("fcDefaultView") : "month");
		var defaultStartDate = (localStorage.getItem("fcDefaultStartDate") !== null ? localStorage.getItem("fcDefaultStartDate") : null);
		var defaultEndDate = (localStorage.getItem("fcDefaultEndDate") !== null ? localStorage.getItem("fcDefaultEndDate") : null);

		$('#calendar').fullCalendar({			
			locale: "es",
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
			dayNamesShort: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
			header: {
				left: 'prev,next today',
				center: 'title',
			 	right: 'month,basicWeek'				
			},    
			buttonText: {
			today:'hoy',month:'Mes',week:'Semana',day:'Dia'
			},			
			defaultView: defaultView ,
			visibleRange:
		    {
		    start: defaultStartDate,
		    end: defaultEndDate
		    },
			firstDay:1,
			defaultDate:  new Date(), 
			editable: true,
			fixedWeekCount: false,
			eventLimit: true, // allow "more" link when too many events
			selectable: true,
			selectHelper: true,
			eventLimitText: "mas",
			viewRender: function (view, element) {
		        // When the view changes, we update our localStorage value with the new view name.
		        localStorage.setItem("fcDefaultView", view.name);
		        localStorage.setItem("fcDefaultStartDate", view.start);
    			localStorage.setItem("fcDefaultEndDate", view.end);
		    },
			select: function(fecha_asignacion) {
				
				//alert(moment(fecha_asignacion).format('YYYY-MM-DD'));
				$Fecha = new Date(moment(new Date()).format('YYYY-MM-DDT03:00:00Z'));
				
				$Anio = moment($Fecha).format('YYYY');

				$Mes = moment($Fecha).format('MM');
				$Dia = moment($Fecha).format('DD');
				
				if($Dia>5)
				{
					$FechaLimite = new Date(moment($Fecha).format('YYYY-MM-01T00:00:00Z'));	
				}
				else
				{
					//alert($Mes);
					if($Mes>1)
					{
						$Mes = $Mes-1;						
						if($Mes<10)
							$Mes = '0'+$Mes;
						//string.padStart(length [, pad_string])
						//$Mes = str_pad($Mes-1, 2, "0", STR_PAD_LEFT);
						$FechaLimite = new Date(moment($Fecha).format('YYYY-'+$Mes+'-01T00:00:00Z'));		
						//alert( new Date(moment($Fecha).format('YYYY-'+$vMes+'-01T00:00:00Z')));
					}
					else
					{
						//alert($Anio);
						$Anio = $Anio - 1;
						$FechaLimite = new Date(moment($Fecha).format($Anio+'-12'+'-01T00:00:00Z'));			
					}
					
				}
				//alert($FechaLimite);
				$FechaSeleccion = new Date(moment(fecha_asignacion).format('YYYY-MM-DDT03:00:00Z'));
				
				if($FechaSeleccion>=$FechaLimite)
				{
					$("#ModalAdd #myTab li:first-child a").tab("show");
					$("#modaldetalle #categoria").val("");
					$('#modaldetalle #categoria').change();
					recargarLista(0);							
						$('#ModalEdit #id').val(event.id);					
						$('#ModalAdd #fecha_asignacion').val(moment(fecha_asignacion).format('YYYY-MM-DD'));
						$('#ModalAdd #fecha_asignacion').val(moment(fecha_asignacion).format('YYYY-MM-DD'));
						$('#ModalAdd').modal('show');					
						recargarListaImputadas($("#modaldetalle").find("#idUsuario").val(), $("#modaldetalle").find("#idProyecto").val(), $('#ModalAdd #fecha_asignacion').val());
					}

			},
			eventRender: function(event, element, fecha_asignacion) {
				element.bind('click', function() {				
					//alert(event.start._i);
					fecha_asignacion = event.start._i;
					
					//alert(moment(fecha_asignacion).format('YYYY-MM-DD'));

					$Fecha = new Date(moment(new Date()).format('YYYY-MM-DDT03:00:00Z'));
					
					$Anio = moment($Fecha).format('YYYY');
					
					$Mes = moment($Fecha).format('MM');
					$Dia = moment($Fecha).format('DD');

					if($Dia>5)
					{
						$FechaLimite = new Date(moment($Fecha).format('YYYY-MM-01T00:00:00Z'));	
					}
					else
					{
						//alert($Mes);
						//$vMes = $Mes-1
						//$FechaLimite = new Date(moment($Fecha).format('YYYY-'+$vMes+'-01T 00:00:00Z'));	
						if($Mes>1)
						{
							$Mes = $Mes-1
							if($Mes<10)
								$Mes = '0'+$Mes;
							$FechaLimite = new Date(moment($Fecha).format('YYYY-'+$Mes+'-01T00:00:00Z'));		
						}
						else
						{
							//alert($Anio);
							$Anio = $Anio - 1;
							$FechaLimite = new Date(moment($Fecha).format($Anio+'-12'+'-01T00:00:00Z'));			
						}
					}
					//alert($FechaLimite);
					$FechaSeleccion = new Date(moment(fecha_asignacion).format('YYYY-MM-DDT03:00:00Z'));
						
					if($FechaSeleccion>=$FechaLimite)
					{

					$("#ModalAdd #myTab li:first-child a").tab("show");
					$("#modaldetalle #categoria").val("");
					$('#modaldetalle #categoria').change();
					recargarLista(0);
						$('#ModalEdit #id').val(event.id);					
						$('#ModalAdd #fecha_asignacion').val(moment(fecha_asignacion).format('YYYY-MM-DD'));
						$('#ModalAdd #fecha_asignacion').val(moment(fecha_asignacion).format('YYYY-MM-DD'));
						$('#ModalAdd').modal('show');					
						recargarListaImputadas($("#modaldetalle").find("#idUsuario").val(), $("#modaldetalle").find("#idProyecto").val(), $('#ModalAdd #fecha_asignacion').val());
					}

				});
			},
			eventDrop: function(event, delta, revertFunc) { // si changement de position

				edit(event);

			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur
				edit(event);
			},
			eventOrder: ["id"],
			events: [
			<?php		
				
						$consulta = "SELECT max(idControlh)+1 as idControlh, sum(horas) horas, fecha_asignacion FROM controlhoras
								 WHERE idUsuario='$idUsuario'
								 GROUP BY fecha_asignacion ";
						$sql = mysqli_query($con, $consulta);
						
						if (mysqli_num_rows($sql) == 0){
							?>							
							
							<?php 
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
										title: '<?php echo 'HT: '.$event['horas'].' Hrs';  ?>',
										start: '<?php echo $fecha_asignacion; ?>',
										end: '<?php echo $fecha_asignacion; ?>',
										color: '<?php echo '#ff4040' ?>',
									},
					    <?php 
							}
						}
						 $consulta_total = "select idcontrolh, horas, fecha_asignacion, 					   proyectos.sigla, proyectos.color from controlhoras
											inner join proyectos on controlhoras.idproyecto = proyectos.idproyecto
											WHERE  idUsuario='$idUsuario'
											order by fecha_asignacion";
						 $sql_total = mysqli_query($con, $consulta_total);
						 
						 if (mysqli_num_rows($sql_total) == 0){
						 	?>							
							
							<?php 
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
										 id: '<?php echo $totales['idcontrolh']; ?>',
										 title: '<?php echo ''.$totales['sigla'].' H: '.$totales['horas'].' Hrs';  ?>',
										 start: '<?php echo $fecha_asignacion; ?>',
										 end: '<?php echo $fecha_asignacion; ?>',
										 color: '<?php echo $totales['color'] ?>',
									 },
						 <?php 
								 }
						 }
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
		//$('#categoria').val();
		recargarLista(0);

		$('#categoria').change(function(){					
			recargarLista(0);
		});

		$('#listaTarea').change(function(){
			var formData = {
                'categoria': $('#categoria').val(), 'tarea': $("#tarea").val(),
                'fecha_asignacion': $('#fecha_asignacion').val(), 'idProyecto': $('#idProyecto').val(),
                'idUsuario':$('#idUsuario').val()//for get 
            };
				$.ajax({
					type:"POST",
					url:"HorasCategoriaTarea.php",
					data:formData,//"categoria=" + $('#categoria').val(),
					success:function(r){				
						$('#hora').val(r);
					}
				});
		});	

		$( "#term" ).autocomplete({
               source: function( request, response ) {
                    $.ajax( {
                      type: "POST",
                      url: "selecttareas.php",
                      dataType: "json",
                      data: {
                        term: request.term
                      },
                      success: function(data) {                      
                         response(data);                        
                     },
                     error: function() {
                          console.log("No se ha podido obtener la información");
                      }
                    } );
                  },
            select: function( event, ui ) {
                 //log( "Selected: " + ui.item.value + " aka " + ui.item.id );
               }
            });

	})
</script>
<script type="text/javascript">
	
	function recargarListaImputadas(idusuario, idproyecto, fecha){
		var formData = {
                'idusuario': idusuario, 
                'idproyecto': idproyecto, 
                'fecha': fecha
            	};
		$.ajax({
			type:"POST",
			url:"showmodalhorasimputadas.php",
			data:formData,
			success:function(r){								
				$('#idTareasImputadas').html(r);							
			}
		});
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
			header("Location: .$url.cuadro.php");
	    });

</script>



</body>

</html>
<?php
} else {
    // Redirect them to the login page
    header("Location: ".$url."login.php");
}
?>
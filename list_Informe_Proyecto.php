<?php
	require_once("conexion.php");
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
		body {
			padding-top: 60px;
			/* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
		}
		.content {
			margin-top: 80px;
		}
	</style>

</head>
<body>
<?php  

	$filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);
	$proyecto = (isset($_GET['proyecto']) ? strtolower($_GET['proyecto']) : NULL);
	$meses = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
	$CantHrs = 0;
	if($filter)
	{
		$fecha = explode("-", $filter);	
		$mes = $fecha[0]==0?date('m'):$fecha[0];
		$ano = isset($fecha[1])?$fecha[1]:date('Y');
		$meses = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
		$fechaaux = new DateTime("1-".$mes."-".$ano."");	
		$indDia = date('w', strtotime($fechaaux->format('Y-m-d')));		
		for ($i = 1; $i <= $meses; $i++){ 
			
			if ($indDia ==7) 
				$indDia = 0;
			else
			{
				if($indDia<6)
					$CantHrs = $CantHrs + 9;
			}
					
			$indDia++;		
		}
	}

	$columns   = 1 ; 
	$dias = array('1' =>'L', 
				  '2' =>'M',
				  '3' =>'M', 
				  '4' =>'J',
				  '5' =>'V',
				  '6' =>'S',
				  '7' =>'D'
		);

        /// funcion que suma las horas segun los proyectos
		function sumar_dia ($i, $proyectos,$proyeHoras){
			$suma =0;

			for($x = 0; $x < count($proyectos); $x++) {
				$cadena = explode("*", $proyeHoras[$x]);
					for($j = 0; $j < count($cadena)-1; $j++) { 
						$dia = explode("/",  $cadena[$j]);
						if ('0'.$i ==  $dia[0]) { 
							$suma = $suma + $dia[1];
						}	
					}
			}		
		    return	$suma; 			
		}
		/// funcion que cuenta las veces un elemento en un array
		function contar_valores($a,$buscado)
		{
			if(!is_array($a)) return NULL;
				$i=0;
			foreach($a as $v)
			if($buscado===$v['nombreUser'])
				$i++;
			return $i;
		}
	?>

	<div class="container">
		<nav class="navbar navbar-default navbar-fixed-top">
			<?php include('nav_home.php');?>
		</nav>	
	
	  <div class="content">
	 	    <h2 style="margin-top:57px;">Resúmenes</h2>
			<hr/>
			<form class="form-inline" method='GET' enctype='multipart/form-data'  id='formId'>
				<div class="row">
					
					<div class="col-md-2 col-xs-3">
						<div class="form-group">
								<div class='input-group date'>
									<input type="text" name="filter"  autocomplete="off" value="<?php if($filter==""){ 
									$mes = date('m');
									$ano = date('Y');
									echo $mes."-".$ano;
										}else echo $filter; ?>"  class="input-group date form-control" data="02-2012" date-format="mm-yyyy" placeholder="00-0000" required>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
											</span>
										</span>
								</div>
						</div>
					</div>
					<div class="col-md-1 col-xs-3">
						<button type="submit"  class="btn btn-sm btn-primary btn-sm">
								<span class="glyphicon glyphicon-search"></span>
						</button>
					</div>
				</div>
			</form>
			<br/>
			<?php
				if($filter){
				    $fecha = explode("-", $filter);
					$mes = $fecha[0];
					$ano = $fecha[1];
					$meses = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
			        $consulta = "SELECT ch.idUsuario, us.nombres as nombreUser, pt.nombre, ch.idControlh, sum(ch.horas) horas, ch.fecha_asignacion 
									 FROM controlhoras ch 
									 INNER JOIN proyectos pt  ON pt.idProyecto = ch.idProyecto
									 INNER JOIN usuarios us  ON us.idUsuario =  ch.idUsuario
									 left join sueldomes sm on us.idusuario = sm.idUsuario
									 			and sm.mesanio = '$filter'
									 WHERE 
									 MONTH(ch.fecha_asignacion) = $mes AND YEAR(ch.fecha_asignacion) = $ano
									 GROUP BY  ch.fecha_asignacion, pt.nombre, nombreUser
									 ORDER BY nombreUser, ch.idProyecto ASC";
			 		$sql = mysqli_query($con, $consulta);
				
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
			    	// $row = mysqli_fetch_assoc($sql);
				}		
			}		
		?>
			<br/>
			<?php
						if($filter){
						    $fecha = explode("-", $filter);
							$mes = $fecha[0]==0?date('m'):$fecha[0];
							$ano = $fecha[1]==0?date('Y'):$fecha[1];
							$fechaaux = new DateTime("1-".$mes."-".$ano."");	
							$meses = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
						    $consulta = "SELECT  usuarios.idusuario,  usuarios.nombres,  sum(ch.horas) as  horas , 	IFNULL(sm.sueldo, 0)  as sueldomesv
								FROM controlhoras ch INNER JOIN proyectos pt  ON pt.idProyecto = ch.idProyecto
								INNER JOIN usuarios ON ch.idusuario = usuarios.IDUSUARIO
								left join sueldomes sm on usuarios.idusuario = sm.idUsuario
									 			and sm.mesanio = '$filter'
								WHERE 
								
								MONTH(ch.fecha_asignacion) = $mes AND YEAR(ch.fecha_asignacion) = $ano
								GROUP BY  usuarios.idusuario, usuarios.nombres, sueldomesv
								HAVING sum(ch.horas)>0
								ORDER BY pt.nombre ASC";											 							
					 		$sql = mysqli_query($con, $consulta);
						$TotalMonto= 0;
						if(mysqli_num_rows($sql) == 0){
							echo '<tr><td colspan="8">No hay datos.</td></tr>';
						}else{

							while ($row = mysqli_fetch_assoc($sql)) { 
										$rows1[] = $row; 
									} 									
							$totalHoras = 0;
							$Costo = 0;
							for($x = 0; $x < count($rows1); $x++) {
								$totalHoras=$totalHoras+$rows1[$x]["horas"];
								$Costo = $rows1[$x]["horas"];
							}
							echo "<div class=\"table-responsive\"> <table width=\"50%\" class=\"table-striped table-bordered table-hover\" data-page-length=\"20\">
							<thead>
								<tr>
									<th colspan=\"1\" >USUARIO</th>
									<th colspan=\"1\" >HORAS</th>";
								
									if($idRol==2)
									{
										echo "
										<th colspan=\"1\" >TOTAL $</th>";
									}

								echo "	</tr></thead><tbody>";

							for($x = 0; $x < count($rows1); $x++) {
								echo "<tr>
							<th >".$rows1[$x]['nombres']."</th>
							<td >".number_format($rows1[$x]['horas'], 0,",",".")."</td>";
							
							//number_format((($rows1[$x]['sueldomesv']/$CantHrs)*$rows1[$x]['horas']), 0,",",".")
							if($idRol==2)
									{ echo "<td align=\"right\">$".number_format($rows1[$x]['sueldomesv'], 0,",",".")."</td>";
							}
							

							echo "</tr>";
							$TotalMonto = $TotalMonto + $rows1[$x]['sueldomesv'];
							///(($rows1[$x]['sueldomesv']/$CantHrs)*$rows1[$x]['horas']);
							}
							echo "<tr>
							<th >Total</th>
							<th >".number_format($totalHoras, 0,",",".")."</th>";
							if($idRol==2)
									{ echo "<th style=\"text-align: right;\">$".number_format($TotalMonto, 0,",",".")."</th>";}
							echo "</tr>";
							echo "</tbody></table></div>";
					    	// $row = mysqli_fetch_assoc($sql);
						}		
					}		
				?>

				<br/>
			<?php
						if($filter){
						    $fecha = explode("-", $filter);
							$mes = $fecha[0]==0?date('m'):$fecha[0];
							$ano = $fecha[1]==0?date('Y'):$fecha[1];
							$fechaaux = new DateTime("1-".$mes."-".$ano."");	
							$meses = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);


							$indDia = date('w', strtotime($fechaaux->format('Y-m-d')));		
							for ($i = 1; $i <= $meses; $i++){ 
								
								if ($indDia ==7) 
									$indDia = 0;
								else
								{
									if($indDia<6)
										$CantHrs = $CantHrs + 0;
								}
										
								$indDia++;		
							}
							 //$CantHrs;

						    $consulta = "SELECT  pt.idProyecto,  pt.nombre AS nombreproyecto,  sum(ch.horas) as  horas , 	sum((IFNULL(sm.sueldo, 0)/$CantHrs)*ch.horas)  as sueldomesv
								FROM controlhoras ch INNER JOIN proyectos pt  ON pt.idProyecto = ch.idProyecto
								INNER JOIN usuarios ON ch.idusuario = usuarios.IDUSUARIO
								left join sueldomes sm on usuarios.idusuario = sm.idUsuario
									 			and sm.mesanio = '$filter'
								WHERE MONTH(ch.fecha_asignacion) = $mes AND YEAR(ch.fecha_asignacion) = $ano
								GROUP BY  pt.idProyecto, pt.nombre
								HAVING sum(ch.horas)>0
								ORDER BY pt.nombre ASC";											 					
					 		$sql = mysqli_query($con, $consulta);
						$TotalMonto= 0;
						if(mysqli_num_rows($sql) == 0){
							echo '<tr><td colspan="8">No hay datos.</td></tr>';
						}else{

							while ($row = mysqli_fetch_assoc($sql)) { 
										$rows2[] = $row; 
									} 									
							$totalHoras = 0;
							$Costo = 0;
							for($x = 0; $x < count($rows2); $x++) {
								$totalHoras=$totalHoras+$rows2[$x]["horas"];
								$Costo = $rows2[$x]["horas"];
							}
							echo "<div class=\"table-responsive\"> <table width=\"50%\" class=\"table-striped table-bordered table-hover\" data-page-length=\"20\">
							<thead>
								<tr>
									<th colspan=\"1\" >PROYECTO</th>
									<th colspan=\"1\" >HORAS</th>";
									if($idRol==2)
									{ echo "<th colspan=\"1\" >TOTAL $</th>";
									}

								echo "	</tr></thead><tbody>";

							for($x = 0; $x < count($rows2); $x++) {
								echo "<tr>
							<th >".$rows2[$x]['nombreproyecto']."</th>
							<td >".number_format($rows2[$x]['horas'], 0,",",".")."</td>";
							$vidProyecto = $rows2[$x]['idProyecto'];
							$consult = "SELECT sum(monto) as Monto	 from (SELECT  (ifnull( sum(case when pt.idProyecto = $vidProyecto then ch.horas end), 0)*100/sum(ch.horas))*IFNULL(sm.sueldo, 0)/100 as Monto FROM usuarios us left JOIN controlhoras ch ON us.idUsuario =  ch.idUsuario left JOIN proyectos pt  ON pt.idProyecto = ch.idProyecto left join sueldomes sm on us.idusuario = sm.idUsuario
								and sm.mesanio = '".$filter."' WHERE MONTH(ch.fecha_asignacion) = $mes AND YEAR(ch.fecha_asignacion) = $ano and pt.idProyecto is not null
								group by us.nombres) tabla";

							//echo  $consult;
							$sql5 = mysqli_query($con, $consult);
							$row = mysqli_fetch_assoc($sql5);
							$TotalMonto1= $row['Monto'];
							//number_format((($rows2[$x]['sueldomesv'])), 0,",",".")
							if($idRol==2)
									{ echo "<td align=\"right\">$".number_format($TotalMonto1, 0,",",".")."</td>";
										}						
							echo "</tr>";
							$TotalMonto = $TotalMonto + (($TotalMonto1));
							}
							echo "<tr>
							<th >Total</th>
							<th >".number_format($totalHoras, 0,",",".")."</th>";
							if($idRol==2)
									{ 
										echo "<th style=\"text-align: right;\">$".number_format($TotalMonto, 0,",",".")."</th>";
									}
							echo "</tr>";
							echo "</tbody></table></div>";
					    	// $row = mysqli_fetch_assoc($sql);
						}		
					}		
				?> 
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
	<script>
		$('.date').datepicker({
			format: "mm-yyyy",
			startView: "months", 
			minViewMode: "months",
			autoclose: true
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
	</script>
</body>
</html>

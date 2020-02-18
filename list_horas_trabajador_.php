	<?php
	require_once("conexion.php");
	$consulta= "SELECT *  FROM usuarios WHERE idUsuario='1'";
	$sql = mysqli_query($con, $consulta);
	$row = mysqli_fetch_assoc($sql);
	$nombreUsuario= $row['nombres'].' '.$row['ap_paterno'].' '.$row['ap_materno'] ;
	$idUsuario = $row['idUsuario'];			 
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
	$usuario = (isset($_GET['usuario']) ? strtolower($_GET['usuario']) : NULL);  
	$filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);
	$meses = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
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

		function contar_valores($a,$buscado)
		{
			if(!is_array($a)) return NULL;
				$i=0;
			foreach($a as $v)
			if($buscado===$v['nombre'])
				$i++;
			return $i;
		}
	?>

	<div class="container">
		<nav class="navbar navbar-default navbar-fixed-top">
			<?php include('nav_home.php');?>
		</nav>	
	
	  <div class="content">
	 	    <h2 style="margin-top:57px;">Horas Hombre por Trabajador</h2>
			<hr/>
			<form class="form-inline" method='GET' enctype='multipart/form-data'  id='formId'>
				<div class="row">
					<div class="col-md-3 col-xs-3">
						<select  id="usuario" name="usuario" class="form-control" required >
									<option value="">Selecciona el usuario</option>
				
										<?php				
											$usuarios= mysqli_query($con,"SELECT * FROM usuarios WHERE habilitado = 1");
											while ($row_usuario = mysqli_fetch_array ($usuarios))
											{ ?>
												<option value="<?php echo ($row_usuario['idUsuario'])?>" 
												<?php  if($usuario == $row_usuario ['idUsuario'] ){ echo 'selected'; } ?>>
													<?php echo $row_usuario ['nombres']; ?>
												</option>
										<?php }?>
							</select>
						</div>
					<div class="col-md-2 col-xs-3">
						<div class="form-group">
								<div class='input-group date'>
									<input type="text" name="filter" autocomplete="off"  value="<?php echo $filter ?>"  class="input-group date form-control" data="02-2012" date-format="mm-yyyy" placeholder="00-0000" required>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
											</span>
										</span>
								</div>
						</div>
					</div>
					<div class="col-md-2 col-xs-3"><b>Costo Emp: </b><?php echo '$10000'  ?> </div>
					<div class="col-md-2 col-xs-3">
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
					$fechaaux = new DateTime("1-".$mes."-".$ano."");					
					$meses = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
				    $consulta = "SELECT  pt.nombre, ch.idControlh, sum(ch.horas) horas, ch.fecha_asignacion 
									 FROM controlhoras ch INNER JOIN proyectos pt  ON pt.idProyecto = ch.idProyecto
									 WHERE ch.idUsuario='$usuario' and 
									 MONTH(ch.fecha_asignacion) = $mes AND YEAR(ch.fecha_asignacion) = $ano
									 GROUP BY  ch.fecha_asignacion, pt.nombre
									 ORDER BY ch.idProyecto ASC";
			 		$sql = mysqli_query($con, $consulta);
				
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
			    	// $row = mysqli_fetch_assoc($sql);
				}		
			}		
		?>
			<div class="table-responsive">
				<table width="100%" class="table table-striped table-bordered table-hover" data-page-length="20">
					<thead>
						<tr>
							<th colspan="1" >PROYECTO</th>
								<?php
									$indDia = date('w', strtotime($fechaaux->format('Y-m-d')));
									for ($i = 1; $i <= $meses; $i++){ 
										?> <th style="text-align: center; font-weight:bold;font-size:12px"><?php echo $dias[$indDia] ?></th> 
											<?php
												if ($indDia ==7) 
													$indDia = 0;
												
										$indDia++;		
									}
								?>
							<th style="text-align: center;font-size:12px"></th>
							<th style="text-align: center;font-size:12px"></th>
						</tr>
						<tr>
							<th ></th>
							<?php
								for ($i = 1; $i <= $meses; $i++){
								?> <th style="text-align: center;font-size:12px"><?php echo $i ?></th> <?php
								}
							?>
							<th style="text-align: center;font-size:12px">Total Mes</th>
							<th style="text-align: center;font-size:12px">$</th>
						</tr>
					</thead>
					<?php	
			
					   if($filter){
						 if(mysqli_num_rows($sql) > 0){
						?>
							<tbody>
								<?php 
									while ($row = mysqli_fetch_assoc($sql)) { 
										$rows[] = $row; 
									} 
									$proyeHoras[] ='';
									$index=0;	
									for($x = 0; $x < count($rows); $x++) { 
										$aux = $rows[$x]['nombre'];	
										$proye[$x]=$aux;	
										$cadena="";
										$cont = 0;
										$cont = contar_valores($rows, $rows[$x]['nombre']);
										for($i = 0; $i <= $cont-1; $i++) { 
										   $dia = date("d", strtotime(@$rows[$x]['fecha_asignacion']));
										   $hora = explode(".", @$rows[$x]['horas']);
										   $cadena =  $cadena.$dia.'/'.$hora[0].'*';
										   if ($i < $cont-1){
											 $x++; 
										   }	 
										}
										$proyeHoras[$index] =  $cadena;
										$index++;
									}
									$i=0;
									foreach ($proye as $key => $value) {
										$proyectos[$i]=$value;
										$i++;
									}
							  		for($x = 0; $x < count($proyectos); $x++) { ?>
										<td style="vertical-align:right;color:blue"> 
											<?php echo $proyectos[$x] ?>
										</td> 
										<?php 
										 for ($i = 1; $i <= $meses; $i++){ ?>
											<td  style="text-align: center; vertical-align:center; color:red; font-weight: bold;font-size:12px"> 
												<?php 
												$totalMes = 0; 
													 $cadena = explode("*", @$proyeHoras[$x]);
												 	 for($j = 0; $j < count($cadena)-1; $j++) { 
														 $dia = explode("/",  $cadena[$j]);
														 $totalMes =$totalMes + $dia[1];
														 if ('0'.$i ==  $dia[0]) 
															 echo $dia[1];
													 }
												?>		
											</td> 
										<?php }?>	
										<td style="text-align: center;font-size:12px; background-color:#eee "><?php echo $totalMes ?></td>
										<td style="text-align: center;font-size:12px; background-color:#eee ">0</td>
									</tr>
								   <?php } ?>

									<!--  TOTAL POR DIA -->
									<tr>
										<td style="vertical-align:right;font-weight: bold;font-size:12px"><b>TOTAL POR DIA</b></td> 
											<?php 
											
											   $totalGeneral=0;
											   for ($i = 1; $i <= $meses; $i++){ ?>
												<td style="vertical-align:center;font-weight: bold;font-size:12px" id=<?php echo $i?>> 
												  <?php 
												      $totalDia=0;
													   echo $totalDia = sumar_dia($i, $proyectos, $proyeHoras);
													   $totalGeneral = $totalGeneral + $totalDia;
													?>	
												</td>
											<?php } ?>
										<td style="text-align: center;font-size:12px; background-color:#eee "><?php echo $totalGeneral ?></td>
										<td style="text-align: center;font-size:12px; background-color:#eee ">0</td>   
									</tr> 	
									<!--  VALOR ESPERADO -->
									<tr>
										<td style="vertical-align:right;font-size:12px"><b>VALOR ESPERADO</b></td> 
										<?php 
											   for ($i = 1; $i <= $meses; $i++){ ?>
												<td style="vertical-align:center;font-weight: bold;font-size:12px" id=<?php echo $i?>> 
												  <?php 
													 echo $totalDia=0;
												  	// echo $totalDia = sumar_dia($i, $proyectos, $proyeHoras);
													?>	
												</td>
											<?php } ?>  
										<td style="text-align: center;font-size:12px; background-color:#eee ">0</td>
										<td style="text-align: center;font-size:12px; background-color:#eee ">0</td> 	
									</tr> 
									<!--  DIFERENCIA -->
									<tr>
										<td style="vertical-align:right;font-size:12px"><b>DIFERENCIA</b></td> 
										<?php 
											   for ($i = 1; $i <= $meses; $i++){ ?>
												<td style="vertical-align:center;font-weight: bold;font-size:12px" id=<?php echo $i?>> 
												  <?php 
													 echo $totalDia=0;
												  	// echo $totalDia = sumar_dia($i, $proyectos, $proyeHoras);
													?>	
												</td>
											<?php } ?>  
										<td style="text-align: center;font-size:12px; background-color:#eee ">0</td>
										<td style="text-align: center;font-size:12px; background-color:#eee ">0</td>	
									</tr> 
									<!--  SUELDO DIARIO -->
									<tr>
										<td style="vertical-align:right;font-size:12px"><b>SUELDO DIARIO</b></td> 
											<?php 
												for ($i = 1; $i <= $meses; $i++){ ?>
													<td style="vertical-align:center;font-weight: bold;font-size:12px" id=<?php echo $i?>> 
													<?php 
														echo $totalDia=0;
														// echo $totalDia = sumar_dia($i, $proyectos, $proyeHoras);
														?>	
													</td>
												<?php } ?>
										<td style="text-align: center;font-size:12px; background-color:#eee ">0</td>
										<td style="text-align: center;font-size:12px; background-color:#eee">0</td>			

									</tr>
							</tbody>
					<?php
					     } 
					   }
					?>		
				</table>
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
	</script>
</body>
</html>

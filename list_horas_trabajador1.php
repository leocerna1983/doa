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

	$consultaConfi = "SELECT idconfiguracion, valordias from configuracion where idconfiguracion = 1";
	$sqlConf = mysqli_query($con, $consultaConfi);

///echo $consulta;
	$valorDias = 0;
	if(mysqli_num_rows($sqlConf) == 0){
		$valorDias = 9;
	}else{
		$rowConf = mysqli_fetch_assoc($sqlConf);
		$valorDias = $rowConf["valordias"];
	}
	

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
	if($usuario!=NULL)
	{
		$consulta= "SELECT * , IFNULL(sm.sueldo, 0) as sueldomesv FROM usuarios 
				left join sueldomes sm on usuarios.idusuario = sm.idUsuario and sm.mesanio = '$filter'
				WHERE usuarios.idUsuario='$usuario'";
				//echo $consulta;
		$sql = mysqli_query($con, $consulta);
		$row = mysqli_fetch_assoc($sql);
		$Sueldo =$row['sueldomesv'];

	}
	
	$meses = cal_days_in_month(CAL_GREGORIAN, date('m')==0?0:date('m'), date('Y')==0?2019:date('Y'));
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
			<?php include('nav_home.php'); ?>
		</nav>	
	
	  <div class="content">
	 	    <h2 style="margin-top:57px;">Horas Hombre por Trabajador 1</h2>
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
									<input type="text" name="filter" autocomplete="off"  value="<?php if($filter==""){ 
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
					<div class="col-md-2 col-xs-3"><b>Costo Emp: </b><?php echo number_format($Sueldo, 0, ",",".");  ?> </div>
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
					$mes = $fecha[0]==0?date('m'):$fecha[0];
					$ano = $fecha[1]==0?date('Y'):$fecha[1];
					$fechaaux = new DateTime("1-".$mes."-".$ano."");	
					$meses = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
					$valor = date("m-Y");
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
									$fecha = explode("-", $filter);	
																	
									$mes = $fecha[0]==0?date('m'):$fecha[0];
									$ano = isset($fecha[1])?$fecha[1]:date('Y');
									$fechaaux = new DateTime("1-".$mes."-".$ano."");	
									$indDia = date('w', strtotime($fechaaux->format('Y-m-d')));
									if($indDia==0)
										$indDia = 7;
									for ($i = 1; $i <= $meses; $i++){ 
										?> <th style="text-align: center; font-weight:bold;font-size:12px"><?php  echo $dias[$indDia]; ?></th> 
											<?php
												if ($indDia ==7) 
													$indDia = 0;
												
										$indDia++;		
									}
								?>
							<th style="text-align: center;font-size:12px"></th>
							<!--<th style="text-align: center;font-size:12px"></th>-->
						</tr>
						<tr>
							<th ></th>
							<?php
								for ($i = 1; $i <= $meses; $i++){
								?> <th style="text-align: center;font-size:12px"><?php echo $i ?></th> <?php
								}
							?>
							<th style="text-align: center;font-size:12px">Total Mes</th>
							<!--<th style="text-align: center;font-size:12px">$</th>-->
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
										   //$hora = explode(".", @$rows[$x]['horas']);
										   $hora = $rows[$x]['horas'];										   										   
										   $cadena =  $cadena.$dia.'/'.$hora.'*';
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
															 echo number_format($dia[1], 0,",",".");
														 	
													 }
												?>		
											</td> 
										<?php }?>	
										<td style="text-align: center;font-size:12px; background-color:#eee "><?php echo number_format($totalMes, 0,",","."); ?></td>
										<!--<td style="text-align: center;font-size:12px; background-color:#eee ">0</td>-->
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
													   $totalDia = sumar_dia($i, $proyectos, $proyeHoras);
													   echo number_format($totalDia, 0,",",".");
													   $totalGeneral = $totalGeneral + $totalDia;
													?>	
												</td>
											<?php } ?>
										<td style="text-align: center;font-size:12px; background-color:#eee "><?php echo number_format($totalGeneral, 0,",","."); ?></td>
										<!--<td style="text-align: center;font-size:12px; background-color:#eee ">0</td>   -->
									</tr> 	
									<!--  VALOR ESPERADO -->
									<tr>
										<td style="vertical-align:right;font-size:12px"><b>VALOR ESPERADO</b></td> 
										<?php 
											   $indDia = date('w', strtotime($fechaaux->format('Y-m-d')));
											   $TotalDiaValorEsperado = 0;
											   for ($i = 1; $i <= $meses; $i++){ ?>
												<td style="vertical-align:center;font-weight: bold;font-size:12px" id=<?php echo $i?>> 
												  <?php 
												  	if ($indDia ==7) 
												  	{
														$indDia = 0;
														echo $totalDia=0;
												  	}
													else
													{
														if($indDia<6)
														{
															echo $totalDia=$valorDias;
															$TotalDiaValorEsperado = $TotalDiaValorEsperado + $totalDia;
														}
														else
															echo $totalDia=0;
													}
													 
													$indDia ++;
												  	// echo $totalDia = sumar_dia($i, $proyectos, $proyeHoras);
													?>	
												</td>
											<?php } ?>  
										<td style="text-align: center;font-size:12px; background-color:#eee "><?php echo $TotalDiaValorEsperado; ?> </td>
										<!--<td style="text-align: center;font-size:12px; background-color:#eee ">0</td> 	-->
									</tr> 
									<!--  DIFERENCIA -->
									<tr>
										<td style="vertical-align:right;font-size:12px"><b>DIFERENCIA</b></td> 
										<?php 
											   $indDia = date('w', strtotime($fechaaux->format('Y-m-d')));
											   $TotalDiferenciaDia = 0;
											   for ($i = 1; $i <= $meses; $i++){ ?>
												<td style="vertical-align:center;font-weight: bold;font-size:12px" id=<?php echo $i?>> 
												  <?php 
												  	 $totalDia1 = sumar_dia($i, $proyectos, $proyeHoras);
													  	 if ($indDia ==7) 
													  	{
															$indDia = 0;
															echo 0;
													  	}
														else
														{
															if($indDia<6)
															{
																//echo $totalDia=9;
																echo number_format($totalDia1- $valorDias, 0,",",".") ;

																$TotalDiferenciaDia= $TotalDiferenciaDia + ($totalDia1- 9);
															}
															else
																echo 0;
														}
														 
														$indDia ++;
													 //echo $totalDia=0;
												  	// echo $totalDia = sumar_dia($i, $proyectos, $proyeHoras);
													?>	
												</td>
											<?php } ?>  
										<td style="text-align: center;font-size:12px; background-color:#eee "> <?php echo  number_format($TotalDiferenciaDia, 0,",","."); ?></td>
										<!--<td style="text-align: center;font-size:12px; background-color:#eee ">0</td>	-->
									</tr> 
									<!--  SUELDO DIARIO -->
									<!--<tr>
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

									</tr>-->
							</tbody>
					<?php
					     } 
					   }
					?>		
				</table>

				<?php
						if($filter){
						    $fecha = explode("-", $filter);
							$mes = $fecha[0]==0?date('m'):$fecha[0];
							$ano = $fecha[1]==0?date('Y'):$fecha[1];
							$fechaaux = new DateTime("1-".$mes."-".$ano."");	
							$meses = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
							$valor = date("m-Y");
						    $consulta = "select @HorasTotal := sum(horas), @MontoTotal := sum(montototal) from(
select tabla.idproyecto, proyectos.nombre, horas, round(horas*100/totalhoras, 2) as Porcentaje, round(horas*100/totalhoras*sueldo/100) as MontoTotal from (
select controlhoras.idusuario,  idproyecto,  year(fecha_asignacion) as AnioControlHoras, month(fecha_asignacion) as Mes, sum(horas) as horas,
sueldomes.sueldo, SUBSTRING_INDEX(SUBSTRING_INDEX(mesanio,'-',2),'-',-1) as Anio, 
(select sum(a.horas) from controlhoras as a where idusuario = controlhoras.idusuario 
and SUBSTRING_INDEX(mesanio,'-',1) = month(a.fecha_asignacion) and 
convert(SUBSTRING_INDEX(SUBSTRING_INDEX(mesanio,'-',2),'-',-1), unsigned integer)= year(a.fecha_asignacion)
limit 1) as TotalHoras
from controlhoras
left join sueldomes on controlhoras.idusuario = sueldomes.idusuario and 
SUBSTRING_INDEX(mesanio,'-',1) = month(fecha_asignacion) and convert(SUBSTRING_INDEX(SUBSTRING_INDEX(mesanio,'-',2),'-',-1), unsigned integer)= year(fecha_asignacion)
where year(fecha_asignacion)=".$ano." and month(fecha_asignacion)<".$mes." and controlhoras.idusuario = ".$usuario."
group by idproyecto, controlhoras.idusuario, year(fecha_asignacion), month(fecha_asignacion)) tabla
inner join proyectos on tabla.idproyecto = proyectos.idproyecto) tabla1;";							


$Consulta1 = "delete from listhoratrabajador;";

$Consulta2 = "insert into listhoratrabajador(idProyecto, DescProyecto, Horas, Porcentaje, Total, HorasAcumulada, PorcentajeAcumulada, TotalAcumulada)
select idproyecto, nombre, 0 as horas, 0 as porcentaje, 0 as porcentaje, sum(horas) as HorasAcumuladas, sum(horas)*100/@HorasTotal as PorcentajeAcumulado, 
sum(montototal) as MontoAcumulado from(
select tabla.idproyecto, proyectos.nombre, horas, round(horas*100/totalhoras, 2) as Porcentaje, round(horas*100/totalhoras*sueldo/100) as MontoTotal from (
select controlhoras.idusuario,  idproyecto,  year(fecha_asignacion) as AnioControlHoras, month(fecha_asignacion) as Mes, sum(horas) as horas,
sueldomes.sueldo, SUBSTRING_INDEX(SUBSTRING_INDEX(mesanio,'-',2),'-',-1) as Anio, 
(select sum(a.horas) from controlhoras as a where idusuario = controlhoras.idusuario 
and SUBSTRING_INDEX(mesanio,'-',1) = month(a.fecha_asignacion) and 
convert(SUBSTRING_INDEX(SUBSTRING_INDEX(mesanio,'-',2),'-',-1), unsigned integer)= year(a.fecha_asignacion)
limit 1) as TotalHoras
from controlhoras
left join sueldomes on controlhoras.idusuario = sueldomes.idusuario and 
SUBSTRING_INDEX(mesanio,'-',1) = month(fecha_asignacion) and convert(SUBSTRING_INDEX(SUBSTRING_INDEX(mesanio,'-',2),'-',-1), unsigned integer)= year(fecha_asignacion)
where year(fecha_asignacion)=".$ano." and month(fecha_asignacion)<".$mes." and controlhoras.idusuario = ".$usuario."
group by idproyecto, controlhoras.idusuario, year(fecha_asignacion), month(fecha_asignacion)) tabla
inner join proyectos on tabla.idproyecto = proyectos.idproyecto) tabla1
group by idproyecto, nombre;";

$Consulta3 = "insert into listhoratrabajador(idProyecto, DescProyecto, Horas, Porcentaje, Total, HorasAcumulada, PorcentajeAcumulada, TotalAcumulada)
select idproyecto, nombre, horas, porcentaje, montototal, 0 AS UNO,0 AS DOS,0 AS TRES from
(select tabla.idproyecto, proyectos.nombre, horas, round(horas*100/totalhoras, 2) as Porcentaje, 
round(horas*100/totalhoras*sueldo/100) as MontoTotal from (
select controlhoras.idusuario,  idproyecto,  year(fecha_asignacion) as AnioControlHoras, month(fecha_asignacion) as Mes, sum(horas) as horas,
sueldomes.sueldo, SUBSTRING_INDEX(SUBSTRING_INDEX(mesanio,'-',2),'-',-1) as Anio, 
(select sum(a.horas) from controlhoras as a where idusuario = controlhoras.idusuario 
and SUBSTRING_INDEX(mesanio,'-',1) = month(a.fecha_asignacion) and 
convert(SUBSTRING_INDEX(SUBSTRING_INDEX(mesanio,'-',2),'-',-1), unsigned integer)= year(a.fecha_asignacion)
limit 1) as TotalHoras
from controlhoras
left join sueldomes on controlhoras.idusuario = sueldomes.idusuario and 
SUBSTRING_INDEX(mesanio,'-',1) = month(fecha_asignacion) and convert(SUBSTRING_INDEX(SUBSTRING_INDEX(mesanio,'-',2),'-',-1), unsigned integer)= year(fecha_asignacion)
where year(fecha_asignacion)=".$ano." and month(fecha_asignacion)=".$mes." and controlhoras.idusuario = ".$usuario."
group by idproyecto, controlhoras.idusuario, year(fecha_asignacion), month(fecha_asignacion)) tabla
inner join proyectos on tabla.idproyecto = proyectos.idproyecto) tabla;";

//echo $Consulta3;

$Consulta4 = "select idProyecto, descproyecto, sum(horas) as horas, sum(porcentaje) as porcentaje, sum(total) as total,
sum(horasacumulada) as HorasAcumulada, sum(PorcentajeAcumulada) as PorcentajeAcumulada, sum(totalacumulada) as totalacumulada from listhoratrabajador group by idproyecto, descproyecto";

		//echo $Consulta4;				
				 		mysqli_query($con, $consulta);
				 		mysqli_query($con, $Consulta1);
				 		mysqli_query($con, $Consulta2);
				 		mysqli_query($con, $Consulta3);
				 		$sql = mysqli_query($con, $Consulta4);
											
						if(mysqli_num_rows($sql) == 0){
							echo '<tr><td colspan="8">No hay datos.</td></tr>';
						}else{							
							
							$totalHoras = 0;
							$Costo = 0;
							// for($x = 0; $x < count($rows1); $x++) {
							// 	$totalHoras=$totalHoras+(float)$rows1[$x]["horas"];
							// 	$Costo = $rows1[$x]["horas"];
							// }
							echo "<div class=\"table-responsive\"> <table width=\"70%\" class=\"table-striped table-bordered table-hover\" data-page-length=\"20\">
							<thead>
								<tr>
									<th colspan=\"1\"></th>
									<th colspan=\"3\" style=\"text-align: center;\">Actual</th>
									<th colspan=\"1\" style=\"padding: 4px;\"></th>
									<th colspan=\"3\" style=\"text-align: center;\">Acumulado</th>
								</tr>
								<tr>
									<th colspan=\"1\" >PROYECTO</th>
									<th colspan=\"1\" >HORAS</th>
									<th colspan=\"1\" >PORC</th>";

									if($idRol==2)
										echo "<th colspan=\"1\" >TOTAL $</th><th colspan=\"1\" style=\"text-align: center;\"></th>";
								echo "<th colspan=\"1\" >HORAS</th>
									  <th colspan=\"1\" >PORC</th>";
								if($idRol==2)
										echo "<th colspan=\"1\" >TOTAL $</th>";
								echo "	</tr></thead><tbody>";
							$vPorcentaje = 0;
							while ($row = mysqli_fetch_assoc($sql)) { 
										 	echo "<tr>
							 			<th >".$row['descproyecto']."</th>
							 			<td >".number_format($row['horas'], 0,",",".")."</td>
							 			<td >".number_format($row['porcentaje'], 2,",",".")."  %</td>";
							 			//$vPorcentaje = $vPorcentaje + ($rows1[$x]['horas']*100/$totalHoras);
							 			if($idRol==2)
							 				echo "<td align=\"right\">$".number_format(($row['total']), 0,",",".")." </td>";
							 			
							 			echo "
							 			<td > </td>
							 			<td >".number_format($row['HorasAcumulada'], 0,",",".")."</td>
							 			<td >".number_format($row['PorcentajeAcumulada'], 2,",",".")."  %</td>";
							 			if($idRol==2)
							 				echo "<td align=\"right\">$".number_format(($row['totalacumulada']), 0,",",".")." </td>";
							 echo "</tr>";
									}
							// for($x = 0; $x < count($rows1); $x++) {
							// 	echo "<tr>
							// 			<th >".$rows1[$x]['nombre']."</th>
							// 			<td >".number_format($rows1[$x]['horas'], 0,",",".")."</td>
							// 			<td >".number_format($rows1[$x]['horas']*100/$totalHoras, 2,",",".")."  %</td>";
							// 			$vPorcentaje = $vPorcentaje + ($rows1[$x]['horas']*100/$totalHoras);
							// 			if($idRol==2)
							// 				echo "<td align=\"right\">$".number_format((($rows1[$x]['horas']*100/$totalHoras)*$rows1[$x]['sueldomesv'])/100, 0,",",".")." </td>";
							// echo "</tr>";
							// }
							//$vPorcentaje = $vPorcentaje + 1.5;
							// echo "<tr>
							// <th >Total</th>
							// <th >".number_format($totalHoras, 0,",",".")."</th>
							// <th >".number_format($vPorcentaje, 2,",",".")." %</th>";
							// if($idRol==2)
							// 	echo "<th style=\"text-align:right\">$".number_format($Sueldo,0,",",".")." </th>";
							// echo "</tr>";
							// echo "</tbody></table></div>";
					    	// $row = mysqli_fetch_assoc($sql);
						}		
					}		
				?>

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
		<p>&copy; Sistemas Web <?php echo date("Y");?></p>
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

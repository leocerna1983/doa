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
	$columns   = 1 ; 

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

		function sumar_dia_valor ($i, $proyectos,$proyeHoras, $CantidadHoras){
			$suma =0;

			for($x = 0; $x < count($proyectos); $x++) {
				$cadena = explode("*", $proyeHoras[$x]);
					for($j = 0; $j < count($cadena)-1; $j++) { 
						$dia = explode("/",  $cadena[$j]);
						if ('0'.$i ==  $dia[0]) { 
							$suma = $suma + ($dia[1]*($dia[2]/$CantidadHoras));
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
	 	    <h2 style="margin-top:57px;">Horas Hombre por Proyecto 2</h2>
			<hr/>
			<form class="form-inline" method='GET' enctype='multipart/form-data'  id='formId'>
				<div class="row">
					<div class="col-md-3 col-xs-3"><b>Proyecto: </b>
						<?php
							if($proyecto){
								$row_proyecto = mysqli_fetch_array(mysqli_query($con,"SELECT nombre FROM proyectos  WHERE idProyecto='$proyecto'"));
								echo $row_proyecto['nombre']; 
							}else{
								echo 'Seleccione Proyecto';
							}
						?>
					</div>
					<div class="col-md-3 col-xs-3">
						<select  id="proyecto" name="proyecto" class="form-control" >
								<option value="">Selecciona el Proyecto</option>
					
									<?php				
										$proy= mysqli_query($con,"SELECT *  FROM proyectos p ");
										while ($row_proyecto = mysqli_fetch_array ($proy))
										{ ?>
											<option value="<?php echo ($row_proyecto ['idProyecto'])?>" 
											 	<?php  if($proyecto == $row_proyecto ['idProyecto'] ){ echo 'selected'; } ?>>
														<?php echo $row_proyecto ['nombre']; ?>
											</option>
								<?php }?>
						</select>
					</div>
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
			        $consulta = "SELECT ch.idUsuario, us.nombres as nombreUser, pt.nombre, ch.idControlh, sum(ch.horas) horas, ch.fecha_asignacion , IFNULL(sm.sueldo, 0)  as sueldomesv, ch.idProyecto
									 FROM controlhoras ch 
									 INNER JOIN proyectos pt  ON pt.idProyecto = ch.idProyecto
									 INNER JOIN usuarios us  ON us.idUsuario =  ch.idUsuario
									 left join sueldomes sm on us.idusuario = sm.idUsuario
									 			and sm.mesanio = '$filter'
									 WHERE ch.idProyecto ='$proyecto' and 
									 MONTH(ch.fecha_asignacion) = $mes AND YEAR(ch.fecha_asignacion) = $ano
									 GROUP BY  ch.fecha_asignacion, pt.nombre, nombreUser, sueldomesv, ch.idProyecto
									 ORDER BY nombreUser, ch.idProyecto ASC";
			 		$sql = mysqli_query($con, $consulta);
				//echo $consulta;
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
							<th colspan="1" ></th>
								<?php
									//$indDia = 1;
									$fecha = explode("-", $filter);	
																	
									$mes = $fecha[0]==0?date('m'):$fecha[0];
									$ano = isset($fecha[1])?$fecha[1]:date('Y');
									$fechaaux = new DateTime("1-".$mes."-".$ano."");	
									$indDia = date('w', strtotime($fechaaux->format('Y-m-d')));
									if($indDia==0)
										$indDia  = 7;
									for ($i = 1; $i <= $meses; $i++){ 
										?> <th style="text-align: center; font-weight:bold;font-size:12px"><?php echo $dias[$indDia]; ?></th> 
											<?php
												if ($indDia ==7) 
													$indDia = 0;
												
										$indDia++;		
									}
									?>
							<th style="text-align: center;font-size:12px"></th>
							<?php 
											if($idRol==2)
												echo "<th style=\"text-align: center;font-size:12px\"></th>"; ?>
						</tr>
						<tr>
							<th ></th>
							<?php
								for ($i = 1; $i <= $meses; $i++){
								?> <th style="text-align: center;font-size:12px"><?php echo $i ?></th> <?php
								}
							?>
							<th style="text-align: center;font-size:12px">Total Mes</th>
							<?php 
								if($idRol==2)
									echo "<th style=\"text-align: center;font-size:12px\">Costo empresa</th>";	
							?>
						</tr>
					</thead>
					<?php	
					$TotalCostoEmpresa = 0;
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
										$nombreUser =  $rows[$x]['nombreUser'];		
										$proye[$x] = $nombreUser;
										$cadena="";
										$cont = 0;
								
										$cont = contar_valores($rows, $rows[$x]['nombreUser']);

										for($i = 0; $i <= $cont-1; $i++) { 
										   $dia = date("d", strtotime(@$rows[$x]['fecha_asignacion']));
										   //$hora = explode(".", @$rows[$x]['horas']);
										   $hora = $rows[$x]['horas'];
										   $cadena =  $cadena.$dia.'/'.$hora.'/'.$rows[$x]['sueldomesv'].'/'.$rows[$x]['idUsuario'].'*';									   
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
										$IdUsuario = 0;
										$HorasTotalesUsuario = 0;
										 for ($i = 1; $i <= $meses; $i++){ ?>
											<td  style="text-align: center; vertical-align:center; color:red; font-weight: bold;font-size:12px"> 
												<?php 
												$totalMes = 0; 
													$cadena = explode("*", @$proyeHoras[$x]);													
												 	 for($j = 0; $j < count($cadena)-1; $j++) { 
														 $dia = @explode("/",  $cadena[$j]);							
														 //echo $dia[1].'  '.$dia[2];
														 $totalMes = $totalMes + $dia[1];
														 //$ValorUsuario = $ValorUsuario + (($dia[2]/$CantHrs)*$dia[1]);
														 $ValorUsuario = $dia[2];
														 $IdUsuario = $dia[3];
														 if ('0'.$i ==  $dia[0]) 
															 echo number_format($dia[1], 0, ",",".");
													  }
												?>		
											</td> 
										<?php }?>	
										<td style="text-align: center;font-size:12px; background-color:#eee "><?php echo number_format($totalMes, 0,",",".") ?></td>
										<?php 
										$TotalHorasUsuario  = 0;
											if($idRol==2)
											{
												$fecha = explode("-", $filter);
												$mes = $fecha[0];
												$ano = $fecha[1];
												$Cons = "SELECT sum(ch.horas) as horas, IFNULL(sm.sueldo, 0)  as sueldomesv, sum(case when pt.idProyecto = '$proyecto' then ch.horas end) horasproyecto
													FROM controlhoras ch
													INNER JOIN proyectos pt  ON pt.idProyecto = ch.idProyecto
													INNER JOIN usuarios us  ON us.idUsuario =  ch.idUsuario
													left join sueldomes sm on us.idusuario = sm.idUsuario
													and sm.mesanio = '".$filter."'
													WHERE ch.idusuario = '".$IdUsuario."' AND
													MONTH(ch.fecha_asignacion) = ".$mes." AND YEAR(ch.fecha_asignacion) = ".$ano."";
													$sql = mysqli_query($con, $Cons);
													$row = mysqli_fetch_assoc($sql);
													$TotalHorasUsuario= $row['horas'];
												echo "<td style= \"text-align: center;font-size:12px; background-color:#eee \">$"; ?><?php 

												$porcentaje = $totalMes*100/$TotalHorasUsuario;

												echo number_format(($ValorUsuario*$porcentaje)/100, 0,",","."); 
												//echo $porcentaje;
											}
										if($TotalHorasUsuario>0)
											$TotalCostoEmpresa = $TotalCostoEmpresa + (($ValorUsuario*$totalMes)/$TotalHorasUsuario);

										?>
										<?php 
											if($idRol==2)
												echo "</td>";
											?>

									</tr>
								   <?php } ?>

									<!--  HORAS-->
									<tr>
										<td style="vertical-align:right;font-weight: bold;font-size:12px"><b>HORAS</b></td> 
											<?php 
											
											   $totalGeneral=0;
											   for ($i = 1; $i <= $meses; $i++){ ?>
												<td style="vertical-align:center;font-weight: bold;font-size:12px"> 
												  <?php 
												      $totalDia=0;
													    $totalDia = sumar_dia($i, $proyectos, $proyeHoras);
													    echo number_format($totalDia, 0,",",".");
													   $totalGeneral = $totalGeneral + $totalDia;
													?>	
												</td>
											<?php } ?>
										<td style="text-align: center;font-size:12px; background-color:#eee "><?php echo number_format($totalGeneral, 0,",",".") ?></td>
										<?php 
											if($idRol==2)
												echo "<td style=\"text-align: center;font-size:12px; background-color:#eee \">$".number_format($TotalCostoEmpresa,0,",",".")."</td>"; ?>   
									</tr> 	
									<!--  VALOR -->
									<?php 
									if($idRol==0)
									{
									echo "<tr>
										<td style=\"vertical-align:right;font-size:12px\"><b>VALOR</b></td>" ;}
										?>
										<?php 
											if($idRol==0)
											{		
											   for ($i = 1; $i <= $meses; $i++){ ?>
												<td style="vertical-align:center;font-weight: bold;font-size:12px"> 
												  <?php 
													 //echo $totalDia=0;
												  	$totalDia = sumar_dia_valor($i, $proyectos, $proyeHoras, $CantHrs);
												  	echo number_format($totalDia, 0,",",".");
													?>	
												</td>
											<?php } }?>  
										<?php 
									if($idRol==0)
									{
									echo "<td style=\"text-align: center;font-size:12px; background-color:#eee \"></td>
										<td style=\"text-align: center;font-size:12px; background-color:#eee \">$"; }?>
										<?php 
										if($idRol==2)
											{	 } ?>
										<?php 
									if($idRol==0)
									{
									echo "</td> 	
									</tr>"; }?>
							</tbody>
					<?php
					     } 
					   }
					?>		
				</table>
		    </div>	 
		    <br/>
		    <br/>
		    <?php
						if($filter){
						    $fecha = explode("-", $filter);
							$mes = $fecha[0]==0?date('m'):$fecha[0];
							$ano = $fecha[1]==0?date('Y'):$fecha[1];
							$fechaaux = new DateTime("1-".$mes."-".$ano."");	
							$meses = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
						    /*$consulta = "SELECT  usuarios.idusuario,  usuarios.nombres,  sum(ch.horas) as  horas , 	IFNULL(sm.sueldo, 0)  as sueldomesv
								FROM controlhoras ch INNER JOIN proyectos pt  ON pt.idProyecto = ch.idProyecto
								INNER JOIN usuarios ON usuarios.idusuario = ch.IDUSUARIO
								left join sueldomes sm on usuarios.idusuario = sm.idUsuario
									 			and sm.mesanio = '$filter'
								WHERE 
								ch.idproyecto = '$proyecto' and 
								MONTH(ch.fecha_asignacion) = $mes AND YEAR(ch.fecha_asignacion) = $ano
								GROUP BY  usuarios.idusuario, usuarios.nombres, sueldomesv
								HAVING sum(ch.horas)>0
								ORDER BY pt.nombre ASC";*/
								$consulta = "SELECT us.nombres,  sum(ch.horas) horas, IFNULL(sm.sueldo, 0)  as sueldomesv, ifnull( sum(case when pt.idProyecto = '$proyecto' then ch.horas end), 0) horasproyecto
									FROM usuarios us
									left JOIN controlhoras ch ON us.idUsuario =  ch.idUsuario
									left JOIN proyectos pt  ON pt.idProyecto = ch.idProyecto
									left join sueldomes sm on us.idusuario = sm.idUsuario
									and sm.mesanio = '$filter'
									WHERE MONTH(ch.fecha_asignacion) = $mes AND YEAR(ch.fecha_asignacion) =  $ano
									group by us.nombres";		
									//echo 			$consulta;						 					//echo $consulta;		
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
								$totalHoras=$totalHoras+$rows1[$x]["horasproyecto"];
								$Costo = $rows1[$x]["horas"];
							}
							echo "<div class=\"table-responsive\"> <table width=\"50%\" class=\"table-striped table-bordered table-hover\" data-page-length=\"20\">
							<thead>
								<tr>
									<th colspan=\"1\" >USUARIO</th>
									<th colspan=\"1\" >HORAS</th>";

									if($idRol==2)
									{									
									echo "<th colspan=\"1\" >TOTAL $</th>";}

								echo "	</tr></thead><tbody>";

							for($x = 0; $x < count($rows1); $x++) {
								echo "<tr>
							<th >".$rows1[$x]['nombres']."</th>
							<td >".number_format($rows1[$x]['horasproyecto'], 0,",",".")."</td>";
							$porcentaje = 0;
							if($rows1[$x]['horasproyecto']!=0)
								$porcentaje = $rows1[$x]['horasproyecto']*100/$rows1[$x]['horas'];
							if($idRol==2)
							{																
								echo "<td align=\"right\">$".number_format((($rows1[$x]['sueldomesv']*$porcentaje)/100), 0,",",".")."</td>";
								}

							echo "</tr>";
							//$TotalMonto = $TotalMonto + (($rows1[$x]['sueldomesv']*$rows1[$x]['horasproyecto'])/$rows1[$x]['horas']);

								//if($porcentaje!="0")								
									$TotalMonto = $TotalMonto +  (float)(($rows1[$x]['sueldomesv']*$porcentaje)/100);
								//else
								//	$TotalMonto = 0;
							}
							echo "<tr>
							<th >Total</th>
							<th >".number_format($totalHoras, 0,",",".")."</th>";
							if($idRol==2)
							{									
							echo "<th style=\"text-align: right;\">$".number_format($TotalMonto,0,",",".")."</th>";
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

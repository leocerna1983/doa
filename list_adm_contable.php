<?php
	require_once("conexion.php");
	session_start();
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
		.content {
			margin-top: 80px;
		}
	</style>

</head>
<body>
<?php  
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
		?>
	<div class="container">
		<nav class="navbar navbar-default navbar-fixed-top">
			<?php include('nav_home.php');?>
		</nav>	
	
	  <div class="content">
		<nav class="navbar navbar-default" style="margin-top:97px;border-color:#ffffff !important"> 
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav ">
						<li class="active"><a href="list_proyecto.php">Horas Hombre por Trabajador</a></li>
						<li ><a href="add_proyecto.php">Costa Horas Hombre</a></li>
					</ul>
				</div>
			</nav>
			<h2>Lista de proyecto</h2>
			<hr/>
			<form class="form-inline" method='GET' enctype='multipart/form-data'  id='formId'>
				<div class="row">
				
					<div class="col-xs-2 col-md-3"><b>Usuario: </b><?php echo $nombreUsuario  ?> </div>
					<div class="col-xs-2 col-md-3">
						<select  id="usuario" name="usuario" class="form-control" >
									<option value="0">Selecciona el usuario</option>
										<?php $usuario = (isset($_GET['usuario']) ? strtolower($_GET['usuario']) : NULL);  ?>
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
					<div class="col-xs-2 col-md-3">
						<div class="form-group">
								<div class='input-group date' >
								<?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);  ?>
									<input type="text" name="filter"  class="input-group date form-control" data="02-2012" date-format="mm-yyyy" placeholder="00-0000" required>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
											</span>
										</span>
								</div>
						</div>
					</div>
					<div class="col-xs-2 col-md-3">
						<button type="submit"  class="btn btn-sm btn-primary btn-xs">
								<span class="glyphicon glyphicon-search"></span>
						</button>
					</div>
				</div>
			</form>
			<br/>

			<?php
				if($filter){
				   $consulta = "SELECT  pt.nombre, ch.idControlh, sum(ch.horas) horas, ch.fecha_asignacion 
									 FROM controlhoras ch INNER JOIN proyectos pt  ON pt.idProyecto = ch.idProyecto
									 WHERE ch.idUsuario='$usuario'
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
						<th colspan="1" class="filaReserva">PROYECTO</th>
							<?php
								$indDia = 1;
								for ($i = 1; $i <= $meses; $i++){
									?> <th style="text-align: center;"><?php echo $dias[$indDia] ?></th> 
										<?php
											if ($indDia ==7) 
												$indDia = 0;
											
									$indDia++;		
								}
							?>
						</tr>
						<tr>
							<th></th>
							<?php
								for ($i = 1; $i <= $meses; $i++){
										?> <th style="text-align: center;"><?php echo $i ?></th> <?php
								}
							?>
						<tr>
					</thead>
					<?php	
					   if($filter){
						?>
							<tbody>
								<?php 
							
									while ($row = mysqli_fetch_assoc($sql)) { 
										$rows[] = $row; 
									} 
		
							  		for($x = 0; $x < count($rows); $x++) { ?>
										<td style="text-align: center; vertical-align:center; color:red"> 
											<?php echo $rows[$x]['nombre'] ?>
										</td> 

										<?php 
											$aux = $rows[$x]['nombre'];			
											for ($i = 1; $i <= $meses; $i++){ 
												echo '<td> </td>';	
												while ($aux == @$rows[$x]['nombre']) {
													$dia = date("d", strtotime(@$rows[$x]['fecha_asignacion']));
										
													$hora = explode(".", @$rows[$x]['horas']);
													if ($dia == $i){ 
													//	echo '<td>'.$hora[0].' d'.$dia.' i'.$i.'</td>';
													
												    	echo '<td>'.$hora[0].'</td>';
													}
													if ($x <= count($rows)){
														$x++;
													}
													$i++;		
													echo '<td> </td>';								
												}		
													if ($aux != @$rows[$x]['nombre'])
														$x--;
											}//fin para			
										?>
									</tr>
								   <?php } ?>
								   


								   
								<!--  Sumatorioa totales -->
								<?php for ($o = 1; $o<= 1; $o++){ ?>
									<tr>
										<td style="text-align: center; vertical-align:center"> <?php echo 'TOTAL POR DIA'  ?></td> 
													<?php for ($i = 1; $i <= $meses; $i++){ ?>
														<td> 
															<?php for ($j = 0; $j <=0; $j++){ ?>
																<table width="100%"  cellspacing="0">          
																	<?php 
																	for ($k = 0; $k < $columns; $k++){ ?>     
																			<tr style="height: 100%;">
																				<td style="text-align:center;width:10px;">
																					<label style="text-align:center;font-size:10px;" >
																						<?php 
																							echo '2' 
																						?>
																					</label>
																				</td>
																			</tr>    
																	<?php } ?> 
																</table>  
															<?php } ?>     
														</td>
													<?php } ?>   
									</tr> 
								<?php } ?> 	
							</tbody>
					<?php }?>		
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


		$(document).ready(function() {
			$('#date').datepicker({
				onSelect : function (dateText, inst) {
					$('#formId').submit(); // <-- SUBMIT
			}});
		});

		$('.date').datepicker({
			format: "mm-yyyy",
			startView: "months", 
			minViewMode: "months"
		})
	</script>
</body>
</html>

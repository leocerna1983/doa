<?php
	require_once("conexion.php");	

	if ( isset( $_SESSION['idUsuario'] ) ) {
			$consulta= "SELECT nombres, ap_paterno, ap_materno, idUsuario, idRol  FROM usuarios WHERE idUsuario=".$_SESSION['idUsuario'] ."";
			$sql = mysqli_query($con, $consulta);
			$row = mysqli_fetch_assoc($sql);
			$nombreUsuario= $row['nombres'].' '.$row['ap_paterno'].' '.$row['ap_materno'] ;
			$idUsuario = $row['idUsuario'];		
			$idRol = $row['idRol'];			 
	}
	else
	{
		$nombreUsuario= "";
		$idUsuario = 0;		
		$idRol = 0;			 
	}

	
?>

<div class="container">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
			<a class="navbar-brand visible-xs-block visible-sm-block" href="">Inicio</a>
	</div>
	<div id="navbar" class="navbar-collapse collapse">
		<ul class="nav navbar-nav">
			<li> 
				<a href="index.php"> 
					<img src="img/logo_doa.png"> 
				</a> 
			</li>
			
			<?php 
			if($idRol==1 || $idRol==3)
			{
				echo "<li class=\"justify-content-end\">
				<a href=\"cuadro.php\">Ingreso Horas
				</a>
			</li>";
			} ?>
			
			<?php 
			if($idRol!=1 && $idRol!=0)
			{
				echo "<li class=\"dropdown\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" 	aria-expanded=\"false\">Maestros <span class=\"caret\"></span></a><ul class=\"dropdown-menu\">";

					if($idRol!=2)
					{
					echo "<li><a href=\"list_proyecto.php\">Proyectos</a>
					</li>";}
					
					echo "<li>
						<a href=\"list_usuario.php\">Usuarios
						</a>
					</li>";	
					
					if($idRol!=2)
					{
					
					echo "<li>
						<a href=\"list_cliente.php\">Clientes
						</a>
					</li>		
					<li>
						<a href=\"list_categoria.php\">Categorias
						</a>
					</li>
					<!--<li>
						<a href=\"list_centrocosto.php\">Centro de Costo
						</a>
					</li>-->
					<li>
						<a href=\"list_cargo.php\">Cargo
						</a>
					</li>
					<li>
						<a href=\"edit_configuracion.php\">Configuración
						</a>
					</li>";
				}
				echo "</ul>
			</li>"; } ?>

			<?php 
			if($idRol!=1  && $idRol!=0) 
			{
				echo "<li class=\"dropdown\" >
					<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Reportes <span class=\"caret\"></span>
					</a>
					<ul class=\"dropdown-menu\">
						<li>
							<a href=\"list_horas_trabajador.php\">Horas Hombre por Trabajador
							</a>
						</li>	
						<li>
							<a href=\"list_horas_trabajador1.php\">Horas Hombre por Trabajador (Modificacion)
							</a>
						</li>	
						<li>
							<a href=\"list_costo_hombre.php\">Horas Hombre por Proyecto
							</a>
						</li>	
						<li>
							<a href=\"list_costo_hombre1.php\">Horas Hombre por Proyecto (Modificacion)
							</a>
						</li>	
						<li>
							<a href=\"list_Informe_Proyecto.php\">Resúmenes
							</a>
						</li>
					</ul>
				</li>";
				}
			?>
		</ul>
		<ul class="nav navbar-nav navbar-right">
		<?php
			//session_start();
			if ( isset( $_SESSION['idUsuario'] ) ) {
		?>
		<li><a href="#"><?php echo $_SESSION['nombre'];?></a> </li>
		<?php
} else { ?>
    <li></li>
<?php }
?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Usuario <span class="caret"></span></a>
						<ul class="dropdown-menu">							
							
							<?php
							if (!isset( $_SESSION['idUsuario'])) {
							?>
								<li><a href="login.php">Iniciar Session</a></li>								
							<?php
							} ?>	
							<?php
							if ( isset( $_SESSION['idUsuario'] ) ) {
							?>
							<li><a style="cursor: pointer;" onclick="CambiarClave(<?php echo $_SESSION['idUsuario']; ?>);" >Cambio de Clave</a></li>								
							<?php
							} else { ?>
							    <li></li>
							<?php }
							?>
							<li role="separator" class="divider"></li>
							<li><a href="logout.php">Cerrar Session</a></li>
						</ul>
				</li>
		</ul>
	</div> 
	<!--/.nav-collapse -->
</div>

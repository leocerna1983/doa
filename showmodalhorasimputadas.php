<table class="table table-striped table-hover" >
<thead>
	<tr>
	    <th>No</th>
		<th>Codigo</th>
		<th>Proyecto</th>
		<th>Categoria</th>
		<th>Tarea</th>
        <th>Horas</th>					
        <th>Acciones</th>
	</tr>
</thead>

<?php 
	include("conexion.php");
	$idusuario = $_POST['idusuario'];	
	//$idproyecto = $_POST['idproyecto'];	
	$fecha = $_POST['fecha'];				
					$consulta = "SELECT ch.idUsuario, ch.fecha_asignacion, ch.idControlh, cat.nombre as NombreCategoria, t.nombre as NombreTarea, ch.Horas, ch.idCategoria, ch.idTarea, p.idproyecto, p.nombre as nombreproyecto FROM controlhoras  ch
					  inner join categorias cat on ch.idCategoria = cat.idCategoria
					  inner join tareas t on ch.idTarea = t.IdTarea
					  inner join proyectos p on p.idproyecto = ch.idproyecto
					  where ch.fecha_asignacion = '$fecha' and ch.idusuario = $idusuario ";
					$sql = mysqli_query($con, $consulta);

				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td>'.$no.'</td>
							<td>'.$row['idControlh'].'</td>
							<td>'.$row['nombreproyecto'].'</td>
							<td>'.$row['NombreCategoria'].'</td>
                            <td>'.$row['NombreTarea'].'</td>
                            <td>'.$row['Horas'].'</td>							
							';
						echo '
							
							<td>
								<a title="Editar datos" onclick="SetPrimeraPestana('.$row['idControlh'].','.$row['idproyecto'].','.$row['idCategoria'].','.$row['idTarea'].', \''.$row['NombreTarea'].'\','.$row['Horas'].')" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<a title="Eliminar dato" onclick="EliminarEvento('.$row['idControlh'].', 0)" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
								
							</td>
						</tr>';
						$no++;
					}
				}
				?>	
</table>
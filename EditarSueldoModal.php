<table class="table table-striped table-hover">
<thead>
	<tr>
		<th>No</th>
	    <th>Mes Año</th>
		<th>Sueldo</th>
        <th>Acciones</th>
	</tr>
</thead>
<?php 
	include("conexion.php");
	$idusuario = $_POST['idusuario'];	
					$consulta = "select idusuario,  mesanio, sueldo, idsueldomes from sueldomes 
					where idusuario = $idusuario ";
					$sql = mysqli_query($con, $consulta);
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td>'.$no.'</td>
							<td>'.$row['mesanio'].'</td>
							<td>'.$row['sueldo'].'</td>
							';
						$MesAnio = $row['mesanio'];
						echo '<td>
								<a title="Editar Sueldo" onclick="SetEdicionSueldo('.$row['idsueldomes'].','.$row['idusuario'].',\''.$MesAnio.'\','.$row['sueldo'].')" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
							</td>
						</tr>';
						$no++;
					}
				}
				?>	
</table>
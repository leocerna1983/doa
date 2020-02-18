<?php
	include("conexion.php");
?>
<?php
session_start();
if ( ! empty( $_POST ) ) {
    if ( isset( $_POST['usuario'] ) && isset( $_POST['clave'] ) ) {
        $proyecto= mysqli_query($con,"SELECT * FROM usuarios WHERE e_mail = '".$_POST['usuario']."'");
        if($row_proyecto = mysqli_fetch_array ($proyecto))
        {
    		if($row_proyecto["password"]==md5($_POST['clave']))
    		{
    			$_SESSION['idUsuario'] = $row_proyecto["idUsuario"];
    			$_SESSION['nombre'] = $row_proyecto["nombres"];
    			$_SESSION['idrol'] = $row_proyecto["idrol"];
    			header("Location: ".$url."index.php");
    		}
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DOA. Control de Horas</title>

	<!-- Bootstrap 
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
	<!-- <link rel="stylesheet" href="css/all.css">-->

	<link href = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css"
         rel = "stylesheet">
      <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
      <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

	<!-- jQuery UI library -->
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">


	<!--<link rel="stylesheet" href="css/demos.css">-->
	<style>
	.ui-autocomplete-loading {
		background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
	}
	</style>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>	
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
		.content {
			margin-top: 80px;
			text-align: center;
		}

		.centrado{
			text-align: center;
		}
		.col-centered{
			float: none;
			margin: 0 auto;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<?php include('nav_home.php');
		?>
	</nav>
	<div class="container">
		<div class="col-sm-6" style="width: 600px; margin-left: 250px; margin-top: 100px;"	>
			<div class="jumbotron">
				<div class="container">
					<div class="form-group">
						<form action="/doa/login.php" method="post">
					        <h2 class="text-center">Iniciar Session</h2>       
					        <div class="form-group">
					            <input type="text" name="usuario" class="form-control" placeholder="Usuario" required="required">
					        </div>
					        <div class="form-group">
					            <input type="password" name="clave" class="form-control" placeholder="Clave" required="required">
					        </div>
					        <div class="form-group">
					            <button type="submit" class="btn btn-block btn-block">Iniciar Session</button>
					        </div>					        
					    </form>
					</div>	
				</div>
			</div>
			
			<!-- <form class="form-horizontal">
				<div class="form-group input-group">
					<input type="email" name="email" class="form-control">
				</div>
			</form> -->
		</div>
		
	</div>
	
	<center>
	<p>&copy; Sistemas Web Doa Control de Horas © <?php echo date("Y");?> by www.doaarq.cl</p>
	<p>estudio@doaarq.cl | Los Militares 5890, Of 1504, Piso 15, Las Condes, Scl.</p>
		</center>
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>-->
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			    $("#skill_input").autocomplete({
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
			        minLength: 2,
			        select: function( event, ui ) {
			            alert(ui.item.id);
			            $("#skill_input").val(ui.item.id);
			        }
			    });
	})
	</script>
</body>
</html>

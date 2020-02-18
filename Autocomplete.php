
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DOA. Control de Horas</title>

	<!-- Bootstrap 
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
	<!-- <link rel="stylesheet" href="css/all.css">-->

	

	<!-- jQuery UI library -->
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css">


	<!--<link rel="stylesheet" href="css/demos.css">-->
	<style>
	.ui-autocomplete-loading {
		background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
	}
	</style>
	
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>	-->
	<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
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
	
	
        	<div class="ui-widget">
        		<input id="skill_input" class="ui-autocomplete-input"/>
        	</div>
    
	<center>
	<p>&copy; Sistemas Web Doa Control de Horas © <?php echo date("Y");?> by www.doaarq.cl</p>
	<p>estudio@doaarq.cl | Los Militares 5890, Of 1504, Piso 15, Las Condes, Scl.</p>
		</center>
	<!-- jQuery library -->
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<!--<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap.js"></script>-->
	<script type="text/javascript">
		$(document).ready(function(){
			    var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];
			    $("#skill_input").autocomplete({
			        source: availableTags
			        /*function( request, response ) {
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
				      }*/,
			        minLength: 2,
			        select: function( event, ui ) {
			            //alert(ui.item.id);
			            //$("#skill_input").val(ui.item.id);
			        }
			    });
	})
	</script>
</body>
</html>

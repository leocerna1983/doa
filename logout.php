<?php
	session_start();
	session_destroy();
	unset($_SESSION["idUsuario"]);

	if ( isset( $_SESSION['idUsuario'] ) ) {

	} else {    
    	//header("Location: http://localhost:8090/doa/login.php");
    	header("Location: ".$url."login.php");
	}
?>
<?php
include("conexion.php");
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';


	$mes = date("m");
	$anio = date("Y");
	
	if($mes==1)
	{
		$mes = 12;
		$anio = $anio - 1 ;
	}
	else
	{
		$mes = ($mes*1) - 1;
	}


$consulta = "insert into sueldomes(idusuario,mesanio, sueldo)
select tabla1.idusuario, tabla1.mesanio, tabla1.sueldo from (select idusuario, '".date("m")."-".date("Y")."' as mesanio, sueldo from sueldomes
where mesanio = '".$mes."-".$anio."') tabla1
left join sueldomes tabla2 on tabla1.idusuario = tabla2.idusuario and tabla1.mesanio = tabla2.mesanio
where tabla1.mesanio = '".date("m")."-".date("Y")."' and tabla2.idusuario is null";

	mysqli_query($con, $consulta)




					
?>
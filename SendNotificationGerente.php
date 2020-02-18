<?php
include("conexion.php");
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$consulta = "select usuarios.idusuario, usuarios.nombres, usuarios.e_mail, count(controlhoras.idusuario) as cantidad from usuarios
	left join controlhoras on controlhoras.idUsuario = usuarios.idUsuario
and from_days(to_days(fecha_actualizacion))= from_days(to_days(now()))
where usuarios.idrol = 1
group by usuarios.idusuario, usuarios.nombres, usuarios.e_mail;";

$html = "<table width=\"200\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"wrapper\" >
  <tr>
    <td align=\"center\" valign=\"top\">

      <table width=\"200\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"container\">
        <tr bgcolor=\"#696ef0\">
          <td width=\"180\" class=\"mobile\" align=\"center\" valign=\"top\">
            Usuario
          </td>
        </tr>";
$sql = mysqli_query($con, $consulta);
if(mysqli_num_rows($sql) == 0){
	echo '<tr><td colspan="8">No existen Categorías.</td></tr>';
}else{
	while($row = mysqli_fetch_assoc($sql)){
		
		if($row["cantidad"]==0)
		{
			$html =$html. "<tr>
					          <td width=\"300\" class=\"mobile\" align=\"center\" valign=\"top\">
					            ".$row["nombres"]."
					          </td>
					        </tr>";
		}
	}
	$html =$html. "</table>
    </td>
  </tr>
</table>";
}


$consulta = "select usuarios.idusuario, usuarios.nombres, usuarios.e_mail from usuarios
			 where usuarios.idrol = 3";


$sql = mysqli_query($con, $consulta);
if(mysqli_num_rows($sql) == 0){
	
}else{
	while($row = mysqli_fetch_assoc($sql)){
		$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->SMTPDebug = 0;
					$mail->setFrom('no-replay@doaarq.cl', 'DOA');
					//$mail->addReplyTo($row['e_mail'], $row['nombres']);
					$mail->SMTPSecure = 'ssl';
					$mail->addAddress($row['e_mail'], $row['nombres']);
					//$mail->addAddress("rcontreras@iitec.cl ", $row['nombres']);
					//$mail->addAddress("rcontreras@iitec.cl", $row['nombres']);
					//$mail->addAddress("leonidas.cerna@gmail.com", $row['nombres']);
					$mail->addCC("lcernas@iitec.cl", "Leonidas Cerna");
					
					$mail->Subject = 'Usuarios sin Registro de horas';
					$mail->Body = "Buenas noches estimado, le recordamos que el dia de hoy no ha realizado el registro de las actividades realizadas";

					$mail->msgHTML($html);
					//$mail->AltBody = 'This is a plain text message body';
					if (!$mail->send()) {
					    echo 'Mailer Error: ' . $mail->ErrorInfo;
					} else {
					    echo 'Message sent!';
					}
	}

}



					
?>
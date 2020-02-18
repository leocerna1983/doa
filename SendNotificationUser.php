<?php
include("conexion.php");
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$consulta = "select usuarios.idusuario, usuarios.nombres, usuarios.e_mail, count(controlhoras.idusuario) as cantidad from usuarios
	left join controlhoras on controlhoras.idUsuario = usuarios.idUsuario
and from_days(to_days(fecha_actualizacion))= from_days(to_days(now()))
where usuarios.idrol = 1
group by usuarios.idusuario, usuarios.nombres, usuarios.e_mail;";


$sql = mysqli_query($con, $consulta);
					if(mysqli_num_rows($sql) == 0){
						echo '<tr><td colspan="8">No existen Categorías.</td></tr>';
					}else{
						while($row = mysqli_fetch_assoc($sql)){
							
							if($row["cantidad"]==0)
							{
								$mail = new PHPMailer;
								$mail->isSMTP();
								$mail->isHTML(true);
								$mail->SMTPDebug = 0;
								$mail->setFrom('no-replay@doaarq.cl', 'DOA');
								$mail->SMTPSecure = 'ssl';
								$mail->addReplyTo($row['e_mail'], $row['nombres']);
								//$mail->addAddress($row['e_mail'], $row['nombres']);
								//$mail->addAddress('rcontreras@iitec.cl', $row['nombres']);
								//$mail->addAddress('leonidas.cerna@gmail.com', $row['nombres']);
								$mail->addCC("lcerna@iitec.cl", "Leonidas Cerna");
								$mail->Subject = 'No registro de Horas';
								$mail->Body = "Buenas noches estimado, le recordamos que el dia de hoy no ha realizado el registro de las actividades realizadas";
								//$mail->AltBody = 'This is a plain text message body';
								if (!$mail->send()) {
								    echo 'Mailer Error: ' . $mail->ErrorInfo;
								} else {
								    echo 'Message sent!';
								}
							}
						}
					}
?>
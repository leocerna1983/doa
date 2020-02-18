<?php

$fecha = new DateTime("1-8-2019");
$fecha->modify('first day of this month');
echo $fecha->format('d/m/Y');
echo date('w', strtotime($fecha->format('Y-m-d'))); 

?>
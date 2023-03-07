<?php
require_once dirname(__FILE__) . '/../BLL/formulario/HojaVidaSSTBLL.php';
require_once dirname(__FILE__) . '\..\BLL\Helpers\Mail\sendMail.php';
require_once dirname(__FILE__) . '/../BLL/configuracion/EmpresaBLL.php';
$hh = new HojaVidaSSTBLL();
$eh = new EmpresaBLL();

$Empresa = $eh->GetEmpresa();
$hojas = $hh->ProximosVencer();
$sh = new sendMail();
$Contenido = "<table border='1'> <tr>"
        . "<th>Numero Extintor</th>"
        . "<th>Sede</th>"
        . "<th>Servicio</th>"
        . "<th>Ubicacion</th>"
        . "<th>Sector</th>"
        . "<th>Fecha Recarga</th>"
        . "<th>Fecha Vencimiento</th>"
        . "<th>Tipo Extintor</th> </tr>";

foreach ($hojas as $o) {
    $Contenido .= "<tr>"
        . "<th>$o->NumeroExtintor</th>"
        . "<th>$o->Sede</th>"
        . "<th>$o->Servicio</th>"
        . "<th>$o->Ubicacion</th>"
        . "<th>$o->Sector</th>"
        . "<th>$o->FechaRecarga</th>"
        . "<th>$o->FechaVencimiento</th>"
        . "<th>$o->ClaseExtintor</th> </tr>";
}
$Contenido .= "</table>";

$sh->EnviarEmail_NotificacionExtintores($Empresa, "Reporte de Extintores proximos a vencer", $Contenido, "ospi89@hotmail.com", "Franklin Ospino");
echo json_encode($hojas);

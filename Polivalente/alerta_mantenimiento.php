<?php

require_once dirname(__FILE__) . "\BLL\Helpers\Mail\sendMail.php";
require_once dirname(__FILE__) . "\BLL\configuracion\EmpresaBLL.php";
require_once dirname(__FILE__) . "\BLL\configuracion\SedeBLL.php";
require_once dirname(__FILE__) . "\BLL\\formulario\MantenimientoPreventivoBLL.php";
require_once dirname(__FILE__) . "\DAL\DB.php";

$Helper = new SedeBLL();
$Lsede = json_decode($Helper->GetAll());

$Helper = new MantenimientoPreventivoBLL();

foreach ($Lsede as $s) {
    $lst = $Helper->GetAllMantenimientoPreventivosBySedeId($s->SedeId);
    if (count($lst) > 0) {
        $tabla = "
                <tr>
                        <th>ID</th>
                        <th>Equipo</th>
                        <th>Marca</th>
                        <th>Serie</th>
                        <th>Modelo</th>
                        <th>Servicio</th>
                        <th>Ubicación</th>
                        <th>Fecha Siguiente Mantenimiento</th>
                        <th>Diferencia Fecha</th>
                </tr>
            ";

        foreach ($lst as $value) {
            $tabla .= "
                    <tr>
                            <th  style='border:1px solid black;'><a href='http://192.168.9.116:8080/Biomedico#/hoja_vida/ficha_tecnica/$value->HojaVidaId' target='_blank'>$value->HojaVidaId</a></th>
                            <td  style='border:1px solid black;'>$value->Equipo</td>
                            <td  style='border:1px solid black;'>$value->Marca</td>
                            <td  style='border:1px solid black;'>$value->Modelo</td>
                            <td  style='border:1px solid black;'>$value->Serie</td>
                            <td  style='border:1px solid black;'>$value->Servicio</td>
                            <td  style='border:1px solid black;'>$value->Ubicacion</td>
                            <td  style='border:1px solid black;'>$value->FechaSiguienteMantenimiento</td>
                            <td  style='border:1px solid black;'>$value->DiferenciaFecha</td>
                    </tr>
                ";
        }
        $He = new EmpresaBLL();
        $Empresa = $He->GetEmpresa();


        $Hs = new sendMail();

        $Hs->EnviarEmail_Alerta($s->Correo,$s->Nombre,$Empresa, $tabla, "Alerta de Mantenimiento Preventivo");
        echo "<table>" . $tabla;
        echo "</table>";
    }
}



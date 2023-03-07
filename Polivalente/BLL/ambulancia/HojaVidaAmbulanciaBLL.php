<?php

require_once dirname(__FILE__) . '/../../DAL/ambulancia/HojaVidaAmbulanciaDAL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';

class HojaVidaAmbulanciaBLL {

    public function GetALLHojas() {
        $Helper = new HojaVidaAmbulanciaDAL();
        return $Helper->GetALLHojas();
    }

    public function EmailMovilesProxVencerSoatTec() {
        $Helper = new HojaVidaAmbulanciaDAL();
        $Usuarios = $Helper->GetUsuariosANotificar();
        $Moviles = $Helper->GetMovilesProxVencerSoatTec();
        $hs = new sendMail();
        $Contenido = '<p>Alertas: </p><br>';
        $filas = "";
//        print_r($Moviles);
        foreach ($Moviles as $value) {
            $filas .= '<tr align="CENTER">
                            <td colspan="4">' . $value->Placa . '</td>
                            <td colspan="2">' . $value->FechaSoat . '</td>
                            <td colspan="2">' . $value->DSoat . '</td>
                            <td colspan="2">' . $value->FechaTecnomecanica . '</td>
                            <td colspan="2">' . $value->DTec . '</td>
                         </tr>';
        }
        $tabla = '<table border="1">
                        <tr align="CENTER">
                           <th colspan="4">PLACA</th>
                           <th colspan="2">FECHA SOAT</th>
                           <th colspan="2">DIF. SOAT</th>
                           <th colspan="2">FECHA TECNOMECANICA</th>
                           <th colspan="2">DIF. TEC.</th>
                        </tr>
                        ' . $filas . '
                    </table>';

        $He = new EmpresaBLL();
        $EmpresaObj = $He->GetEmpresa();
        if(count($Usuarios) > 0){
            foreach ($Usuarios as $u) {
                echo $hs->EnviarEmail_Notificacion($EmpresaObj, "Alerta ambulancias", $Contenido . $tabla, $u->Email, $u->NombreCompleto);
            }
            echo $hs->EnviarEmail_Notificacion($EmpresaObj, "Alerta ambulancias", $Contenido . $tabla, "jhonyamayamed@hotmail.com", "jhony");
        }
        
//        echo $hs->EnviarEmail_Notificacion($EmpresaObj, "Alerta ambulancias", $Contenido . $tabla, "ospi89@hotmail.com", "frank");
//        echo $hs->EnviarEmail_Notificacion($EmpresaObj, "Alerta ambulancias", $Contenido . $tabla, "referencia@cield.com.co", "jakeline");
//        echo $hs->EnviarEmail_Notificacion($EmpresaObj, "Alerta ambulancias", $Contenido . $tabla, "menamedina@hotmail.com", "mena");
//        echo $hs->EnviarEmail_Notificacion($EmpresaObj, "Alerta ambulancias", $Contenido . $tabla, "luisrenecacha@hotmail.com", "frank");
    }

    public function GetHojasByServicio($ServicioId) {
        $Helper = new HojaVidaAmbulanciaDAL();
        return $Helper->GetHojaVidaByServicio($ServicioId);
    }

    public function GetHojasBySedeId($SedeId) {
        $Helper = new HojaVidaAmbulanciaDAL();
        return $Helper->GetHojaVidaBySedeId($SedeId);
    }

    public function GetHojasBySerie($Serie) {
        $Helper = new HojaVidaAmbulanciaDAL();
        return $Helper->GetReporteBySerie($Serie);
    }

    public function GetHojasByHojaVidaId($HojaVidaId) {
        $Helper = new HojaVidaAmbulanciaDAL();
        return $Helper->GetHojaVidaByHojaVidaId($HojaVidaId);
    }

    public function GetNHojaVida() {
        $Helper = new HojaVidaAmbulanciaDAL();
        return $Helper->GetNHojaVida();
    }

    public function CreateHojaVida($list) {
        $Helper = new HojaVidaAmbulanciaDAL();
//        $Hoja = $this->GetHojasBySerie($list->Serie);
//        if ($list->Serie == "N/A" or $Hoja == NULL) {
        return $Helper->CreateHojaVida($this->MAPToArray($list));
//        }
//        return "Ya existe esta hoja de vida en la base de datos";
    }

    public function UpdateEstado($list) {
        $Helper = new HojaVidaAmbulanciaDAL();
        return $Helper->UpdateHojaVida($this->MAPToUpdateEstado($list), $list->HojaVidaId);
    }

    public function UpdateEstadoMovil($list) {
        $Helper = new HojaVidaAmbulanciaDAL();
        return $Helper->UpdateHojaVida($this->MAPToUpdateEstadoMovil($list), $list->HojaVidaId);
    }

    public function UpdateHojaVida($list) {
        $Helper = new HojaVidaAmbulanciaDAL();
//        $Hoja = $this->GetHojasBySerie($list->Serie);
//        if ($list->Serie == "N/A" or $Hoja == NULL) {
        return $Helper->UpdateHojaVida($this->MAPToUpdate($list), $list->HojaVidaId);
//        }
//        return "Ya existe esta hoja de vida en la base de datos";
    }

    public function CountHojaVidas() {
        $Helper = new HojaVidaAmbulanciaDAL();
        return json_encode($Helper->CountHojaVidas());
    }

    public function CountHojaVidas2($UsuarioId) {
        $Helper = new HojaVidaAmbulanciaDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountHojaVidasBySede($value->SedeId)[0]->Total;
        }
        return Array("Total" => $contador);
    }

    public function UpdateFechaHojaVida($list, $HojaVidaId) {
        $Helper = new HojaVidaAmbulanciaDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray2($list), $HojaVidaId);
    }

    public function DeleteHojaVida($HojaVida) {
        $Helper = new HojaVidaAmbulanciaDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray4($HojaVida), $HojaVida->HojaVidaId);
    }

    public function TrasladoHojaVida($list, $HojaVidaId) {
        $Helper = new HojaVidaAmbulanciaDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray3($list), $HojaVidaId);
    }

    public function MAPToArray3($list) {
        $list2 = Array();
        array_push($list2, Array(
            "SedeId" => $list->SedeId,
            "ServicioId" => $list->ServicioId,
            "Ubicacion" => $list->Ubicacion,
            "ModifiedBy" => $list->CreatedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToArray4($list) {
        $list2 = Array();
        array_push($list2, Array(
            "Estado" => 'Inactivo',
            "ModifiedBy" => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToArray2($list) {
        $list2 = Array();
        array_push($list2, Array(
            "ModifiedBy" => $list->CreatedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
            "FechaInstalacion" => $list->Fecha
        ));
        return $list2;
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Placa' => $list->Placa,
            'Linea' => $list->Linea,
            'Marca' => $list->Marca,
            'Modelo' => $list->Modelo,
            'ClaseVehiculo' => $list->ClaseVehiculo,
            'TipoCarroceria' => $list->TipoCarroceria,
            'LicenciaTransito' => $list->LicenciaTransito,
            'Soat' => $list->Soat,
            'FechaSoat' => $list->FechaSoat,
            'FechaTecnomecanica' => $list->FechaTecnomecanica,
            'Cilindrada' => $list->Cilindrada,
            'Color' => $list->Color,
            'Motor' => $list->Motor,
            'Serie' => $list->Serie,
            'Combustible' => $list->Combustible,
            'Capacidad' => $list->Capacidad,
            'Foto' => $list->Foto,
            'CreatedBy' => $list->UserId
        ));

        return $list2;
    }

    public function MAPToUpdateEstadoMovil($list) {
        $list2 = Array();
        array_push($list2, Array(
            'EstadoMovil' => $list->EstadoMovil,
            "ModifiedBy" => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));

        return $list2;
    }

    public function MAPToUpdateEstado($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Estado' => $list->Estado,
            "ModifiedBy" => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));

        return $list2;
    }

    public function MAPToUpdate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Placa' => $list->Placa,
            'Linea' => $list->Linea,
            'Marca' => $list->Marca,
            'Modelo' => $list->Modelo,
            'ClaseVehiculo' => $list->ClaseVehiculo,
            'TipoCarroceria' => $list->TipoCarroceria,
            'LicenciaTransito' => $list->LicenciaTransito,
            'Soat' => $list->Soat,
            'FechaSoat' => $list->FechaSoat,
            'FechaTecnomecanica' => $list->FechaTecnomecanica,
            'Cilindrada' => $list->Cilindrada,
            'Color' => $list->Color,
            'Motor' => $list->Motor,
            'Serie' => $list->Serie,
            'Combustible' => $list->Combustible,
            'Capacidad' => $list->Capacidad,
            'Foto' => $list->Foto,
            "ModifiedBy" => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));

        return $list2;
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

}

<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/SolicitudDAL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require __DIR__ . "/../../vendor/autoload.php";

class SolicitudBLL {

    private function GetTablas($Prefijo) {
        // contruyo las tablas y columnas para la consulta
        $tablas = new stdClass();
        $tablas->sede = "sede";
        $tablas->servicio = "servicio";
        $tablas->hojavida = "hojavida";
        $tablas->reporte = "reporte";
        $tablas->Equipo = "Equipo";
        $tablas->Marca = "Marca";
        $tablas->Serie = "Serie";
        if (strtolower($Prefijo) == 'biomedicos') {
            $tablas->sede = "biomedico.sede";
            $tablas->servicio = "biomedico.servicio";
            $tablas->hojavida = "biomedico.hojavida";
            $tablas->reporte = "biomedico.reporte";
        } elseif (strtolower($Prefijo) == 'sistemas') {
            $tablas->sede = "sede";
            $tablas->servicio = "servicio";
            $tablas->hojavida = "sistemas_hojavida";
            $tablas->reporte = "sistemas_reporte";
            $tablas->Equipo = "Nombre";
            $tablas->Marca = "Fabricante";
            $tablas->Serie = "NSerial";
        }
        # TODO: Pendiente añadir ambulancia
        return $tablas;
    }

    public function GetSolicitudesPolByUsuario($UsuarioId, $prefijo) {
        $Helper = new SolicitudDAL();
        $tablas = $this->GetTablas($prefijo);
        return $Helper->GetSolicitudesPolByUsuario($UsuarioId, $prefijo, $tablas);
    }

    public function GetReportesBySolicitudId($SolicitudId, $Prefijo) {
        $Helper = new SolicitudDAL();
        $tablas = $this->GetTablas($Prefijo);
        return $Helper->GetReportesBySolicitudId($SolicitudId, $Prefijo, $tablas);
    }

    public function GetProcesosBySolicitudId($SolicitudId, $Prefijo) {
        $Helper = new SolicitudDAL();
        $tablas = $this->GetTablas($Prefijo);
        return $Helper->GetProcesosBySolicitudId($SolicitudId, $Prefijo, $tablas);
    }

    public function GetSolicitudPolById($SolicitudId, $Prefijo) {
        $Helper = new SolicitudDAL();
        $tablas = $this->GetTablas($Prefijo);
        return $Helper->GetSolicitudPolById($SolicitudId, $Prefijo, $tablas);
    }

    public function GetAllSolicitudesPol($Prefijo, $Mes, $Year) {
        $Helper = new SolicitudDAL();
        $tablas = $this->GetTablas($Prefijo);
        return $Helper->GetAllSolicitudesPol($Prefijo, $Mes, $Year, $tablas);
    }

    public function GetTotalSolicitudes() {
        $Helper = new SolicitudDAL();
        return $Helper->GetTotalSolicitudes();
    }

    public function GetReporteExternoById($ReporteExternoId, $Prefijo) {
        $Helper = new SolicitudDAL();
        if (strtolower($Prefijo) == 'biomedicos') {
            return $Helper->GetReporteExternoBiomedicoById($ReporteExternoId);
        }
        return $Helper->GetReporteExternoById($ReporteExternoId);
    }

    public function GetEventosBySolicitudId($SolicitudId, $Prefijo) {
        $Helper = new SolicitudDAL();
        return $Helper->GetEventosBySolicitudId($SolicitudId, $Prefijo);
    }

    public function CreateEventoSolicitudPol($list, $Prefijo) {
        $Helper = new SolicitudDAL();
        $id = $Helper->CreateEventoSolicitudPol($this->MAPToCESPol($list), $Prefijo);
        if (count($id) > 0 && is_array($id)) {
            if ($list->TipoEvento == "Reporte" || $list->TipoEvento == "Reporte Externo" || $list->TipoEvento == "Fin de la solicitud" || $list->TipoEvento == "Cancelar solicitud") {
                $usuario = $Helper->getUsuarioSolicitudPol($list->SolicitudId, $Prefijo);
                $Eh = new EmpresaBLL();
                $Empresa = $Eh->GetEmpresa();
                $e = new sendMail();
                $Mensaje = "";
                switch ($list->TipoEvento) {
                    case 'Reporte':
                        $Mensaje = "Tu solicitud ha sido atendida y se ha generado un reporte";
                        break;
                    case 'Reporte Externo':
                        $Mensaje = "Tu solicitud ha sido atendida y se ha generado un reporte";
                        break;
                    case 'Fin de la solicitud':
                        $Mensaje = "Tu solicitud ha sido finalizada. <br>" . $list->Descripcion;
                        break;
                    case 'Cancelar solicitud':
                        $Mensaje = "Tu solicitud ha sido cancelada. <br>" . $list->Descripcion;
                        break;
                    default:
                        break;
                }
                $e->EnviarEmail_Notificacion($Empresa, "Solicitud Mant {$Prefijo} No " . $list->SolicitudId, "<p style='text-align:justify'>$Mensaje</p>", $usuario[0]->Email, $usuario[0]->NombreCompleto);
                $this->UpdateSolicitudPol([Array(
                'IsVisto' => 1,
                'IsFinalizada' => 1,
                'FechaFinalizacion' => $this->getDatetimeNow(),
                'NombreUsuarioFinaliza' => $list->NombreUsuario,
                'UsuarioFinalizaId' => $list->UsuarioEventoId,
                'ModifiedAt' => $this->getDatetimeNow()
                    )], $list->SolicitudId, $Prefijo);
            }
        }
        return $id;
    }

    public function CreateSolicitudPol($list, $prefijo) {
        $Helper = new SolicitudDAL();
        $id = $Helper->CreateSolicitudPol($this->MAPToCSPol($list), $prefijo);
        if (count($id) > 0 && is_array($id) && $list->IsVisto == 0) {
            $Helper->CreateDetalleSolicitudPol($this->MAPToCDSPol($list, $id[0]), $prefijo);
            $options = array(
                'cluster' => 'us2',
                'useTLS' => true,
                'scheme' => 'http'
            );

            $pusher = new Pusher\Pusher(
                    '82b644826921442b1ad3',
                    'db1f911e76e600d391b3',
                    '1008478',
                    $options
            );
            $data['prefijo'] = $prefijo;
            $data['msg'] = $list->Descripcion;
            $data['Nombres'] = $list->CreatedBy;
            if (!$this->isLocalhost()) {
                $pusher->trigger('solicitud', 'new-solicitud', $data);
            }
            $mensaje = "{$list->Servicio}\n{$list->CreatedBy}:\n{$list->Descripcion}";
            $this->NotificarJEFES($list->Sede, $mensaje, $prefijo);
//            $Helper = new SedeBLL();
//            $Sede = $Helper->GetSedeBySedeId($list->SedeId);
//            $Eh = new EmpresaBLL();
//            $Empresa = $Eh->GetEmpresa();
//            $e = new sendMail();
//            $e->EnviarEmail_Notificacion($Empresa, "Solicitud Mto. Correctivo No " . $id[0], "<p style='text-align:justify'>$list->Descripcion</p>", $Sede->Correo, $Sede->Nombre);
        }
        return $id;
    }

    public function UpdateSolicitudPol($data, $SolicitudId, $Prefijo) {
        $Helper = new SolicitudDAL();
        return $Helper->UpdateSolicitudPol($data, $SolicitudId, $Prefijo);
    }

    public function CountSolicitudes($UsuarioId) {
        $Helper = new SolicitudDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountSolicitudes($value->SedeId)[0]->Total;
        }
        return Array("Total" => $contador);
    }

    public function MAPToCSPol($list) {
        $list2 = Array();
        array_push($list2, Array(
            'FechaSolicitud' => $this->getDatetimeNow(),
            'UsuarioSolicitaId' => $list->UsuarioSolicitaId,
            'NombreUsuarioSolicita' => $list->NombreUsuarioSolicita,
            'CargoUsuarioSolicita' => $list->CargoUsuarioSolicita,
            'EstadoSolicitud' => "Nueva solicitud",
            'CreatedBy' => $list->CreatedBy,
            'CreatedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToCDSPol($list, $SolicitudId) {
        $list2 = Array();
        array_push($list2, Array(
            'SolicitudId' => $SolicitudId,
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'EquipoId' => NULL, # por defecto
            'HasNotEquipo' => $list->HasNotEquipo,
            'EquipoOtro' => $list->EquipoOtro,
            'Ubicacion' => $list->Ubicacion,
            'Descripcion' => $list->Descripcion,
            'CreatedBy' => $list->CreatedBy,
            'CreatedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    function MAPToCESPol($list) {
        $list2 = Array();
        array_push($list2, Array(
            'UsuarioEventoId' => $list->UsuarioEventoId,
            'NombreUsuario' => $list->NombreUsuario,
            'NombreBreveEvento' => $list->NombreBreveEvento,
            'Pedido2_0Id' => $list->Pedido2_0Id,
            'PedidoId' => $list->PedidoId,
            'PedidoFarmaciaId' => $list->PedidoFarmaciaId,
            'ReporteId' => $list->ReporteId,
            'ReporteExternoId' => $list->ReporteExternoId,
            'ProcesoId' => $list->ProcesoId,
            'SolicitudId' => $list->SolicitudId,
            'TipoEvento' => $list->TipoEvento,
            'Descripcion' => $list->Descripcion,
            'TecnicoResponsable' => $list->TecnicoResponsable,
            'FechaEvento' => $this->getDatetimeNow(),
            'CreatedAt' => $this->getDatetimeNow()
        ));

        return $list2;
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d h:i:s');
    }
    /**
     * Notificamos los jefes y auxiliares, si esas en entorno de pruebas los mensajes me llegaran al celular 3046449579
     *
     * @param [type] $Sede
     * @param [type] $mensaje
     * @param [type] $prefijo
     * @return void
     */
    public function NotificarJEFES($Sede, $mensaje, $prefijo) {
        if (!$this->isLocalhost()) {
            $array_numbers = $Sede == "CSI" ? ["573155221055"] : ["573153469231"];
            //573175105001, 573043889713
            if (strtolower($prefijo) == 'biomedicos') {
                $array_numbers = ["573133526840"];
            } elseif (strtolower($prefijo) == 'sistemas') {
                $array_numbers = $Sede == "CSI" ? ["573175105001", "573043889713"] : ["573153476591"];
            }
            $this->EnviarSMS($array_numbers, $mensaje);
        } else {
            $array_numbers = ["573046449579"];
            $this->EnviarSMS($array_numbers, $mensaje);
        }
    }
    /**
     * Funcion para enviar mensajes de texto
     *
     * @param [type] $array_numbers
     * @param [type] $mensaje
     * @return void
     */
    public function EnviarSMS($array_numbers, $mensaje) {
        $basic = base64_encode("emerlaura:Colombia2019");
        try {
            $data = array(
                'from' => 'Solicitud MANT',
                'to' => $array_numbers,
                'text' => $mensaje
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://107.20.199.106/sms/1/text/single");
            // indicamos el tipo de petición: POST
            #curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Basic $basic",
                "Content-Type: application/json",
                "Accept: application/json"
            ));
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close($ch);
            #echo $result;
        } catch (Exception $exc) {
            #echo $exc->getTraceAsString();
        }
    }
    private function isLocalhost($whitelist = ['127.0.0.1', '::1', 'localhost']) {
        return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
    }

}

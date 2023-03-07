<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/ct/ControlDAL.php';
require_once dirname(__FILE__) . '/../../DAL/ct/PersonaDAL.php';
require_once dirname(__FILE__) . '/../Helpers/Classes/PHPExcel.php';
require_once dirname(__FILE__) . '/../../DAL/ct/DispositivoDAL.php';

class ControlBLL {

    private $objPHPExcel;

    public function CrearControlesOffline($list) {
        $Helper = new ControlDAL();
        $ids = $Helper->CreateControl($this->MAPToCreateControlOffline($list));
        for ($i=0; $i < count($ids); $i++) { 
            $Turno = array();
            if ($list[$i]->Tipo === 'Salida') {
              $Turno = $Helper->GetEstadoTurnoSalida($list[$i]->PersonaId, $list[$i]->Fecha);
            }else{
              $Turno = $Helper->GetEstadoTurno($list[$i]->PersonaId, $list[$i]->Fecha);
            }
            if (count($Turno) > 0) {
              $this->SetHorario($ids[$i], $Turno[0]);
            }
        }
        return $list[0];
    }
    // <editor-fold defaultstate="collapsed" desc="NOTIFICAR">
    public function NotificarJefe($Persona, $EstadoEntrada) {
        $Helper = new ControlDAL();
        $Ph = new PersonaDAL();
        if ($Persona->JefeId != NULL && $Persona->JefeId != '') {
            $j = $Ph->GetNumeroJefe($Persona->JefeId);
            $LlegadasTarde = $Ph->GetCountLlegadasTarde($Persona->PersonaId, $this->getDatetimeNow());
            if (count($j) > 0) {
                $mensaje = "Debe hacerle seguimiento a su grupo primario,"
                        . "$Persona->PrimerNombre $Persona->PrimerApellido, Ha llegado " . $EstadoEntrada . ". Fecha: " . $this->getDatetimeNow();
                if (count($LlegadasTarde) > 0) {
                    $mensaje .= ". Llegadas tardes: " . $LlegadasTarde[0]->T_Tarde;
                }
                if ($j[0]->Celular != null && $j[0]->Celular != '') {
                    $basic = base64_encode("emerlaura:Colombia2019");
                    try {
                        $data = array(
                            'from' => 'CIELD',
                            'to' => ['57' . $j[0]->Celular], #, '573046449579' '57' . $j[0]->Celular,
                            'text' => $mensaje
                        );
                        // guardamos el mensaje
                        $list2 = Array();
                        array_push($list2, array(
                            'PersonaId' => $Persona->JefeId,
                            'ColaboradorId' => $Persona->PersonaId,
                            'Mensaje' => $mensaje
                        ));
                        $Helper->CreateMensaje($list2);
                        $data_string = json_encode($data);
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
            }
        }
    }

// </editor-fold>
    public function LogicaControl($Persona, $Dispositivo) {
        $id = NULL;
        $Helper = new ControlDAL();
        $Ph = new PersonaDAL();
        $hd = new DispositivoDAL();
        $Bandera = 0;
        if ($Persona->TipoPersona === 'Lider') {
            $Control = $Helper->GetLastControlInDayLider($Persona->PersonaId, $this->getDatetimeNow());
        } else {
            $Control = $Helper->GetLastControlInDay($Persona->PersonaId, $this->getDatetimeNow());
        }

        $Tipo = "Entrada";
        if (count($Control) > 0) {
            $Persona->IsCumpleano = $Control[0]->IsCumpleano;
            $Tipo = $Control[0]->Tipo == 'Entrada' ? "Salida" : "Entrada";
            if (!$Control[0]->CanMarcar) {
//                return "Debes esperar que pasen 5 minutos desde la ultima vez que marcaste, para poder marcar.";
                return [$Persona];
            }
            $DispositivoObj = $hd->GetDispositivoByName($Dispositivo);
            $Permiso = $Ph->GetPermisoByPersonaId($Persona->PersonaId, $this->getDatetimeNow());
            $PermisoId = 0;
            if (count($Permiso) > 0) {
                $PermisoId = $Permiso[0]->PermisoId;
                $Persona->PermisoIsGeneral = $Permiso[0]->IsGeneral;
            } else {
                $PermisoId = 0;
            }
            if (count($DispositivoObj) > 0) {

                if ($Control[0]->EstadoActual == 'No necesita permiso') {
                    $Bandera = $Helper->VerificarTiempo($Control[0]->Fecha, $this->getDatetimeNow())->Bandera;
                    if ($Tipo == "Entrada" && $Control[0]->PrePermiso == 1 && $Bandera && $Control[0]->Dispositivo != $Dispositivo) {
                        $id = $Helper->CreateControl($this->MAPToCreateControlPBySede($Persona, $Tipo, $Dispositivo, $PermisoId));
                        $Helper->UpdateControl($this->MAPToUpdateControlPermisoBySede(), $Control[0]->ControlId);
                    } else {
                        $id = $Helper->CreateControl($this->MAPToCreateControl($Persona, $Tipo, $Dispositivo, $PermisoId));
                    }
                } else {
                    $id = $Helper->CreateControl($this->MAPToCreateControl($Persona, $Tipo, $Dispositivo, $PermisoId));
                }

                if (is_array($id)) {
                    $Persona->Tipo = $Tipo;
                    if ($PermisoId == 0) {
                        $VerificarEntrada = $Helper->GetEstadoTurno($Persona->PersonaId, $this->getDatetimeNow());
                        
                        if (count($VerificarEntrada) > 0) {
                            if(is_array($id)){
                                $this->SetHorario($id[0], $VerificarEntrada[0]);
                            }
//                            $Persona->EstadoTurnoEntrada = $Tipo == 'Entrada' ? "Usted ha llegado: " . $VerificarEntrada[0]->EstadoTurno : 'SALIENDO';
                            $Persona->EstadoTurnoEntrada = $Tipo == 'Entrada' ? "Usted ha llegado: A tiempo" : 'SALIENDO';
                            if ($Control[0]->PrePermiso && $Bandera == 1 && $Control[0]->Dispositivo != $Dispositivo) {
                                $Persona->EstadoTurnoEntrada = "Usted ha cambiado de sede";
                            } else if ($VerificarEntrada[0]->EstadoTurno == "Tarde" && $Tipo == "Entrada") {
                                if($Persona->JefeId == 177){
                                    // ERIca SILVA reporto estos Medicos
                                    $this->NotificarJefe($Persona, $VerificarEntrada[0]->EstadoTurno); #POR AHORA COVID 19
                                }
                            }
                        }
                    } else {
                        $Persona->HasPermiso = true;
                        $Persona->EstadoTurnoEntrada = $Tipo . `: usted tiene un permiso`;
                    }

                    if ($Tipo == "Salida") {
                        $VerificarSalida = $Helper->GetEstadoTurnoSalida($Persona->PersonaId, $this->getDatetimeNow());
                        if (count($VerificarSalida) > 0) {
                            if(is_array($id)){
                                $this->SetHorario($id[0], $VerificarSalida[0]);
                            }
                            if ($Persona->TipoPersona == 'Lider') {
                                if ($Control[0]->EstadoActual == 'Necesita permiso') {
                                    $Persona->EstadoTurnoEntrada = 'Tienes 1 hora para registrarte en una de las sedes.';
                                    $Helper->UpdateControl($this->MAPToUpdateControlPrePermisoBySede(), $id[0]);
                                } else if ($Control[0]->EstadoActual == 'No deberia marcar') {
                                    $Persona->EstadoTurnoEntrada = $VerificarSalida[0]->EstadoSalida;
                                } else {
                                    $Persona->EstadoTurnoEntrada = $VerificarSalida[0]->EstadoSalida;
                                }
                            } else {
                                $Persona->EstadoTurnoEntrada = $VerificarSalida[0]->EstadoSalida;
                            }
                        }
                    }

                    return [$Persona];
                } else {
                    return $id;
                }
            } else {
                return "Debes añadir un dispositivo valido.";
            }
        } else {
            # Primera vez
            $DispositivoObj = $hd->GetDispositivoByName($Dispositivo);
            $Permiso = $Ph->GetPermisoByPersonaId($Persona->PersonaId, $this->getDatetimeNow());
            $PermisoId = 0;
            if (count($Permiso) > 0) {
                $PermisoId = $Permiso[0]->PermisoId;
                $Persona->PermisoIsGeneral = $Permiso[0]->IsGeneral;
            } else {
                $PermisoId = 0;
            }
            if (count($DispositivoObj) > 0) {
                $id = $Helper->CreateControl($this->MAPToCreateControl($Persona, $Tipo, $Dispositivo, $PermisoId));
                if (is_array($id)) {
                    $Persona->Tipo = $Tipo;
                    if ($PermisoId == 0) {
                        $VerificarEntrada = $Helper->GetEstadoTurno($Persona->PersonaId, $this->getDatetimeNow());

                        if (count($VerificarEntrada) > 0) {
                            $Persona->EstadoTurnoEntrada = $Tipo == 'Entrada' ? "Usted ha llegado: " . $VerificarEntrada[0]->EstadoTurno : 'SALIENDO';
                            if ($VerificarEntrada[0]->EstadoTurno == "Tarde" && $Tipo == "Entrada") {
                                #$this->NotificarJefe($Persona, $VerificarEntrada[0]->EstadoTurno); OMITIDO POR AHORA COVID-19
                            }
                        }
                    }
                    return [$Persona];
                } else {
                    return $id;
                }
            }
        }
    }

    public function GetControlByPersonaIdAndFecha($PersonaId, $Desde, $Hasta) {
        $Helper = new ControlDAL();
        return $Helper->GetControlByPersonaIdAndFecha($PersonaId, $Desde, $Hasta);
    }

    public function GetControlByPersonaIdAndFechaXLSX($PersonaId, $Desde, $Hasta) {
        $Helper = new ControlDAL();
        $Biometrico = $Helper->GetControlByPersonaIdAndFecha($PersonaId, $Desde, $Hasta);
        if (count($Biometrico) > 0) {

            $this->objPHPExcel = new PHPExcel();
            // Set document properties
            $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                    ->setLastModifiedBy("Franklin Ospino")
                    ->setTitle("Comparativo de reportes en un rango determinado")
                    ->setSubject("Comparativo de reportes en un rango determinado")
                    ->setDescription("Comparativo de reportes en un rango determinado.")
                    ->setKeywords("office 2013 openxml php")
                    ->setCategory("Facturacion");


// Add some data
            $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, 'Tiempo')
                    ->setCellValue('B' . $cont, 'Nombre')
                    ->setCellValue('C' . $cont, 'Apellido')
                    ->setCellValue('D' . $cont, 'Tipo')
                    ->setCellValue('E' . $cont, 'Dispositivo');

            $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':E' . $cont)->getFont()->setBold(true);
            $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
            // Ancho
            $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//        echo print_r($array);
            foreach ($Biometrico as $value) {
                $cont++;
                $this->objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $cont, $value->Fecha)
                        ->setCellValue('B' . $cont, "$value->PrimerNombre $value->SegundoNombre")
                        ->setCellValue('C' . $cont, "$value->PrimerApellido $value->SegundoApellido")
                        ->setCellValue('D' . $cont, $value->Tipo)
                        ->setCellValue('E' . $cont, $value->Dispositivo);
            }
//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
            $this->objPHPExcel->getActiveSheet()->setTitle("Empleado $value->PersonaId");


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . "$value->PrimerNombre $value->PrimerApellido-" . $this->getDatetimeNow() . '.xlsx"');
            header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=' . $cont);

// If you're serving to IE over SSL, then the following may be needed
            header($this->getDatetimeNow()); // Date in the past
//        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        } else {
            echo "<center><h1>No tiene reporte entre estas fechas</h1></center>";
        }
    }

    public function AsignarPermiso($ControlId, $PermisoId) {
        $Helper = new ControlDAL();
        return $Helper->UpdateControl($this->MAPToUpdateControlPermisoId($PermisoId), $ControlId);
    }

    public function GetControlWithLimite($Limite) {
        $Helper = new ControlDAL();
        return $Helper->GetControlWithLimite($Limite);
    }

    public function GetListEmpleados($Dispositivo) {
        $Helper = new ControlDAL();
        return $Helper->GetListEmpleados($Dispositivo);
    }

    public function GetUltimoControl($PersonaId) {
        $Helper = new ControlDAL();
        return $Helper->GetUltimoControl($PersonaId);
    }
    
    public function SetHorario($ControlId, $Horario){
        $Helper = new ControlDAL();
        return $Helper->UpdateControl([array(
            "HorarioId" => $Horario->HorarioId,
            "HoraInicio" => $Horario->HoraInicio,
            "HoraFin" => $Horario->HoraFin,
            "DiaSemana" => $Horario->DiaSemana,
            "DiaMes" => $Horario->DiaMes,
        )], $ControlId);
    }

    public function MAPToCreateControlOffline($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'PersonaId' => $list[$index]->PersonaId,
                'Tipo' => $list[$index]->Tipo,
                'Dispositivo' => $list[$index]->Dispositivo,
                'Fecha' => $list[$index]->Fecha,
                'PermisoId' => $list[$index]->PermisoId,
                'CreatedBy' => 'Automatico offline',
            ));
        }
        return $list2;
    }

    public function MAPToCreateControl($list, $Tipo, $Dispositivo, $PermisoId) {
        $list2 = Array();
        array_push($list2, Array(
            'PersonaId' => $list->PersonaId,
            'Tipo' => $Tipo,
            'PermisoId' => $PermisoId,
            'Dispositivo' => $Dispositivo,
            'Fecha' => $this->getDatetimeNow(),
            'CreatedBy' => $list->Nombres,
        ));
        return $list2;
    }

    public function MAPToCreateControlPBySede($list, $Tipo, $Dispositivo, $PermisoId) {
        $list2 = Array();
        array_push($list2, Array(
            'PersonaId' => $list->PersonaId,
            'PermisoBySede' => 1,
            'Tipo' => $Tipo,
            'PermisoId' => $PermisoId,
            'Dispositivo' => $Dispositivo,
            'Fecha' => $this->getDatetimeNow(),
            'CreatedBy' => $list->Nombres,
        ));
        return $list2;
    }

    public function MAPToUpdateControlPermisoBySede() {
        $list2 = Array();
        array_push($list2, Array(
            'PermisoBySede' => 1,
        ));
        return $list2;
    }

    public function MAPToUpdateControlPrePermisoBySede() {
        $list2 = Array();
        array_push($list2, Array(
            'PrePermiso' => 1,
        ));
        return $list2;
    }

    public function MAPToUpdateControlPermisoId($PermisoId) {
        $list2 = Array();
        array_push($list2, Array(
            'PermisoId' => $PermisoId,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToUpdateControl($list) {
        $list2 = Array();
        array_push($list2, Array(
            'FechaSalida' => $this->getDatetimeNow(),
            'Estado' => "Finalizado",
            'ModifiedBy' => $list->Nombres,
        ));
        return $list2;
    }

    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d H:i:s');
    }

}

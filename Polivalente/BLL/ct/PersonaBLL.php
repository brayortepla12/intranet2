<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/ct/PersonaDAL.php';
require_once dirname(__FILE__) . '/../../DAL/ct/CargoDAL.php';
require_once dirname(__FILE__) . '/../../DAL/ct/DispositivoDAL.php';
require_once dirname(__FILE__) . '/../../DAL/ct/VariableDAL.php';
require_once dirname(__FILE__) . '/ControlBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Classes/PHPExcel.php';
require_once dirname(__FILE__) . '/../Helpers/Classes/Festivos.php';
require_once dirname(__FILE__) . '/../../DAL/seguridad/UsuarioDAL.php';

class PersonaBLL {

    private $objPHPExcel;

    public function __construct() {
        $this->objPHPExcel = new PHPExcel();
    }

    // <editor-fold defaultstate="collapsed" desc="Excel">
    public function GetExcelEstadisticas($LiderId, $Mes, $Year) {
        $fes = new Festivos($Year);
        $ph = new PersonaDAL();
        $EstadisticasP = $ph->GetEstadisticasPByLiderId($LiderId, $Mes, $Year);
        $UltimoDiaMes = $this->_data_last_month_day($Mes, $Year);
        $letras = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG'];
        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de referencia en un rango determinado")
                ->setSubject("Comparativo de referencia en un rango determinado")
                ->setDescription("Comparativo de referencia en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Reportes");
        $cont = 1;
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Nombres')
                ->setCellValue('B1', 'Dia')
                ->setCellValue('C1', 'E1')
                ->setCellValue('D1', 'S2')
                ->setCellValue('E1', 'E3')
                ->setCellValue('F1', 'S4')
                ->setCellValue('G1', 'E5')
                ->setCellValue('H1', 'S6')
                ->setCellValue('I1', 'E7')
                ->setCellValue('J1', 'S8');
        $HOJA = $this->objPHPExcel->getActiveSheet();
        $HOJA->getStyle('A1:AJ' . $cont)->getFont()->setBold(true);
        // Ancho
        $HOJA->getColumnDimension('A')->setWidth(40);
        $HOJA->getColumnDimension('B')->setWidth(5);
        $HOJA->getColumnDimension('C')->setWidth(10);
        $HOJA->getColumnDimension('D')->setWidth(10);
        $HOJA->getColumnDimension('E')->setWidth(10);
        $HOJA->getColumnDimension('F')->setWidth(10);
        $HOJA->getColumnDimension('G')->setWidth(10);
        $HOJA->getColumnDimension('H')->setWidth(10);
        $HOJA->getColumnDimension('I')->setWidth(10);
        $HOJA->getColumnDimension('J')->setWidth(10);
        $contador = 2;
        for ($ContadorDia = 0; $ContadorDia < $UltimoDiaMes; $ContadorDia++) {
            foreach ($EstadisticasP as $p) {
                $l = "B" . $contador;
                $d = $ContadorDia + 1;

                if ($d == $p->Dia) {
                    $day_number = str_pad($d, 2, "0", STR_PAD_LEFT);
                    $Mes2 = str_pad($Mes, 2, "0", STR_PAD_LEFT);
                    $datetime = DateTime::createFromFormat('YmdHi', $Year . $Mes2 . $day_number . "1830");
                    $HOJA->setCellValue($l, $ContadorDia + 1);
                    $date_nom = $datetime->format('D');

                    $HOJA->getComment($l)->getText()->createTextRun($this->GetNameEspanol($date_nom));
                    $color = '';
                    if ($fes->esFestivo($day_number, $Mes)) {
                        $color = ($date_nom == 'Sat' || $date_nom == 'Sun') ? 'C9FFAB' : 'FFFE9A';
                        $HOJA
                                ->getStyle($l)
                                ->getFill()
                                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB($color);
                    } else if ($date_nom == 'Sat' || $date_nom == 'Sun') {
                        $color = 'CCCCCC';
                        $HOJA
                                ->getStyle($l)
                                ->getFill()
                                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB($color);
                    }
                    $HOJA->setCellValue("A{$contador}", $p->usuario);
                    $HOJA->setCellValue("C{$contador}", $p->E1);
                    $HOJA->setCellValue("D{$contador}", $p->S2);
                    $HOJA->setCellValue("E{$contador}", $p->E3);
                    $HOJA->setCellValue("F{$contador}", $p->S4);
                    $HOJA->setCellValue("G{$contador}", $p->E5);
                    $HOJA->setCellValue("H{$contador}", $p->S6);
                    $HOJA->setCellValue("I{$contador}", $p->E7);
                    $HOJA->setCellValue("J{$contador}", $p->S8);
                    $HOJA->getComment("C{$contador}")->getText()->createTextRun($p->E1_C);
                    $HOJA->getComment("D{$contador}")->getText()->createTextRun($p->S2_C);
                    $HOJA->getComment("E{$contador}")->getText()->createTextRun($p->E3_C);
                    $HOJA->getComment("F{$contador}")->getText()->createTextRun($p->S4_C);
                    $HOJA->getComment("G{$contador}")->getText()->createTextRun($p->E5_C);
                    $HOJA->getComment("H{$contador}")->getText()->createTextRun($p->S6_C);
                    $HOJA->getComment("I{$contador}")->getText()->createTextRun($p->E7_C);
                    $HOJA->getComment("J{$contador}")->getText()->createTextRun($p->S8_C);
                    $contador++;
                }
            }
        }
        $HOJA->setTitle("Estadisticas {$Mes}-{$Year}");

        header('Content-type: application/vnd.ms-excel');

        header("Content-Disposition: attachment; filename='Estadisticas{$Mes}{$Year}_.xlsx");
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function GetExcelParaSH($LiderId, $Mes, $Year) {
        $fes = new Festivos($Year);
        $vh = new VariableDAL();
        $ph = new PersonaDAL();
        $Personas = $ph->GetPersonasByLiderId($LiderId);
        $Variables = $vh->GetVariables($LiderId);
        if (count($Variables) == 0) {
            echo "DEBES REGISTRAR TUS VARIABLES";
            exit;
        }
        $UltimoDiaMes = $this->_data_last_month_day($Mes, $Year);
        $letras = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG'];
        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de referencia en un rango determinado")
                ->setSubject("Comparativo de referencia en un rango determinado")
                ->setDescription("Comparativo de referencia en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Reportes");

        $cont = 1;
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Nombres')
                ->setCellValue('B1', 'Codigo');
        $this->objPHPExcel->createSheet(1);
        $this->objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('A1', 'Mes')
                ->setCellValue('B1', $Mes)
                ->setCellValue('C1', 'Año')
                ->setCellValue('D1', $Year)
                ->setCellValue('A2', 'Variable')
                ->setCellValue('B2', 'Abrev.')
                ->setCellValue('C2', 'H. Inicio')
                ->setCellValue('D2', 'H. Fin')
                ->setCellValue('E2', 'H. Inicio 2')
                ->setCellValue('F2', 'H. Fin 2')->setTitle('Turnos');
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->objPHPExcel->getActiveSheet()->getStyle('A1:AH' . $cont)->getFont()->setBold(true);
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $this->objPHPExcel->setActiveSheetIndex(0);
        $HOJA = $this->objPHPExcel->getActiveSheet();
        $this->objPHPExcel->setActiveSheetIndex(1);
        $HOJA2 = $this->objPHPExcel->getActiveSheet();

        $HOJA2->getStyle('A1')->getFont()->setBold(true);
        $HOJA2->getStyle('C1')->getFont()->setBold(true);
        $HOJA2->getStyle('A2:F2')->getFont()->setBold(true);
        for ($i = 0; $i < $UltimoDiaMes; $i++) {
            $l = $letras[$i] . '1';
            $d = $i + 1;
            $day_number = str_pad($d, 2, "0", STR_PAD_LEFT);
            $Mes2 = str_pad($Mes, 2, "0", STR_PAD_LEFT);
            $datetime = DateTime::createFromFormat('YmdHi', $Year . $Mes2 . $day_number . "1830");
            $HOJA->setCellValue($l, $i + 1);
            $date_nom = $datetime->format('D');

            $HOJA->getComment($letras[$i] . '1')->getText()->createTextRun($this->GetNameEspanol($date_nom));
            $color = '';
            if ($fes->esFestivo($day_number, $Mes)) {
                $color = ($date_nom == 'Sat' || $date_nom == 'Sun') ? 'C9FFAB' : 'FFFE9A';
                $HOJA
                        ->getStyle($l)
                        ->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB($color);
            } else if ($date_nom == 'Sat' || $date_nom == 'Sun') {
                $color = 'CCCCCC';
                $HOJA
                        ->getStyle($l)
                        ->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB($color);
            }

            $HOJA->getColumnDimension($letras[$i])->setWidth(7);
        }
        $vc = 3;
        for ($k = 0; $k < count($Variables); $k++) {
            $HOJA2->setCellValue('A' . $vc, $Variables[$k]->Variable);
            $HOJA2->setCellValue('B' . $vc, $Variables[$k]->Abreviatura);
            $HOJA2->setCellValue('C' . $vc, $Variables[$k]->FechaInicio);
            $HOJA2->setCellValue('D' . $vc, $Variables[$k]->FechaFin);
            if ($Variables[$k]->IsDoble == 1) {
                $HOJA2->setCellValue('E' . $vc, $Variables[$k]->FechaInicio2);
                $HOJA2->setCellValue('F' . $vc, $Variables[$k]->FechaFin2);
            }
            $vc++;
        }

        $this->objPHPExcel->addNamedRange(new PHPExcel_NamedRange('variables', $HOJA2, 'B3:B' . $vc));
        // pintamos al personal
        $cp = 2;
        for ($k = 0; $k < count($Personas); $k++) {
            $HOJA->setCellValue('A' . $cp, $Personas[$k]->Nombres)
                    ->setCellValue('B' . $cp, $Personas[$k]->PersonaId);
            for ($i = 0; $i < $UltimoDiaMes; $i++) {
                $l2 = $letras[$i] . $cp;
                $objValidation = $HOJA->getCell($l2)->getDataValidation();
                $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(true);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('El valor no está en la lista.');
                $objValidation->setPromptTitle('Elegir de la lista.');
                $objValidation->setPrompt('Por favor, elija un valor de la lista desplegable.');
                $objValidation->setFormula1("=variables"); //note this! 
            }
            $cp++;
        }



        $this->objPHPExcel->getActiveSheet()->setTitle('Listado Personal');


        $this->objPHPExcel->setActiveSheetIndex(0);



        header('Content-type: application/vnd.ms-excel');

        header('Content-Disposition: attachment; filename="HorarioCT_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

    }

    public function GetNameEspanol($date_name) {
        switch ($date_name) {
            case "Mon":
                return "Lunes";
            case "Tue":
                return "Martes";
            case "Wed":
                return "Miercoles";
            case "Thu":
                return "Jueves";
            case "Fri":
                return "Viernes";
            case "Sat":
                return "Sabado";
            case "Sun":
                return "Domingo";
            default:
                return "";
        }
    }

// </editor-fold>

    public function GetPersonasLite($Estado) {
        $Helper = new PersonaDAL();
        $list = $Helper->GetPersonasLite($Estado);
        return $list;
    }

    public function GetPersonaByUsuario($Usuario) {
        $Helper = new PersonaDAL();
        $list = $Helper->GetPersonaByUsuario($Usuario);
        return $list;
    }

    public function GetVariablesByUP($UsuarioOrPersona) {
        $Helper = new PersonaDAL();
        $list = $Helper->GetVariablesByUP($UsuarioOrPersona);
        return $list;
    }

    public function VerificarUsuarioIdRegUser($UsuarioId) {
        $Helper = new PersonaDAL();
        $list = $Helper->VerificarUsuarioIdRegUser($UsuarioId);
        if (count($list) > 0) {
            return true;
        }
        return false;
    }

    private function _data_last_month_day($Mes, $Ano) {
        $month = $Mes;
        $year = $Ano;
        $day = date("t", mktime(0, 0, 0, $month + 1, 0, $year));
        return $day;
    }

    public function GetHorarioByJefeId($JefeId, $Mes, $Year) {
        $Helper = new PersonaDAL();
        $Personas = $Helper->GetPersonasByLiderIdVer2All($JefeId);
//        $UltimoDiaMes = $this->_data_last_month_day($Mes, $Year);
        $listado = [];
        foreach ($Personas as $p) {
            $obj = new stdClass();
            $obj->PersonaId = $p->PersonaId;
            $obj->Nombres = $p->Nombres;
            $obj->HasHorarioFijo = $p->HasHorarioFijo;
            if ($p->HasHorarioFijo == 1) {
                $turno = $Helper->GetTurnosByPersonaIdLite($p->PersonaId);
                if (count($turno) > 0) {
                    $obj->Turno = $turno[0]->Nombre;
                    $obj->TurnoId = $turno[0]->TurnoId;
                }
                $obj->Horario = [];
            } else {
                $obj->Horario = $Helper->GetHorarioByJefeId($p->PersonaId, $JefeId, $Mes, $Year);
            }

            array_push($listado, $obj);
        }
        return $listado;
    }

    public function GetCambioHorarios($SedeId, $Mes, $Year) {
        $Helper = new PersonaDAL();
        $list = $Helper->GetCambioHorarios($SedeId, $Mes, $Year);
        return $list;
    }

    public function GetHorarioByColaboradorId($PersonaId, $Mes, $Year) {
        $Helper = new PersonaDAL();
        $list = $Helper->GetHorarioByColaboradorId($PersonaId, $Mes, $Year);
        if (count($list) == 0) {
            $list = $Helper->GetHorarioFijoByColaboradorId($PersonaId);
        }
        return $list;
    }

    public function UpdateCargo($Cargo) {
        $Helper = new PersonaDAL();
        return $Helper->UpdateCargo($this->MAPToUpdateCargo($Cargo), $Cargo->CargoId);
    }

    public function AutorizarPermiso($PermisoId, $UsuarioGHId, $Permisos) {
        $Helper = new PersonaDAL();
        $Hc = new ControlBLL();
        foreach ($Permisos as $p) {
            if ($p->Validar) {
                $Hc->AsignarPermiso($p->ControlId, $PermisoId);
            }
        }
        return $Helper->UpdatePermiso($this->MAPToAutorizarPermiso($UsuarioGHId), $PermisoId);
    }

    public function GetPermisoByRango_PersonaId($PersonaId, $FechaInicio, $FechaFin) {
        $Helper = new PersonaDAL();
        return $Helper->GetPermisoByRango_PersonaId($PersonaId, $FechaInicio, $FechaFin);
    }

    public function UpdateEstadoPersona($PersonaId, $Estado, $ModifiedBy) {
        $Helper = new PersonaDAL();
        return $Helper->UpdatePersona($this->MapToUpdateEstadoPersona($Estado, $ModifiedBy), $PersonaId);
    }

    public function UpdateJefe($lst) {
        $Helper = new PersonaDAL();
        foreach ($lst as $k) {
            $Helper->UpdatePersona([Array(
            'JefeId' => $k->JefeId_Destino,
            'ModifiedBy' => $k->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
                )], $k->PersonaId);
        }
        return [true];
    }

    public function VincularUsuarioCT($UsuarioCT) {
        $Helper = new PersonaDAL();
        $hu = new UsuarioDAL();
        $u = $hu->GetUsuarioById($UsuarioCT->UsuarioId);
        if ($u != NULL) {
            // if ($u->Firma != "" && substr($u->Firma, 0, 4) === "data") {
            //     $data = $u->Firma;
            //     list($type, $data) = explode(';', $data);
            //     list(, $data) = explode(',', $data);
            //     list(, $type) = explode('/', $type);
            //     $data = base64_decode($data);
            //     if ($data === false) {
            //         return 'Archivo incorrecto.';
            //     }
            //     $cadena = $UsuarioCT->PersonaId . "." . $type;
            //     file_put_contents(dirname(__FILE__) . "/../..//persona_firma//" . $cadena, $data);
            //     $UsuarioCT->Firma = "/Polivalente/persona_firma/$cadena";
            // }
            $Helper->UpdatePersonaUsuario([Array(
            'UsuarioIntranetId' => $UsuarioCT->UsuarioId,
            // 'Firma' => $UsuarioCT->Firma,
            'ModifiedBy' => $UsuarioCT->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
                )], $UsuarioCT->PersonaId, $UsuarioCT->UsuarioId);
            return [true];
        } else {
            return "Usuario no encontrado";
        }
    }

    public function UpdateCambioHorario($SolicitudHorario) {
        $Helper = new PersonaDAL();
        $this->InsertHorarioGH($SolicitudHorario->JefeId, $SolicitudHorario->Tabla,
                $SolicitudHorario->CreatedBy, $SolicitudHorario->Year,
                $SolicitudHorario->Mes, $Helper);
        return $Helper->UpdateCambioHorario([Array(
                'IsRevisado' => 1,
                    )], $SolicitudHorario->SolicitudHorarioId);
    }

    public function UpdatePersona($Persona) {
        $Helper = new PersonaDAL();
        if ($Persona->Foto != "") {
            $data = $Persona->Foto;
            if (substr($data, 0, 4) === "data") {
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                list(, $type) = explode('/', $type);
                $data = base64_decode($data);
                if ($data === false) {
                    return 'Archivo incorrecto.';
                }
                $cadena = $Persona->Cedula . "." . $type;
                file_put_contents(dirname(__FILE__) . "/../..//fotos//" . $cadena, $data);
                $Persona->Foto = "/Polivalente/fotos/$cadena";
            }
        }
        if ($Persona->Firma != "" && substr($Persona->Firma, 0, 4) === "data") {
            $data = $Persona->Firma;
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            list(, $type) = explode('/', $type);
            $data = base64_decode($data);
            if ($data === false) {
                return 'Archivo incorrecto.';
            }
            $cadena = $Persona->PersonaId . "." . $type;
            file_put_contents(dirname(__FILE__) . "/../..//persona_firma//" . $cadena, $data);
            $Persona->Firma = "/Polivalente/persona_firma/$cadena";
        }
        return $Helper->UpdatePersonaUsuario($this->MAPToUpdate($Persona), $Persona->PersonaId, $Persona->UsuarioIntranetId);
    }

    public function DeletePermiso($Permiso) {
        $Helper = new PersonaDAL();
        return $Helper->UpdatePermiso($this->MAPToDeletePermiso($Permiso), $Permiso->PermisoId);
    }

    public function CreateCargo($Cargo) {
        $Helper = new PersonaDAL();
        return $Helper->CreateCargo($this->MAPToCreateCargo($Cargo));
    }

    public function CreatePermiso($Permiso) {
        $Helper = new PersonaDAL();
        return $Helper->CreatePermiso($this->MAPToCreatePermiso($Permiso));
    }

    public function CreateControlOffline($list) {
        $Helper = new ControlBLL();
        return $Helper->CrearControlesOffline($list);
    }

    public function CreateTurnosByUsuario($UsuarioLider, $list, $CreatedBy, $Year, $Mes, $IsUGH) {
        $Helper = new PersonaDAL();
        $Per = $Helper->GetPersonaByUsuario($UsuarioLider);
        if (count($Per) > 0) {
            if ($IsUGH) {
                return $this->InsertHorarioGH($Per[0]->PersonaId, $list, $CreatedBy, $Year, $Mes, $Helper);
            } else {
                return $this->PrepareInsertHorario($Per[0]->PersonaId, $list, $CreatedBy, $Year, $Mes, $Helper);
            }
            return $Per;
        } else {
            return "Este usuario no existe.";
        }
    }

    public function CreateVariableUP($Variable) {
        $Helper = new PersonaDAL();
        $Per = $Helper->GetPersonaByUsuario($Variable->UsuarioPersona);
        if (count($Per) > 0) {
            $var = $Helper->GetVariableByAbrevByUsuario($Variable->Abreviatura, $Per[0]->UsuarioIntranetId);
            if (count($var) > 0) {
                return "Ya existe una variable con esta abreviatura";
            } else {
                $id = $Helper->CreateVariable([
                    Array(
                        "Variable" => $Variable->Nombre,
                        "Abreviatura" => $Variable->Abreviatura,
                        "FechaInicio" => $Variable->FechaInicio,
                        "FechaFin" => $Variable->FechaFin,
                        "FechaInicio2" => $Variable->FechaInicio2,
                        "FechaFin2" => $Variable->FechaFin2,
                        "UsuarioLiderId" => $Per[0]->UsuarioIntranetId,
                        "CreatedBy" => $Variable->CreatedBy,
                        "IsDoble" => $Variable->FechaInicio2 && $Variable->FechaFin2 ? 1 : 0
                    )
                ]);
                if (is_array($id) && count($id) > 0) {
                    return [true];
                } else {
                    return "Hubo un error al momento de guardar.";
                }
            }
        } else {
            return "Este usuario no existe.";
        }
    }

    public function UpdateVariable($Variable) {
        $Helper = new PersonaDAL();
        $id = $Helper->UpdateVariable([
            Array(
                "Variable" => $Variable->Nombre,
                "FechaInicio" => $Variable->FechaInicio,
                "FechaFin" => $Variable->FechaFin,
                "FechaInicio2" => $Variable->FechaInicio2,
                "FechaFin2" => $Variable->FechaFin2,
                "ModifiedBy" => $Variable->ModifiedBy,
                "ModifiedAt" => $this->getDatetimeNow(),
                "IsDoble" => $Variable->FechaInicio2 && $Variable->FechaFin2 ? 1 : 0
            )
                ], $Variable->VariableId);
        if (is_array($id) && count($id) > 0) {
            return [true];
        } else {
            return "Hubo un error al momento de actualizar.";
        }
    }

    public function PrepareInsertHorario($PersonaId, $list, $CreatedBy, $Year, $Mes, $Helper) {
        $Solicitud = $Helper->ExistSolicitud($PersonaId, $Mes, $Year);
        if (count($Solicitud) > 0) {
            return "Ya se encuentra una solicitud de horario pendiente. No: " . $Solicitud[0]->SolicitudHorarioId;
        } else {
            $path = dirname(__FILE__) . "/../..//horarios_temp//";
            $fp = fopen($path . $PersonaId . ".txt", 'w');
            fwrite($fp, json_encode($list));
            fclose($fp);
            return $Helper->CreateSolicitudGH($this->MAPToGH($PersonaId, $CreatedBy, $Mes, $Year));
        }
    }

    public function InsertHorarioGH($PersonaId, $list, $CreatedBy, $Year, $Mes, $Helper) {
        $list_horarios = [];
        $Mes2 = str_pad($Mes, 2, "0", STR_PAD_LEFT);
        $UltimoDiaMes = $this->_data_last_month_day($Mes2, $Year);
        for ($index = 1; $index <= $UltimoDiaMes; $index++) {
            $day_number = str_pad($index, 2, "0", STR_PAD_LEFT);
            $datetime = DateTime::createFromFormat('YmdHi', $Year . $Mes2 . $day_number . "1830");
            $date_nom = $datetime->format('l');
            foreach ($list as $key => $v) {
                if (property_exists($v, "$index") && property_exists($v, "Codigo")) {
                    $Helper->DeleteHorarioByDiaMes($Year . '-' . $Mes2 . '-' . $day_number, $v->Codigo);
                    $variable = $Helper->GetVariableByAbrev($v->{"$index"}, $PersonaId);
                    $turno = $Helper->GetTurnoByJefeId_ColaboradorId($PersonaId, $v->Codigo);
                    if (count($variable) > 0) {
                        if (count($turno) == 0) {
                            $list2 = Array();
                            array_push($list2, Array(
                                'JefeId' => $PersonaId,
                                'ColaboradorId' => $v->Codigo,
                                'Nombre' => 'Turno ' . $v->Nombres,
                                'CreatedBy' => $CreatedBy,
                            ));
                            $turno = new stdClass();
                            $turno->TurnoId = $Helper->CreateTurno($list2)[0];
                        } else {
                            $turno = $turno[0];
                        }
                        array_push($list_horarios, Array(
                            'HoraInicio' => $variable[0]->FechaInicio,
                            'HoraFin' => $variable[0]->FechaFin,
                            'DiaSemana' => $date_nom,
                            'DiaMes' => $Year . '-' . $Mes2 . '-' . $day_number,
                            'FechaVencimiento' => $Year . '-' . $Mes2 . '-' . $UltimoDiaMes,
                            'EsteTurnoVence' => 1,
                            'TurnoId' => $turno->TurnoId,
                            'VariableId' => $variable[0]->VariableId,
                            'CreatedBy' => $CreatedBy,
                        ));
                        if ($variable[0]->IsDoble == '1') {
                            array_push($list_horarios, Array(
                                'HoraInicio' => $variable[0]->FechaInicio2,
                                'HoraFin' => $variable[0]->FechaFin2,
                                'DiaSemana' => $date_nom,
                                'DiaMes' => $Year . '-' . $Mes2 . '-' . $day_number,
                                'FechaVencimiento' => $Year . '-' . $Mes2 . '-' . $UltimoDiaMes,
                                'EsteTurnoVence' => 1,
                                'TurnoId' => $turno->TurnoId,
                                'VariableId' => $variable[0]->VariableId,
                                'CreatedBy' => $CreatedBy,
                            ));
                        }
//                            print_r($this->MAPToUpdateTurnoId($turno->TurnoId, $CreatedBy));
                        $Helper->UpdatePersona($this->MAPToUpdateTurnoId($turno->TurnoId, $CreatedBy), $v->Codigo);
                    }
                }
            }
        }
//        $Helper->CreateSolicitudGH($this->MAPToGH($Per[0]->PersonaId, $CreatedBy, $Mes, $Year));
        $ids = $Helper->CreateHorario($list_horarios);
        if (count($ids) > 0) {
            return $ids;
        }
    }

    public function CreatePersona($Persona) {
        $Helper = new PersonaDAL();
        $Per = $Helper->GetPersonaByCodigo($Persona->CodigoTarjeta);
        if (count($Per) == 0) {
            $Per2 = $Helper->GetPersonaByCedula($Persona->Cedula);
            if (count($Per2) == 0) {
                if (property_exists($Persona, "UsuarioIntranetId")) {
                    $Per3 = $Helper->GetPersonaByUsuarioIntranet($Persona->UsuarioIntranetId);
                    if (count($Per3) == 0) {
                        if ($Persona->Foto != "" && substr($Persona->Foto, 0, 4) === "data") {
                            $data = $Persona->Foto;
                            list($type, $data) = explode(';', $data);
                            list(, $data) = explode(',', $data);
                            list(, $type) = explode('/', $type);
                            $data = base64_decode($data);
                            if ($data === false) {
                                return 'Archivo incorrecto.';
                            }
                            $cadena = $Persona->Cedula . "." . $type;
                            file_put_contents(dirname(__FILE__) . "/../..//fotos//" . $cadena, $data);
                            $Persona->Foto = "/Polivalente/fotos/$cadena";
                        }
                        $p = $Helper->CreatePersona($this->MAPToCreate($Persona));
                        if (count($p) > 0) {
                            if ($Persona->Firma != "" && substr($Persona->Firma, 0, 4) === "data") {
                                $data = $Persona->Firma;
                                list($type, $data) = explode(';', $data);
                                list(, $data) = explode(',', $data);
                                list(, $type) = explode('/', $type);
                                $data = base64_decode($data);
                                if ($data === false) {
                                    return 'Archivo incorrecto.';
                                }
                                $cadena = $p[0] . "." . $type;
                                file_put_contents(dirname(__FILE__) . "/../..//persona_firma//" . $cadena, $data);
                                $Persona->Firma = "/Polivalente/persona_firma/$cadena";
                                $Helper->UpdatePersona([array(
                                "Firma" => $Persona->Firma
                                    )], $p[0]);
                            }
                        }
                        return [true];
                    } else {
                        return "Este usuario ya se encuentra vinculado.";
                    }
                } else {
                    if ($Persona->Foto != "" && substr($Persona->Foto, 0, 4) === "data") {
                        $data = $Persona->Foto;
                        list($type, $data) = explode(';', $data);
                        list(, $data) = explode(',', $data);
                        list(, $type) = explode('/', $type);
                        $data = base64_decode($data);
                        if ($data === false) {
                            return 'Archivo incorrecto.';
                        }
                        $cadena = $Persona->Cedula . "." . $type;
                        file_put_contents(dirname(__FILE__) . "/../..//fotos//" . $cadena, $data);
                        $Persona->Foto = "/Polivalente/fotos/$cadena";
                    }
                    $p = $Helper->CreatePersona($this->MAPToCreate($Persona));
                    return [true];
                }
            } else {
                return "Esta cedula ya se encuentra registrada en la base de datos";
            }
        } else {
            return "Este codigo ya se encuentra registrado en la base de datos";
        }
    }

    public function GetPermisoByLiderId($LiderId, $Mes, $Year) {
        $Helper = new PersonaDAL();
        return $Helper->GetPermisoByLiderId($LiderId, $Mes, $Year);
    }

    public function GetPermisos() {
        $Helper = new PersonaDAL();
        return $Helper->GetPermisos();
    }

    public function GetPermisosBySedeIdAndMes($SedeId, $Mes, $Year) {
        $Helper = new PersonaDAL();
        return $Helper->GetPermisosBySedeIdAndMes($SedeId, $Mes, $Year);
    }

    public function GetAllHorarios() {
        $Helper = new PersonaDAL();
        return $Helper->GetAllHorarios();
    }

    public function GetPermisosLimite($Limite) {
        $Helper = new PersonaDAL();
        return $Helper->GetPermisosLimite($Limite);
    }

    public function GetPermisosLimiteA($Limite) {
        $Helper = new PersonaDAL();
        return $Helper->GetPermisosLimiteA($Limite);
    }

    public function GetUltimoControlByPer($Limite) {
        $Helper = new PersonaDAL();
        return $Helper->GetUltimoControlByPer($Limite);
    }

    public function GetPermisoByPermisoId($PermisoId) {
        $Helper = new PersonaDAL();
        return $Helper->GetPermisoByPermisoId($PermisoId);
    }

    public function GetPermisoByCodigoTarjeta($CodigoTarjeta) {
        $Helper = new PersonaDAL();
        return $Helper->GetPermisoByCodigoTarjeta($CodigoTarjeta);
    }

    public function GetPermisoByDocumento($Documento) {
        $Helper = new PersonaDAL();
        return $Helper->GetPermisoByDocumento($Documento);
    }

    public function GetPersonaById($PersonaId) {
        $Helper = new PersonaDAL();
        $Persona = $Helper->GetPersonaById($PersonaId);
        return $Persona;
    }

    public function GetPersonaByCedula($Cedula) {
        $Helper = new PersonaDAL();
        return $Helper->GetPersonaByCedula($Cedula);
    }

    public function GetHorarioByTurnoId($TurnoId) {
        $Helper = new PersonaDAL();
        return $Helper->GetHorarioByTurnoId($TurnoId);
    }

    public function GetTurnosByPersonaId($PersonaId) {
        $Helper = new PersonaDAL();
        $Turnos = $Helper->GetTurnosByPersonaId($PersonaId);
        if (count($Turnos) == 0) {
            $Turnos = $Helper->GetTurnos();
        }
        return $Turnos;
    }

    public function GetTurnos() {
        $Helper = new PersonaDAL();
        return $Helper->GetTurnos();
    }

    public function GetPersonaByCodigo($Codigo, $Dispositivo) {
        $Helper = new PersonaDAL();
        $Persona = $Helper->GetPersonaByCodigo($Codigo);
        if (count($Persona) > 0) {
            if ($Persona[0]->Estado == 'Activo') {
                $ch = new ControlBLL();
                return $ch->LogicaControl($Persona[0], $Dispositivo);
            } else {
                return $Persona;
            }
        } else {
            return "Usuario, no encontrado. Diríjase a gestión humana. ";
        }
    }

    public function GetPersonasByLider($Usuario) {
        $Helper = new PersonaDAL();
        return $Helper->GetPersonasByLider($Usuario);
    }

    public function GetPersonasActivas() {
        $Helper = new PersonaDAL();
        return $Helper->GetPersonasActivas();
    }

    public function GetCPersonasActivas() {
        $Helper = new PersonaDAL();
        return $Helper->GetCPersonasActivas();
    }

    public function GetPersonas($Estado) {
        $Helper = new PersonaDAL();
        $plist = $Helper->GetPersonas();
        return $plist;
    }

    public function GetLideres() {
        $Helper = new PersonaDAL();
        return $Helper->GetLideres();
    }

    public function GetColaboradoresAll($Year, $Mes, $Tipo) {
        $Helper = new PersonaDAL();
        if ($Tipo == "Entrada") {
            return $Helper->GetColaboradoresAll($Year, $Mes);
        } else if ($Tipo == "Salida") {
            return $Helper->GetColaboradoresAll_salidas($Year, $Mes);
        }
    }

    public function GetColaboradoresByLiderId($Year, $Mes, $Tipo, $LiderId) {
        $Helper = new PersonaDAL();
        if ($Tipo == "Entrada") {
            return $Helper->GetColaboradoresByLiderId($Year, $Mes, $LiderId);
        } else if ($Tipo == "Salida") {
            return $Helper->GetColaboradoresByLiderId_salidas($Year, $Mes, $LiderId);
        }
    }

    public function GetColaboradoresByLider($Year, $Mes, $Tipo, $UsuarioLider) {
        $Helper = new PersonaDAL();
        if ($Tipo == "Entrada") {
            return $Helper->GetColaboradoresByLider($Year, $Mes, $UsuarioLider);
        } else if ($Tipo == "Salida") {
            return $Helper->GetColaboradoresByLider_salidas($Year, $Mes, $UsuarioLider);
        }
    }

    public function GetLideresAll($Year, $Mes, $Tipo) {
        $Helper = new PersonaDAL();
        if ($Tipo == "Entrada") {
            return $Helper->GetLideresAll($Year, $Mes);
        } else if ($Tipo == "Salida") {
            return $Helper->GetLideresAll_salidas($Year, $Mes);
        }
    }

    public function GetListado_ES($Year, $Mes, $PersonaId) {
        $Helper = new PersonaDAL();
        return $Helper->GetListado_ES($Year, $Mes, $PersonaId);
    }

    public function GetESLideres($Year, $Mes, $Dia) {
        $Helper = new PersonaDAL();
        return $Helper->GetESLideres($Year, $Mes, $Dia);
    }

    public function GetESColaboradores($Year, $Mes, $Dia) {
        $Helper = new PersonaDAL();
        return $Helper->GetESColaboradores($Year, $Mes, $Dia);
    }

    public function GetListadoE_S($Year, $Mes, $Tipo, $PersonaId, $TipoTurno) {
        $Helper = new PersonaDAL();
        if ($Tipo == "Entrada") {
            return $Helper->GetListado_Entradas($Year, $Mes, $PersonaId, $TipoTurno);
        } else if ($Tipo == "Salida") {
            return $Helper->GetListado_Salidas($Year, $Mes, $PersonaId, $TipoTurno);
        }
    }

    public function GetCargoById($CargoId) {
        $Helper = new CargoDAL();
        return $Helper->GetCargoById($CargoId);
    }

    public function GetCargos() {
        $Helper = new CargoDAL();
        return $Helper->GetCargos();
    }

    public function GetDispositivoByName($Nombre) {
        $Helper = new DispositivoDAL();
        return $Helper->GetDispositivoByName($Nombre);
    }

    public function GetDispositivos() {
        $Helper = new DispositivoDAL();
        return $Helper->GetDispositivos();
    }

    public function MAPToCreateCargo($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Cargo' => $list->Cargo,
            'CreatedBy' => $list->CreatedBy,
        ));
        return $list2;
    }

    public function MAPToUpdateCargo($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Cargo' => $list->Cargo,
            'ModifiedBy' => $list->ModifiedBy,
        ));
        return $list2;
    }

    public function MAPToDeletePermiso($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Estado' => 'Inactivo',
            'ModifiedBy' => $list->ModifiedBy,
        ));
        return $list2;
    }

    public function MAPToCreatePermiso($list) {
        $list2 = Array();
        array_push($list2, Array(
            'LiderId' => $list->LiderId,
            'PersonaId' => $list->PersonaId,
            'Otro' => $list->Otro,
            'Cual' => $list->Cual,
            'Motivo' => $list->Motivo,
            'FechaInicio' => $list->FechaInicio,
            'FechaFin' => $list->FechaFin,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToAutorizarPermiso($UsuarioGHId) {
        $list2 = Array();
        array_push($list2, Array(
            'UsuarioGHId' => $UsuarioGHId,
            'VBGestionHumana' => true,
        ));
        return $list2;
    }

    public function MAPToCreate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'PrimerNombre' => $list->PrimerNombre,
            'SegundoNombre' => $list->SegundoNombre,
            'PrimerApellido' => $list->PrimerApellido,
            'SegundoApellido' => $list->SegundoApellido,
            'Genero' => $list->Genero,
            'Cedula' => $list->Cedula,
            'Usuario' => $list->Usuario,
            'Celular' => $list->Celular,
            'Email' => $list->Correo,
            'CodigoTarjeta' => $list->CodigoTarjeta,
            'FechaNacimiento' => $list->FechaNacimiento,
            'IsAdOrAsist' => $list->IsAdOrAsist,
            'HasHorarioFijo' => $list->HasHorarioFijo,
            'Rh' => $list->Rh,
            'Foto' => $list->Foto,
            'UsuarioIntranetId' => $list->UsuarioIntranetId,
            'TurnoId' => $list->TurnoId,
            'TipoPersona' => $list->TipoPersona,
            'CargoId' => $list->CargoId,
            'JefeId' => $list->JefeId,
            'CreatedBy' => $list->CreatedBy,
        ));
        return $list2;
    }

    public function MapToUpdateEstadoPersona($Estado, $ModifiedBy) {
        $list2 = Array();
        array_push($list2, Array(
            'Estado' => $Estado,
            'ModifiedBy' => $ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToUpdate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'PrimerNombre' => $list->PrimerNombre,
            'SegundoNombre' => $list->SegundoNombre,
            'PrimerApellido' => $list->PrimerApellido,
            'SegundoApellido' => $list->SegundoApellido,
            'Genero' => $list->Genero,
            'Cedula' => $list->Cedula,
            'Usuario' => $list->Usuario,
            'Celular' => $list->Celular,
            'Email' => $list->Correo,
            'CodigoTarjeta' => $list->CodigoTarjeta,
            'FechaNacimiento' => $list->FechaNacimiento,
            'IsAdOrAsist' => $list->IsAdOrAsist,
            'HasHorarioFijo' => $list->HasHorarioFijo,
            'Rh' => $list->Rh,
            'Foto' => $list->Foto,
            'Firma' => $list->Firma,
            'UsuarioIntranetId' => $list->UsuarioIntranetId,
            'TurnoId' => $list->TurnoId,
            'TipoPersona' => $list->TipoPersona,
            'CargoId' => $list->CargoId,
            'JefeId' => $list->JefeId,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToUpdateTurnoId($TurnoId, $ModifiedBy) {
        $list2 = Array();
        array_push($list2, Array(
            'TurnoId' => $TurnoId,
            'ModifiedBy' => $ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToGH($JefeId, $CreatedBy, $Mes, $Year) {
        $list2 = Array();
        array_push($list2, Array(
            'JefeId' => $JefeId,
            'Mes' => $Mes,
            'Year' => $Year,
            'CreatedBy' => $CreatedBy,
        ));
        return $list2;
    }

    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d H:i:s');
    }

    function is_base64($str) {
        if (base64_encode(base64_decode($str, true)) === $str) {
            return true;
        } else {
            return false;
        }
    }

}

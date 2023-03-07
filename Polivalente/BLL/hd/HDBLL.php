<?php

require_once dirname(__FILE__) . '/../../DAL/hd/HDDAL.php';
require_once dirname(__FILE__) . '/../../DAL/hd/PacienteDAL.php';
require_once dirname(__FILE__) . '/../../Auth.php';

/**
 * Description of HDBLL
 *
 * @author DESARROLLO2
 */
class HDBLL
{
    private $hh;

    public function __construct() {
        $this->hh = new HDDAL();
    }

    public function GetHDById($HDId)
    {
        $HD = $this->hh->GetHDById($HDId);
        if (count($HD) == 0) {
            return "No se encontro la hoja de dieta N° $HDId";
        }
        $HD[0]->Detalles = $this->hh->GetDHDByHDId($HDId);
        $ph = new PacienteDAL();
        $Pacientes = [];
        if ($HD[0]->Estado == 'Activo') {
            $Pacientes = $ph->GetPacientesBySector($HD[0]->SECTOR);
            $this->utf8_encode_deep($Pacientes);
        }
        if (count($Pacientes) == 0) {
            for ($i = 0; $i < count($HD[0]->Detalles); $i++) {
                $HD[0]->Detalles[$i]->Continua = false;
                $HD[0]->Detalles[$i]->NoExiste = true;
                $HD[0]->Detalles[$i]->Nuevo = false;
            }
            return $HD; # se retorna la hoja sin cambio
        } else {
            // VERIFICAMOS LOS PAcientes que no existen en el servicio
            for ($i = 0; $i < count($HD[0]->Detalles); $i++) {
                $existeB = false;
                // Verificamos y marcamos como validos los pacientes que son de otros servicios
                if($HD[0]->Detalles[$i]->Trasladado){
                    $existeB = true;
                    $HD[0]->Detalles[$i]->Continua = true;
                    $HD[0]->Detalles[$i]->NoExiste = false;
                    $HD[0]->Detalles[$i]->Nuevo = false;
                    continue;
                }
                for ($j = 0; $j < count($Pacientes); $j++) {
                    if ($Pacientes[$j]) {
                        if ((strval($Pacientes[$j]->IDAFILIADO) === strval($HD[0]->Detalles[$i]->IDAFILIADO))) { # && !$Pacientes[$j]->MARCADO) {
                            $existeB = true;
                            $Pacientes[$j]->MARCADO = true;
                            $HD[0]->Detalles[$i]->Continua = true;
                            break;
                        }
                    }
                }
                if (!$existeB) {
                    $HD[0]->Detalles[$i]->Continua = false;
                    $HD[0]->Detalles[$i]->NoExiste = true;
                    $HD[0]->Detalles[$i]->Nuevo = false;
                }
            }
            // añadimos pacientes nuevos
            foreach ($Pacientes as $p) {
                $existeB = false;
                for ($i = 0; $i < count($HD[0]->Detalles); $i++) {
                    if (strval($p->IDAFILIADO) === strval($HD[0]->Detalles[$i]->IDAFILIADO)) {
                        $existeB = true;
                        break;
                    }
                }
                if (!$existeB) {
                    $p->Continua = true;
                    $p->Nuevo = true;
                    $p->Seleccionado = true;
                    array_push($HD[0]->Detalles, $p);
                }
            }
            return $HD;
        }
    }

    public function GetHDByIdNop($HDId)
    {
        
        $HD = $this->hh->GetHDById($HDId);
        if (empty($HD)) {
            return "No se encontro la hoja de dieta N° $HDId";
        }
        $HD[0]->Detalles = $this->hh->GetDHDByHDId($HDId);
        return $HD;
    }

    /*     * *
     * Validar Comida (Desayuno, almuerzo, cena) en un rango de fecha determinado
     */

    private function VCRangoFecha($Detalles, $Fecha, $HD = '09:00:00', $HA = '14:00:00', $HC = '20:00:00')
    {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        $FechaHOY = $this->GetFHNow();
        $HoraActual = $FechaHOY . ' ' . $datetime->format('H:i:s');
        // for ($i = 0; $i < count($Detalles); $i++) {
        //     if (strtotime($HoraActual) <= strtotime($Fecha . ' ' . $HD) && $Detalles[$i]->CanDesayunar == 1) {
        //         $Detalles[$i]->CanDesayunar = true;
        //     } else {
        //         $Detalles[$i]->CanDesayunar = false;
        //     }
        //     if (strtotime($HoraActual) <= strtotime($Fecha . ' ' . $HA) && $Detalles[$i]->CanAlmorzar == 1) {
        //         $Detalles[$i]->CanAlmorzar = true;
        //     } else {
        //         $Detalles[$i]->CanAlmorzar = false;
        //     }
        //     if (strtotime($HoraActual) <= strtotime($Fecha . ' ' . $HC) && $Detalles[$i]->CanCenar == 1) {
        //         $Detalles[$i]->CanCenar = true;
        //     } else {
        //         $Detalles[$i]->CanCenar = false;
        //     }
        // }
        return $Detalles;
    }

    public function GetVariables()
    {
        
        return $this->hh->GetVariables();
    }

    public function GetDistribucion()
    {
        
        return $this->hh->GetDistribucion();
    }

    public function GetEmpresas()
    {
        
        return $this->hh->GetEmpresas();
    }

    public function GetSectores()
    {
        
        return $this->hh->GetSectores();
    }

    public function GetHDs($Estado, $Dia, $Mes, $Year)
    {
        
        return $this->hh->GetHDs($Estado, $Dia, $Mes, $Year);
    }

    public function GetHDsByUsuarioId(string $UsuarioId, string $Estado, string $Dia, string $Mes, string $Year): ?array
    {
        
        return $this->hh->GetHDsByUsuarioId($UsuarioId, $Estado, $Dia, $Mes, $Year);
    }

    public function GetHDsByUsuarioEmpresa(string $UsuarioId, string $Estado, string $Dia, string $Mes, string $Year): ?array
    {
        
        $completosql = ";";
        switch ($Estado) {
            case 'Desayuno':
                $completosql = "AND (hd.Desayuno);";
                break;
            case 'MM':
                $completosql = "AND (hd.MM);";
                break;
            case 'Almuerzo':
                $completosql = "AND (hd.Almuerzo);";
                break;
            case 'MT':
                $completosql = "AND (hd.MT);";
                break;
            case 'Cena':
                $completosql = "AND (hd.Cena);";
                break;
            case 'MN':
                $completosql = "AND (hd.MN);";
                break;
            case 'TODOS':
                $completosql = ";";
                break;
            default:
                $completosql = ";";
                break;
        }
        return $this->hh->GetHDsByUsuarioEmpresa($UsuarioId, $completosql, $Dia, $Mes, $Year);
    }
    
    public function VerificarPaciente($NoAdmision, $Distribucion, $FechaAPreparar)
    {
        
        $dhd = $this->hh->VerificarPaciente($NoAdmision, $Distribucion, $FechaAPreparar);
        return [empty($dhd)];
    }

    public function GetCantidadesAP($Estado, $Dia, $Mes, $Year)
    {
        
        $sql = "";
        if ($Estado === 'Desayuno Preparado') {
            $sql = "SELECT  dhd.DId as VarId,
                (SELECT count(_dhd.DId) FROM sa_dhd as _dhd where _dhd.HDId = dhd.HDId and _dhd.DId = dhd.DId and _dhd.DId > 0) Cantidad
                from sa_dhd as dhd
                inner join sa_hd as hd on dhd.HDId = hd.HDId
                where
                dayofmonth(hd.Fecha) = $Dia and month(hd.Fecha) = $Mes and year(hd.Fecha) = $Year and hd.CDesayuno";
        } else if ($Estado === 'Almuerzo Preparado') {
            $sql = "SELECT  dhd.AId as VarId,
            (SELECT count(_dhd.AId) FROM sa_dhd as _dhd where _dhd.HDId = dhd.HDId and _dhd.AId = dhd.AId and _dhd.AId > 0) Cantidad
            from sa_dhd as dhd
            inner join sa_hd as hd on dhd.HDId = hd.HDId
            where
            dayofmonth(hd.Fecha) = $Dia and month(hd.Fecha) = $Mes and year(hd.Fecha) = $Year and hd.CAlmuerzo";
        } else if ($Estado === 'Cena Preparada') {
            $sql = "SELECT  dhd.CId as VarId,
            (SELECT count(_dhd.CId) FROM sa_dhd as _dhd where _dhd.HDId = dhd.HDId and _dhd.CId = dhd.CId and _dhd.CId > 0) Cantidad
            from sa_dhd as dhd
            inner join sa_hd as hd on dhd.HDId = hd.HDId
            where
            dayofmonth(hd.Fecha) = $Dia and month(hd.Fecha) = $Mes and year(hd.Fecha) = $Year and hd.CCena";
        }
        return $this->hh->GetCantidadesAP($sql);
    }

    public function GetPacientesBySector($Sector, $Key)
    {
        $hs = new Auth();
        $jwt = $this->getBearerToken();
        $result = $hs->DecodeJWT($jwt);
        if (is_object($result)) {
            $ph = new PacienteDAL();
            $Pacientes = $ph->GetPacientesBySector($Sector);
            $Fecha = $this->GetFHNow();
            $Pacientes = $this->VCRangoFecha($Pacientes, $Fecha);
            return $Pacientes;
        } else {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }
    }

    public function CreateHD($hd)
    {
        
        $_Fecha = $this->GetFHNow();
        $_Hora = $this->GetFHNow("Hora");
        $hoja = $this->hh->GetHDBySecDay($hd->Sector, $_Fecha);
        if (count($hoja) > 0) {
            return "Este sector ya se encuentra registrado, por favor editelo";
        }
        // retiramos los deselcionados
        $array_seleccion = [];
        for ($l = 0; $l < count($hd->Detalles); $l++) {
            if ($hd->Detalles[$l]->Seleccionado) {
                array_push($array_seleccion, $hd->Detalles[$l]);
            }
        }
        $hd->Detalles = $array_seleccion;
        $HDA = $this->VerificarDistribucion($hd, array(
            "DESCRIPCION" => $hd->Descripcion,
            "SECTOR" => $hd->Sector,
            "UsuarioId" => $hd->UResponsableId,
            "FechaCreacion" => $_Fecha . ' ' . $_Hora,
            "Fecha" => $hd->FechaAPreparar,
            "CreatedBy" => $hd->CreatedBy,
        ));
        $id = $this->hh->CreateHD([$HDA]);
        if (is_array($id) && count($id) > 0) {
            $Detalles = [];
            foreach ($hd->Detalles as $d) {
                if (property_exists($d, "TipoId")) {
                    $array_dhd = $this->DhdByDistribucion($hd, $d, array(
                        "HDId" => $id[0], // ID de la hoja
                        "HABCAMA" => $d->habcama,
                        "NOADMISION" => $d->NOADMISION,
                        "IDAFILIADO" => $d->IDAFILIADO,
                        "EDAD" => $d->EDAD,
                        "NOMBREAFI" => $d->NOMBREAFI,
                        "TIPOESTANCIA" => $d->TIPOESTANCIA,
                        "IDTERCERO" => $d->IDTERCERO,
                        "RAZONSOCIAL" => $d->RAZONSOCIAL,
                        "SEXO" => $d->SEXO,
                        "ESTADOPSALIDA" => $d->ESTADOPSALIDA,
                        "Trasladado" => property_exists($d, "Trasladado") ? $d->Trasladado : 0,
                        "FNACIMIENTO" => is_object($d->FNACIMIENTO) ? $d->FNACIMIENTO->date : null,
                        "Fecha" => is_object($d->FECHA) ? $d->FECHA->date : null,
                        "FIHD" => $_Fecha,
                        "HIHD" => $_Hora,
                        "CreatedBy" => $hd->CreatedBy,
                    ));
                    array_push($Detalles, $array_dhd);
                }
            }
            $ids_detalles = $this->hh->CreateDHD($Detalles);
            if (count($ids_detalles) > 0) {
                return [true];
            } else {
                return "Hubo un error al momento de guardar la información.";
            }
        }
    }

    // #region CreatePHD
    public function CreatePHD($hd)
    {
        $array_Edhd = [];
        $array_Cdhd = []; // Registramos comidas
        
        $_Fecha = $this->GetFHNow();
        $_Hora = $this->GetFHNow("Hora");
        $distribucion = $this->hh->GetDistribucionByNombre($hd->Distribucion);
        if (count($distribucion) == 0) {
            return "No se reconoce tipo de alimentacion, ejem. Desayuno, almuerzo o cena";
        }
        // retiramos los que se encuentran sin seleccionar
        $array_seleccion = [];
        for ($l = 0; $l < count($hd->Detalles); $l++) {
            if ($hd->Detalles[$l]->Seleccionado) {
                array_push($array_seleccion, $hd->Detalles[$l]);
            }
        }
        $hd->Detalles = $array_seleccion;
        $Detalles = [];
        $contador = 0;
        foreach ($hd->Detalles as $d) {
            if (property_exists($d, "TipoId")) {
                $array_dhd = array(
                    "HDId" => $hd->HDId, // ID de la hoja
                    "HABCAMA" => $d->habcama,
                    "NOADMISION" => $d->NOADMISION,
                    "IDAFILIADO" => $d->IDAFILIADO,
                    "EDAD" => $d->EDAD,
                    "NOMBREAFI" => $d->NOMBREAFI,
                    "TIPOESTANCIA" => $d->TIPOESTANCIA,
                    "IDTERCERO" => $d->IDTERCERO,
                    "RAZONSOCIAL" => $d->RAZONSOCIAL,
                    "SEXO" => $d->SEXO,
                    "ESTADOPSALIDA" => $d->ESTADOPSALIDA,
                    "Trasladado" => property_exists($d, "Trasladado") ? $d->Trasladado : 0,
                    "FNACIMIENTO" => is_object($d->FNACIMIENTO) ? $d->FNACIMIENTO->date : null,
                    "Fecha" => is_object($d->FECHA) ? $d->FECHA->date : null,
                    "FIHD" => $_Fecha,
                    "HIHD" => $_Hora,
                    "CreatedBy" => $hd->CreatedBy,
                    $hd->Distribucion . "Id" => $d->TipoId,
                    $hd->Distribucion => $d->Tipo,
                    "O" . $hd->Distribucion => $d->Observacion,
                    "CreatedBy" => $hd->CreatedBy,
                );
                array_push($Detalles, $array_dhd);
                $contador++;
            }
        }
        $ids_detalles = $this->hh->CreateDHD($Detalles);
        if (is_array($ids_detalles)) {
            return [true];
        } else {
            return "Hubo un error al momento de guardar la información.";
        }
    }
    // #endregion

    public function CreateNCHD($hd)
    {
        
        $_Fecha = $this->GetFHNow();
        $_Hora = $this->GetFHNow("Hora");
        $distribucion = $this->hh->GetDistribucionByNombre($hd->Distribucion);
        if (count($distribucion) == 0) {
            return "No se reconoce tipo de alimentacion, ejem. Desayuno, almuerzo o cena";
        }
        // retiramos los que se encuentran sin seleccionar
        $array_seleccion = [];
        for ($l = 0; $l < count($hd->Detalles); $l++) {
            if ($hd->Detalles[$l]->Seleccionado) {
                array_push($array_seleccion, $hd->Detalles[$l]);
            }
        }
        $hd->Detalles = $array_seleccion;
        $Detalles = [];
        $ColumsDhd = ["DHDId", $hd->Distribucion . "Id", $hd->Distribucion, "O" . $hd->Distribucion, "ModifiedBy", "ModifiedAt"];
        $data_dhd = [];
        $_FechaHora = $this->GetFHNow('fh');
        $data_uhd = array();
        switch ($hd->Distribucion) {
            case "Desayuno":
                $data_uhd = array(
                    "Desayuno" => 1,
                    "FSDesayuno" => $_FechaHora,
                    "RD" => $hd->ModifiedBy,
                    "RDId" => $hd->ResponsableId,
                    "UDId" => $hd->UResponsableId,
                    "ModifiedBy" => $hd->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            case "MM":
                $data_uhd = array(
                    "MM" => 1,
                    "FSMM" => $_FechaHora,
                    "RMM" => $hd->ModifiedBy,
                    "RMMId" => $hd->ResponsableId,
                    "UMMId" => $hd->UResponsableId,
                    "ModifiedBy" => $hd->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            case "Almuerzo":
                $data_uhd = array(
                    "Almuerzo" => 1,
                    "FSAlmuerzo" => $_FechaHora,
                    "RA" => $hd->ModifiedBy,
                    "RAId" => $hd->ResponsableId,
                    "UAId" => $hd->UResponsableId,
                    "ModifiedBy" => $hd->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            case "MT":
                $data_uhd = array(
                    "MT" => 1,
                    "FSMT" => $_FechaHora,
                    "RMT" => $hd->ModifiedBy,
                    "RMTId" => $hd->ResponsableId,
                    "UMTId" => $hd->UResponsableId,
                    "ModifiedBy" => $hd->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            case "Cena":
                $data_uhd = array(
                    "Cena" => 1,
                    "FSCena" => $_FechaHora,
                    "RC" => $hd->ModifiedBy,
                    "RCId" => $hd->ResponsableId,
                    "UCId" => $hd->UResponsableId,
                    "ModifiedBy" => $hd->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            case "MN":
                $data_uhd = array(
                    "MN" => 1,
                    "FSMN" => $_FechaHora,
                    "RMN" => $hd->ModifiedBy,
                    "RMNId" => $hd->ResponsableId,
                    "UMNId" => $hd->UResponsableId,
                    "ModifiedBy" => $hd->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            default:
                return "Comida a preparar desconocida";
        }
        $this->hh->UpdateHD([$data_uhd], $hd->HDId);
        foreach ($hd->Detalles as $d) {
            if (!property_exists($d, "DHDId")) {
                $array_dhd = array(
                    "HDId" => $hd->HDId, // ID de la hoja
                    "HABCAMA" => $d->habcama,
                    "NOADMISION" => $d->NOADMISION,
                    "IDAFILIADO" => $d->IDAFILIADO,
                    "EDAD" => $d->EDAD,
                    "NOMBREAFI" => $d->NOMBREAFI,
                    "TIPOESTANCIA" => $d->TIPOESTANCIA,
                    "IDTERCERO" => $d->IDTERCERO,
                    "RAZONSOCIAL" => $d->RAZONSOCIAL,
                    "SEXO" => $d->SEXO,
                    "ESTADOPSALIDA" => $d->ESTADOPSALIDA,
                    "FNACIMIENTO" => is_object($d->FNACIMIENTO) ? $d->FNACIMIENTO->date : null,
                    "Fecha" => is_object($d->FECHA) ? $d->FECHA->date : null,
                    "FIHD" => $_Fecha,
                    "HIHD" => $_Hora,
                    "CreatedBy" => $hd->CreatedBy,
                    $hd->Distribucion . "Id" => $d->TipoId,
                    $hd->Distribucion => $d->Tipo,
                    "O" . $hd->Distribucion => $d->Observacion,
                    "CreatedAt" => $this->GetFHNow('fh')
                );
                array_push($Detalles, $array_dhd);
            } else {
                array_push($data_dhd, array(
                    "DHDId" => $d->DHDId,
                    $d->TipoId,
                    $d->Tipo,
                    $d->Observacion,
                    $hd->CreatedBy,
                    $this->GetFHNow('fh')
                ));
            }
        }
        if (!empty($data_dhd)) {
            $this->hh->UpdateBulk("sa_dhd", $ColumsDhd, $data_dhd);
        }
        if (!empty($Detalles)) {
            $this->hh->CreateDHD($Detalles);
        }
        return [true];
    }

    public function PrepararCHD($data)
    {
        
        $_FechaHora = $this->GetFHNow('fh');
        $data_uhd = array();
        switch ($data->Distribucion) {
            case "Desayuno":
                $data_uhd = array(
                    "CDesayuno" => 1,
                    "FCDesayuno" => $_FechaHora,
                    "RPD" => $data->ModifiedBy,
                    "RPDId" => $data->ResponsableId,
                    "UPDId" => $data->UResponsableId,
                    "ModifiedBy" => $data->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            case "MM":
                $data_uhd = array(
                    "CMM" => 1,
                    "FCMM" => $_FechaHora,
                    "RPMM" => $data->ModifiedBy,
                    "RPMMId" => $data->ResponsableId,
                    "UPMMId" => $data->UResponsableId,
                    "ModifiedBy" => $data->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            case "Almuerzo":
                $data_uhd = array(
                    "CAlmuerzo" => 1,
                    "FCAlmuerzo" => $_FechaHora,
                    "RPA" => $data->ModifiedBy,
                    "RPAId" => $data->ResponsableId,
                    "UPAId" => $data->UResponsableId,
                    "ModifiedBy" => $data->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            case "MT":
                $data_uhd = array(
                    "CMT" => 1,
                    "FCMT" => $_FechaHora,
                    "RPMT" => $data->ModifiedBy,
                    "RPMTId" => $data->ResponsableId,
                    "UPMTId" => $data->UResponsableId,
                    "ModifiedBy" => $data->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            case "Cena":
                $data_uhd = array(
                    "CCena" => 1,
                    "FCCena" => $_FechaHora,
                    "RPC" => $data->ModifiedBy,
                    "RPCId" => $data->ResponsableId,
                    "UPCId" => $data->UResponsableId,
                    "ModifiedBy" => $data->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            case "MN":
                $data_uhd = array(
                    "CMN" => 1,
                    "FCMN" => $_FechaHora,
                    "RPMN" => $data->ModifiedBy,
                    "RPMNId" => $data->ResponsableId,
                    "UPMNId" => $data->UResponsableId,
                    "ModifiedBy" => $data->ModifiedBy,
                    "ModifiedAt" => $_FechaHora,
                );
                break;
            default:
                return "Comida a preparar desconocida";
        }
        $this->hh->UpdateHD([$data_uhd], $data->HDId);
        $data_dhd = [];
        $column_dhd = ["DHDId", "E" . $data->Distribucion, "ModifiedBy", "ModifiedAt"];
        foreach ($data->Detalles as $d) {
            if ($d->Estado == 'Activo') {
                array_push($data_dhd, [
                    $d->DHDId,
                    "Preparado",
                    $data->ModifiedBy,
                    $_FechaHora,
                ]);
            }
        }
        $this->hh->UpdateBulk("sa_dhd", $column_dhd, $data_dhd);
        return [true];
    }

    
    public function CancelarComida(object $uphd) : array
    {
        if ($uphd->Comida === 'Desayuno') {
            $prefijo = 'D';
        } elseif($uphd->Comida === 'Almuerzo') {
            $prefijo = 'A';
        } elseif($uphd->Comida === 'Cena') {
            $prefijo = 'C';
        } else {
            $prefijo = $uphd->Comida;
        }
        $count = $this->hh->UpdateBulk("sa_dhd", 
        ['DHDId', 'Cancelar' . $uphd->Comida, 'MotivoC' . $prefijo, 'FechaC'. $prefijo, 'ResponsableC' . $prefijo ], 
        [[$uphd->DHDId, true, $uphd->Motivo, $this->GetFHNow('fh'), $uphd->ModifiedBy]]);
        if ($count > 0) {
            return ['data' => 'Datos guardados correctamente.'];
        } else {
            return ['error' => 'Hubo un error al intentar guardar la información.'];
        }
    }

    public function UpdatePHD($uphd)
    {
        $data_dhd = [];
        
        $distribucion = $this->hh->GetDistribucionByNombre($uphd->Distribucion);
        if (count($distribucion) == 0) {
            return "No se reconoce tipo de alimentacion, ejem. Desayuno, almuerzo o cena";
        }
        // retiramos los que se encuentran sin seleccionar
        $array_seleccion = [];
        for ($l = 0; $l < count($uphd->Detalles); $l++) {
            if (property_exists($uphd->Detalles[$l], "Seleccionado")) {
                if ($uphd->Detalles[$l]->Seleccionado) {
                    array_push($array_seleccion, $uphd->Detalles[$l]);
                }
            }
        }
        $uphd->Detalles = $array_seleccion;
        $ColumsDhd = ["DHDId", $uphd->Distribucion . "Id", $uphd->Distribucion, "O" . $uphd->Distribucion, "ModifiedBy", "ModifiedAt"];
        foreach ($uphd->Detalles as $d) {
            if (property_exists($d, "NuevoTipoId")) {
                array_push($data_dhd, array(
                    "DHDId" => $d->DHDId,
                    $d->NuevoTipoId,
                    $d->Tipo,
                    $d->ObservacionComida,
                    $uphd->ModifiedBy,
                    $this->GetFHNow('fh')
                ));
            }
        }
        $this->hh->UpdateBulk("sa_dhd", $ColumsDhd, $data_dhd);
        return [true];
    }
    /**
     * Esta funcion genera la consulta sql para la matriz de estadisticas
     *
     * @param [type] $Mes
     * @param [type] $Year
     * @return [Matriz] $Estadisticas
     */
    public function GetEstadisticas($Empresa, $Mes, $Year)
    {
        $sql = "";
        if ($Empresa == 'TODOEMPRESA') {
            # Verificamos que el mes exista, sino lo creeamos
            $this->VerificarMes($Mes, $Year);
            $sql = "SELECT s.SECTOR, s.DESCRIPCION, t.* from sa_sector as s
            left join (
            select h.SECTOR as '_Sector', 
                {$this->BuildQuerySectorForEstadiscasDIA($Mes, $Year)}
                ContarComidasPorHDPorMes(h.SECTOR, $Mes, $Year) as Total
            from sa_hd as h
            where month(h.Fecha) = $Mes and year(h.Fecha) = $Year
            group by h.SECTOR
            ) as t on s.SECTOR = t._Sector 
            order by SECTOR";
        } else {
            # Verificamos que el mes exista, sino lo creeamos
            $this->VerificarMes($Mes, $Year);
            $sql = "SELECT s.SECTOR, s.DESCRIPCION, t.* from sa_sector as s
            left join (
            select h.SECTOR as '_Sector', 
                {$this->BuildQuerySectorForEstadiscasDIA($Mes, $Year)}
                ContarComidasPorHDPorMes(h.SECTOR, $Mes, $Year) as Total
            from sa_hd as h
            inner join sa_sector as s on s.SECTOR = h.SECTOR
            inner join sa_empresasector as es on es.SectorId = s.SectorId
            where month(h.Fecha) = $Mes and year(h.Fecha) = $Year and es.EmpresaId = $Empresa
            group by h.SECTOR
            ) as t on s.SECTOR = t._Sector 
            order by SECTOR";
        }
        
        return $this->hh->GetExecuteQuery($sql);
    }

    public function GetEstadisticasDetalladas($Empresa, $Dia, $Mes, $Year)
    {
        if ($Empresa == 'TODOEMPRESA') {
            return $this->hh->GetEstadisticasDetalladas($Dia, $Mes, $Year);
        } else {
            return $this->hh->GetEstadisticasDetalladasByEmpresa($Empresa, $Dia, $Mes, $Year);
        }
    }

    /**
     * Ayuda contruir query de estadisticas GENERALES
     *
     * @param [type] $Mes
     * @param [type] $Year
     * @return void
     */
    private function BuildQuerySectorForEstadiscasDIA($Mes, $Year)
    {
        $UltimoDiaMes = $this->day_last_month_day($Mes, $Year);
        $query = "";
        for ($i=1; $i <= $UltimoDiaMes ; $i++) { 
            $query .= "ContarComidasPorHDPorDia(h.SECTOR, $i, $Mes, $Year) as '$i', ";
        }
        return $query;
    }

    /**
     * Esta funcion permite completar la hoja de dieta por cada ditribucion(Desayuno, almuerzo, cena)
     *
     * @param [HojaDieta] $hd
     * @param [array] $HDA
     * @return $HDA MERGE
     */
    private function VerificarDistribucion($hd, $HDA)
    {
        switch ($hd->Distribucion) {
            case 'Desayuno':
                $HDA = array_merge(
                    $HDA,
                    [
                        "Desayuno" => 1,
                        "FSDesayuno" => $this->GetFHNow('fh'),
                        "RD" => $hd->Responsable,
                        "UDId" => $hd->UResponsableId,
                        "RDId" => $hd->ResponsableId,
                    ]
                );
                break;
            case 'MM':
                $HDA = array_merge(
                    $HDA,
                    [
                        "MM" => 1,
                        "FSMM" => $this->GetFHNow('fh'),
                        "RMM" => $hd->Responsable,
                        "UMMId" => $hd->UResponsableId,
                        "RMMId" => $hd->ResponsableId,
                    ]
                );
                break;
            case 'Almuerzo':
                $HDA = array_merge(
                    $HDA,
                    [
                        "Almuerzo" => 1,
                        "FSAlmuerzo" => $this->GetFHNow('fh'),
                        "RA" => $hd->Responsable,
                        "UAId" => $hd->UResponsableId,
                        "RAId" => $hd->ResponsableId,
                    ]
                );
                break;
            case 'MT':
                $HDA = array_merge(
                    $HDA,
                    [
                        "MT" => 1,
                        "FSMT" => $this->GetFHNow('fh'),
                        "RMT" => $hd->Responsable,
                        "UMTId" => $hd->UResponsableId,
                        "RMTId" => $hd->ResponsableId,
                    ]
                );
                break;
            case 'Cena':
                $HDA = array_merge(
                    $HDA,
                    [
                        "Cena" => 1,
                        "FSCena" => $this->GetFHNow('fh'),
                        "RC" => $hd->Responsable,
                        "UCId" => $hd->UResponsableId,
                        "RCId" => $hd->ResponsableId,
                    ]
                );
                break;
            case 'MN':
                $HDA = array_merge(
                    $HDA,
                    [
                        "MN" => 1,
                        "FSMN" => $this->GetFHNow('fh'),
                        "RMN" => $hd->Responsable,
                        "UMNId" => $hd->UResponsableId,
                        "RMNId" => $hd->ResponsableId,
                    ]
                );
                break;
            default:
                return "No se reconoce el tipo de solicitud. Ej. Desayuno, Almuerzo, Cena";
                break;
        }
        return $HDA;
    }
    /**
     * Verificamos la distribucion de los alimentos y actualizamos los datos
     *
     * @param [HojaDieta] $hd
     * @param [DetalleHojaDieta] $dhd
     * @param [Array_for_db] $array_dhd
     * @return array_dhd MERGE
     */
    private function DhdByDistribucion($hd, $dhd, $array_dhd)
    {
        switch ($hd->Distribucion) {
            case 'Desayuno':
                $array_dhd = array_merge($array_dhd, [
                    "DesayunoId" => $dhd->TipoId,
                    "Desayuno" => $dhd->Tipo,
                    "ODesayuno" =>  property_exists($dhd, 'Observacion') ? $dhd->Observacion : '',
                    "ODesayuno" =>  property_exists($dhd, 'Observacion') ? $dhd->Observacion : '',
                    "SectorPDesayuno" => $hd->Sector
                ]);
                break;
            case 'MM':
                $array_dhd = array_merge($array_dhd, [
                    "MMId" => $dhd->TipoId,
                    "MM" => $dhd->Tipo,
                    "OMM" =>  property_exists($dhd, 'Observacion') ? $dhd->Observacion : '',
                    "SectorPMM" => $hd->Sector
                ]);
                break;
            case 'Almuerzo':
                $array_dhd = array_merge($array_dhd, [
                    "AlmuerzoId" => $dhd->TipoId,
                    "Almuerzo" => $dhd->Tipo,
                    "OAlmuerzo" =>  property_exists($dhd, 'Observacion') ? $dhd->Observacion : '',
                    "SectorPAlmuerzo" => $hd->Sector
                ]);
                break;
            case 'MT':
                $array_dhd = array_merge($array_dhd, [
                    "MTId" => $dhd->TipoId,
                    "MT" => $dhd->Tipo,
                    "OMT" =>  property_exists($dhd, 'Observacion') ? $dhd->Observacion : '',
                    "SectorPMT" => $hd->Sector
                ]);
                break;
            case 'Cena':
                $array_dhd = array_merge($array_dhd, [
                    "CenaId" => $dhd->TipoId,
                    "Cena" => $dhd->Tipo,
                    "OCena" =>  property_exists($dhd, 'Observacion') ? $dhd->Observacion : '',
                    "SectorPCena" => $hd->Sector
                ]);
                break;
            case 'MN':
                $array_dhd = array_merge($array_dhd, [
                    "MNId" => $dhd->TipoId,
                    "MN" => $dhd->Tipo,
                    "OMN" =>  property_exists($dhd, 'Observacion') ? $dhd->Observacion : '',
                    "SectorPMN" => $hd->Sector
                ]);
                break;
            default:
                return "No se reconoce el tipo de solicitud. Ej. Desayuno, Almuerzo, Cena";
                break;
        }
        return $array_dhd;
    }

    public function GetPacienteByNoAdmision($NoAdmision)
    {
        $ph = new PacienteDAL();
        $Pacientes = $ph->GetPacienteByNoAdmision($NoAdmision);
        $this->utf8_encode_deep($Pacientes);
        return $Pacientes;
    }

    /**
     * Get header Authorization
     * */
    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    private function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * Obtener fechas
     *
     * @param string $F Fecha|fh|h
     * @return string
     */
    private function GetFHNow(string $F = "Fecha"): string
    {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        $format = '';
        switch ($F) {
            case 'Fecha':
                $format = 'Y-m-d';
                break;
            case 'fh':
                $format = 'Y-m-d H:i:s';
                break;

            default:
                $format = 'H:i:s';
                break;
        }
        return $datetime->format($format);
    }

    private function day_last_month_day($Mes, $Ano) {
        $month = $Mes;
        $year = $Ano;
        $day = date("d", mktime(0, 0, 0, $month + 1, 0, $year));
        return $day;
    }

    public function utf8_encode_deep(&$input)
    {
        if (is_string($input)) {
            $input = $this->sanear_string(utf8_encode($input));
        } else if (is_array($input)) {
            foreach ($input as &$value) {
                $this->utf8_encode_deep($value);
            }
            unset($value);
        } else if (is_object($input)) {
            $vars = array_keys(get_object_vars($input));

            foreach ($vars as $var) {
                $this->utf8_encode_deep($input->$var);
            }
        }
    }

    public function sanear_string($string)
    {

        $string = trim($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array(
                "\\", "¨", "º", "-", "~",
                "·", "$", "%", "&", "/",
                "(", ")", "?", "'", "¡",
                "¿", "[", "^", "<code>", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":",
                "."
            ),
            '',
            $string
        );

        return $string;
    }
    /**
     * Verificamos si el mes existe, sino lo creamos
     *
     * @param [type] $Mes
     * @param [type] $Year
     * @return void
     */
    private function VerificarMes($Mes, $Year)
    {
        $m = $this->hh->VerificarMes($Mes, $Year);
        if (empty($m)) {
            $UltimoDiaMes = $this->day_last_month_day($Mes, $Year);
            $array_mes = [];
            for ($i=1; $i <= $UltimoDiaMes ; $i++) { 
                $mp = str_pad($Mes,2,"0",STR_PAD_LEFT);
                $dp = str_pad($i,2,"0",STR_PAD_LEFT);
                $fecha = "$Year-$mp-$dp";
                array_push($array_mes, [
                    "Dia" => $i,
                    "Mes" => $Mes,
                    "annio" => $Year,
                    "Fecha" => $fecha
                ]);
            }
            $this->hh->CreateMes($array_mes);
        }
    }
}

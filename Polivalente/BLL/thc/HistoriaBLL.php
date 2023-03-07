<?php

require_once dirname(__FILE__) . '/../../DAL/thc/HistoriaDAL.php';

/**
 * Description of HistoriaBLL
 *
 * @author DESARROLLO2
 */
class HistoriaBLL {

    public function GetAutoCompleteHC($data, $top = 10) {
        $helper = new HistoriaDAL();
        $obj = new stdClass();
        $obj->items = $helper->GetAutoCompleteHC($data, $top);
        return $obj;
    }
    
    public function GetLiteHistoriaByAdmision($data, $top = 10) {
        $helper = new HistoriaDAL();
        $obj = new stdClass();
        $obj->items = $helper->GetLiteHistoriaByAdmision($data, $top);
        return $obj;
    }
    
    public function GetTrazabilidadByHistoriaId($HistoriaId) {
        $helper = new HistoriaDAL();
        $th = $helper->GetTrazabilidadByHistoriaId($HistoriaId);
        return $th;
    }

    public function GetHistoriaById($HistoriaId) {
        $helper = new HistoriaDAL();
        $h = $helper->GetHistoriaById($HistoriaId);
        if (count($h) > 0) {
            return $h[0];
        } else {
            return NULL;
        }
    }

    public function GetMisHistorias($UsuarioId) {
        $helper = new HistoriaDAL();
        return $helper->GetMisHistorias($UsuarioId);
    }
    
    public function GetHistoriasPR($UsuarioId) {
        $helper = new HistoriaDAL();
        return $helper->GetHistoriasPR($UsuarioId);
    }

    public function GetHistoriaCKrystalosByNoAdmision($NoAdmision) {
        $helper = new HistoriaDAL();
        $h = $helper->GetHistoriaByAdmision($NoAdmision);
        if (count($h) == 0) {
            $Datos = $helper->GetHistoriaCKrystalosByNoAdmision($NoAdmision);
            $this->utf8_encode_deep($Datos);
            return $Datos;
        } else {
            return "Esta historia ya se encuentra registrada en el sistema";
        }
    }

    public function CreateHistoria($Historia) {
        $helper = new HistoriaDAL();
        $HIds = $helper->CreateHistoria($this->MAPToCreateHistoria($Historia));
        $ids = [];
        if (gettype($HIds) != 'string') {
            $ids = $helper->CreateTHistoria($this->MAPToCreateTH($Historia, $HIds));
            return $ids;
        } else {
            return "Debes seleccionar minimo una historia";
        }
    }

    public function CreateTraslado($Traslado) {
        $helper = new HistoriaDAL();
        for ($index = 0; $index < count($Traslado->Historias); $index++) {
            $helper->FinTHistoria([array(
            "FechaFin" => $this->getDatetimeNow(),
            'ModifiedBy' => $Traslado->CreatedBy,
            "ModifiedAt" => $this->getDatetimeNow(),
                )], $Traslado->UsuarioEntregaId, $Traslado->Historias[$index]->HistoriaId);
        }
        $ids = $helper->CreateTHistoria($this->MAPToCreateTraslado($Traslado));
        return $ids;
    }
    
    public function RecibirHistoria($list) {
        $helper = new HistoriaDAL();
        for ($index = 0; $index < count($list->HistoriaPR); $index++) {
            $helper->FinTHistoria([array(
            "FechaFin" => $this->getDatetimeNow(),
            'ModifiedBy' => $list->NombreUsuarioRecibe,
            "ModifiedAt" => $this->getDatetimeNow(),
                )], $list->UsuarioRecibeId, $list->HistoriaPR[$index]->HistoriaId);
        }
        $list->Historias = $list->HistoriaPR;
        $GrupoId = $helper->GetGrupoByUsuarioId($list->UsuarioRecibeId);
        $list->GrupoId = $GrupoId[0]->GrupoId;
        $id = $helper->CreateTHistoria($this->MAPToCreateTraslado($list));
        return $id;
    }
    
    // <editor-fold defaultstate="collapsed" desc="MAPS">
    public function MAPToCreateTraslado($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list->Historias); $index++) {
            array_push($list2, Array(
                'UsuarioId' => $list->UsuarioId,
                'GrupoId' => $list->GrupoId,
                'HistoriaId' => $list->Historias[$index]->HistoriaId,
                'NombreUsuario' => $list->NombreUsuario,
                'UsuarioRecibeId' => $list->Estado == 'Recibido' || $list->Estado == 'Entrega' ? $list->UsuarioRecibeId : $list->UsuarioId,
                'Fecha' => $this->getDatetimeNow(),
                'CreatedBy' => $list->CreatedBy,
                'Estado' => $list->Estado,
                'IsRecibido' => $list->IsRecibido,
            ));
        }
        return $list2;
    }
    public function MAPToCreateTH($list, $HIds) {
        $list2 = Array();
        for ($index = 0; $index < count($HIds); $index++) {
            if ($HIds[$index] != NULL) {
                array_push($list2, Array(
                    'UsuarioId' => $list->UsuarioEntregaId,
                    'NombreUsuario' => $list->NombreUsuarioEntrega,
                    'UsuarioRecibeId' => $list->UsuarioId,
                    'GrupoId' => $list->GrupoId,
                    'HistoriaId' => $HIds[$index],
                    'Fecha' => $this->getDatetimeNow(),
                    'CreatedBy' => $list->CreatedBy,
                    'Estado' => $list->Estado,
                    'IsEntrega' => $list->IsEntrega,
                ));
            }
        }
        return $list2;
    }

    public function MAPToCreateHistoria($list) {
        $list2 = Array();
        foreach ($list->Historias as $key => $value) {
            if (property_exists($value, "NOADMISION")) {
                $FECHAALTAADMINISTRATIVA = NULL;
                $FECHAALTAMED = NULL;
                if ($value->FECHAALTAADMINISTRATIVA) {
                    $FECHAALTAADMINISTRATIVA = $value->FECHAALTAADMINISTRATIVA->date;
                }
                if ($value->FECHAALTAMED) {
                    $FECHAALTAMED = $value->FECHAALTAMED->date;
                }
                array_push($list2, Array(
                    'NOADMISION' => $value->NOADMISION,
                    'PNOMBRE' => $value->PNOMBRE,
                    'SNOMBRE' => $value->SNOMBRE,
                    'PAPELLIDO' => $value->PAPELLIDO,
                    'SAPELLIDO' => $value->SAPELLIDO,
                    'TIPO_DOC' => $value->TIPO_DOC,
                    'IDAFILIADO' => $value->IDAFILIADO,
                    'FECHAALTAADMINISTRATIVA' => $FECHAALTAADMINISTRATIVA,
                    'FECHAALTAMED' => $FECHAALTAMED,
                    'CERRADA' => $value->CERRADA,
                    'EPS' => $value->EPS,
                    'SECTOR' => $value->SECTOR,
                    'CreatedBy' => $list->CreatedBy,
                ));
            }
        }
        return $list2;
    }

// </editor-fold>
    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d H:i:s');
    }
    public function utf8_encode_deep(&$input) {
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
    function sanear_string($string) {
        $string = trim($string);
        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );
        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );
        $string = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );
        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );
        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );
        $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string
        );
        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
                array("\\", "¨", "º", "-", "~",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "<code>", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            "."), '', $string
        );
        return $string;
    }
}

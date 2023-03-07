<?php

/**
 * Description of RondaMantBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/pol/RondaMantDAL.php';

class RondaMantBLL {

    public function GetDetalleRMById($RondaMantId, $PREFIJO) {
        $Helper = new RondaMantDAL();
        return $Helper->GetDetalleRMById($RondaMantId, $PREFIJO);
    }

    public function GetRondaMants($UsuarioId, $PREFIJO) {
        $Helper = new RondaMantDAL();
        return $Helper->GetRondaMants($UsuarioId, $PREFIJO);
    }

    public function CreateRondaMant($RondaMant, $PREFIJO) {
        $Helper = new RondaMantDAL();
        $RMId = $Helper->CreateRondaMant($this->MapToCreateRM($RondaMant), $PREFIJO);
        if (count($RMId) > 0) {
            // Creamos los detalles por medio de un insert SQL
            $sql = "INSERT INTO `{$PREFIJO}_detallerondamant`
                (
                `ServicioId`,
                `Descripcion`,
                `TecnicoResponsable`,
                `RondaMantId`,
                `CoordinadorFirmaId`,
                `CreatedBy`)
                VALUES ";
            $cont = 0;
            $total = count($RondaMant->DetalleRonda);
            foreach ($RondaMant->DetalleRonda as $dr) {
                $data = "'{$dr->ServicioId}','{$dr->Descripcion}','{$dr->TecnicoResponsable}','{$RMId[0]}','{$dr->CoordinadorId}','{$RondaMant->Responsable}'";
                $sql .= "({$data})";
                if ($cont < $total - 1) {
                    $sql .= ",";
                } else if ($cont == $total - 1) {
                    $sql .= ";";
                }
                $cont++;
            }
            $Helper->CreateDetalleRondaMant($sql);
            return [true];
        } else {
            return "Hubo un error al momento de guardar la información de la ronda";
        }
    }

    public function UpdateDetalleRondaMant($RondaMant, $PREFIJO) {
        $Helper = new RondaMantDAL();
        // SOLO COMPLETAMOS LO QUE HACE FALTA
        // Cumplimiento y observaciones
        $query = "";
        $RondaMant->DetalleRonda = json_decode($RondaMant->DetalleRonda);
        foreach ($RondaMant->DetalleRonda as $dr) {
            $sql = "UPDATE `{$PREFIJO}_detallerondamant` SET 
                `Cumplimiento`= '{$dr->Cumplimiento}',  
                `Observaciones`= '{$dr->Observaciones}',  
                `ModifiedBy`= '{$RondaMant->ModifiedBy}',  
                `ModifiedAt`= '{$this->getDatetimeNow()}'
                WHERE `DetalleRondaMantId`={$dr->DetalleRondaMantId}; ";
                $Helper->UpdateDetalleRondaMant($sql);
        }
        
            
        return [true];
    }

    public function MapToCreateRM($list) {
        return [Array(
        "SedeId" => $list->SedeId,
        "Fecha" => $list->Fecha,
        "Hora" => $list->Hora,
        "Responsable" => $list->Responsable,
        "CreatedBy" => $list->CreatedBy
        )];
    }
    
    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d H:i:s');
    }
}

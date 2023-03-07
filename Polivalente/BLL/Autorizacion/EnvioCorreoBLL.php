<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/Autorizacion/EnvioCorreoDAL.php';
require_once dirname(__FILE__) . '/LogEmailBLL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require_once dirname(__FILE__) . '/../../BLL/seguridad/UsuarioBLL.php';

class EnvioCorreoBLL {

    public function GetAll() {
        $db = new EnvioCorreoDAL();
        return $db->getAll();
    }
    
    public function getToSendEmailByOrden($EnvioCorreoId, $OrdenEnCurso) {
        $db = new EnvioCorreoDAL();
        return $db->getToSendEmailByOrden($EnvioCorreoId, $OrdenEnCurso);
    }
    
    public function GetSiguiente($ProtocoloId, $Orden)  {
        $db = new EnvioCorreoDAL();
        return $db->GetSiguiente($ProtocoloId, $Orden);
    }

    public function GetAllById($EnvioCorreoId) {
        $db = new EnvioCorreoDAL();
        return $db->getAllById($EnvioCorreoId);
    }

    public function CreateEnvioCorreo($EnvioCorreo) {
        $db = new EnvioCorreoDAL();
        $leh = new LogEmailBLL();
        $id = $db->CreateEnvioCorreo($this->MAPToEnvioCorreo($EnvioCorreo));
        if (count($id) > 0) {
            $leh->CreateLogEmail($EnvioCorreo, $id[0]);
            
            return $id;
        } else {
            return "No se han podido guardar los datos. MANDESE UNA MERIENDA A SISTEMAS. LLAMENOS... Att: SISTEMAS. :D";
        }
    }

    public function MAPToEnvioCorreo($list) {
        $list2 = Array();
        array_push($list2, Array(
            'EmailSolicitante' => $list->EmailSolicitante,
            'ProtocoloId' => $list->ProtocoloId,
            'Archivos' => json_encode($list->Archivos),
            'Mensaje' => $list->Mensaje,
            'OrdenEnCurso' => 0,
            'CreatedBy' => $list->CreatedBy,
        ));
        return $list2;
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'NombreKrystalos' => $list->NombreKrystalos,
            'CodigoKrystalos' => $list->CodigoKrystalos,
            'Nombre' => $list->Nombre,
            'GrupoId' => $list->GrupoId,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToUpdate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'NombreKrystalos' => $list->NombreKrystalos,
            'CodigoKrystalos' => $list->CodigoKrystalos,
            'Nombre' => $list->Nombre,
            'GrupoId' => $list->GrupoId,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));

        return $list2;
    }

    public function MAPToDelete($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Estado' => 'Inactivo',
            'ModifiedAt' => $list->ModifiedAt,
        ));
        return $list2;
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ H:i:s');
    }

}

<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/Autorizacion/LogEmailDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/AutorizacionBLL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require_once dirname(__FILE__) . '/../../BLL/seguridad/UsuarioBLL.php';

class LogEmailBLL {

    public function GetAll() {
        $db = new LogEmailDAL();
        return $db->getAll();
    }

    public function GetAllByUserId($UserId) {
        $db = new LogEmailDAL();
        return $db->getAllByUserId($UserId);
    }

    public function GetAllByPlantilla($ServicioId, $UserId) {
        $db = new LogEmailDAL();
        return $db->GetAllByPlantilla($ServicioId, $UserId);
    }

    public function GetAllBySedeId($UserId) {
        $db = new LogEmailDAL();
        $hs = new SedeBLL();
        $list = json_decode($hs->GetAllByUserId($UserId));
        $listado = array();
        foreach ($list as $s) {
            $listado = array_merge($listado, $db->getAllBySedeId($s->SedeId));
        }
        return $listado;
    }

    public function GetAllById($LogEmailId) {
        $db = new LogEmailDAL();
        return $db->getAllById($LogEmailId);
    }

    public function CreateLogEmail($EnvioCorreo, $EnvioCorreoId) {
        $db = new LogEmailDAL();
        $ah = new AutorizacionBLL();
       
        $ItemsProtocolo = $ah->GetItemProtocoloById($EnvioCorreo->ProtocoloId);
        if (count($ItemsProtocolo) > 0) {
            $id = $db->CreateLogEmail($this->MAPToLogEmail($EnvioCorreoId, $ItemsProtocolo[0]->Orden, $EnvioCorreo->CreatedBy));
            if (count($id) > 0) {
                $eh = new sendMail();
                $e = new EmpresaBLL();
                $EmpresaObj = $e->GetEmpresa();
                $eh->EnviarEmail_Autorizacion($EmpresaObj, $ItemsProtocolo[0]->Nombre, $ItemsProtocolo[0]->Email, $EnvioCorreo->Archivos, "ASUNTO de PRUEBA ");
                return $id;
            }
            
        } else {
            return "No se han podido enviar el email";
        }
    }

    public function MAPToLogEmail($EnvioCorreoId, $Orden, $CreatedBy) {
        $list2 = Array();
        array_push($list2, Array(
            'EnvioCorreoId' => $EnvioCorreoId,
            'Orden' => $Orden,
            'CreatedBy' => $CreatedBy,
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

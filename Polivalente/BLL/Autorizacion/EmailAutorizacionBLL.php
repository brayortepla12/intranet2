<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';

class EmailAutorizacionBLL {

    public function EnviarEmail($files) {
        $eh = new sendMail();
        $Eh = new EmpresaBLL();
        $Empresa = $Eh->GetEmpresa();
        return $eh->EnviarEmail_Autorizacion($Empresa, "franklin", "ospi89@hotmail.com", $files, "Asuntos");
    }

    public function GetAllByUserId($UserId) {
        $db = new ArticuloDAL();
        return $db->getAllByUserId($UserId);
    }
    
    public function GetAllByPlantilla($ServicioId, $UserId) {
        $db = new ArticuloDAL();
        return $db->GetAllByPlantilla($ServicioId, $UserId);
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ H:i:s');
    }

}

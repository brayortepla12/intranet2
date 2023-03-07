<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/facturacion/CensosDAL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
class CompartirBLL {
    
    public function Compartir($de, $para, $url) {
        $Security = new Security();
        $token = $Security->GenerateTokenHoras($de, "Frank_123458", 2);
        $url = $url . "&TokenValid=" . $token;
        $Eh = new EmpresaBLL();
        $EmpresaObj = $Eh->GetEmpresa();
        $sh = new sendMail();
        $sh->EnviarEmail_Notificacion($EmpresaObj, "Descargar Excel", "<p>Por medio de la siguiente dirección podras generar un archivo excel. <a href='" . $url . "'>Enlace</a><br> <strong>Nota: este mensaje solo es valido por dos horas.</strong></p>", $para, $para);
    }
}

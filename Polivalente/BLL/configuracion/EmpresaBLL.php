<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/EmpresaDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';

class EmpresaBLL {
    
    public function GetEmpresa() {
        $db = new EmpresaDAL();
        return $db->GetEmpresa();
    }
    
    public function UpdateEmpresa($list, $id) {
        $db = new EmpresaDAL();
        return $db->UpdateEmpresa($this->MAPToArray($list), $id);
    }
    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            
            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'Nit' => $list[$index]->Nit,
                'Direccion' => $list[$index]->Direccion,
                'Logo' => $list[$index]->Logo,
                'Telefono' => $list[$index]->Telefono,
                'Contacto' => $list[$index]->Contacto,
                'SitioWeb' => $list[$index]->SitioWeb,
                'Correo' => $list[$index]->Correo,
                'SMTP' => $list[$index]->SMTP,
                'PuertoSmtp' => $list[$index]->PuertoSmtp,
                'CorreoSmtp' => $list[$index]->CorreoSmtp,
                'PasswordSmtp' => $list[$index]->PasswordSmtp,
                'FormatoCorreo' => $list[$index]->FormatoCorreo,
                
                'ModifiedBy' => $list[$index]->ModifiedBy,
                'ModifiedAt' => date("Y-m-d"),
            ));
        }
        return $list2;
    }

}

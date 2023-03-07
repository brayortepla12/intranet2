<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/ProveedorDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';

class ProveedorBLL {
    
    public function GetAll() {
        $db = new ProveedorDAL();
        return $db->getAll();
    }
    
    public function CreateProveedor($Proveedor) {
        $db = new ProveedorDAL();
        $c = $db->GetProveedor($Proveedor[0]->Nombre);
        if ($c) {
            return 'Este servicio ya se encuentra registrado en la base de datos';
        }
        return $db->CreateProveedor($this->MAPToArray($Proveedor));
    }
    
    public function UpdateProveedor($list, $id) {
        $db = new ProveedorDAL();
        return $db->UpdateProveedor($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'Documento' => $list[$index]->Documento,
                'TipoDocumento' => $list[$index]->TipoDocumento,
                'Telefono' => $list[$index]->Telefono,
                'Direccion' => $list[$index]->Direccion,
                'Email' => $list[$index]->Email,
                'CreatedBy' => $list[$index]->CreatedBy
            ));
        }
        return $list2;
    }
    public function MAPToArray2($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            
            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'Documento' => $list[$index]->Documento,
                'TipoDocumento' => $list[$index]->TipoDocumento,
                'Telefono' => $list[$index]->Telefono,
                'Direccion' => $list[$index]->Direccion,
                'Email' => $list[$index]->Email,
                'ModifiedBy' => $list[$index]->ModifiedBy,
                'ModifiedAt' => date("Y-m-d"),
            ));
        }
        return $list2;
    }

}

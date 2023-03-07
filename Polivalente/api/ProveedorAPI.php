<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/ProveedorBLL.php';

class ProveedorAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new ProveedorBLL();
                echo $Helper->GetAll();
                break;
            case 'POST'://inserta
                if (isset($_POST["Proveedor"])) {
                    $Proveedor = json_decode($_POST["Proveedor"]);
                    $Helper = new ProveedorBLL();
                    echo json_encode($Helper->CreateProveedor($Proveedor));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Proveedor"]) && isset($_PUT["ID"])) {
                    $Proveedor = json_decode($_PUT["Proveedor"]);
                    $id = $_PUT["ID"];
                    $Helper = new ProveedorBLL();
                    echo json_encode($Helper->UpdateProveedor($Proveedor, $id));
                }

                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                $m = new ModuloDTO();
                $m = $this->Mapper($_DELETE);
                echo json_encode($m);
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

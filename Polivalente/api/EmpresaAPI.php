<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/EmpresaBLL.php';

class EmpresaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new EmpresaBLL();
                echo json_encode($Helper->GetEmpresa());
                break;
            case 'POST'://inserta
                if (isset($_POST["Empresa"])) {
                    $Empresa = json_decode($_POST["Empresa"]);
                    $Helper = new EmpresaBLL();
                    echo json_encode($Helper->CreateEmpresa($Empresa));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Empresa"]) && isset($_PUT["ID"])) {
                    $Empresa = json_decode($_PUT["Empresa"]);
                    $id = $_PUT["ID"];
                    $Helper = new EmpresaBLL();
                    echo json_encode($Helper->UpdateEmpresa($Empresa, $id));
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

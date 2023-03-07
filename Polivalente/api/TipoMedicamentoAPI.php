<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/cm/TipoMedicamentoBLL.php';

class TipoMedicamentoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new TipoMedicamentoBLL();
                echo json_encode($Helper->GetTipoMedicamento());
                break;
            case 'POST'://inserta
                if (isset($_POST["TipoMedicamento"])) {
                    $Helper = new TipoMedicamentoBLL();
                    $Obj = json_decode($_POST["TipoMedicamento"]);
                    echo json_encode($Helper->CreateTipoMedicamento($Obj));
                }else{
                    echo "invalido.";
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new TipoMedicamentoBLL();
                if (isset($_PUT["TipoMedicamento"])) {
                    $Obj = json_decode($_PUT["TipoMedicamento"]);
                    echo json_encode($Helper->UpdateTipoMedicamento($Obj));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

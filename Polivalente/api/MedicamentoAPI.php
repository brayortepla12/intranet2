<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/cm/MedicamentoBLL.php';

class MedicamentoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new MedicamentoBLL();
                
                if (isset($_GET["TipoMedicamentoId"])) {
                    $Helper = new MedicamentoBLL();
                    echo json_encode($Helper->GetMedicamentosByTipoMedicamentoId_Lite($_GET["TipoMedicamentoId"]));
                }else if (isset($_GET["TipoMedicamento_loteado"])) {
                    $Helper = new MedicamentoBLL();
                    echo json_encode($Helper->GetMedicamentosByTipoMedicamentoId_Lite_Loteado($_GET["TipoMedicamento_loteado"]));
                }else if (isset($_GET["TipoMedicamento"])) {
                    $Helper = new MedicamentoBLL();
                    echo json_encode($Helper->GetMedicamentos_Lite($_GET["TipoMedicamento"]));
                }else if (isset($_GET["MedicamentoId"])) {
                    $Helper = new MedicamentoBLL();
                    echo json_encode($Helper->GetMedicamentoById($_GET["MedicamentoId"]));
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Medicamento"])) {
                    $Helper = new MedicamentoBLL();
                    $Obj = json_decode($_POST["Medicamento"]);
                    echo json_encode($Helper->CreateMedicamento($Obj));
                }else{
                    echo "invalido.";
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new MedicamentoBLL();
                if (isset($_PUT["Medicamento"])) {
                    $Obj = json_decode($_PUT["Medicamento"]);
                    echo json_encode($Helper->UpdateMedicamento($Obj));
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

<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../../BLL/pol/RondaMantBLL.php';

class RondaMantAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new RondaMantBLL();
                if (isset($_GET["UsuarioId"]) && isset($_GET["PREFIJO"])) {
                    echo json_encode($Helper->GetRondaMants($_GET["UsuarioId"], $_GET["PREFIJO"]));
                }else if (isset($_GET["RondaMantId"]) && isset($_GET["PREFIJO_id"])) {
                    echo json_encode($Helper->GetDetalleRMById($_GET["RondaMantId"], $_GET["PREFIJO_id"]));
                } else {
                    echo "Error te equivocaste :)";
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["RondaMant"]) && isset($_POST["PREFIJO"])) {
                    $Helper = new RondaMantBLL();
                    $Obj = json_decode($_POST["RondaMant"]);
                    echo json_encode($Helper->CreateRondaMant($Obj,$_POST["PREFIJO"]));
                } 
//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new RondaMantBLL();
                if (isset($_PUT["RondaMant"]) && isset($_PUT["PREFIJO"])) {
                    $Obj = json_decode($_PUT["RondaMant"]);
                    echo json_encode($Helper->UpdateDetalleRondaMant($Obj, $_PUT["PREFIJO"]));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["RondaMantId"])) {
                    $Helper = new RondaMantBLL();
                    #echo $Helper->DeleteRondaMantByRondaMantId($_DELETE["RondaMantId"]);
                }
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

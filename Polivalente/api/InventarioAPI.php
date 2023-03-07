<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/almacen/ArticuloBLL.php';

class InventarioAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new ArticuloBLL();
                if (isset($_GET["Codigo"]) && isset($_GET["Act"])) {
                    echo json_encode($Helper->GetHojaDeVidaByCod($_GET["Codigo"]));
                }else{
                    echo "SUEÑALO";
                }
                break;
            case 'POST'://inserta
                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["InventarioId"])) {
                    $Helper = new InventarioBLL();
                    echo json_encode($Helper->DeleteInventario($_DELETE["InventarioId"]));
                }
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

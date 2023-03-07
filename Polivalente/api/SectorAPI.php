<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/cm/SectorBLL.php';

class SectorAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new SectorBLL();
                echo json_encode($Helper->GetSectores());
                break;
            case 'POST'://inserta
                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                echo "invalido.";
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                echo "invalido.";
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

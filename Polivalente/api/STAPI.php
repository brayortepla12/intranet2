<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/bs/STBLL.php';

class STAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new STBLL();
                if (isset($_GET["FFrom"]) && isset($_GET["FTo"])) {
                    echo json_encode($Helper->GetSTs($_GET["FFrom"], $_GET["FTo"]));
                }
                break;
            case 'POST'://inserta
                $Helper = new STBLL();
                if (isset($_POST["temp_sensor"]) && isset($_POST["NSensor"])) {
                    echo json_encode($Helper->CrearST($_POST["temp_sensor"], $_POST["NSensor"]));
                }else{
                    
                    echo "error";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $m = new ModuloDTO();
                $m = $this->Mapper($_PUT);
                echo json_encode($m);
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

<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/CunaBLL.php';

class CunaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new CunaBLL();
                if (isset($_GET["UserId"])) {
                    echo $Helper->GetAllByUserId($_GET["UserId"]);
                }else if (isset($_GET["Token"])) {
                    echo $Helper->GetAllByToken($_GET["Token"]);
                }else{
                    echo $Helper->GetAll();
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Cuna"])) {
                    $Cuna = json_decode($_POST["Cuna"]);
                    $Helper = new CunaBLL();
                    echo json_encode($Helper->CreateCuna($Cuna));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Cuna"]) && isset($_PUT["ID"])) {
                    $Cuna = json_decode($_PUT["Cuna"]);
                    $id = $_PUT["ID"];
                    $Helper = new CunaBLL();
                    echo json_encode($Helper->UpdateCuna($Cuna, $id));
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

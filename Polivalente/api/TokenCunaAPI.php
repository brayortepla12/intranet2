<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/TokenCunaBLL.php';

class TokenCunaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new TokenCunaBLL();
                if (isset($_GET["UserId"])) {
                    echo $Helper->GetAllByUserId($_GET["UserId"]);
                }else{
                    echo $Helper->GetAll();
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["TokenCuna"])) {
                    $TokenCuna = json_decode($_POST["TokenCuna"]);
                    $Helper = new TokenCunaBLL();
                    echo json_encode($Helper->CreateTokenCuna($TokenCuna));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["TokenCuna"]) && isset($_PUT["ID"])) {
                    $TokenCuna = json_decode($_PUT["TokenCuna"]);
                    $id = $_PUT["ID"];
                    $Helper = new TokenCunaBLL();
                    echo json_encode($Helper->UpdateTokenCuna($TokenCuna, $id));
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

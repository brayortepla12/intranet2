<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/facturacion/NFacturacionFBBLL.php';

class NFacturacionFBAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $NFacturacionFBHelper = new NFacturacionFBBLL();
                if (isset($_GET["UsuarioId"])) {
                    $NFacturacionFBHelper->NotificarByUsuarioId($_GET["UsuarioId"]);
                }else if (isset($_GET["UsuarioId_all"])){
                    $NFacturacionFBHelper->NotificarALLUsuario();
                }
                break;
            case 'POST'://inserta
                $m = new NFacturacionFBDTO();
                $m = $this->Mapper($_POST);
                echo json_encode($m);
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $m = new NFacturacionFBDTO();
                $m = $this->Mapper($_PUT);
                echo json_encode($m);
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                $m = new NFacturacionFBDTO();
                $m = $this->Mapper($_DELETE);
                echo json_encode($m);
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

    function getRealIP(){

        if (isset($_SERVER["HTTP_CLIENT_IP"])){

            return $_SERVER["HTTP_CLIENT_IP"];

        }elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){

            return $_SERVER["HTTP_X_FORWARDED_FOR"];

        }elseif (isset($_SERVER["HTTP_X_FORWARDED"])){

            return $_SERVER["HTTP_X_FORWARDED"];

        }elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){

            return $_SERVER["HTTP_FORWARDED_FOR"];

        }elseif (isset($_SERVER["HTTP_FORWARDED"])){

            return $_SERVER["HTTP_FORWARDED"];

        }else{

            return $_SERVER["REMOTE_ADDR"];

        }
    }       

}

//end class

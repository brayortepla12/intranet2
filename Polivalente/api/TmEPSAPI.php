<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/tm/TmEPSBLL.php';

class TmEPSAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new TmEPSBLL();
                if (isset($_GET["UsuarioId"])) {
                    echo $Helper->GetTmEPSes($_GET["UsuarioId"]);
                }else if (isset($_GET["EPSId"])) {
                    echo json_encode($Helper->GetTmEPSByEPSId($_GET["EPSId"]));
                }else{
                    echo json_encode($Helper->GetTmEPSs());
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["EPS"])) {
                    $Helper = new TmEPSBLL();
                    $Obj = json_decode($_POST["EPS"]);
                    echo json_encode($Helper->CreateTmEPS($Obj));
                } else {
                    echo "invalido.";
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new TmEPSBLL();
                if (isset($_PUT["TmEPS"])) {
                    $Obj = json_decode($_PUT["TmEPS"]);
                    echo json_encode($Helper->UpdateTmEPS($Obj));
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

    public function utf8_encode_deep(&$input) {
        if (is_string($input)) {
            $input = utf8_encode($input);
        } else if (is_array($input)) {
            foreach ($input as &$value) {
                $this->utf8_encode_deep($value);
            }
            unset($value);
        } else if (is_object($input)) {
            $vars = array_keys(get_object_vars($input));

            foreach ($vars as $var) {
                $this->utf8_encode_deep($input->$var);
            }
        }
    }

}

//end class

<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/cheque/CHEQUEBLL.php';

class CHEQUEAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new CHEQUEBLL();
                $Helper = new CHEQUEBLL();
                $Data = $Helper->GetCHEQUE( $_GET["Numero"]);
                $this->utf8_encode_deep($Data);
                echo json_encode($Data);
                break;
            case 'POST'://inserta
                if (isset($_POST["Numero"])) {
                    $Helper = new CHEQUEBLL();
                    $Data = $Helper->GetCHEQUE( $_POST["Numero"]);
                    $this->utf8_encode_deep($Data);
                    echo json_encode($Data);
                } else {
                    $json = file_get_contents('php://input');
                    $obj = json_decode($json);
                    $Helper = new CHEQUEBLL();
                    $Data = $Helper->GetCHEQUE($obj->Numero);
                    $this->utf8_encode_deep($Data);
                    echo json_encode($Data);
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["CHEQUE"]) && isset($_PUT["ID"])) {
                    $CHEQUE = json_decode($_PUT["CHEQUE"]);
                    $id = $_PUT["ID"];
                    $Helper = new CHEQUEBLL();
                    echo json_encode($Helper->UpdateCHEQUE($CHEQUE, $id));
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

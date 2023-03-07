<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/tm/TmDepartamentoBLL.php';

class TmDepartamentoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new TmDepartamentoBLL();
                echo json_encode($Helper->GetTmDepartamentos());
                break;
            case 'POST'://inserta
                if (isset($_POST["TmDepartamento"])) {
                    $Helper = new TmDepartamentoBLL();
                    $Obj = json_decode($_POST["TmDepartamento"]);
                    echo json_encode($Helper->CreateTmDepartamento($Obj));
                } else {
                    echo "invalido.";
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new TmDepartamentoBLL();
                if (isset($_PUT["TmDepartamento"])) {
                    $Obj = json_decode($_PUT["TmDepartamento"]);
                    echo json_encode($Helper->UpdateTmDepartamento($Obj));
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

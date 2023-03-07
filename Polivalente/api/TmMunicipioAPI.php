<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/tm/TmMunicipioBLL.php';

class TmMunicipioAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new TmMunicipioBLL();
                if (isset($_GET["DepartamentoId"])) {
                    echo json_encode($Helper->GetTmMunicipiosByDepartamentoId($_GET["DepartamentoId"]));
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["TmMunicipio"])) {
                    $Helper = new TmMunicipioBLL();
                    $Obj = json_decode($_POST["TmMunicipio"]);
                    echo json_encode($Helper->CreateTmMunicipio($Obj));
                } else {
                    echo "invalido.";
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new TmMunicipioBLL();
                if (isset($_PUT["TmMunicipio"])) {
                    $Obj = json_decode($_PUT["TmMunicipio"]);
                    echo json_encode($Helper->UpdateTmMunicipio($Obj));
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

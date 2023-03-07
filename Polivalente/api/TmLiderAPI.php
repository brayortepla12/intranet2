<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/tm/TmLiderBLL.php';

class TmLiderAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new TmLiderBLL();
                if (isset($_GET["UsuarioId"])) {
                    echo $Helper->GetTmLideres($_GET["UsuarioId"]);
                }else if (isset($_GET["LiderId"])) {
                    echo json_encode($Helper->GetTmLiderByLiderId($_GET["LiderId"]));
                }else if (isset($_GET["MunicipioId"])) {
                    echo json_encode($Helper->GetTmLiderByMunicipioId($_GET["MunicipioId"]));
                }else{
                    echo json_encode($Helper->GetTmLiders());
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Lider"])) {
                    $Helper = new TmLiderBLL();
                    $Obj = json_decode($_POST["Lider"]);
                    echo json_encode($Helper->CreateTmLider($Obj));
                } else {
                    echo "invalido.";
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new TmLiderBLL();
                if (isset($_PUT["TmLider"])) {
                    $Obj = json_decode($_PUT["TmLider"]);
                    echo json_encode($Helper->UpdateTmLider($Obj));
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

<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/tm/TmEventoBLL.php';

class TmEventoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new TmEventoBLL();
                if (isset($_GET["UsuarioId"])) {
                    echo $Helper->GetTmEventoes($_GET["UsuarioId"]);
                }else if (isset($_GET["EventoId"])) {
                    echo json_encode($Helper->GetTmEventoByEventoId($_GET["EventoId"]));
                }else if (isset($_GET["MaternaId"])) {
                    echo json_encode($Helper->GetTmEventoByMaternaId($_GET["MaternaId"]));
                }else if (isset($_GET["MaternaId_left"]) && isset($_GET["EventoId_left"])) {
                    echo json_encode($Helper->GetTmEventoByMaternaIdMenosEste($_GET["MaternaId_left"], $_GET["EventoId_left"]));
                }else if (isset($_GET["TipoEvento"])){
                    echo json_encode($Helper->GetTmEventos($_GET["TipoEvento"]));
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Evento"])) {
                    $Helper = new TmEventoBLL();
                    $Obj = json_decode($_POST["Evento"]);
                    echo json_encode($Helper->CreateTmEvento($Obj));
                } else {
                    echo "invalido.";
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new TmEventoBLL();
                if (isset($_PUT["GestionarEventoId"]) && isset($_PUT["ModifiedBy"])) {
                    echo json_encode($Helper->GestionarEvento($_PUT["GestionarEventoId"], $_PUT["ModifiedBy"]));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                $Helper = new TmEventoBLL();
                if (isset($_DELETE["EventoId"])) {
                    echo json_encode($Helper->DeleteTmEvento($_DELETE["EventoId"]));
                }
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

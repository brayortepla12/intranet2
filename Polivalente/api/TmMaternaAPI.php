<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/tm/TmMaternaBLL.php';

class TmMaternaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new TmMaternaBLL();
                if (isset($_GET["Documento"])) {
                    echo json_encode($Helper->GetTmMaternaByDocumento($_GET["Documento"]));
                }else if (isset($_GET["MaternaId"])) {
                    echo json_encode($Helper->GetTmMaternaByMaternaId($_GET["MaternaId"]));
                }else if (isset($_GET["LiderId"]) && isset($_GET["From"]) && isset($_GET["To"])) {
                    echo json_encode($Helper->GetTmAgendaMaterna($_GET["LiderId"], $_GET["From"], $_GET["To"]));
                }else if (isset($_GET["Year"]) && isset($_GET["Mes"]) && isset($_GET["MunicipioId"])) {
                    echo json_encode($Helper->GetActividadByMes($_GET["Year"], $_GET["Mes"], $_GET["MunicipioId"]));
                }else if (isset($_GET["Year_reg"]) && isset($_GET["Mes_reg"]) && isset($_GET["MunicipioId_reg"])) {
                    echo json_encode($Helper->GetMaternaRegistradasByMes($_GET["Year_reg"], $_GET["Mes_reg"], $_GET["MunicipioId_reg"]));
                }else{
                    echo json_encode($Helper->GetTmMaternas());
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["TmMaterna"])) {
                    $Helper = new TmMaternaBLL();
                    $Obj = json_decode($_POST["TmMaterna"]);
                    echo json_encode($Helper->CreateTmMaterna($Obj));
                } else {
                    echo "invalido.";
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new TmMaternaBLL();
                if (isset($_PUT["TmMaterna"])) {
                    $Obj = json_decode($_PUT["TmMaterna"]);
                    echo json_encode($Helper->UpdateTmMaterna($Obj));
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

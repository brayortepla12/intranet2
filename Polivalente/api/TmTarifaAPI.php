<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/tm/TmTarifaBLL.php';

class TmTarifaAPI
{

    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET': //consulta
                $Helper = new TmTarifaBLL();
                if (isset($_GET["TarifaId"])) {
                    echo json_encode($Helper->GetTmTarifaByTarifaId($_GET["TarifaId"]));
                } else if (isset($_GET["Documento"])) {
                    echo json_encode($Helper->GetTarifaBYMaterna($_GET["Documento"]));
                } else if (isset($_GET["OrigenId"])) {
                    echo json_encode($Helper->GetTarifaByOrigen($_GET["OrigenId"]));
                } else if (isset($_GET["TarifasAdmin"])) {
                    echo json_encode($Helper->getTarifasAdmin($_GET["TarifasAdmin"]));
                } else {
                    echo json_encode($Helper->GetTmTarifas());
                }
                break;
            case 'POST': //inserta
                if (isset($_POST["TmTarifa"])) {
                    $Helper = new TmTarifaBLL();
                    $Obj = json_decode($_POST["TmTarifa"]);
                    echo json_encode($Helper->CreateTmTarifa($Obj));
                } else {
                    echo "invalido.";
                }

                //                echo "invalido.";
                break;
            case 'PUT': //actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new TmTarifaBLL();
                if (isset($_PUT["Tarifa"])) {
                    $Obj = json_decode($_PUT["Tarifa"]);
                    echo json_encode($Helper->UpdateTmTarifa($Obj));
                }
                break;
            case 'DELETE': //elimina
                parse_str(file_get_contents('php://input'), $_DELETE);

                break;
            default: //metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

    public function utf8_encode_deep(&$input)
    {
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

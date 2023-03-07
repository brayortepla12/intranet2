<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/cm/OrdenRRBLL.php';

class OrdenRRAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new OrdenRRBLL();
                if (isset($_GET["Fecha"]) && isset($_GET["TipoMedicamento"])) {
                    echo json_encode($Helper->GetOrdenRRByFecha($_GET["Fecha"], $_GET["TipoMedicamento"]));
                }else {
                    echo "Error te equivocaste :)";
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["OrdenRR"])) {
                    $Helper = new OrdenRRBLL();
                    $Obj = json_decode($_POST["OrdenRR"]);
                    echo json_encode($Helper->CreateOrdenRR($Obj));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new OrdenRRBLL();
                if (isset($_PUT["OrdenRR_AProduccion"])) {
                    $Obj = json_decode($_PUT["OrdenRR_AProduccion"]);
                    echo json_encode($Helper->UpdateOrdenRR_AP($Obj[0]));
                }else if (isset($_PUT["OrdenRR_AFarmacia"])) {
                    $Obj = json_decode($_PUT["OrdenRR_AFarmacia"]);
                    echo json_encode($Helper->UpdateOrdenRR_AF($Obj[0]));
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

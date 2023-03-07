<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/cm/PacienteBLL.php';

class PacienteCMAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new PacienteBLL();
                echo "invalido GET";
                break;
            case 'POST'://inserta
                if (isset($_POST["Sector"])) {
                    $h = new PacienteBLL();
                    if (isset($_POST["Sector"]) && isset($_POST["TipoMedicamentoId"]) && isset($_POST["FechaPS"]) && isset($_POST["TipoRonda"])) {
                        $Sector = $_POST["Sector"];
                        $Pacientes = $h->GetPacientesBySector($Sector, $_POST["TipoMedicamentoId"], $_POST["FechaPS"], $_POST["TipoRonda"]);
                        $this->utf8_encode_deep($Pacientes);
                        echo json_encode($Pacientes);
                    }else{
                        echo "invalido post";
                    }
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["ReporteId"]) && isset($_PUT["RecibeEmail"])) {
                    $Helper = new PacienteBLL();
                    echo json_encode($Helper->FirmarReporte($_PUT["ReporteId"], $_PUT["RecibeEmail"]));
                } else if (isset($_PUT["Reporte"])) {
                    $Helper = new PacienteBLL();
                    $Obj = json_decode($_PUT["Reporte"])[0];
                    echo json_encode($Helper->UpdateReporte($Obj));
                } else {
                    $json = file_get_contents('php://input');
                    $obj = json_decode($json);
                    $Helper = new PacienteBLL();
                    // echo print_r($obj->Solicitud[0]);
                    echo json_encode($Helper->FirmarReporte($obj->ReporteId, $obj->RecibeEmail));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["Reporte"])) {
                    $Helper = new PacienteBLL();
                    $Obj = json_decode($_DELETE["Reporte"])[0];
                    echo json_encode($this->utf8_encode_deep($Helper->DeleteReporteById($Obj)));
                }
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }
    public function utf8_encode_deep(&$input) {
        if (is_string($input)) {
            $input = $this->sanear_string(utf8_encode($input));
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
    
    function sanear_string($string) {

        $string = trim($string);

        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
                array("\\", "¨", "º", "-", "~",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "<code>", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            "."), '', $string
        );


        return $string;
    }
}

//end class

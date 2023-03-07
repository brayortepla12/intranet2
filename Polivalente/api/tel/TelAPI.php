<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../../BLL/tel/TelBLL.php';

class TelAPI
{

    public function API()
    {
        header('Content-Type: application/JSON; charset=utf-8');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET': //consulta
                $Helper = new TelBLL();
                if (isset($_GET["Dia"]) && isset($_GET["Mes"]) && isset($_GET["Year"]) && isset($_GET["UsuarioId"])) {
                    echo json_encode($Helper->GetSolucitudesByUsuarioId($_GET["Dia"], $_GET["Mes"], $_GET["Year"], $_GET["UsuarioId"]));
                } else if (isset($_GET["Dia"]) && isset($_GET["Mes"]) && isset($_GET["Year"])) {
                    echo json_encode($Helper->GetSolicitudes($_GET["Dia"], $_GET["Mes"], $_GET["Year"]));
                } else if (isset($_GET["TELEFONOS"])) {
                    echo json_encode($Helper->GetTelefonos());
                } else if (isset($_GET["TELPerId"])) {
                    echo json_encode($Helper->GetTelefonosByPersonaId($_GET["TELPerId"]));
                } else if (isset($_GET["SolicitudId"])) {
                    echo json_encode($Helper->GetSolicitudById($_GET["SolicitudId"]));
                } else if (isset($_GET["ESolicitudId"])) {
                    echo json_encode($Helper->GetEntregaBySolicitudId($_GET["ESolicitudId"]));
                } else if (isset($_GET["HT"])) {
                    echo json_encode($Helper->GetHTID($_GET["HT"]));
                } else if (isset($_GET["Inv"])) {
                    echo json_encode($Helper->GetInventario($_GET["Inv"]));
                } else if (isset($_GET["token"])) {
                    echo json_encode($Helper->GetSolicitudesByToken($_GET["token"]));
                } else if (isset($_GET["TPDF"])) {
                    $Helper->SHOWPDF($_GET["TPDF"]);
                } else if (isset($_GET["NEntregaId"])) {
                    echo json_encode($Helper->ReenviarNotificacion($_GET["NEntregaId"]));
                } else if (isset($_GET["Dato_per"])) {
                    echo json_encode($Helper->getPersonaByCedulaOrNombre($_GET["Dato_per"]));
                } else {
                    echo "Sueñalo";
                }
                break;
            case 'POST': //inserta
                if (isset($_POST["Solicitud"])) {
                    $Data = json_decode($_POST["Solicitud"]);
                    $Helper = new TelBLL();
                    $datos = $Helper->CreateSolicitud($Data);
                    echo json_encode($datos);
                } else if (isset($_POST["Entrega"])) {
                    $Data = json_decode($_POST["Entrega"]);
                    $Helper = new TelBLL();
                    $datos = $Helper->CreateEntrega($Data);
                    echo json_encode($datos);
                } else if (isset($_POST["Inventario"])) {
                    $Data = json_decode($_POST["Inventario"]);
                    $Helper = new TelBLL();
                    $datos = $Helper->CreateInv($Data);
                    echo json_encode($datos);
                } else if (isset($_POST["Telefono"])) {
                    $Data = json_decode($_POST["Telefono"]);
                    $Helper = new TelBLL();
                    $datos = $Helper->createTelefono($Data);
                    echo json_encode($datos);
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT': //actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["FirmarEntrega"])) {
                    $obj = json_decode($_PUT["FirmarEntrega"]);
                    $Helper = new TelBLL();
                    echo json_encode($Helper->UpdateFirmaEntrega($obj));
                } else if (isset($_PUT["Inventario"])) {
                    $Data = json_decode($_PUT["Inventario"]);
                    $Helper = new TelBLL();
                    $datos = $Helper->UpdateInv($Data);
                    echo json_encode($datos);
                } else if (isset($_PUT["Telefono"])) {
                    $Data = json_decode($_PUT["Telefono"]);
                    $Helper = new TelBLL();
                    $datos = $Helper->UpdateTelefono($Data);
                    echo json_encode($datos);
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

    function sanear_string($string)
    {

        $string = trim($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array(
                "\\", "¨", "º", "-", "~",
                "·", "$", "%", "&", "/",
                "(", ")", "?", "'", "¡",
                "¿", "[", "^", "<code>", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":",
                "."
            ),
            '',
            $string
        );


        return $string;
    }
}

//end class

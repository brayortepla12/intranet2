<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../../BLL/hd/HDBLL.php';

class HDAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new HDBLL();
                if (isset($_GET["Variables"])) {
                    echo json_encode($Helper->GetVariables());
                } else if (isset($_GET["Estado"]) && isset($_GET["Dia"]) && isset($_GET["Mes"]) && isset($_GET["Year"])) {
                    echo json_encode($Helper->GetHDs($_GET["Estado"],$_GET["Dia"], $_GET["Mes"], $_GET["Year"]));
                } else if (isset($_GET["UsuarioId_serv"]) && isset($_GET["Estado_serv"]) && isset($_GET["Dia_serv"]) && isset($_GET["Mes_serv"]) && isset($_GET["Year_serv"])) {
                    echo json_encode($Helper->GetHDsByUsuarioId($_GET["UsuarioId_serv"], $_GET["Estado_serv"], $_GET["Dia_serv"], $_GET["Mes_serv"], $_GET["Year_serv"]));
                } else if (isset($_GET["UsuarioId_emp"]) && isset($_GET["Estado_emp"]) && isset($_GET["Dia_emp"]) && isset($_GET["Mes_emp"]) && isset($_GET["Year_emp"])) {
                    echo json_encode($Helper->GetHDsByUsuarioEmpresa($_GET["UsuarioId_emp"], $_GET["Estado_emp"], $_GET["Dia_emp"], $_GET["Mes_emp"], $_GET["Year_emp"]));
                } else if (isset($_GET["Estadoap"]) &&isset($_GET["Diaap"]) && isset($_GET["Mesap"]) && isset($_GET["Yearap"])) {
                    echo json_encode($Helper->GetCantidadesAP($_GET["Estadoap"],$_GET["Diaap"], $_GET["Mesap"], $_GET["Yearap"]));
                } else if (isset($_GET["NoAdmision_v"]) &&isset($_GET["Distribucion_v"]) && isset($_GET["FechaAPreparar_v"])) {
                    echo json_encode($Helper->VerificarPaciente($_GET["NoAdmision_v"],$_GET["Distribucion_v"], $_GET["FechaAPreparar_v"]));
                } else if (isset($_GET["HDId"])) {
                    echo json_encode($Helper->GetHDById($_GET["HDId"]));
                } else if (isset($_GET["HDId_NoP"])) {
                    echo json_encode($Helper->GetHDByIdNop($_GET["HDId_NoP"]));
                }  else if (isset($_GET["Distribucion"])) {
                    echo json_encode($Helper->GetDistribucion());
                } else if (isset($_GET["Empresas"])) {
                    echo json_encode($Helper->GetEmpresas());
                } else if (isset($_GET["Sectoresmysql"])) {
                    echo json_encode($Helper->GetSectores());
                } else if (isset($_GET["NOADMISION"])) {
                    echo json_encode($Helper->GetPacienteByNoAdmision($_GET["NOADMISION"]));
                } else if (isset($_GET["Empresa_estadisticas"]) && isset($_GET["Mes_estadisticas"]) && isset($_GET["Year_estadisticas"])) {
                    echo json_encode($Helper->GetEstadisticas($_GET["Empresa_estadisticas"], $_GET["Mes_estadisticas"], $_GET["Year_estadisticas"]));
                } else if (isset($_GET["Empresa_ed"]) && isset($_GET["Dia_ed"]) && isset($_GET["Mes_ed"]) && isset($_GET["Year_ed"])) {
                    echo json_encode($Helper->GetEstadisticasDetalladas($_GET["Empresa_ed"], $_GET["Dia_ed"], $_GET["Mes_ed"], $_GET["Year_ed"]));
                } else {
                    echo "Sueñalo";
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Sector"]) && isset($_POST["Usuario"])) {
                    $Sector = $_POST["Sector"];
                    $Helper = new HDBLL();
                    $Pacientes = $Helper->GetPacientesBySector($Sector, $_POST["Usuario"]);
                    $this->utf8_encode_deep($Pacientes);
                    echo json_encode($Pacientes);
                } else if (isset($_POST["HD"])) {
                    $obj = json_decode($_POST["HD"]);
                    $Helper = new HDBLL();
                    echo json_encode($Helper->CreateHD($obj));
                } else if (isset($_POST["PHD"])) {
                    $obj = json_decode($_POST["PHD"]);
                    $Helper = new HDBLL();
                    echo json_encode($Helper->CreatePHD($obj));
                } else if (isset($_POST["SolicitarComida"])) {
                    $obj = json_decode($_POST["SolicitarComida"]);
                    $Helper = new HDBLL();
                    echo json_encode($Helper->CreateNCHD($obj));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if(isset($_PUT["Apreparar"])){
                    $Apreparar = json_decode($_PUT["Apreparar"]);
                    $Helper = new HDBLL();
                    echo json_encode($Helper->PrepararCHD($Apreparar));
                }else if(isset($_PUT["UPHD"])){
                    $Uphd = json_decode($_PUT["UPHD"]);
                    $Helper = new HDBLL();
                    echo json_encode($Helper->UpdatePHD($Uphd));
                }else if(isset($_PUT["CancelarComida"])){
                    $Uphd = json_decode($_PUT["CancelarComida"]);
                    $Helper = new HDBLL();
                    echo json_encode($Helper->CancelarComida($Uphd));
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

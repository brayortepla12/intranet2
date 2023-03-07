<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/formulario/SolicitudBLL.php';

require __DIR__ . "/../vendor/autoload.php";

class MyLogger {

    public function log($msg) {
        print_r($msg . "<br />");
    }

}

class SolicitudAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new SolicitudBLL();
                if (isset($_GET["UsuarioId_pol"]) && isset($_GET["PREFIJO"])) {
                    echo $Helper->GetSolicitudesPolByUsuario($_GET["UsuarioId_pol"], $_GET["PREFIJO"]);
                } else if (isset($_GET["Pol_SolicitudId"]) && isset($_GET["PREFIJO"])) {
                    echo $Helper->GetSolicitudPolById($_GET["Pol_SolicitudId"], $_GET["PREFIJO"]);
                } else if (isset($_GET["SolicitudId_r"]) && isset($_GET["PREFIJO_r"])) {
                    echo json_encode($Helper->GetReportesBySolicitudId($_GET["SolicitudId_r"], $_GET["PREFIJO_r"]));
                } else if (isset($_GET["SolicitudId_p"]) && isset($_GET["PREFIJO_p"])) {
                    echo json_encode($Helper->GetProcesosBySolicitudId($_GET["SolicitudId_p"], $_GET["PREFIJO_p"]));
                } else if (isset($_GET["Key_pol"]) && isset($_GET["PREFIJO"]) && isset($_GET["MES"]) && isset($_GET["YEAR"])) {
                    echo $Helper->GetAllSolicitudesPol($_GET["PREFIJO"],$_GET["MES"],$_GET["YEAR"]);
                } else if (isset($_GET["Cuenta"]) && isset($_GET["UsuarioId2"])) {
                    echo json_encode($Helper->CountSolicitudes($_GET["UsuarioId2"]));
                } else if (isset($_GET["Pol_EventoBySolicitudId"]) && isset($_GET["PREFIJO"])) {
                    echo json_encode($Helper->GetEventosBySolicitudId($_GET["Pol_EventoBySolicitudId"], $_GET["PREFIJO"]));
                } else if (isset($_GET["Pol_ReporteExternoId"]) && isset($_GET["PREFIJO"])) {
                    echo json_encode($Helper->GetReporteExternoById($_GET["Pol_ReporteExternoId"], $_GET["PREFIJO"]));
                } else if (isset($_GET["PREFIJO_TS"])) {
                    echo json_encode($Helper->GetTotalSolicitudes($_GET["PREFIJO_TS"]));
                } else {
                    echo $_SERVER['REMOTE_ADDR'];
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["SolicitudPolivalente"]) && isset($_POST["PREFIJO"])) {
                    $Helper = new SolicitudBLL();
                    $Obj = json_decode($_POST["SolicitudPolivalente"])[0];
                    echo json_encode($Helper->CreateSolicitudPol($Obj, $_POST["PREFIJO"]));
                } else if (isset($_POST["EventoSolicitudPol"]) && isset($_POST["PREFIJO"])) {
                    $Helper = new SolicitudBLL();
                    $Obj = json_decode($_POST["EventoSolicitudPol"]);
                    echo json_encode($Helper->CreateEventoSolicitudPol($Obj, $_POST["PREFIJO"]));
                } else {
                    echo "error";
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["SolicitudId"])) {
                    $Helper = new SolicitudBLL();
                    echo json_encode($Helper->CambiarEstadoSolicitud($_PUT["SolicitudId"]));
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

}

//end class

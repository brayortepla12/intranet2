<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/formulario/ReporteSSTBLL.php';

class ReporteSSTAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new ReporteSSTBLL();
                if (isset($_GET["ReporteId"])) {
                    echo json_encode($Helper->GetReporteById($_GET["ReporteId"]));
                } else if (isset($_GET["SolicitudId"])) {
                    echo json_encode($Helper->GetReporteBySolicitudId($_GET["SolicitudId"]));
                } else if (isset($_GET["EquipoId"])) {
                    echo json_encode($Helper->GetReporteByEquipoId($_GET["EquipoId"]));
                } else if (isset($_GET["UsuarioId_all"])) {
                    echo $Helper->GetAllReportesByUsuarioServicio($_GET["UsuarioId_all"]);
                } else if (isset($_GET["ReporteId_Send"])) {
                    echo json_encode($Helper->ReenviarCorreo($_GET["ReporteId_Send"]));
                } else if (isset($_GET["SedeId"]) && isset($_GET["ServicioId"]) && isset($_GET["Year"]) && isset($_GET["Mes"])) {
                    echo json_encode($Helper->GetReporteBySerivicioId($_GET["SedeId"], $_GET["ServicioId"], $_GET["Year"], $_GET["Mes"]));
                } else if (isset($_GET["UserId"])) {
                    echo $Helper->GetAllReportes();
                } else if (isset($_GET["Year"]) && isset($_GET["Month"])) {
                    echo json_encode($Helper->GetEstadisticas($_GET["Year"], $_GET["Month"]));
                } else if (isset($_GET["From"]) && isset($_GET["To"]) && isset($_GET["UsuarioId"])) {
                    echo json_encode($Helper->GetReportesBetweenFechaBySede($_GET["From"], $_GET["To"], $_GET["UsuarioId"]));
                } else if (isset($_GET["From2"]) && isset($_GET["To2"]) && isset($_GET["UsuarioId2"])) {
                    echo json_encode($Helper->GetReportesBetweenFechaALLBySede($_GET["From2"], $_GET["To2"], $_GET["UsuarioId2"]));
                } else if (isset($_GET["AutoFirmar"])) {
                    echo json_encode($Helper->AutoFirmarTODO($_GET["AutoFirmar"]));
//                    echo 'Por favor';
                } else if (isset($_GET["AutoFirmar_Email"])) {
                    echo json_encode($Helper->AutoFirmarTODOByEmail($_GET["AutoFirmar_Email"]));
                } else if (isset($_GET["UsuarioIdPlantaElectrica"])) {
                    echo json_encode($Helper->GetReportesPlantasElectricas($_GET["UsuarioIdPlantaElectrica"]));
                } else if (isset($_GET["EmailSolicitante"])) {
                    echo json_encode($Helper->GetReporteByEmail($_GET["EmailSolicitante"]));
                } else {
                    echo $Helper->GetNReporte();
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Reporte"]) && isset($_POST["UserId"])) {
                    $Helper = new ReporteSSTBLL();
                    $Obj = json_decode($_POST["Reporte"])[0];
                    echo json_encode($Helper->CreateReporte($Obj));
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["ReporteId"]) && isset($_PUT["RecibeEmail"])) {
                    $Helper = new ReporteSSTBLL();
                    echo json_encode($Helper->FirmarReporte($_PUT["ReporteId"], $_PUT["RecibeEmail"]));
                } else if (isset($_PUT["Reporte"])) {
                    $Helper = new ReporteSSTBLL();
                    $Obj = json_decode($_PUT["Reporte"])[0];
                    echo json_encode($Helper->UpdateReporte($Obj));
                } else {
                    $json = file_get_contents('php://input');
                    $obj = json_decode($json);
                    $Helper = new ReporteSSTBLL();
                    // echo print_r($obj->Solicitud[0]);
                    echo json_encode($Helper->FirmarReporte($obj->ReporteId, $obj->RecibeEmail));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["Reporte"])) {
                    $Helper = new ReporteSSTBLL();
                    $Obj = json_decode($_DELETE["Reporte"])[0];
                    echo json_encode($Helper->DeleteReporteById($Obj));
                }
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

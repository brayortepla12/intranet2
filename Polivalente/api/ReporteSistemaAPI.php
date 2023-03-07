<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/formulario/ReporteSistemaBLL.php';

class ReporteSistemaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new ReporteSistemaBLL();
                if (isset($_GET["ReporteId"])) {
                    echo json_encode($Helper->GetReporteById($_GET["ReporteId"]));
                } else if (isset($_GET["SolicitudId"])) {
                    echo json_encode($Helper->GetReporteBySolicitudId($_GET["SolicitudId"]));
                } else if (isset($_GET["EquipoId"])) {
                    echo json_encode($Helper->GetReporteByEquipoId($_GET["EquipoId"]));
                } else if (isset($_GET["UsuarioId_all"]) && isset($_GET["SedeId_all"]) && isset($_GET["ServicioId_all"]) && isset($_GET["TipoServicio_all"]) && isset($_GET["TipoArticulo_all"])) {
                    echo $Helper->GetAllReportesByUsuarioServicio($_GET["UsuarioId_all"], $_GET["SedeId_all"], $_GET["ServicioId_all"], $_GET["TipoServicio_all"], $_GET["TipoArticulo_all"]);
                } else if (isset($_GET["UsuarioId_Exc"]) && isset($_GET["SedeId_Exc"]) && isset($_GET["ServicioId_Exc"]) && isset($_GET["TipoServicio_Exc"]) && isset($_GET["TipoArticulo_Exc"])) {
                    $Helper->GetAllReportesByUsuarioServicioExcel($_GET["UsuarioId_Exc"], $_GET["SedeId_Exc"], $_GET["ServicioId_Exc"], $_GET["TipoServicio_Exc"], $_GET["TipoArticulo_Exc"]);
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
                } else if (isset($_GET["AutoFirmar_RecibeId"])) {
                    echo json_encode($Helper->AutoFirmarTODOByRecibeId($_GET["AutoFirmar_RecibeId"]));
                } else if (isset($_GET["UsuarioIdPlantaElectrica"])) {
                    echo json_encode($Helper->GetReportesPlantasElectricas($_GET["UsuarioIdPlantaElectrica"]));
                } else if (isset($_GET["PersonaRecibeId"])) {
                    echo json_encode($Helper->GetReporteByPersonaRecibeId($_GET["PersonaRecibeId"]));
                } else {
                    echo $Helper->GetNReporte();
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Reporte"]) && isset($_POST["UserId"])) {
                    $Helper = new ReporteSistemaBLL();
                    $Obj = json_decode($_POST["Reporte"])[0];
                    echo json_encode($Helper->CreateReporte($Obj));
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["ReporteId"]) && isset($_PUT["RecibeEmail"])) {
                    $Helper = new ReporteSistemaBLL();
                    echo json_encode($Helper->FirmarReporte($_PUT["ReporteId"], $_PUT["RecibeEmail"]));
                } else if (isset($_PUT["Reporte"])) {
                    $Helper = new ReporteSistemaBLL();
                    $Obj = json_decode($_PUT["Reporte"])[0];
                    echo json_encode($Helper->UpdateReporte($Obj));
                } else {
                    $json = file_get_contents('php://input');
                    $obj = json_decode($json);
                    $Helper = new ReporteSistemaBLL();
                    // echo print_r($obj->Solicitud[0]);
                    echo json_encode($Helper->FirmarReporte($obj->ReporteId, $obj->RecibeEmail));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["Reporte"])) {
                    $Helper = new ReporteSistemaBLL();
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

<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/Hemodinamia/HistoriaClinicaBLL.php';

class HistoriaClinicaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new HistoriaClinicaBLL();
                if (isset($_GET["PacienteId"])) {
                    echo json_encode($Helper->GetHistoriaClinicaByPacienteId($_GET["PacienteId"]));
                }else if (isset($_GET["HistoriaClinicaId"])) {
                    echo json_encode($Helper->GetHistoriaClinicaById($_GET["HistoriaClinicaId"]));
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Reporte"]) && isset($_POST["UserId"])) {
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["ReporteId"]) && isset($_PUT["RecibeEmail"])) {
                    $Helper = new HistoriaClinicaBLL();
                    echo json_encode($Helper->FirmarReporte($_PUT["ReporteId"], $_PUT["RecibeEmail"]));
                } else if (isset($_PUT["Reporte"])) {
                    $Helper = new HistoriaClinicaBLL();
                    $Obj = json_decode($_PUT["Reporte"])[0];
                    echo json_encode($Helper->UpdateReporte($Obj));
                } else {
                    $json = file_get_contents('php://input');
                    $obj = json_decode($json);
                    $Helper = new HistoriaClinicaBLL();
                    // echo print_r($obj->Solicitud[0]);
                    echo json_encode($Helper->FirmarReporte($obj->ReporteId, $obj->RecibeEmail));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["Reporte"])) {
                    $Helper = new HistoriaClinicaBLL();
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

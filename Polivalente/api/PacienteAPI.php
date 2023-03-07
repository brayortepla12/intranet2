<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/Hemodinamia/PacienteBLL.php';

class PacienteAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new PacienteBLL();
                if (isset($_GET["Documento"])) {
                    echo json_encode($Helper->GetPacienteByDocumento($_GET["Documento"]));
                }else if (isset($_GET["CodigoQR"])) {
                    echo json_encode($Helper->GetPacienteByCodigoQR($_GET["CodigoQR"]));
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Paciente"])) {
                    $h = new PacienteBLL();
                    $Paciente = json_decode($_POST["Paciente"]);
                    echo json_encode($h->CreatePaciente($Paciente));
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

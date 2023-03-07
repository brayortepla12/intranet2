<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/ambulancia/DetallesAmbulanciaBLL.php';

class DetalleAmbulanciaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new DetallesAmbulanciaBLL();
                echo json_encode($Helper->GetAll());
                break;
            case 'POST'://inserta
                if (isset($_POST["ReporteAmbulancia"]) && isset($_POST["UserId"])) {
                    $Helper = new ReporteAmbulanciaBLL();
                    $Obj = json_decode($_POST["ReporteAmbulancia"])[0];
                    echo json_encode($Helper->CreateReporteAmbulancia($Obj));
                } else {
                    echo "error";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["ReporteAmbulancia"])) {
                    $Helper = new ReporteAmbulanciaBLL();
                    $Obj = json_decode($_PUT["ReporteAmbulancia"]);
                    echo json_encode($Helper->UpdateReporteAmbulancia($Obj[0]));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                $Helper = new ReporteAmbulanciaBLL();
                $Obj = json_decode($_DELETE["ReporteAmbulancia"])[0];
                echo json_encode($Helper->DeleteReporteAmbulanciaById($Obj));
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

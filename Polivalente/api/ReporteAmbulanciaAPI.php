<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/ambulancia/ReporteAmbulanciaBLL.php';

class ReporteAmbulanciaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new ReporteAmbulanciaBLL();
                if (isset($_GET["ReporteId"])) {
                    echo json_encode($Helper->GetReporteAmbulanciaById($_GET["ReporteId"]));
                } else if (isset($_GET["EquipoId"])) {
                    echo json_encode($Helper->GetReporteAmbulanciaByEquipoId($_GET["EquipoId"]));
                } else if (isset($_GET["EquipoId_crono"])) {
                    echo json_encode($Helper->GetCronogramaReporteAmbulanciaByEquipoId($_GET["EquipoId_crono"]));
                } else if (isset($_GET["SedeId_all"])) {
                    echo $Helper->GetAllReporteAmbulanciasBySede($_GET["SedeId_all"]);
                }  else if (isset($_GET["UserId"])) {
                    echo $Helper->GetAllReporteAmbulancias();
                } else if (isset($_GET["Cronograma"])) {
                    echo json_encode($Helper->GetCronogramaMantenimiento());
                } else {
                    echo $Helper->GetNReporteAmbulancia();
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Reporte"])) {
                    $Helper = new ReporteAmbulanciaBLL();
                    $Obj = json_decode($_POST["Reporte"]);
                    $Obj->Detalles = json_decode($Obj->Detalles);
                    echo json_encode($Helper->CreateReporteAmbulancia($Obj));
                } else {
                    echo "error";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Reporte"])) {
                    $Helper = new ReporteAmbulanciaBLL();
                    $Obj = json_decode($_PUT["Reporte"]);
                    $Obj->Detalles = json_decode($Obj->Detalles);
                    echo json_encode($Helper->UpdateReporteAmbulancia($Obj));
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

<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/ct/ControlBLL.php';

class ControlAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new ControlBLL();
                if (isset($_GET["Codigo"])) {
                    echo json_encode($Helper->GetControlByCodigo($_GET["Codigo"]));
                }else if(isset($_GET["PersonaId"])){
                    echo json_encode($Helper->GetUltimoControl($_GET["PersonaId"]));
                }else if(isset($_GET["PersonaId_c"]) && isset($_GET["Desde_c"]) && isset($_GET["Hasta_c"])){
                    echo json_encode($Helper->GetControlByPersonaIdAndFecha($_GET["PersonaId_c"], $_GET["Desde_c"], $_GET["Hasta_c"]));
                }else if(isset($_GET["PersonaId_xlsx"]) && isset($_GET["Desde_xlsx"]) && isset($_GET["Hasta_xlsx"])){
                    json_encode($Helper->GetControlByPersonaIdAndFechaXLSX($_GET["PersonaId_xlsx"], $_GET["Desde_xlsx"], $_GET["Hasta_xlsx"]));
                }else if(isset($_GET["Dispositivo"])){
                    echo json_encode($Helper->GetListEmpleados($_GET["Dispositivo"]));
                }else if(isset($_GET["Control_limit"])){
                    echo json_encode($Helper->GetControlWithLimite($_GET["Control_limit"]));
                }
                break;
            case 'POST'://inserta
                $Helper = new ControlBLL();
                if (isset($_POST["Control"])) {
                    echo json_encode($Helper->validateControl($_POST["Control"], "Biomedico_123458"));
                }else if (isset($_POST["DMByRonda"])) {
                    $obj = json_decode($_POST["DMByRonda"]);
                    echo json_encode($Helper->CreateDMByRonda($obj));
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $m = new ModuloDTO();
                $m = $this->Mapper($_PUT);
                echo json_encode($m);
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                $m = new ModuloDTO();
                $m = $this->Mapper($_DELETE);
                echo json_encode($m);
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

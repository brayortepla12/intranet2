<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/cm/VehiculoBLL.php';


class VehiculoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new VehiculoBLL();
                if (isset($_GET["ALL"])) {
                    echo json_encode($Helper->GetALLVehiculos());
                } else {
                    echo json_encode($Helper->GetVehiculos());
                }
                break;
            case 'POST'://inserta
                $Helper = new VehiculoBLL();
                if (isset($_POST["Vehiculo"])) {
                    echo json_encode($Helper->validateVehiculo($_POST["Vehiculo"], "Biomedico_123458"));
                } else if (isset($_POST["DMByRonda"])) {
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

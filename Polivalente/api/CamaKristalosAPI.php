<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/CamaKristalosBLL.php';

class CamaKristalosAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new CamaKristalosBLL();
                if (isset($_GET["UserId"])) {
                    echo json_encode($Helper->GetCamas());
                }else if(isset($_GET["NoAdmision"])){
                    echo json_encode($Helper->GetCunaByAdmision($_GET["NoAdmision"]));
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["CamaKristalos"])) {
                    $CamaKristalos = json_decode($_POST["CamaKristalos"]);
                    $Helper = new CamaKristalosBLL();
                    echo json_encode($Helper->CreateCamaKristalos($CamaKristalos));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["CamaKristalos"]) && isset($_PUT["ID"])) {
                    $CamaKristalos = json_decode($_PUT["CamaKristalos"]);
                    $id = $_PUT["ID"];
                    $Helper = new CamaKristalosBLL();
                    echo json_encode($Helper->UpdateCamaKristalos($CamaKristalos, $id));
                }

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

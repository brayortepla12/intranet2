<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/formulario/EquipoBLL.php';
class EquipoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new EquipoBLL();
                if (isset($_GET["UserId"])) {
                    echo json_encode($Helper->GetAllPlantasBySede($_GET["UserId"]));
                }
                break;
            case 'POST'://inserta
                $Helper = new EquipoBLL();
                if (isset($_POST["Servicios"]) && isset($_POST["Year"])) {
                    $Servicios = json_decode($_POST["Servicios"]);
                    echo $Helper->GetAllEquipoes($Servicios[0],$_POST["Year"]);
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["EquipoId"])) {
                   $Helper = new EquipoBLL();
                   echo json_encode($Helper->CambiarEstadoEquipo($_PUT["EquipoId"]));
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

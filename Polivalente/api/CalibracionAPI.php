<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/formulario/CalibracionBLL.php';
class CalibracionAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new CalibracionBLL();
                if(isset($_GET["Cuenta"])){
                    echo json_encode($Helper->GetNEquiposByServicio());
                }else if(isset($_GET["UsuarioId"])){
                    echo json_encode($Helper->GetAllCalibraciones($_GET["UsuarioId"]));
                }
                break;
            case 'POST'://inserta
                $Helper = new CalibracionBLL();
                if (isset($_POST["Servicios"]) && isset($_POST["Year"])) {
                    $Servicios = json_decode($_POST["Servicios"]);
                    echo $Helper->GetAllCalibracionesByServicio($Servicios[0],$_POST["Year"]);
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["CalibracionId"])) {
                   $Helper = new CalibracionBLL();
                   echo json_encode($Helper->CambiarEstadoCalibracion($_PUT["CalibracionId"]));
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

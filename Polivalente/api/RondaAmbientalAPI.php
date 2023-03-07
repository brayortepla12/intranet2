<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/RondaAmbientalBLL.php';

class RondaAmbientalAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new RondaAmbientalBLL();
                if (isset($_GET["UsuarioId3"])) {
                    echo $Helper->GetRondaAmbientalesByUsuario($_GET["UsuarioId3"]);
                }else if (isset($_GET["ServicioId"])) {
                    echo json_encode($Helper->GetRondaAmbientalByServicioId($_GET["ServicioId"]));
                }else if (isset($_GET["ServicioId_form"])) {
                    echo json_encode($Helper->GetFormatosByServicioId($_GET["ServicioId_form"]));
                } else {
                    echo json_encode($Helper->GetAllFormularios());
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["ListadoItems"])) {
                    $Helper = new RondaAmbientalBLL();
                    $Obj = json_decode($_POST["ListadoItems"])[0];
                    echo json_encode($Helper->CreateRondaAmbiental($Obj));
                } else if (isset($_POST["RondaAmbientalTarea"])) {
                    $Helper = new RondaAmbientalBLL();
                    $Obj = json_decode($_POST["RondaAmbientalTarea"]);
                    echo json_encode($Helper->AsignarRondaAmbiental($Obj[0], $_POST["CreatedBy"]));
                } else if (isset($_POST["Notificar"]) && isset($_POST["ActividadUsuario"])) {
                    $Helper = new RondaAmbientalBLL();
                    echo $Helper->NotificarCumplimiento($_POST["Notificar"],$_POST["ActividadUsuario"]);
                } else {
                    $json = file_get_contents('php://input');
                    $obj = json_decode($json);
                    $Helper = new RondaAmbientalBLL();
                    // echo print_r($obj->RondaAmbiental[0]);
                    echo json_encode($Helper->CreateRondaAmbiental(json_decode($obj->RondaAmbiental)[0]));
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new RondaAmbientalBLL();
                if (isset($_PUT["ListadoItems"])) {
                    $Helper = new RondaAmbientalBLL();
                    $Obj = json_decode($_PUT["ListadoItems"]);
                    echo json_encode($Helper->UpdateRondaAmbiental($Obj));
                }else{
                    echo 'error';
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

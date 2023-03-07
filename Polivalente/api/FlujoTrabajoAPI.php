<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/FlujoTrabajoBLL.php';

class FlujoTrabajoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new FlujoTrabajoBLL();
                if (isset($_GET["ProtocoloId"])) {
                    echo json_encode($Helper->GetAllByProtocoloId($_GET["ProtocoloId"]));
                }else{
                    echo json_encode($Helper->GetAll());
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["FlujoTrabajo"])) {
                    $FlujoTrabajo = json_decode($_POST["FlujoTrabajo"]);
                    $Helper = new FlujoTrabajoBLL();
                    echo json_encode($Helper->CreateFlujoTrabajo($FlujoTrabajo[0]));
                }else if (isset($_POST["UsuarioFlujoTrabajo"])) {
                    $FlujoTrabajo = json_decode($_POST["UsuarioFlujoTrabajo"]);
                    $Helper = new FlujoTrabajoBLL();
                    echo $Helper->AsignarFlujoTrabajoUsuario($FlujoTrabajo);
                }  else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["FlujoTrabajo"]) && isset($_PUT["ID"])) {
                    $FlujoTrabajo = json_decode($_PUT["FlujoTrabajo"]);
                    $id = $_PUT["ID"];
                    $Helper = new FlujoTrabajoBLL();
                    echo json_encode($Helper->UpdateFlujoTrabajo($FlujoTrabajo, $id));
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

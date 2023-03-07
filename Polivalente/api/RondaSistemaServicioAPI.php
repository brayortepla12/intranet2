<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/RondaSistemaServicioBLL.php';

class RondaSistemaServicioAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new RondaSistemaServicioBLL();
                if (isset($_GET["RondaId"])) {
                    echo json_encode($Helper->GetByRondaId($_GET["RondaId"]));
                }else if (isset($_GET["UsuarioId"])) {
                    echo json_encode($Helper->GetRondaSistemaServicioByUsuario($_GET["UsuarioId"]));
                }else if (isset($_GET["UsuarioId2"]) && isset($_GET["RondaId2"])) {
                    echo json_encode($Helper->GetRondaSistemaServicioByUsuario_Ronda($_GET["UsuarioId2"], $_GET["RondaId2"]));
                }else if (isset($_GET["UsuarioId3"]) && isset($_GET["RondaId3"]) && isset($_GET["Fecha2"])) {
                    echo json_encode($Helper->GetRondaSistemaServicioByUsuario_RondaFecha($_GET["UsuarioId3"], $_GET["RondaId3"], $_GET["Fecha2"]));
                }else{
                    echo $Helper->GetAll();
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["RondaSistemaServicio"])) {
                    $RondaSistemaServicio = json_decode($_POST["RondaSistemaServicio"]);
                    $Helper = new RondaSistemaServicioBLL();
                    echo json_encode($Helper->CreateRondaSistemaServicio($RondaSistemaServicio));
                }else if (isset($_POST["RondaSistema_Detalle"]) && isset($_POST["CreatedBy"])) {
                    $RondaSistema_Detalle = json_decode($_POST["RondaSistema_Detalle"]);
                    $Helper = new RondaSistemaServicioBLL();
                    echo json_encode($Helper->CreateRondaSistema_Detalle($RondaSistema_Detalle[0], $_POST["CreatedBy"]));
                }else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["RondaSistemaServicio"]) && isset($_PUT["ID"])) {
                    $RondaSistemaServicio = json_decode($_PUT["RondaSistemaServicio"]);
                    $id = $_PUT["ID"];
                    $Helper = new RondaSistemaServicioBLL();
                    echo json_encode($Helper->UpdateRondaSistemaServicio($RondaSistemaServicio, $id));
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

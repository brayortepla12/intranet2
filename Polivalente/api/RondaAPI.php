<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/formulario/RondaBLL.php';

class RondaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new RondaBLL();
                if (isset($_GET["UsuarioId3"])) {
                    echo $Helper->GetRondaesByUsuario($_GET["UsuarioId3"]);
                } else if (isset($_GET["RondaId"])) {
                    echo json_encode($Helper->GetRondaesById($_GET["RondaId"]));
                } else if (isset($_GET["Key"])) {
                    echo $Helper->GetAllRondas();
                } else if (isset($_GET["Cuenta"]) && isset($_GET["UsuarioId2"])) {
                    echo json_encode($Helper->CountRondas($_GET["UsuarioId2"]));
                } else if (isset($_GET["Fecha"]) && isset($_GET["UsuarioId"])) {
                    echo json_encode($Helper->GetAllRondasByFecha($_GET["Fecha"], $_GET["UsuarioId"]));
                } else if (isset($_GET["Lite"])) {
                    echo json_encode($Helper->GetAllRondasLite($_GET["Lite"]));
                } else if (isset($_GET["RondaUID2"])) {
                    echo json_encode($Helper->GetAllRondas($_GET["RondaUID2"]));
                } else if (isset($_GET["RondaUID"])) {
                    echo json_encode($Helper->GetAllRondasHiastorico($_GET["RondaUID"]));
                } else if (isset($_GET["RondaUID_Lite"])) {
                    echo json_encode($Helper->GetAllRondasHiastorico_lite($_GET["RondaUID_Lite"]));
                } else if (isset($_GET["Tarea_UserId"])) {
                    echo json_encode($Helper->GetTareas($_GET["Tarea_UserId"]));
                } else {
                    echo "Error te equivocaste :)";
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["ListadoItems"])) {
                    $Helper = new RondaBLL();
                    $Obj = json_decode($_POST["ListadoItems"]);
                    echo json_encode($Helper->CreateRonda($Obj));
                } else if (isset($_POST["RondaTarea"])) {
                    $Helper = new RondaBLL();
                    $Obj = json_decode($_POST["RondaTarea"]);
                    echo json_encode($Helper->AsignarRonda($Obj[0], $_POST["CreatedBy"]));
                } else if (isset($_POST["Notificar"]) && isset($_POST["ActividadUsuario"])) {
                    $Helper = new RondaBLL();
                    echo $Helper->NotificarCumplimiento($_POST["Notificar"],$_POST["ActividadUsuario"]);
                } else {
                    $json = file_get_contents('php://input');
                    $obj = json_decode($json);
                    $Helper = new RondaBLL();
                    // echo print_r($obj->Ronda[0]);
                    echo json_encode($Helper->CreateRonda(json_decode($obj->Ronda)[0]));
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new RondaBLL();
                if (isset($_PUT["RondaId"])) {
                    echo json_encode($Helper->CambiarEstadoRonda($_PUT["RondaId"]));
                } else if (isset($_PUT["Ronda"]) && isset($_PUT["ModifiedBy"])) {
                    $obj = json_decode($_PUT["Ronda"]);
                    echo json_encode($Helper->Actualizar($obj[0],$_PUT["ModifiedBy"]));
                }else if(isset($_PUT["ActividadUsuario"]) && isset($_PUT["ModifiedBy"])){
                    $obj = json_decode($_PUT["ActividadUsuario"]);
                    echo json_encode($Helper->ActualizarActividadUsuario($obj[0], $_PUT["ModifiedBy"]));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["RondaId"])) {
                    $Helper = new RondaBLL();
                    echo $Helper->DeleteRondaByRondaId($_DELETE["RondaId"]);
                }
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

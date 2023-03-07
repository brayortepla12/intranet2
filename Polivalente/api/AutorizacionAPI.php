<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/Autorizacion/AutorizacionBLL.php';

class AutorizacionAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new AutorizacionBLL();
                if (isset($_GET["UserId"])) {
                    echo json_encode($Helper->GetAllByUserId($_GET["UserId"]));
                }else if(isset($_GET["Usuario_sede"])){
                    echo json_encode($Helper->GetAllBySedeId($_GET["Usuario_sede"]));
                }else if(isset($_GET["AutorizacionId"])){
                    echo json_encode($Helper->GetAllById($_GET["AutorizacionId"]));
                }else if(isset($_GET["ServicioId_p"]) && isset($_GET["UserId_p"])){
                    echo json_encode($Helper->GetAllByPlantilla($_GET["ServicioId_p"], $_GET["UserId_p"]));
                }else if(isset($_GET["MenaSoft"]) && isset($_GET["Dia"]) && isset($_GET["Mes"]) && isset($_GET["Year"])){
                    echo json_encode($Helper->GetAutorizaciones($_GET["Dia"], $_GET["Mes"], $_GET["Year"]));
                }else{
                    echo json_encode($Helper->GetAll());
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Protocolo"])) {
                    $Protocolo = json_decode($_POST["Protocolo"]);
                    $Helper = new AutorizacionBLL();
                    echo json_encode($Helper->CreateProtocoloAutorizacion($Protocolo[0]));
                }else if (isset($_POST["UsuarioAutorizacion"])) {
                    $Autorizacion = json_decode($_POST["UsuarioAutorizacion"]);
                    $Helper = new AutorizacionBLL();
                    echo $Helper->AsignarAutorizacionUsuario($Autorizacion);
                }  else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Autorizacion"])) {
                    $Autorizacion = json_decode($_PUT["Autorizacion"]);
                    $Helper = new AutorizacionBLL();
                    echo json_encode($Helper->UpdateAutorizacion($Autorizacion));
                }

                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["AutorizacionId"])) {
                    $Helper = new AutorizacionBLL();
                    echo json_encode($Helper->DeleteAutorizacion($_DELETE["AutorizacionId"]));
                }
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/ProtocoloBLL.php';

class ProtocoloAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new ProtocoloBLL();
                if (isset($_GET["UserId"])) {
                    echo json_encode($Helper->GetAllByUserId($_GET["UserId"]));
                }else{
                    echo json_encode($Helper->GetAll());
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Protocolo"])) {
                    $Protocolo = json_decode($_POST["Protocolo"]);
                    $Helper = new ProtocoloBLL();
                    echo json_encode($Helper->CreateProtocolo($Protocolo));
                }else if (isset($_POST["UsuarioProtocolo"])) {
                    $Protocolo = json_decode($_POST["UsuarioProtocolo"]);
                    $Helper = new ProtocoloBLL();
                    echo $Helper->AsignarProtocoloUsuario($Protocolo);
                }  else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Protocolo"]) && isset($_PUT["ID"])) {
                    $Protocolo = json_decode($_PUT["Protocolo"]);
                    $id = $_PUT["ID"];
                    $Helper = new ProtocoloBLL();
                    echo json_encode($Helper->UpdateProtocolo($Protocolo, $id));
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

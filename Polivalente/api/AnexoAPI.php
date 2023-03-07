<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/AnexoBLL.php';

class AnexoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new AnexoBLL();
                if (isset($_GET["FlujoTrabajoId"]) && isset($_GET["VerificadorId"])) {
                    echo json_encode($Helper->GetAllByVerificadorId($_GET["VerificadorId"], $_GET["FlujoTrabajoId"]));
                }else{
                    echo json_encode($Helper->GetAll());
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Anexo"])) {
                    $Anexo = json_decode($_POST["Anexo"]);
                    $Helper = new AnexoBLL();
                    echo json_encode($Helper->CreateAnexo($Anexo));
                }else if (isset($_POST["UsuarioAnexo"])) {
                    $Anexo = json_decode($_POST["UsuarioAnexo"]);
                    $Helper = new AnexoBLL();
                    echo $Helper->AsignarAnexoUsuario($Anexo);
                }  else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Anexo"]) && isset($_PUT["ID"])) {
                    $Anexo = json_decode($_PUT["Anexo"]);
                    $id = $_PUT["ID"];
                    $Helper = new AnexoBLL();
                    echo json_encode($Helper->UpdateAnexo($Anexo, $id));
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

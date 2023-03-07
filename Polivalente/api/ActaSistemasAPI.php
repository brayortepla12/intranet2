<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/formulario/ActaSistemasBLL.php';
class ActaSistemasAPI {
    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new ActaSistemasBLL();
                if(isset($_GET["Actas"])){
                    echo json_encode($Helper->GetAll());
                }else if(isset($_GET["ActaId"])){
                    echo json_encode($Helper->GetDetalleByActaId($_GET["ActaId"]));
                }
                break;
            case 'POST'://inserta
                $Helper = new ActaSistemasBLL();
                if (isset($_POST["ActaSistemas"])) {
                    $Acta = json_decode($_POST["ActaSistemas"]);
                    echo json_encode($Helper->CreateActaSistemas($Acta));
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["ActaSistemasId"])) {
                   $Helper = new ActaSistemasBLL();
                   echo json_encode($Helper->CambiarEstadoActaSistemas($_PUT["ActaSistemasId"]));
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

<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../../BLL/thc/HistoriaBLL.php';
class HistoriaAPI {
    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new HistoriaBLL();
                if(isset($_GET["data"])){
                    echo json_encode($Helper->GetAutoCompleteHC($_GET["data"]));
                }else if(isset($_GET["NoAdmision"])){
                    echo json_encode($Helper->GetHistoriaCKrystalosByNoAdmision($_GET["NoAdmision"]));
                }else if(isset($_GET["UsuarioId"])){
                    echo json_encode($Helper->GetMisHistorias($_GET["UsuarioId"]));
                }else if(isset($_GET["UsuarioIdPR"])){// pendientes recibir
                    echo json_encode($Helper->GetHistoriasPR($_GET["UsuarioIdPR"]));
                }else if(isset($_GET["HistoriaId"])){
                    echo json_encode($Helper->GetHistoriaById($_GET["HistoriaId"]));
                }else if(isset($_GET["TrazaHistoriaId"])){
                    echo json_encode($Helper->GetTrazabilidadByHistoriaId($_GET["TrazaHistoriaId"]));
                }else if(isset($_GET["cahistoria"])){
                    echo json_encode($Helper->GetLiteHistoriaByAdmision($_GET["cahistoria"]));
                }else{
                    echo "error";
                }
                break;
            case 'POST'://inserta
                $Helper = new HistoriaBLL();
                if (isset($_POST["Entrega"])) {
                    $Historia = json_decode($_POST["Entrega"]);
                    echo json_encode($Helper->CreateHistoria($Historia[0]));
                }else if (isset($_POST["Traslado"])) {
                    $Historia = json_decode($_POST["Traslado"]);
                    echo json_encode($Helper->CreateTraslado($Historia[0]));
                }else{
                    echo 'data_error';
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["HistoriaId"])) {
                   $Helper = new HistoriaBLL();
                   echo json_encode($Helper->CambiarEstadoHistoria($_PUT["HistoriaId"]));
                }else if (isset($_PUT["data_pr"])) {
                   $Helper = new HistoriaBLL();
                   echo json_encode($Helper->RecibirHistoria(json_decode($_PUT["data_pr"])));
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

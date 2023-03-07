<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../../BLL/thc/NotasBLL.php';
class NotasAPI {
    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new NotasBLL();
                if(isset($_GET["HistoriaId"])){
                    echo json_encode($Helper->GetNotasByHistoriaId($_GET["HistoriaId"]));
                }else{
                    echo "error";
                }
                break;
            case 'POST'://inserta
                $Helper = new NotasBLL();
                if (isset($_POST["Nota"])) {
                    $obj = json_decode($_POST["Nota"]);
                    echo json_encode($Helper->CreateNota($obj));
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["NotasId"])) {
                   $Helper = new NotasBLL();
                   echo json_encode($Helper->CambiarEstadoNotas($_PUT["NotasId"]));
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

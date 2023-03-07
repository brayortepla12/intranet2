<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/FrecuenciaBLL.php';

class FrecuenciaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new FrecuenciaBLL();
                echo $Helper->GetAll();
                break;
            case 'POST'://inserta
                if (isset($_POST["Frecuencia"])) {
                    $Frecuencia = json_decode($_POST["Frecuencia"]);
                    $Helper = new FrecuenciaBLL();
                    echo json_encode($Helper->CreateFrecuencia($Frecuencia));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Frecuencia"]) && isset($_PUT["ID"])) {
                    $Frecuencia = json_decode($_PUT["Frecuencia"]);
                    $id = $_PUT["ID"];
                    $Helper = new FrecuenciaBLL();
                    echo json_encode($Helper->UpdateFrecuencia($Frecuencia, $id));
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

<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/EncabezadoBLL.php';

class EncabezadoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new EncabezadoBLL();
                echo json_encode($Helper->GetEncabezado());
                break;
            case 'POST'://inserta
                if (isset($_POST["Encabezado"])) {
                    $Encabezado = json_decode($_POST["Encabezado"]);
                    $Helper = new EncabezadoBLL();
                    echo json_encode($Helper->CreateEncabezado($Encabezado));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Encabezado"]) && isset($_PUT["ID"])) {
                    $Encabezado = json_decode($_PUT["Encabezado"]);
                    $id = $_PUT["ID"];
                    $Helper = new EncabezadoBLL();
                    echo json_encode($Helper->UpdateEncabezado($Encabezado, $id));
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

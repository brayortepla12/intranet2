<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/CronogramaBLL.php';

class CronogramaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new CronogramaBLL();
                echo $Helper->GetAll();
                break;
            case 'POST'://inserta
                if (isset($_POST["Cronograma"])) {
                    $Cronograma = json_decode($_POST["Cronograma"]);
                    $Helper = new CronogramaBLL();
                    echo json_encode($Helper->CreateCronograma($Cronograma));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Cronograma"]) && isset($_PUT["ID"])) {
                    $Cronograma = json_decode($_PUT["Cronograma"]);
                    $id = $_PUT["ID"];
                    $Helper = new CronogramaBLL();
                    echo json_encode($Helper->UpdateCronograma($Cronograma, $id));
                }

                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["Cronograma"])) {
                    $h = new CronogramaDAL();
                    $c = json_decode($_DELETE["Cronograma"]);
                    echo $h->DeleteCronograma($c[0]->CronogramaId);
                }
                
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

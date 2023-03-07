<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/ambulancia/KMAmbulanciaBLL.php';

class KMAmbulanciaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new KMAmbulanciaBLL();
                if (isset($_GET["HojaVidaId"])) {
                    echo json_encode($Helper->GetLastKMByHojaVidaId($_GET["HojaVidaId"]));
                }else {
                    echo $Helper->GetAll();
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["KM"])) {
                    $KM = json_decode($_POST["KM"]);
                    $Helper = new KMAmbulanciaBLL();
                    echo json_encode($Helper->CreateKM($KM));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["EditKm"])) {
                    $KM = json_decode($_PUT["EditKm"]);
                    $Helper = new KMAmbulanciaBLL();
                    echo json_encode($Helper->UpdateKM($KM, $KM->KMId));
                }

                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["KM"])) {
                    $h = new KMDAL();
                    $c = json_decode($_DELETE["KM"]);
                    echo $h->DeleteKM($c[0]->KMId);
                }

                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

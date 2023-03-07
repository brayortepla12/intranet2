<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/CIBLL.php';

class CIAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new CIBLL();
                if (isset($_GET["Vigencia"]) && isset($_GET["SedeId"])) {
                    $Helper->GetExcelCronograma($_GET["Vigencia"], $_GET["SedeId"]);
                }else if(isset($_GET["Vigencia_ci"]) && isset($_GET["ServicioId"])){
                    echo json_encode($Helper->GetAllByServicioId($_GET["Vigencia_ci"], $_GET["ServicioId"]));
                }else{
                    echo "Sueñalo";
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Data"]) && isset($_POST["Year"])) {
                    $CI = json_decode($_POST["Data"]);
                    $Helper = new CIBLL();
                    echo json_encode($Helper->CreateCI($CI, $_POST["Year"], $_POST["CreatedBy"]));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["CI"]) && isset($_PUT["ID"])) {
                    $CI = json_decode($_PUT["CI"]);
                    $id = $_PUT["ID"];
                    $Helper = new CIBLL();
                    echo json_encode($Helper->UpdateCI($CI, $id));
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

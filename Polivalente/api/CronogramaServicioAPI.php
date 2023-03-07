<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/CronogramaServicioBLL.php';

class CronogramaServicioAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new CronogramaServicioBLL();
                if (isset($_GET["UserId"])) {
                    echo $Helper->GetAllByUserId($_GET["UserId"]);
                }else if (isset($_GET["GenerateExcel_SedeId"]) && isset($_GET["GenerateExcel_Vigencia"]) && isset($_GET["Prefijo"])) {
                    $Helper->GetExcelCronograma($_GET["GenerateExcel_SedeId"], $_GET["GenerateExcel_Vigencia"], $_GET["Prefijo"]);
                }else{
                    echo json_encode($Helper->GetAll());
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["CronogramaServicio"]) && isset($_POST["CreatedBy"]) && isset($_POST["Prefijo"])) {
                    $CronogramaServicio = json_decode($_POST["CronogramaServicio"]);
                    $Helper = new CronogramaServicioBLL();
                    echo json_encode($Helper->CreateCronogramaServicio($CronogramaServicio, $_POST["CreatedBy"], $_POST["Prefijo"]));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["CronogramaServicio"]) && isset($_PUT["ID"])) {
                    $CronogramaServicio = json_decode($_PUT["CronogramaServicio"]);
                    $id = $_PUT["ID"];
                    $Helper = new CronogramaServicioBLL();
                    echo json_encode($Helper->UpdateCronogramaServicio($CronogramaServicio, $id));
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

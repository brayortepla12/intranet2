<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/CronogramaServicioSistemaBLL.php';

class CronogramaServicioSistemaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new CronogramaServicioSistemaBLL();
                if (isset($_GET["UserId"])) {
                    echo $Helper->GetAllByUserId($_GET["UserId"]);
                }else if (isset($_GET["Mes_ex"]) && isset($_GET["Year_ex"])) {
                    echo $Helper->GenerateExcelCronograma($_GET["Mes_ex"], $_GET["Year_ex"]);
                }else if (isset($_GET["GenerateExcel_UserId"])) {
                    echo $Helper->GenerateExcelCronograma($_GET["GenerateExcel_UserId"]);
                }else if(isset($_GET["Vigencia"])){
                    echo json_encode($Helper->getAllByVigencia($_GET["Vigencia"]));
                }else if(isset($_GET["prueba_noti"])){
                    echo json_encode($Helper->EnviarNotificacionMantenimiento());
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["CronogramaServicio"])) {
                    $CronogramaServicioSistema = json_decode($_POST["CronogramaServicio"]);
                    $Helper = new CronogramaServicioSistemaBLL();
                    echo json_encode($Helper->CreateCronogramaServicioSistema($CronogramaServicioSistema));
                } else if (isset($_POST["Data"]) && isset($_POST["Year"]) && isset($_POST["CreatedBy"])) {
                    $Helper = new CronogramaServicioSistemaBLL();
                    $Data = json_decode($_POST["Data"]);
                    echo json_encode($Helper->CreateCronogramaServicioSistema($Data, $_POST["Year"], $_POST["CreatedBy"]));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["CronogramaServicio"]) && isset($_PUT["ID"])) {
                    $CronogramaServicioSistema = json_decode($_PUT["CronogramaServicio"]);
                    $id = $_PUT["ID"];
                    $Helper = new CronogramaServicioSistemaBLL();
                    echo json_encode($Helper->UpdateCronogramaServicioSistema($CronogramaServicioSistema, $id));
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

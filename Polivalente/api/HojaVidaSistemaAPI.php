<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/formulario/HojaVidaSistemaBLL.php';

class HojaVidaSistemaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new HojaVidaSistemaBLL();
                if (isset($_GET["ServicioId"])) {
                    echo $Helper->GetHojasByServicio($_GET["ServicioId"]);
                } else if (isset($_GET["ServicioId_print"])) {
                    echo $Helper->GetHojasByServicioPrint($_GET["ServicioId_print"]);
                } else if (isset($_GET["SedeId"]) && isset($_GET["ServicioId_all"]) && isset($_GET["Estado"])) {
                    echo $Helper->GetHojasBySedeId($_GET["SedeId"], $_GET["ServicioId_all"], $_GET["Estado"]);
                } else if (isset($_GET["HojaVidaAll"])) {
                    echo $Helper->GetHojaVidas();
                } else if (isset($_GET["HojaVidaId"])) {
                    echo $Helper->GetHojasByHojaVidaId($_GET["HojaVidaId"]);
                } else if (isset($_GET["Cuenta"]) && isset($_GET["UsuarioId"])) {
                    switch ($_GET["Cuenta"]) {
                        case 1:
                            echo json_encode($Helper->CountHojaVidas2($_GET["UsuarioId"]));
                            break;
                        case 2:
                            echo json_encode($Helper->CountComputadores($_GET["UsuarioId"]));
                            break;
                        case 3:
                            echo json_encode($Helper->CountImpresoras($_GET["UsuarioId"]));
                            break;
                        default:
                            echo "Error";
                            break;
                    }
                } else {
                    echo $Helper->GetNHojaVida();
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["HojaVida"]) && isset($_POST["UserId"])) {
                    $Helper = new HojaVidaSistemaBLL();
                    $Obj = json_decode($_POST["HojaVida"])[0];
                    echo json_encode($Helper->CreateHojaVida($Obj));
                }
//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["HojaVida"]) && isset($_PUT["UserId"])) {
                    $Helper = new HojaVidaSistemaBLL();
                    $Obj = json_decode($_PUT["HojaVida"])[0];
                    echo json_encode($Helper->UpdateHojaVida($Obj));
                }else if(isset($_PUT["HojaVida_Mant"])){
                    $Helper = new HojaVidaSistemaBLL();
                    $HojaVida = json_decode($_PUT["HojaVida_Mant"]);
                    echo json_encode($Helper->MantHojaVida($HojaVida[0]));
                }else if(isset($_PUT["HojaVida_Act"])){
                    $Helper = new HojaVidaSistemaBLL();
                    $HojaVida = json_decode($_PUT["HojaVida_Act"]);
                    echo json_encode($Helper->ActHojaVida($HojaVida[0]));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["HojaVida"])) {
                    $Helper = new HojaVidaSistemaBLL();
                    $HojaVida = json_decode($_DELETE["HojaVida"]);
                    echo json_encode($Helper->DeleteHojaVida($HojaVida[0]));
                }else if (isset($_DELETE["HojaVida_baja"])) {
                    $Helper = new HojaVidaSistemaBLL();
                    $HojaVida = json_decode($_DELETE["HojaVida_baja"]);
                    echo json_encode($Helper->BajaHojaVida($HojaVida[0]));
                }
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

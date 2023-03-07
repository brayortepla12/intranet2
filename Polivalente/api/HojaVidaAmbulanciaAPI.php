<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/ambulancia/HojaVidaAmbulanciaBLL.php';

class HojaVidaAmbulanciaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new HojaVidaAmbulanciaBLL();
                if (isset($_GET["ServicioId"])) {
                    echo $Helper->GetHojasByServicio($_GET["ServicioId"]);
                }else if (isset($_GET["SedeId"])) {
                    echo $Helper->GetHojasBySedeId($_GET["SedeId"]);
                }else if (isset($_GET["HojaVidaALL"])) {
                    echo json_encode($Helper->GetAllHojas());
                }else if (isset($_GET["HojaVidaId"])) {
                    echo $Helper->GetHojasByHojaVidaId($_GET["HojaVidaId"]);
                }else if(isset($_GET["Cuenta"]) && isset($_GET["UsuarioId"])){
                    echo json_encode($Helper->CountHojaVidas2($_GET["UsuarioId"]));
                }else if(isset($_GET["Correo"])){
                    $Helper->EmailMovilesProxVencerSoatTec();
                }else{
                    echo $Helper->GetNHojaVida();
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["HojaVida"]) && isset($_POST["UserId"])) {
                   $Helper = new HojaVidaAmbulanciaBLL();
                   $Obj = json_decode($_POST["HojaVida"])[0];
                   echo json_encode($Helper->CreateHojaVida($Obj));
                }
//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["HojaVida"]) && isset($_PUT["UserId"])) {
                    $Helper = new HojaVidaAmbulanciaBLL();
                    $Obj = json_decode($_PUT["HojaVida"])[0];
                    echo json_encode($Helper->UpdateHojaVida($Obj));
                }else if (isset($_PUT["EstadoMovil"])) {
                    $Helper = new HojaVidaAmbulanciaBLL();
                    $Obj = json_decode($_PUT["EstadoMovil"]);
                    echo json_encode($Helper->UpdateEstadoMovil($Obj));
                }else if (isset($_PUT["EliminarMovil"])) {
                    $Helper = new HojaVidaAmbulanciaBLL();
                    $Obj = json_decode($_PUT["EliminarMovil"]);
                    echo json_encode($Helper->UpdateEstado($Obj));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["HojaVida"])) {
                    $Helper = new HojaVidaAmbulanciaBLL();
                    $HojaVida = json_decode($_DELETE["HojaVida"]);
                    echo json_encode($Helper->DeleteHojaVida($HojaVida[0]));
                }
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

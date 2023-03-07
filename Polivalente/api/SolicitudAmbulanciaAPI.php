<?php  require_once "ErrorHandler.php";

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/ambulancia/SolicitudAmbulanciaBLL.php';
include_once dirname(__FILE__) . '/../BLL/ambulancia/ReporteAmbulanciaBLL.php';
class SolicitudAmbulanciaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new SolicitudAmbulanciaBLL();
                if (isset($_GET["Solicitudes"]) && isset($_GET["Year"]) && isset($_GET["Mes"]) && isset($_GET["Estado"]) && isset($_GET["Placa"])) {
                    echo json_encode($Helper->GetSolicitudAmbulancia($_GET["Year"],$_GET["Mes"], $_GET["Estado"], $_GET["Placa"]));
                }else if (isset($_GET["Item"])) {
                    echo json_encode($Helper->GetItem());
                }else if (isset($_GET["Proveedor"])) {
                    echo json_encode($Helper->GetProveedores());
                }else if (isset($_GET["SolicitudMantenimientoId"])) {
                    echo json_encode($Helper->GetFacturaBySolicitudMantenimientoId($_GET["SolicitudMantenimientoId"]));
                }else if (isset($_GET["Reporte_SolicitudMantenimientoId"])) {
                    $hr = new ReporteAmbulanciaBLL();
                    echo json_encode($hr->GetReporteBySolicitudMantenimientoId($_GET["Reporte_SolicitudMantenimientoId"]));
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Solicitud"])) {
                    $Helper = new SolicitudAmbulanciaBLL();
                    $Obj = json_decode($_POST["Solicitud"]);
                    echo json_encode($Helper->CreateSolicitudAmbulancia($Obj));
                } else if (isset($_POST["Item"])) {
                    $Helper = new SolicitudAmbulanciaBLL();
                    $Obj = json_decode($_POST["Item"]);
                    echo json_encode($Helper->CreateItem($Obj));
                } else if (isset($_POST["Proveedor"])) {
                    $Helper = new SolicitudAmbulanciaBLL();
                    $Obj = json_decode($_POST["Proveedor"]);
                    echo json_encode($Helper->CreateProveedor($Obj));
                } else if (isset($_POST["Factura"])) {
                    $Helper = new SolicitudAmbulanciaBLL();
                    $Obj = json_decode($_POST["Factura"]);
                    $Detalles = json_decode($Obj->Detalles)[0];
                    echo json_encode($Helper->CreateFactura($Obj, $Detalles));
                }  else {
                    echo "error";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Solicitud"])) {
                    $Helper = new SolicitudAmbulanciaBLL();
                    $Obj = json_decode($_PUT["Solicitud"]);
                    $Obj->Detalles = json_decode($Obj->Detalles);
                    echo json_encode($Helper->UpdateSolicitudAmbulancia($Obj));
                }else if (isset($_PUT["SolicitudMantenimientoId"]) && isset($_PUT["Estado"])) {
                    $Helper = new SolicitudAmbulanciaBLL();
                    echo json_encode($Helper->UpdateEstadoSolicitudAmbulancia($_PUT["SolicitudMantenimientoId"], $_PUT["Estado"]));
                }else if (isset($_PUT["Factura"])) {
                    $Helper = new SolicitudAmbulanciaBLL();
                    $Obj = json_decode($_PUT["Factura"]);
                    $Detalles = json_decode($Obj->Detalles)[0];
                    echo json_encode($Helper->UpdateFactura($Obj, $Detalles));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                $Helper = new SolicitudAmbulanciaBLL();
                $Obj = json_decode($_DELETE["SolicitudAmbulancia"])[0];
                echo json_encode($Helper->DeleteSolicitudAmbulanciaById($Obj));
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class

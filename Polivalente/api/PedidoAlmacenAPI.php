<?php

/**
 * @author Franklin ospino
 */
require_once __DIR__ . '/../BLL/almacen/PedidoAlmacenBLL.php';

class PedidoAlmacenAPI
{

    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET': //consulta
                $Helper = new PedidoAlmacenBLL();
                if (isset($_GET["UserId"])) {
                    echo json_encode($Helper->GetAllByUserId($_GET["UserId"]));
                } else if (isset($_GET["SolicitanteId"]) && isset($_GET["Tipo1"])) {
                    echo json_encode($Helper->GetAllByUserIdVer2($_GET["SolicitanteId"], $_GET["Tipo1"]));
                } else if (isset($_GET["SolicitanteIdRepuesto"]) && isset($_GET["Tipo1Repuesto"])) {
                    echo json_encode($Helper->GetAllByUserIdVer2Repuesto($_GET["SolicitanteIdRepuesto"], $_GET["Tipo1Repuesto"]));
                }else if (isset($_GET["Usuario_sede"]) && isset($_GET["Estado_sede"])) {
                    echo json_encode($Helper->GetAllBySedeId($_GET["Usuario_sede"], $_GET["Estado_sede"]));
                } else if (isset($_GET["Usuario_sede_2"]) && isset($_GET["Estado_2"]) && isset($_GET["Tipo"])) {
                    echo json_encode($Helper->GetAllBySedeIdVer2($_GET["Usuario_sede_2"], $_GET["Estado_2"], $_GET["Tipo"]));
                } else if (isset($_GET["Usuario_sede_2Pedido"]) && isset($_GET["Estado_2Pedido"]) && isset($_GET["TipoPedido"])) {
                    echo json_encode($Helper->GetAllBySedeIdVer2Pedido($_GET["Usuario_sede_2Pedido"], $_GET["Estado_2Pedido"], $_GET["TipoPedido"]));
                } else if (isset($_GET["PedidoAlmacenId"])) {
                    echo json_encode($Helper->GetAllById($_GET["PedidoAlmacenId"]));
                } else if (isset($_GET["PedidoAlmacenId_item"])) {
                    echo json_encode($Helper->GetAllItemsById($_GET["PedidoAlmacenId_item"]));
                } else if (isset($_GET["PedidoAlmacenId_itemAdmin"])) {
                    echo json_encode($Helper->GetAllItemsByIdAdmin($_GET["PedidoAlmacenId_itemAdmin"]));
                } else if (isset($_GET["PedidoAlmacenId_itemAdminRepuesto"])) {
                    echo json_encode($Helper->GetAllItemsByIdAdminRepuesto($_GET["PedidoAlmacenId_itemAdminRepuesto"]));
                } else if (isset($_GET["Pedido_sm_Id"])) {
                    echo json_encode($Helper->GetPedidoById_sm($_GET["Pedido_sm_Id"]));
                } else if (isset($_GET["Desde_Es"]) && isset($_GET["Hasta_Es"]) && isset($_GET["UsuarioId_Es"])) { # estadisticas
                    echo $Helper->getEstadisticasByUsuarioId($_GET["Desde_Es"], $_GET["Hasta_Es"], $_GET["UsuarioId_Es"]);
                }
                break;
            case 'POST': //inserta
                if (isset($_POST["PedidoAlmacen"])) {
                    $PedidoAlmacen = json_decode($_POST["PedidoAlmacen"]);
                    $Helper = new PedidoAlmacenBLL();
                    echo json_encode($Helper->CreatePedidoAlmacen($PedidoAlmacen));
                } else if (isset($_POST["PedidoAlmacenRepuesto"])) {
                    $PedidoAlmacen = json_decode($_POST["PedidoAlmacenRepuesto"]);
                    $Helper = new PedidoAlmacenBLL();
                    echo json_encode($Helper->CreatePedidoAlmacenRepuesto($PedidoAlmacen));
                } else if (isset($_POST["PedidoAlmacen2"])) {
                    $PedidoAlmacen = json_decode($_POST["PedidoAlmacen2"]);
                    $Helper = new PedidoAlmacenBLL();
                    echo json_encode($Helper->CreatePedidoAlmacen2($PedidoAlmacen));
                } else if (isset($_POST["UsuarioPedidoAlmacen"])) {
                    $PedidoAlmacen = json_decode($_POST["UsuarioPedidoAlmacen"]);
                    $Helper = new PedidoAlmacenBLL();
                    echo $Helper->AsignarPedidoAlmacenUsuario($PedidoAlmacen);
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT': //actualiza

                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["PedidoAlmacen"])) {
                    $PedidoAlmacen = json_decode($_PUT["PedidoAlmacen"]);
                    $Helper = new PedidoAlmacenBLL();
                    echo json_encode($Helper->UpdatePedidoAlmacen($PedidoAlmacen));
                } else if (isset($_PUT["PedidoAlmacen_2Repuesto"])) {
                    $PedidoAlmacen = json_decode($_PUT["PedidoAlmacen_2Repuesto"]);
                    $Helper = new PedidoAlmacenBLL();
                    echo json_encode($Helper->UpdatePedidoAlmacenVer2Repuesto($PedidoAlmacen));
                } else if (isset($_PUT["PedidoAlmacen_2"])) {
                    $PedidoAlmacen = json_decode($_PUT["PedidoAlmacen_2"]);
                    $Helper = new PedidoAlmacenBLL();
                    echo json_encode($Helper->UpdatePedidoAlmacenVer2($PedidoAlmacen));
                }

                break;
            case 'DELETE': //elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["PedidoAlmacenId"])) {
                    $Helper = new PedidoAlmacenBLL();
                    echo json_encode($Helper->DeletePedidoAlmacen($_DELETE["PedidoAlmacenId"]));
                } else if (isset($_DELETE["PedidoAlmacenId_2"])) {
                    $Helper = new PedidoAlmacenBLL();
                    echo json_encode($Helper->DeletePedidoAlmacenVer2($_DELETE["PedidoAlmacenId_2"]));
                } else if (isset($_DELETE["PedidoAlmacenId_2Repuesto"])) {
                    $Helper = new PedidoAlmacenBLL();
                    echo json_encode($Helper->DeletePedidoAlmacenVer2Repuesto($_DELETE["PedidoAlmacenId_2Repuesto"]));
                }
                break;
            default: //metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }
}

//end class

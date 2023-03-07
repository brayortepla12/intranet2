<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/procesos/ProcesosBLL.php';

class ProcesosAPI
{

    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET': //consulta
                $Helper = new ProcesosBLL();
                if (isset($_GET["UserId"])) {
                    echo $Helper->GetAllByUserId($_GET["UserId"]);
                } else if (isset($_GET["VerificadorId"]) && isset($_GET["Estado"])) {
                    echo json_encode($Helper->GetAllByVerificadorId($_GET["VerificadorId"], $_GET["Estado"]));
                } else if (isset($_GET["VerificadorId_f"]) && isset($_GET["Estado_f"]) && isset($_GET["Mes_f"]) && isset($_GET["Year_f"])) {
                    echo json_encode($Helper->getAllFinalizadoByVerificadorId($_GET["VerificadorId_f"], $_GET["Estado_f"], $_GET["Mes_f"], $_GET["Year_f"]));
                } else if (isset($_GET["EstadoAutitoria"])) {
                    echo json_encode($Helper->GetProcesosForAuditoria($_GET["EstadoAutitoria"]));
                } else if (isset($_GET["ProcesoId_data"])) {
                    echo json_encode($Helper->GetProcesoData($_GET["ProcesoId_data"]));
                } else if (isset($_GET["ProcesoId"])) {
                    echo json_encode($Helper->GetProcesosByProcesosId($_GET["ProcesoId"]));
                } else if (isset($_GET["VERFProcesoId"])) {
                    $Helper->GetFirmaPDFByProcesoId($_GET["VERFProcesoId"]);
                } else if (isset($_GET["SolicitudMantId"])) {
                    echo json_encode($Helper->GetNotasBySolMantId($_GET["SolicitudMantId"]));
                } else if (isset($_GET["NotaProcesoId"])) {
                    echo json_encode($Helper->GetNotasByProcesoId($_GET["NotaProcesoId"]));
                } else if (isset($_GET["PrefijoReporteId"])) {
                    echo json_encode($Helper->GetPrefijoByReporteId($_GET["PrefijoReporteId"]));
                } else if (isset($_GET["Dato_vendedor"])) {
                    echo json_encode($Helper->getVendedores($_GET["Dato_vendedor"]));
                } else if (isset($_GET["ProcesosOrdenCompra"])) {
                    echo json_encode($Helper->getProcesosOrdenCompra());
                } else if (isset($_GET["OrdenCompraId_pdf"])) {
                    $Helper->getPdfOrdenCompra($_GET["OrdenCompraId_pdf"], true);
                } else {
                    echo $Helper->GetAll();
                }
                break;
            case 'POST': //inserta
                if (isset($_POST["Proceso"])) {
                    $Procesos = json_decode($_POST["Proceso"]);
                    $Helper = new ProcesosBLL();
                    echo json_encode($Helper->CreateProcesos($Procesos));
                } else if (isset($_POST["Nota"])) {
                    $Nota = json_decode($_POST["Nota"]);
                    $Helper = new ProcesosBLL();
                    echo json_encode($Helper->CreateNota($Nota));
                } else if (isset($_POST["Seguimiento"])) {
                    $Seguimiento = json_decode($_POST["Seguimiento"]);
                    $Helper = new ProcesosBLL();
                    echo json_encode($Helper->CreateSeguimiento($Seguimiento));
                } else if (isset($_POST["OrdenCompra"])) {
                    $obj = json_decode($_POST["OrdenCompra"]);
                    $Helper = new ProcesosBLL();
                    echo json_encode($Helper->createOrdenCompra($obj));
                } else if (isset($_POST["Seguimiento_devolver"])) {
                    $Seguimiento = json_decode($_POST["Seguimiento_devolver"]);
                    $Helper = new ProcesosBLL();
                    //                    echo json_encode($Seguimiento);
                    echo json_encode($Helper->CreateSeguimiento_devolver($Seguimiento));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT': //actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Proceso"]) && isset($_PUT["ID"])) {
                    $Procesos = json_decode($_PUT["Proceso"]);
                    $id = $_PUT["ID"];
                    $Helper = new ProcesosBLL();
                    echo json_encode($Helper->UpdateProcesos($Procesos, $id));
                } else if (isset($_PUT["ReanudarProceso"])) {
                    $Helper = new ProcesosBLL();
                    $Procesos = json_decode($_PUT["ReanudarProceso"]);
                    echo json_encode($Helper->ReanudarProceso($Procesos));
                }

                break;
            case 'DELETE': //elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                $m = new ModuloDTO();
                $m = $this->Mapper($_DELETE);
                echo json_encode($m);
                break;
            default: //metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }
}

//end class

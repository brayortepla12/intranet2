<?php

/**
 * @author Franklin ospino
 */
include_once __DIR__ . '/../../BLL/viatico/LegalizacionBLL.php';

class LegalizacionAPI
{

    public function API()
    {
        
        $method = $_SERVER['REQUEST_METHOD'];
        $header = 'Content-Type: application/JSON';
        switch ($method) {
            case 'GET': //consulta
                $Helper = new LegalizacionBLL();
                if (isset($_GET["Mes_leg"]) && isset($_GET["Year_leg"]) && isset($_GET["Tipo_leg"]) && isset($_GET["UsuarioId_leg"])) {
                    header($header);
                    echo json_encode($Helper->GetSolicitudesPendientesLegalizar($_GET["UsuarioId_leg"], $_GET["Tipo_leg"]));
                } else if (isset($_GET["Mes_rl"]) && isset($_GET["Year_rl"]) && isset($_GET["UsuarioId_rl"])) {
                    header($header);
                    echo json_encode($Helper->GetLegalizaciones($_GET["UsuarioId_rl"]));
                } else if (isset($_GET["LegalizacionId_pdf"])) {
                    header($header);
                    echo $Helper->GetLegalizacionPDFById($_GET["LegalizacionId_pdf"], True);
                } else if (isset($_GET["LegalizacionId_dl"])) {
                    header($header);
                    echo json_encode($Helper->GetAnexosByLegalizacionId($_GET["LegalizacionId_dl"]));
                } else if (isset($_GET["DetalleLegalizacionId_dl"])) {
                    echo json_encode($Helper->GetAnexoById($_GET["DetalleLegalizacionId_dl"]));
                } else {
                    echo "invalido";
                }
                break;
            case 'POST': //inserta
                if (isset($_POST["LegalizarViatico"])) {
                    $Helper = new LegalizacionBLL();
                    /**
                     * @var LegalizacionDto $Obj
                     */
                    $Obj = json_decode($_POST["LegalizarViatico"]);
                    echo json_encode($Helper->CreateLegalizacionViatico($Obj));
                } else {
                    echo "error";
                }
                break;
            case 'PUT': //actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["VistoBueno"])) {
                    $Helper = new LegalizacionBLL();
                    $Obj = json_decode($_PUT["VistoBueno"]);
                    echo json_encode($Helper->VistoBueno($Obj));
                }
                break;
            case 'DELETE': //elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                break;
            default: //metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }
}

//end class

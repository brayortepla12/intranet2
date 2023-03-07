<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/almacen/RelacionBLL.php';

class RelacionAPI
{

    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET': //consulta
                $Helper = new RelacionBLL();
                if (isset($_GET["UsuarioId"]) && isset($_GET["ServicioId"]) && isset($_GET["Tipo"])) {
                    echo json_encode($Helper->getAllByUsuarioId($_GET["ServicioId"], $_GET["UsuarioId"], $_GET["Tipo"]));
                } else if (isset($_GET["Usuario_sede"])) {
                    echo json_encode($Helper->GetAllBySedeId($_GET["Usuario_sede"]));
                } else if (isset($_GET["RelacionId"])) {
                    echo json_encode($Helper->GetAllById($_GET["RelacionId"]));
                } else if (isset($_GET["UsuarioId_all"]) && isset($_GET["Estado_all"])) {
                    echo json_encode($Helper->GetPlantilla($_GET["UsuarioId_all"], $_GET["Estado_all"]));
                } else if (isset($_GET["TODO"])) {
                    $Helper->getTODO();
                } else if (isset($_GET["From"]) && isset($_GET["To"]) && isset($_GET["SedeId"]) && isset($_GET["ServicioId"]) && isset($_GET["Tipoe"]) && isset($_GET["TipoSolicitud"])) {
                    $Helper->getEstadisticasPedidos($_GET["From"], $_GET["To"], $_GET["SedeId"], $_GET["ServicioId"], $_GET["Tipoe"], $_GET["TipoSolicitud"]);
                } else if (isset($_GET["Estadistica"]) && isset($_GET["From2"]) && isset($_GET["To2"]) && isset($_GET["SedeId"]) && isset($_GET["ServicioId"]) && isset($_GET["Tipo_e"])) {
                    echo json_encode($Helper->getEstadisticasPedidos_data($_GET["From2"], $_GET["To2"], $_GET["SedeId"], $_GET["ServicioId"], $_GET["Tipo_e"]));
                } else if (isset($_GET["EstadisticaRepuesto"]) && isset($_GET["From2Repuesto"]) && isset($_GET["To2Repuesto"]) && isset($_GET["SedeIdRepuesto"]) && isset($_GET["ServicioIdRepuesto"]) && isset($_GET["Tipo_eRepuesto"])) {
                    echo json_encode($Helper->getEstadisticasPedidos_dataRepuesto($_GET["From2Repuesto"], $_GET["To2Repuesto"], $_GET["SedeIdRepuesto"], $_GET["ServicioIdRepuesto"], $_GET["Tipo_eRepuesto"]));
                } else {
                    echo json_encode($Helper->GetAll());
                }
                break;
            case 'POST': //inserta
                if (isset($_POST["Relacion"])) {
                    $Relacion = json_decode($_POST["Relacion"]);
                    $Helper = new RelacionBLL();
                    echo json_encode($Helper->CreateRelacion($Relacion));
                } else if (isset($_POST["UsuarioRelacion"])) {
                    $Relacion = json_decode($_POST["UsuarioRelacion"]);
                    $Helper = new RelacionBLL();
                    echo $Helper->AsignarRelacionUsuario($Relacion);
                } else if (isset($_POST["ClonarPlantilla"])) {
                    $obj = json_decode($_POST["ClonarPlantilla"]);
                    $Helper = new RelacionBLL();
                    echo json_encode($Helper->ClonarPlantilla($obj[0]));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT': //actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Relacion"])) {
                    $Relacion = json_decode($_PUT["Relacion"]);
                    $Helper = new RelacionBLL();
                    echo json_encode($Helper->UpdateRelacion($Relacion));
                } else if (isset($_PUT["EditarLimite"])) {
                    $Relacion = json_decode($_PUT["EditarLimite"]);
                    $Helper = new RelacionBLL();
                    echo json_encode($Helper->EditarLimite($Relacion[0]));
                }

                break;
            case 'DELETE': //elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["RelacionId"])) {
                    $Helper = new RelacionBLL();
                    echo json_encode($Helper->DeleteRelacion($_DELETE["RelacionId"]));
                }
                break;
            default: //metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }
}

//end class

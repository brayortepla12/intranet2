<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/ServicioBLL.php';

class ServicioAPI
{

    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET': //consulta
                $Helper = new ServicioBLL();
                if (isset($_GET["SedeId"])) {
                    echo $Helper->GetAllBySede($_GET["SedeId"]);
                } else if (isset($_GET["SedeId_ta"]) && isset($_GET["UsuarioId_ta"]) && isset($_GET["TA"])) {
                    echo $Helper->GetAllBySedeByTA($_GET["SedeId_ta"], $_GET["UsuarioId_ta"], $_GET["TA"]);
                } else if (isset($_GET["SedeId_2"]) && isset($_GET["UserId_2"])) {
                    echo $Helper->GetAllBySedeAndUserId($_GET["SedeId_2"], $_GET["UserId_2"]);
                } else if (isset($_GET["UserId"])) {
                    echo $Helper->GetAllByUserId($_GET["UserId"]);
                } else if (isset($_GET["UserIdRepuesto"])) {
                    echo $Helper->GetAllByUserIdRepuesto($_GET["UserIdRepuesto"]);
                } else if (isset($_GET["LiderUsuarioId"])) {
                    echo $Helper->GetAllByLiderUsuarioId($_GET["LiderUsuarioId"]);
                } else if (isset($_GET["FormatoId"])) {
                    echo $Helper->getAllByFormatoId($_GET["FormatoId"]);
                } else {
                    echo $Helper->GetAll();
                }
                break;
            case 'POST': //inserta
                if (isset($_POST["Servicio"])) {
                    $Servicio = json_decode($_POST["Servicio"]);
                    $Helper = new ServicioBLL();
                    echo json_encode($Helper->CreateServicio($Servicio));
                } else if (isset($_POST["ServicioUsuario"])) {
                    $ServicioUsuario = json_decode($_POST["ServicioUsuario"]);
                    $Helper = new ServicioBLL();
                    echo json_encode($Helper->AsignarServicioUsuario($ServicioUsuario));
                } else if (isset($_POST["FormatoServicio"])) {
                    $ServicioUsuario = json_decode($_POST["FormatoServicio"]);
                    $Helper = new ServicioBLL();
                    echo json_encode($Helper->AsignarFormatoServicio($ServicioUsuario));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT': //actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Servicio"]) && isset($_PUT["ID"])) {
                    $Servicio = json_decode($_PUT["Servicio"]);
                    $id = $_PUT["ID"];
                    $Helper = new ServicioBLL();
                    echo json_encode($Helper->UpdateServicio($Servicio, $id));
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

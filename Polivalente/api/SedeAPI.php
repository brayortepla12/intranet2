<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/SedeBLL.php';

class SedeAPI
{

    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET': //consulta
                $Helper = new SedeBLL();
                if (isset($_GET["UserId"])) {
                    echo $Helper->GetAllByUserId($_GET["UserId"]);
                } else if (isset($_GET["UserIdRepuesto"])) {
                    echo $Helper->GetAllByUserIdRepuesto($_GET["UserIdRepuesto"]);
                } else if (isset($_GET["LiderUsuarioId"])) {
                    echo $Helper->GetAllByLiderUsuarioId($_GET["LiderUsuarioId"]);
                } else if (isset($_GET["UserId_ta"]) && isset($_GET["TA"])) {
                    echo $Helper->GetAllByUserId_ta($_GET["UserId_ta"], $_GET["TA"]);
                } else {
                    echo $Helper->GetAll();
                }
                break;
            case 'POST': //inserta
                if (isset($_POST["Sede"])) {
                    $Sede = json_decode($_POST["Sede"]);
                    $Helper = new SedeBLL();
                    echo json_encode($Helper->CreateSede($Sede));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT': //actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Sede"]) && isset($_PUT["ID"])) {
                    $Sede = json_decode($_PUT["Sede"]);
                    $id = $_PUT["ID"];
                    $Helper = new SedeBLL();
                    echo json_encode($Helper->UpdateSede($Sede, $id));
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

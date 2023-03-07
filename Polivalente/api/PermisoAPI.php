<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/PermisoBLL.php';

class PermisoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new PermisoBLL();
                if (isset($_GET["UserId"])) {
                    echo json_encode($Helper->GetAllByUserId($_GET["UserId"]));
                } else if (isset($_GET["LiderUsuarioId"])) {
                    echo json_encode($Helper->GetAllByLiderUsuarioId($_GET["LiderUsuarioId"]));
                } else {
                    echo json_encode($Helper->GetAll());
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Permiso"])) {
                    $Permiso = json_decode($_POST["Permiso"]);
                    $Helper = new PermisoBLL();
                    echo json_encode($Helper->CreatePermiso($Permiso));
                } else if (isset($_POST["UsuarioPermiso"])) {
                    $Permiso = json_decode($_POST["UsuarioPermiso"]);
                    $Helper = new PermisoBLL();
                    echo $Helper->AsignarPermisoUsuario($Permiso);
                } else if (isset($_POST["UsuarioPermisoH"])) {
                    $Permiso = json_decode($_POST["UsuarioPermisoH"]);
                    $Helper = new PermisoBLL();
                    echo $Helper->AsignarPermisoUsuarioH($Permiso);
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Permiso"]) && isset($_PUT["ID"])) {
                    $Permiso = json_decode($_PUT["Permiso"]);
                    $id = $_PUT["ID"];
                    $Helper = new PermisoBLL();
                    echo json_encode($Helper->UpdatePermiso($Permiso, $id));
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

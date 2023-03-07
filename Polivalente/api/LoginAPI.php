<?php

/**  
 * @author Franklin ospino
 */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include_once dirname(__FILE__) . '/../BLL/seguridad/UsuarioBLL.php';
class LoginAPI
{

    public function API()
    {

        header('Content-Type: application/JSON; charset=utf-8');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET': //consulta
                echo 'Error';
                break;
            case 'POST': //inserta
                if (isset($_POST["usuario"]) && isset($_POST["contrasena"])) {
                    $Nombre = $_POST["usuario"];
                    $Contrasena = $_POST["contrasena"];
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->Login($Nombre, $Contrasena));
                } else if (isset($_POST["User"]) && isset($_POST["Password"])) {
                    $Nombre = $_POST["User"];
                    $Contrasena = $_POST["Password"];
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->LoginIntranet2($Nombre, $Contrasena));
                } else if (isset($_POST["AutoLogin"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->LoginAuto($_POST["AutoLogin"]));
                } else if (isset($_POST["usuario_2"]) && isset($_POST["contrasena_2"])) {
                    $Nombre = $_POST["usuario_2"];
                    $Contrasena = $_POST["contrasena_2"];
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->Login($Nombre, $Contrasena));
                    //                echo $_POST["usuario_2"];
                } else if (isset($_POST["usuario2"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->ResetPassword($_POST["usuario2"]));
                } else {
                    $json = file_get_contents('php://input');
                    $obj = json_decode($json);
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->Login($obj->usuario, $obj->contrasena));
                }
                break;
            case 'PUT': //actualiza
                parse_str(file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']), $_PUT);
                $m = new ModuloDTO();
                $m = $this->Mapper($_PUT);
                echo json_encode($m);
                break;
            case 'DELETE': //elimina
                parse_str(file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']), $_DELETE);
                $m = new ModuloDTO();
                $m = $this->Mapper($_DELETE);
                echo json_encode($m);
                break;
            default: //metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }
}//end class
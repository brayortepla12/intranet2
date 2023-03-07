<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/seguridad/UsuarioBLL.php';
include_once dirname(__FILE__) . '/../Security.php';
include_once dirname(__FILE__) . '/../Auth.php';

class UsuarioAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                if (isset($_GET["U_Key"]) && isset($_GET["Clave"])) {
                    $auth = new Auth();
                    if (is_object($auth->DecodeJWT($_GET["U_Key"]))) {
                        $Helper = new UsuarioBLL();
                        echo json_encode($Helper->GetALL());
                    } else {
                        echo "No es valido";
                    }
                } else if (isset($_GET["ServicioId"])) {
                    $Helper = new UsuarioBLL();
                    echo $Helper->GetUsuarioByServicioId($_GET["ServicioId"]);
                } else if (isset($_GET["Email_r"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->GetRolesByUsuario($_GET["Email_r"]));
                } else if (isset($_GET["UsuarioId"])) {
                    $Helper = new UsuarioBLL();
                    echo $Helper->GetUsuarioByUsuarioId($_GET["UsuarioId"]);
                } else if (isset($_GET["Email"])) {
                    $Helper = new UsuarioBLL();
                    echo $Helper->isExistInDB($_GET["Email"]);
                } else if (isset($_GET["Email_reset"])) {
                    $Helper = new UsuarioBLL();
                    echo $Helper->ResetPassword($_GET["Email_reset"]);
                } else if (isset($_GET["ServicioId_plantilla"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->GetUsuarioWithPlantilla());
                } else if (isset($_GET["DocumentoCT"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->GetPersonaByDocumento($_GET["DocumentoCT"]));
                } else if (isset($_GET["U_Key_CM"]) && isset($_GET["Clave_CM"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->GetALLCM());
                } else if (isset($_GET["LiderPId"]) && isset($_GET["PEmail"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->GetUsuariosCol($_GET["LiderPId"],$_GET["PEmail"]));
                } else if (isset($_GET["U_CT"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->GetUsuariosCT());
                } else if (isset($_GET["RolesByUsuarioId"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->GetRoles($_GET["RolesByUsuarioId"]));
                } else if (isset($_GET["ColUId"]) && isset($_GET["RolesByUsuarioLiderId"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->GetRolesByLiderId($_GET["ColUId"], $_GET["RolesByUsuarioLiderId"]));
                } else if (isset($_GET["PERSONA"])) {
                    $Helper = new UsuarioBLL();
                    // $Helper->TraspasarUsuario_ct_persona();
                } else {
                    echo 'No hay Usuarios en DB :(';
                }

                break;
            case 'POST'://inserta
                if (isset($_POST["UserId"]) && isset($_POST["Email"])) {
                    $UserId = $_POST["UserId"];
                    $Helper = new UsuarioBLL();
                    echo $Helper->GetPermisos($UserId);
                } else if (isset($_POST["GETALLPERMISOS"])) {
                    $Helper = new UsuarioBLL();
                    echo $Helper->GetALLPermisos();
                } else if (isset($_POST["Usuario"])) {
                    $Obj = json_decode($_POST["Usuario"])[0];
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->CreateUsuario($Obj));
                }else if(isset ($_POST["UsuarioFromP"])){ 
                    $Obj = json_decode($_POST["UsuarioFromP"])[0];
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->CreateUsuarioFromP($Obj));
                }else {
                    $json = file_get_contents('php://input');
                    $obj = json_decode($json);
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->ActualizarTokenFB($obj->Token_FB, $obj->UsuarioId_FB));
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Usuario"]) && isset($_PUT["Key"])) {
                    $User = json_decode($_PUT["Usuario"]);
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->UpdateUsuario($User[0]));
                } else if (isset($_PUT["pass"]) && isset($_PUT["UsuarioId"]) && isset($_PUT["Email"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->UpdatePassUsuario($_PUT["UsuarioId"], $_PUT["pass"], $_PUT["Email"]));
                } else if (isset($_PUT["UsuarioId2"]) && isset($_PUT["Token"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->UpdateTokenUsuario($_PUT["UsuarioId2"], $_PUT["Token"]));
                } else if (isset($_PUT["pass_c"]) && isset($_PUT["UsuarioId_c"]) && isset($_PUT["pass2_c"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->UpdatePass($_PUT["UsuarioId_c"], $_PUT["pass_c"], $_PUT["pass2_c"]));
                } else if (isset($_PUT["SedeId_all"]) && isset($_PUT["All"]) && isset($_PUT["UsuarioId_all"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->MarcarSedes($_PUT["SedeId_all"], $_PUT["All"], $_PUT["UsuarioId_all"]));
                } else if (isset($_PUT["ModuloId_allp"]) && isset($_PUT["Allp"]) && isset($_PUT["UsuarioId_allp"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->MarcarPermisos($_PUT["ModuloId_allp"], $_PUT["Allp"], $_PUT["UsuarioId_allp"]));
                } else if (isset($_PUT["ModuloId_allph"]) && isset($_PUT["Allph"]) && isset($_PUT["UsuarioId_allph"]) && isset($_PUT["UOId_allph"])) {
                    $Helper = new UsuarioBLL();
                    echo json_encode($Helper->MarcarPermisosH($_PUT["ModuloId_allph"], $_PUT["Allph"], $_PUT["UsuarioId_allph"], $_PUT["UOId_allph"]));
                } else if (isset($_PUT["UpdateUsuario"])) {
                    $Helper = new UsuarioBLL();
                    $User = json_decode($_PUT["UpdateUsuario"]);
                    echo json_encode($Helper->UpdateUsuario($User[0]));
                } else if (isset($_PUT["RUsuario"])) {
                    $Helper = new UsuarioBLL();
                    $User = json_decode($_PUT["RUsuario"]);
                    echo json_encode($Helper->UpdatePass($User[0]->UsuarioId, $User[0]->Contrasena, $User[0]->Contrasena2));
                } else if (isset($_PUT["RolUsuario"])) {
                    $Helper = new UsuarioBLL();
                    $RolUser = json_decode($_PUT["RolUsuario"]);
                    echo json_encode($Helper->UpdateRolUsuario($RolUser));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']), $_DELETE);
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

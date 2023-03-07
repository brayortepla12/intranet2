<?php

/**
 * NO INTENTAR MODIFICAR POR QUE REVIENTA BIEN CHIDO :p
 * 
 * INTENTOS DE REFACTORIZAR -> 2
 * 
 * T-T
 *
 * @author DESARROLLO2
 */
require_once __DIR__ . '/../../DAL/seguridad/UsuarioDAL.php';
require_once __DIR__ . '/../../DAL/ct/PersonaDAL.php';
require_once __DIR__ . '/PermisoBLL.php';
require_once __DIR__ . '/../../Security.php';
require_once __DIR__ . '/../../Auth.php';
require_once __DIR__ . '/../Helpers/Mail/sendMail.php';
require_once __DIR__ . '/../configuracion/EmpresaBLL.php';

class UsuarioBLL
{
    private $db;

    public function __construct() {
        $this->db = new UsuarioDAL();
    }

    public function GetUsuarioById($UserId)
    {
        
        return $this->db->GetUsuarioById($UserId);
    }

    public function GetUsuariosCT()
    {
        
        return $this->db->GetUsuariosCT();
    }

    public function GetRoles(string $UsuarioId) : array
    {
        
        $Roles = $this->db->GetRoles($UsuarioId);
        return ["data" => !empty($Roles) && is_array($Roles) ? $Roles : []];
    }

    public function GetRolesByLiderId(string $UsuarioColId, string $UsuarioLiderId) : array
    {
        
        $Roles = $this->db->Search("SELECT rr.RolId, rr.Nombre, IF(ru.Id, true, false) AS IsSelected  FROM (SELECT r.RolId, r.Nombre
        FROM rol as r 
        INNER JOIN rolusuario as ru on r.RolId = ru.RolId AND ru.UsuarioId = $UsuarioLiderId Order BY r.Nombre) as rr
        LEFT JOIN rolusuario as ru on rr.RolId = ru.RolId AND ru.UsuarioId = $UsuarioColId Order BY rr.Nombre");
        return ["data" => !empty($Roles) && is_array($Roles) ? $Roles : []];
    }

    public function GetUsuarioWithPlantilla()
    {
        
        return $this->db->GetUsuarioWithPlantilla();
    }

    public function GetPersonaByDocumento($Documento)
    {
        
        return $this->db->GetPersonaByDocumento($Documento);
    }

    public function MarcarSedes($SedeId, $All, $UsuarioId)
    {
        $uh = new UsuarioDAL();
        $uh->DeleteServiciosUsuario($UsuarioId, $SedeId);
        if ($All == 1) {
            $uh->AddAllServiciosToUsuarioBySedeId($UsuarioId, $SedeId);
        }
        return [$SedeId];
    }

    public function MarcarPermisos($ModuloId, $All, $UsuarioId)
    {
        $uh = new UsuarioDAL();
        $uh->DeleteModulosUsuario($UsuarioId, $ModuloId);
        if ($All == 1) {
            $uh->AddAllPermisosToUsuarioByModuloId($UsuarioId, $ModuloId);
        }
        return [$ModuloId];
    }

    public function MarcarPermisosH($ModuloId, $All, $UsuarioId, $UOId)
    {
        $uh = new UsuarioDAL();
        $uh->DeleteModulosUsuario($UsuarioId, $ModuloId);
        if ($All == 1) {
            $uh->AddAllPermisosToUsuarioByModuloIdH($UsuarioId, $ModuloId, $UOId);
        }
        return [$ModuloId];
    }

    public function GetUsuariosCol($LiderPId, $Email)
    {
        $hs = new Auth();
        $uh = new UsuarioDAL();
        $jwt = $this->getBearerToken();
        $result = $hs->DecodeJWT($jwt);
        if (is_array($result) || is_object($result)) {
            return $uh->GetColaboradoresByLiderPId($LiderPId);
        } else {
            $this->LogFile("{$this->GetFHNow('fh')}: $Email: Hubo un error\n\n", "log-auth.txt"); #Guardamos un log
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }
    }

    public function GetPermisoFacturacion($Email)
    {
        $s = new Security();
        $Usuario = $this->db->GetUsuarioByEmail($Email);
        if ($Usuario) {
            if (new DateTime($Usuario->FechaVecimiento) > new DateTime() && $Usuario->Estado == 1) {
                return array(
                    'UserId' => $Usuario->UsuarioId,
                    'NombreUsuario' => $Usuario->NombreUsuario,
                    'NombreCompleto' => $Usuario->NombreCompleto,
                    'Cargo' => $Usuario->Cargo,
                    'Email' => $Usuario->Email,
                    'IsStaff' => $Usuario->IsStaff,
                    'Is_staff' => $Usuario->IsStaff,
                    'IsSistemas' => $Usuario->IsSistemas,
                    'IsPolivalente' => $Usuario->IsPolivalente,
                    'IsBiomedico' => $Usuario->IsBiomedico,
                    'IsAdminSistemas' => $Usuario->IsAdminSistemas,
                    'key' => $s->GenerateToken($Usuario->NombreUsuario, $Usuario->Email, 7, []),
                    'FCield' => $Usuario->FCield,
                    'FPrado' => $Usuario->FPrado,
                    'FTesoreria' => $Usuario->FTesoreria,
                );
            }
        } else {
            return Null;
        }
    }

    public function LoginAuto($UsuarioId)
    {
        $auth = new Auth();
        $data = [];
        try {
            $Usuario = $this->db->LoginAuto($UsuarioId);
            if ($Usuario) {
                $Roles = $this->db->GetRolesByUsuarioId($Usuario->UsuarioId);
                if (new DateTime($Usuario->FechaVecimiento) > new DateTime() && $Usuario->Estado == 1) {
                    // $Jefe = $this->GetUsuarioById(195); // id del jefe
                    $Ip = $this->getRealIP();
                    $data = ["data" => array(
                        'UserId' => $Usuario->UsuarioId,
                        'UsuarioBiomedicoId' => $Usuario->UsuarioBiomedicoId,
                        'NombreUsuario' => $Usuario->NombreUsuario,
                        'NombreCompleto' => $Usuario->NombreCompleto,
                        'Cargo' => $Usuario->Cargo,
                        'Email' => $Usuario->Email,
                        'Url_Foto' => $Usuario->Url_Foto,
                        'Firma' => $Usuario->Firma,
                        'NombreJefe' => $Usuario->NombreCompletoJefe,
                        'CargoJefe' => $Usuario->Cargo,
                        //'FirmaJefe' => $Jefe->Firma,
                        'ResponsableId' => $Usuario->JefeId,
                        'IsStaff' => $Usuario->IsStaff,
                        'Is_staff' => $Usuario->IsStaff,
                        'IsGHUser' => $Usuario->IsGHUser,
                        'IsSistemas' => $Usuario->IsSistemas,
                        'IsPolivalente' => $Usuario->IsPolivalente,
                        'IsBiomedico' => $Usuario->IsBiomedico,
                        'IsAdminSistemas' => $Usuario->IsAdminSistemas,
                        'key' => $auth->CrearToken($Usuario, $Roles),
                        'IsDireccionTecnica' => $Usuario->IsDireccionTecnica,
                        'IsACalidad' => $Usuario->IsACalidad,
                        'IsQFarmaceutico' => $Usuario->IsQFarmaceutico,
                        'IsAFarmacia' => $Usuario->IsAFarmacia,
                        'IP' => $Ip,
                        'FCield' => $Usuario->FCield,
                        'FPrado' => $Usuario->FPrado,
                        'FTesoreria' => $Usuario->FTesoreria,
                        'PersonaId' => $Usuario->PersonaId,
                        'Maquina' => gethostbyaddr($Ip)
                    )];
                } else {
                    $Usuario->Estado = 0;
                    $this->UpdateUsuario($Usuario);
                    $data = ["error" => $Usuario->Estado == 0 ? "Este usuario ha sido desactivado" : "Este Usuario a vencido el " . $Usuario->FechaVecimiento];
                }
            } else {
                $data = ["error" => "Usuario o contraseña no validos."];
            }
        } catch (\Throwable $th) {
            return ["error" => $th->getMessage()];
        }
        return $data;
    }

    public function Login($Nombre, $Contraseña)
    {
        $s = new Security();
        $Usuario = $this->db->Login($Nombre, $Contraseña);
        if ($Usuario) {
            $Roles = $this->db->GetRolesByUsuarioId($Usuario->UsuarioId);
            if (new DateTime($Usuario->FechaVecimiento) > new DateTime() && $Usuario->Estado == 1) {
                //                $Jefe = $this->GetUsuarioById(195); // id del jefe
                $Ip = $this->getRealIP();
                return array(
                    'UserId' => $Usuario->UsuarioId,
                    'UsuarioBiomedicoId' => $Usuario->UsuarioBiomedicoId,
                    'NombreUsuario' => $Usuario->NombreUsuario,
                    'NombreCompleto' => $Usuario->NombreCompleto,
                    'Cargo' => $Usuario->Cargo,
                    'Email' => $Usuario->Email,
                    'Url_Foto' => $Usuario->Url_Foto,
                    'Firma' => $Usuario->Firma,
                    'NombreJefe' => $Usuario->NombreCompletoJefe,
                    'CargoJefe' => $Usuario->Cargo,
                    'ResponsableId' => $Usuario->JefeId,
                    'IsStaff' => $Usuario->IsStaff,
                    'Is_staff' => $Usuario->IsStaff,
                    'IsGHUser' => $Usuario->IsGHUser,
                    'IsSistemas' => $Usuario->IsSistemas,
                    'IsBiomedico' => $Usuario->IsBiomedico,
                    'IsPolivalente' => $Usuario->IsPolivalente,
                    'IsAdminSistemas' => $Usuario->IsAdminSistemas,
                    'key' => $s->GenerateToken($Nombre, $Usuario->Email, 60, $Roles),
                    'IsDireccionTecnica' => $Usuario->IsDireccionTecnica,
                    'IsACalidad' => $Usuario->IsACalidad,
                    'IsQFarmaceutico' => $Usuario->IsQFarmaceutico,
                    'IsAFarmacia' => $Usuario->IsAFarmacia,
                    'IP' => $Ip,
                    'FCield' => $Usuario->FCield,
                    'FPrado' => $Usuario->FPrado,
                    'FTesoreria' => $Usuario->FTesoreria,
                    'PersonaId' => $Usuario->PersonaId,
                    'Maquina' => gethostbyaddr($Ip)
                );
            } else {
                $Usuario->Estado = 0;
                $this->UpdateUsuario($Usuario);
                return $Usuario->Estado == 0 ? "Este usuario ha sido desactivado" : "Este Usuario a vencido el " . $Usuario->FechaVecimiento;
            }
        } else {

            return "Usuario o contraseña no validos.";
        }
    }
    /**
     * esta funcion retornara un objeto siguiendo las especificaciones JSON API SPEC
     * Recibe usuario y contraseña, y genera un token con la libreria firebase JWT
     *
     * @param String $User
     * @param String $Password
     * @return array
     */
    public function LoginIntranet2(String $User, String $Password): array
    {
        $auth = new Auth();
        $data = [];
        try {
            $Usuario = $this->db->Login($User, $Password);
            if ($Usuario) {
                $Roles = $this->db->GetRolesByUsuarioId($Usuario->UsuarioId);
                if (new DateTime($Usuario->FechaVecimiento) > new DateTime() && $Usuario->Estado == 1) {
                    $Ip = $this->getRealIP();
                    $data = ["data" => array(
                        'UserId' => $Usuario->UsuarioId,
                        'UsuarioBiomedicoId' => $Usuario->UsuarioBiomedicoId,
                        'NombreUsuario' => $Usuario->NombreUsuario,
                        'NombreCompleto' => $Usuario->NombreCompleto,
                        'Cargo' => $Usuario->Cargo,
                        'Email' => $Usuario->Email,
                        'Url_Foto' => $Usuario->Url_Foto,
                        'Firma' => $Usuario->Firma,
                        'NombreJefe' => $Usuario->NombreCompletoJefe,
                        'CargoJefe' => $Usuario->Cargo,
                        'ResponsableId' => $Usuario->JefeId,
                        'IsStaff' => $Usuario->IsStaff,
                        'Is_staff' => $Usuario->IsStaff,
                        'IsGHUser' => $Usuario->IsGHUser,
                        'IsSistemas' => $Usuario->IsSistemas,
                        'IsBiomedico' => $Usuario->IsBiomedico,
                        'IsPolivalente' => $Usuario->IsPolivalente,
                        'IsAdminSistemas' => $Usuario->IsAdminSistemas,
                        'key' => $auth->CrearToken($Usuario, $Roles),
                        'IsDireccionTecnica' => $Usuario->IsDireccionTecnica,
                        'IsACalidad' => $Usuario->IsACalidad,
                        'IsQFarmaceutico' => $Usuario->IsQFarmaceutico,
                        'IsAFarmacia' => $Usuario->IsAFarmacia,
                        'IP' => $Ip,
                        'FCield' => $Usuario->FCield,
                        'FPrado' => $Usuario->FPrado,
                        'FTesoreria' => $Usuario->FTesoreria,
                        'PersonaId' => $Usuario->PersonaId,
                        'Maquina' => gethostbyaddr($Ip)
                    )];
                } else {
                    $Usuario->Estado = 0;
                    $this->UpdateUsuario($Usuario);
                    $data = ["error" => $Usuario->Estado == 0 ? "Este usuario ha sido desactivado" : "Este Usuario a vencido el " . $Usuario->FechaVecimiento];
                }
            } else {
                $data = ["error" => "Usuario o contraseña no validos."];
            }
        } catch (\Throwable $th) {
            return ["error" => $th->getMessage()];
        }
        return $data;
    }



    public function ResetPassword($Email)
    {
        
        $s = new Security();
        $Usuario = $this->db->GetUsuarioByEmail($Email);
        if ($Usuario) {
            if (new DateTime($Usuario->FechaVecimiento) > new DateTime() && $Usuario->Estado == 1) {
                $token = $s->GenerateToken($Usuario->NombreUsuario, "Biomedico_123458", 0.045, []); // 1hr APRX
                $Helper = new EmpresaBLL();
                $EmpresaObj = $Helper->GetEmpresa();
                $hs = new sendMail();
                $Url = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Polivalente#/restablecer_contrasena/$Usuario->UsuarioId/$token";
                $Contenido = '<p>Para restablecer tu contraseña te invitamos a hacer click en el siguiente enlace:</p>
                            <br>
                            <div style="center">
                                <a href="' . $Url . '">Restablecer Contraseña</a>
                            </div>
                            <br>
                            <br>
                            <small>Este mensaje vencera en <strong>1 hora</strong> luego de la recepción del mismo.</small>';
                $hs->EnviarEmail_Notificacion($EmpresaObj, "Restablecimiento de Password", $Contenido, $Usuario->Email, $Usuario->NombreCompleto);
                return 1;
            }
        }
        return 0;
    }

    public function UpdatePassUsuario($UsuarioId, $Pass, $Email)
    {
        
        $Usuario = $this->db->GetUsuarioById($UsuarioId);
        if ($Usuario->Email == $Email) {
            $Contrasena = md5($Pass);
            return $this->db->UpdateUsuario($this->MAPTopass($Contrasena), $Usuario->UsuarioId);
        }
        return "Usuario invalido";
    }

    public function UpdatePass($UsuarioId, $Pass, $Pass2)
    {
        
        if ($Pass == $Pass2) {
            $Contrasena = md5($Pass);
            return $this->db->UpdateUsuario($this->MAPTopass($Contrasena), $UsuarioId);
        } else {
            return "las contraseñas no coinciden";
        }
    }

    /**
     * Actualizar los roles por usuario
     *
     * @param RolUsuarioDto $RolUsuario
     * @return void
     */
    public function UpdateRolUsuario(object $RolUsuario) : array
    {
        $Ru = $this->db->GetRolUsuarioByUsuarioId($RolUsuario->UsuarioId, $RolUsuario->RolId);
        if (!empty($Ru) && is_array($Ru)) {
            $this->db->Search("DELETE FROM rolusuario where UsuarioId = {$RolUsuario->UsuarioId} AND RolId = {$RolUsuario->RolId};");
            return ["data" => "Se han actualizado los datos."];
        } else {
            $Id = $this->db->Create("rolusuario", [
                (array) $RolUsuario
            ]);
            if (!empty($Id) && is_array($Id)) {
                return ["data" => "Se han actualizado los datos."];
            } else {
                return ["error" => "Hubo un error."];
            }
        }
    }

    public function ActualizarTokenFB($Token, $UsuarioId)
    {
        
        return $this->db->UpdateUsuario($this->MAPToUToken($Token), $UsuarioId);
    }

    public function UpdateTokenUsuario($UsuarioId, $Token)
    {
        
        return $this->db->UpdateUsuario($this->MAPToToken($Token), $UsuarioId);
    }

    public function UpdateUsuario($Usuario)
    {
        $ph = new PersonaDAL();
        $this->db->UpdateUsuario($this->MAPToArray2($Usuario), $Usuario->UsuarioId);
        $ph->UpdatePersonaUsuario([array(
            'UsuarioIntranetId' => $Usuario->UsuarioId,
            'Firma' => $Usuario->Firma,
        )], $Usuario->PersonaId, $Usuario->UsuarioId);
        return [true];
    }

    public function TraspasarUsuario_ct_persona()
    {
        $ph = new PersonaDAL();
        $Usuarios = $this->db->GetALL2();
        // foreach ($Usuarios as $u) {
        //     $ph->UpdatePersonaUsuario([array(
        //         // 'UsuarioIntranetId' => $u->UsuarioId,
        //         'Firma' => $u->Firma,
        //     )], $u->PersonaId, $u->UsuarioId);
        // }
    }

    public function GetALLCM()
    {
        return $this->db->GetALLCM();
    }

    public function GetALL()
    {
        return $this->db->GetALL();
    }

    public function GetALLFB()
    {
        return $this->db->GetALLFB();
    }

    public function GetPermisos($UserId)
    {
        $db = new PermisoBLL();
        return  $db->GetPermisos($UserId);
    }

    public function GetALLPermisos($UserId)
    {
        $db = new PermisoBLL();
        return  $db->GetALLPermisos($UserId);
    }

    public function GetUsuarioByUsuarioId($UsuarioId)
    {
        return $this->db->GetUsuarioByUsuarioId($UsuarioId);
    }

    public function GetUsuarioByServicioId($ServicioId)
    {
        return $this->db->GetUsuarioByServicioId($ServicioId);
    }

    public function GetUsuarioByEmail($Email)
    {
        #Uso esta linea para validar el usuario de las aplicaciones moviles
        return $this->db->GetUsuarioByEmail($Email);
    }

    public function GetUsuarioByNombre($Nombre)
    {
        return $this->db->GetUsuarioByNombre($Nombre);
    }

    public function GetRolesByUsuario($Email)
    {
        $auth = new Auth();
        $jwt = $this->getBearerToken();
        $data = $auth->DecodeJWT($jwt);
        return ["data" => json_decode($data->data->Roles)];
    }

    public function CreateUsuario($list)
    {
        $ph = new PersonaDAL();
        $usuario = $this->db->GetUsuarioByEmail($list->Email);
        if ($usuario == NULL) {
            $u = $this->db->CreateUsuario($this->MAPToArray($list));
            if (count($u) > 0) {
                $ph->UpdatePersonaUsuario([array(
                    'UsuarioIntranetId' => $u[0],
                )], $list->PersonaId, $u[0]);
                $Helper = new EmpresaBLL();
                $EmpresaObj = $Helper->GetEmpresa();
                $Helper = new sendMail();
                $Helper->EnviarEmail_Bienvenida($EmpresaObj, $list->NombreCompleto, $list->Email, $list->Contrasena, "Bienvenido a INTRANET!");
            }
            return $u;
        } else {
            return "Este usuario ya se encuentra registrado.";
        }
        //        }
    }

    public function CreateUsuarioFromP($list)
    {
        $ph = new PersonaDAL();
        $u = $this->db->CreateUsuario([
            array(
                "NombreUsuario" => $list->Cedula,
                "NombreCompleto" => $list->NombreCompleto,
                "Contrasena" => md5("1234"),
                'FechaVecimiento' => $list->FechaVecimiento,
                'Estado' => 1,
                'CreatedBy' => $list->CreatedBy
            )
        ]);
        if (count($u) > 0) {
            $ph->UpdatePersonaUsuario([array(
                'UsuarioIntranetId' => $u[0],
            )], $list->PersonaId, $u[0]);
        }
        return $u;
    }

    public function isExistInDB($Email)
    {
        $u = $this->GetUsuarioByEmail($Email);
        if ($u != NULL) {
            return $u->UsuarioId;
        } else {
            return 0;
        }
    }

    public function MAPToArray($list)
    {
        $list2 = array();
        array_push($list2, array(
            'NombreUsuario' => $list->Email,
            'NombreCompleto' => $list->NombreCompleto,
            'Email' => $list->Email,
            'Contrasena' => md5($list->Contrasena),
            'FechaVecimiento' => $list->FechaVecimiento,
            'Estado' => 1,
            //            'Firma' => $list->Firma,
            //            'Cargo' => $list->Cargo,
            'Url_Foto' => "/Polivalente/public_html/fotos_perfiles/default-user.png",
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToUToken($Token)
    {
        $list2 = array();
        array_push($list2, array(
            'TokenFB' => $Token,
            'FechaModificacion' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToArray2($list)
    {
        $list2 = array();
        array_push($list2, array(
            #'NombreUsuario' => $list->Email,
            'NombreCompleto' => $list->NombreCompleto,
            #'Email' => $list->Email,
            'FechaVecimiento' => $list->FechaVecimiento,
            'Estado' => $list->Estado,
            //            'Contrasena' => $list->Contrasena,
            //            'Firma' => property_exists($list, "Firma") ? $list->Firma : NULL,
            //            'Cargo' => $list->Cargo,
            //            'Url_Foto' => property_exists($list, "Url_Foto") ? $list->Url_Foto,
            'FechaModificacion' => $this->getDatetimeNow(),
            'ModifiedBy' => property_exists($list, "ModifiedBy") ? $list->ModifiedBy : NULL,
        ));
        return $list2;
    }

    public function MAPTopass($Contrasena)
    {
        $list2 = array();
        array_push($list2, array(
            'Contrasena' => $Contrasena,
            'FechaModificacion' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToToken($Token)
    {
        $list2 = array();
        array_push($list2, array(
            'Token' => $Token,
        ));
        return $list2;
    }

    private function getDatetimeNow()
    {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d H:i:s');
    }

    //Obtiene la IP del cliente
    function getRealIP()
    {
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        } else {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

    /**
     * Get header Authorization
     * */
    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    private function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * Obtener fechas
     *
     * @param string $F Fecha|fh|h
     * @return string
     */
    private function GetFHNow(string $F = "Fecha"): string
    {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        $format = '';
        switch ($F) {
            case 'f':
                $format = 'Y-m-d';
                break;
            case 'fh':
                $format = 'Y-m-d H:i:s';
                break;

            default:
                $format = 'H:i:s';
                break;
        }
        return $datetime->format($format);
    }
    public function LogFile(string $Msg, string $fileName)
    {
        $fp = fopen(__DIR__ . "/$fileName", 'a+');
        fwrite($fp, $Msg);
        fclose($fp);
    }
}

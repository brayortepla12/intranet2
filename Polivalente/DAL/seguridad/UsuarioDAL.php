<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class UsuarioDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct() {
        $this->db->disconnect();
    }

    public function GetPersonaByDocumento($Documento) {
        return $this->db->objectBuilder()->rawQuery("SELECT PersonaId, PrimerNombre, SegundoNombre, PrimerApellido, SegundoApellido, Foto FROM ct_persona where Cedula = '$Documento' and Cedula <> '';");
    }
    
    public function GetColaboradoresByLiderPId($LiderPId) {
        return $this->db->objectBuilder()->rawQuery("select p.PersonaId, 
        concat(p.PrimerNombre, ' ', p.SegundoNombre, ' ', p.PrimerApellido, ' ', p.SegundoApellido) as NombreCompleto, p.Cedula, c.Cargo, 
        u.NombreUsuario, u.UsuarioId, u.Estado, u.Email, u.FechaVecimiento
        from ct_persona as p
        left join usuario as u on p.UsuarioIntranetId = u.UsuarioId
        left join ct_cargo as c on p.CargoId = c.CargoId
        where p.JefeId = $LiderPId and p.Estado = 'Activo';");
    }

    public function DeleteModulosUsuario($UsuarioId, $ModuloId) {
        return $this->db->objectBuilder()->rawQuery("DELETE up FROM usuariopermiso as up 
        STRAIGHT_JOIN permiso as p on p.PermisoId = up.PermisoId
        where p.ModuloId = $ModuloId and up.UsuarioId = $UsuarioId;");
    }

    public function AddAllPermisosToUsuarioByModuloId($UsuarioId, $ModuloId) {
        return $this->db->objectBuilder()->rawQuery("INSERT INTO usuariopermiso (PermisoId, UsuarioId)
        SELECT  p.PermisoId, $UsuarioId
        FROM    permiso as p where p.ModuloId = $ModuloId;");
    }
    
    public function AddAllPermisosToUsuarioByModuloIdH($UsuarioId, $ModuloId, $UOId) {
        return $this->db->objectBuilder()->rawQuery("INSERT INTO usuariopermiso (PermisoId, UsuarioId, IsPH, UOId)
        SELECT  p.PermisoId, $UsuarioId, 1, $UOId
        FROM permiso as p 
        STRAIGHT_JOIN usuariopermiso as up on up.UsuarioId = $UOId 
        where p.ModuloId = $ModuloId and p.PermisoId = up.PermisoId;");
    }
    
    public function GetUsuariosCT() {
        return $this->db->objectBuilder()->rawQuery("SELECT NombreUsuario, UsuarioId FROM usuario;");
    }

    public function DeleteServiciosUsuario($UsuarioId, $SedeId) {
        return $this->db->objectBuilder()->rawQuery("DELETE su FROM serviciousuario as su 
        STRAIGHT_JOIN servicio as ser on su.ServicioId = ser.ServicioId
        where ser.SedeId = $SedeId and su.UsuarioId = $UsuarioId");
    }

    public function AddAllServiciosToUsuarioBySedeId($UsuarioId, $SedeId) {
        return $this->db->objectBuilder()->rawQuery("INSERT INTO serviciousuario (ServicioId, UsuarioId)
        SELECT  ser.ServicioId, $UsuarioId
        FROM    servicio as ser where ser.SedeId = $SedeId;");
    }

    public function GetALLCM() {
        return $this->db->objectBuilder()->rawQuery("SELECT u.NombreCompleto,u.UsuarioId, u.Email,u.IsDireccionTecnica,
        u.IsACalidad, u.IsQFarmaceutico, u.IsAFarmacia
        FROM usuario as u 
        where (u.IsDireccionTecnica = 1 or u.IsACalidad = 1 or u.IsQFarmaceutico = 1 or u.IsAFarmacia = 1) and u.Estado = 1
        order by u.NombreCompleto;");
    }

    public function LoginAuto($UsuarioId) {
        return (object) $this->db->objectBuilder()->rawQueryOne("select u.UsuarioId, u.IsStaff, u.IsSistemas, u.IsPolivalente, u.IsAdminSistemas, u.IsDireccionTecnica, 
            u.IsACalidad, u.IsQFarmaceutico, 
        u.IsAFarmacia, u.IsGHUser, u.IsBiomedico, u.FCield, u.FPrado, u.FTesoreria, u.CanRPN, u.IsLiderGrupoCM,
        u.Email, u.Contrasena, u.FechaVecimiento, u.Estado, u.Firma, u.Cargo, u.Token, u.TokenFB, u.CreatedBy, u.FechaCreacion, 
        u.FechaModificacion, u.ModifiedBy, u.NombreCompleto, u.NombreUsuario, concat(p.PrimerNombre, ' ', p.PrimerApellido) as NombreCompleto, 
        concat(p.PrimerNombre, ' ', p.PrimerApellido) as NombreUsuario, p.PersonaId, p.Foto as Url_Foto, 
        concat(jp.PrimerNombre, ' ', jp.SegundoApellido, ' ', jp.PrimerApellido, ' ', jp.SegundoApellido) as NombreCompletoJefe,
        cj.Cargo as CargoJefe, jp.PersonaId as JefeId, p.UsuarioBiomedicoId 
        from usuario as u 
        left join ct_persona as p on u.UsuarioId = p.UsuarioIntranetId
        left join ct_persona as jp on p.JefeId = jp.PersonaId
        left join ct_cargo as c on p.CargoId = c.CargoId
        left join ct_cargo as cj on jp.CargoId = cj.CargoId
        where u.UsuarioId = $UsuarioId and u.Estado = 1");
    }

    public function Login($Nombre, $Contraseña) {
        return $this->db->objectBuilder()->rawQueryOne("select u.UsuarioId, u.IsStaff, u.IsSistemas, u.IsPolivalente, u.IsAdminSistemas, u.IsDireccionTecnica, 
            u.IsACalidad, u.IsQFarmaceutico, 
        u.IsAFarmacia, u.IsGHUser, u.IsBiomedico, u.FCield, u.FPrado, u.FTesoreria, u.CanRPN, u.IsLiderGrupoCM,
        u.Email, u.Contrasena, u.FechaVecimiento, u.Estado, p.Firma, c.Cargo, u.Token, u.TokenFB, u.CreatedBy, u.FechaCreacion, 
        u.FechaModificacion, u.ModifiedBy, u.NombreCompleto, u.NombreUsuario, concat(p.PrimerNombre, ' ', p.PrimerApellido) as NombreCompleto, 
        concat(p.PrimerNombre, ' ', p.PrimerApellido) as NombreUsuario, p.PersonaId, p.Foto as Url_Foto, 
        concat(jp.PrimerNombre, ' ', jp.SegundoApellido, ' ', jp.PrimerApellido, ' ', jp.SegundoApellido) as NombreCompletoJefe,
        cj.Cargo as CargoJefe, jp.PersonaId as JefeId, p.UsuarioBiomedicoId
        from usuario as u 
        left join ct_persona as p on u.UsuarioId = p.UsuarioIntranetId
        left join ct_persona as jp on p.JefeId = jp.PersonaId
        left join ct_cargo as c on p.CargoId = c.CargoId
        left join ct_cargo as cj on jp.CargoId = cj.CargoId
        where u.NombreUsuario = '$Nombre' and u.Estado = 1 and u.Contrasena = '" . md5($Contraseña) . "'");
    }

    public function GetRolesByUsuarioId($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.RolId, r.Nombre FROM rol as r 
        STRAIGHT_JOIN rolusuario as ru on r.RolId = ru.RolId
        WHERE ru.UsuarioId = $UsuarioId;");
    }

    public function GetRoles($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.RolId, r.Nombre, IF(ru.Id, true, false) AS IsSelected 
        FROM rol as r 
        LEFT JOIN rolusuario as ru on r.RolId = ru.RolId AND ru.UsuarioId = $UsuarioId Order BY r.Nombre");
    }

    public function GetRolUsuarioByUsuarioId(string $UsuarioId, string $RolId) : array
    {
        return $this->db->objectBuilder()->query("SELECT Id FROM rolusuario where UsuarioId = $UsuarioId AND RolId = $RolId;");
    }

    public function GetUsuarioById($UserId) {
        return $this->db->objectBuilder()->rawQueryOne("SELECT u.*, p.PersonaId, p.Firma, CONCAT(p.PrimerNombre,
        ' ',
        p.PrimerApellido) as Nombres, c.Cargo
        FROM usuario as u 
        STRAIGHT_JOIN ct_persona as p on u.UsuarioId = p.UsuarioIntranetId
        left join ct_cargo as c on c.CargoId = p.CargoId
        where u.UsuarioId = $UserId");
    }

    public function GetUsuarioByEmail($Email) {
        return $this->db->objectBuilder()->rawQueryOne("SELECT u.*, p.PersonaId, p.Firma 
        FROM usuario as u 
        left join ct_persona as p on u.UsuarioId = p.UsuarioIntranetId and p.Estado = 'Activo'
        where u.Email = '$Email' and u.Estado = 1;");
    }

    public function GetUsuarioByNombre($Nombre) {
        return $this->db->objectBuilder()->rawQueryOne("SELECT u.*, p.PersonaId, p.Firma 
        FROM usuario as u 
        left join ct_persona as p on u.UsuarioId = p.UsuarioIntranetId and p.Estado = 'Activo'
        where u.NombreCompleto = '$Nombre' and u.Estado = 1;");
    }

    public function GetUsuarioByUsuarioId($UsuarioId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT u.UsuarioId, p.PersonaId, u.Email, c.Cargo, u.FechaVecimiento, p.Firma, u.Estado 
        FROM usuario as u 
        left join ct_persona as p on u.UsuarioId = p.UsuarioIntranetId
        left join ct_cargo as c on c.CargoId = p.CargoId
        where u.UsuarioId = $UsuarioId;");
    }

    public function GetUsuarioByServicioId($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT u.UsuarioId, p.PersonaId, 
        CONCAT(p.PrimerNombre, ' ', p.SegundoNombre, ' ', p.PrimerApellido, ' ', p.SegundoApellido) as NombreCompleto, 
        u.Email,c.Cargo,u.IsStaff 
        FROM serviciousuario as su 
        STRAIGHT_JOIN servicio as s on su.ServicioId = s.ServicioId
        STRAIGHT_JOIN usuario as u on su.UsuarioId = u.UsuarioId
        STRAIGHT_JOIN ct_persona as p on p.UsuarioIntranetId = u.UsuarioId and p.Estado = 'Activo'
        left join ct_cargo as c on p.CargoId = c.CargoId
        where su.ServicioId = '$ServicioId';");
    }

    public function GetALL() {
        return $this->db->objectBuilder()->rawQuery("SELECT CONCAT(p.PrimerNombre, ' ', p.SegundoNombre, ' ', p.PrimerApellido, ' ', p.SegundoApellido) as NombreCompleto,
            u.UsuarioId,  u.Estado,  c.Cargo,  
        u.FechaVecimiento, u.Email, u.TokenFB, u.FCield, u.FPrado, p.PersonaId
        FROM usuario as u 
        left join ct_persona as p on u.UsuarioId = p.UsuarioIntranetId
        left join ct_cargo as c on c.CargoId = p.CargoId
        order by NombreCompleto;");
    }

    public function GetALL2() {
        return $this->db->objectBuilder()->rawQuery("SELECT u.*, p.PersonaId FROM usuario as u 
        STRAIGHT_JOIN ct_persona as p on u.UsuarioId = p.UsuarioIntranetId;");
    }

    public function GetALLFB() {
        return $this->db->objectBuilder()->rawQuery("SELECT u.NombreCompleto,u.UsuarioId,  u.Estado,  u.Cargo,  
        u.FechaVecimiento, u.Email, u.TokenFB, u.FCield, u.FPrado
        FROM usuario as u where (u.FCield = 1 or u.FPrado = 1) and u.CanRPN = 1;");
    }

    public function GetUsuarioWithPlantilla() {
        return $this->db->objectBuilder()->rawQuery("SELECT r.UsuarioId, CONCAT(p.PrimerNombre, ' ', p.PrimerApellido, ' ', p.SegundoApellido) as NombreCompleto FROM almacen_relacioncosto as r
        STRAIGHT_JOIN usuario as u on u.UsuarioId = r.UsuarioId
        STRAIGHT_JOIN ct_persona as p on p.UsuarioIntranetId = u.UsuarioId
        where  r.Estado = 'Activo' group by r.UsuarioId order by u.NombreCompleto;");
    }

    public function CreateUsuario($list) {
        $ids = $this->db->insertMulti("Usuario", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateUsuario($list, $id) {
        $this->db->where('UsuarioId', $id);
        if ($this->db->update('Usuario', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    /**
     * Undocumented function
     *
     * @param string $TableName
     * @param array $data
     * @return array|string
     */
    public function Create(string $TableName, array $data) : ?array
    {
        $ids = $this->db->insertMulti($TableName,  $data);
        if (!$ids) {
            $this->LogFile("{$this->GetFHNow('fh')}: insert failed {$this->db->getLastError()}\n\n", "error-log-insert.txt"); #Guardamos un log
            return NULL;
        }
        return $ids;
    }

    public function Search(string $query): ?array
    {
        try {
            return $this->db->objectBuilder()->query($query);
        } catch (Exception $e) {
            $this->LogFile("{$this->GetFHNow('fh')}: $query \n {$e->getMessage()}\n\n", "error-log-search.txt"); #Guardamos un log
        }
        return [];
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

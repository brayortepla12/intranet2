<?php
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
/**
 * Description of TelDAL
 *
 * @author DESARROLLO2
 */
class TelDAL {
    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct() {
        $this->db->disconnect();
    }

    public function GetEntregaById($EntregaId) {
        $sql = "SELECT * FROM tel_entrega WHERE EntregaId = $EntregaId;";
        return $this->db->objectBuilder()->rawQuery($sql);
    }

    public function GetSolicitudesByPersonaId($PersonaId) {
        $sql = "SELECT s.SolicitudId, s.Fecha, s.Usuario, s.Estado, t.TelefonoId, t.Telefono 
        FROM tel_solicitud as s 
        inner join tel_telefonos as t on s.TelefonoId = t.TelefonoId
        where s.RSolicitaId = $PersonaId and  (select e.Estado from tel_entrega as e WHERE s.SolicitudId = e.SolicitudId order by EntregaId DESC limit 1) = 'Activo'";
        return $this->db->objectBuilder()->rawQuery($sql);
    }

    public function ValidarSolicitud($Fecha, $UsuarioId, $TelefonoId) {
        $sql = "SELECT SolicitudId, DATE_ADD(Fecha, INTERVAL 1 year) > '$Fecha' as VFecha from tel_solicitud 
        WHERE USolicitaId = $UsuarioId and TelefonoId = $TelefonoId";
        return $this->db->objectBuilder()->rawQuery($sql);
    }

    public function GetHTID($TelefonoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT e.TelefonoId, e.EntregaId, e.Fecha, e.Marca, e.Operador, 
        e.Color, e.IMEI1, e.IMEI2, e.Modelo 
        FROM polivalente.tel_entrega as e where e.TelefonoId = $TelefonoId;");
    }

    public function GetSolicitudById($SolicitudId) {
        return $this->db->objectBuilder()->rawQuery("SELECT s.*, t.Responsable, t.Telefono, c.Cargo, e.Color,
        e.IMEI1, e.IMEI2, e.Marca, e.Modelo, e.Solicita, e.CargoSolicita , e.Operador 
        FROM tel_solicitud as s 
        INNER JOIN tel_telefonos as t on s.TelefonoId = t.TelefonoId
        INNER JOIN ct_persona as p on p.PersonaId = s.RSolicitaId
        LEFT JOIN ct_cargo as c on c.CargoId = p.CargoId
        LEFT JOIN tel_entrega as e on e.SolicitudId = s.SolicitudId
        where s.SolicitudId= $SolicitudId;");
    }

    public function GetUsuarioBySolicitudId($SolicitudId) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.PersonaId, p.Firma, p.PrimerNombre, s.SolicitudId, p.Email
        from tel_solicitud as s
        inner join ct_persona as p on s.RSolicitaId = p.PersonaId
        where s.SolicitudId = $SolicitudId;");
    }

    public function GetDatosPersonaEntrega($PersonaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT concat(p.PrimerNombre, ' ', p.PrimerApellido) as Nombres, c.Cargo, p.Firma
        FROM ct_persona as p 
        INNER JOIN ct_cargo as c on p.CargoId = c.CargoId
        WHERE p.PersonaId = $PersonaId;");
    }

    public function GetEntregaBySolicitudId($SolicitudId) {
        return $this->db->objectBuilder()->rawQuery("SELECT t.* FROM tel_entrega as t where t.SolicitudId = $SolicitudId;");
    }

    public function GetTelefonos() {
        return $this->db->objectBuilder()->rawQuery("SELECT t.*, CONCAT(p.PrimerNombre, ' ', p.SegundoNombre, ' ', p.PrimerApellido, ' ', p.SegundoApellido) AS Lider FROM tel_telefonos as t 
        LEFT JOIN ct_persona as p on t.LiderTelefonoId = p.PersonaId
        ORDER BY t.Responsable, t.Telefono;");
    }

    public function GetTelefonosByPersonaId($PersonaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT t.* FROM tel_telefonos as t WHERE LiderTelefonoId = $PersonaId ORDER BY t.Responsable, t.Telefono;");
    }

    public function GetInventario() {
        return $this->db->objectBuilder()->rawQuery("SELECT inv.* FROM tel_inventario as inv ORDER BY inv.InventarioId DESC;");
    }
    
    public function GetTelByUsuarioId(string $Dia, string $Mes, string $Year, $UsuarioId) {
        $sql = "SELECT s.SolicitudId, s.Fecha, s.Usuario, s.Estado, t.TelefonoId, t.Telefono, polivalente.GetPosEsperaTel(s.SolicitudId) as PosEsperaTel
        FROM tel_solicitud as s 
        inner join tel_telefonos as t on s.TelefonoId = t.TelefonoId
        WHERE s.USolicitaId = $UsuarioId
        AND YEAR(s.Fecha) = $Year ORDER BY s.SolicitudId DESC;";
        return $this->db->objectBuilder()->rawQuery($sql);
    }

    public function GetSolicitudes(string $Dia, string $Mes, string $Year) {
        $sql = "SELECT s.SolicitudId, s.Fecha, s.Usuario, s.Estado, t.TelefonoId, t.Telefono, e.EntregaId 
        FROM tel_solicitud as s 
        inner join tel_telefonos as t on s.TelefonoId = t.TelefonoId
        left join tel_entrega as e on e.SolicitudId = s.SolicitudId
        WHERE YEAR(s.Fecha) = $Year AND ((month(s.Fecha) = '$Mes' and (day(s.Fecha) = '$Dia' or '$Dia' = 'TODOS')) or '$Mes' = 'TODOS') ORDER BY s.SolicitudId DESC;";
        return $this->db->objectBuilder()->rawQuery($sql);
    }

    public function Search(string $query): array
    {
        try {
            return $this->db->objectBuilder()->query($query);
        } catch (Exception $e) {
            $this->LogFile("{$this->GetFHNow('fh')}: $query \n {$e->getMessage()}\n\n", "log-search.txt"); #Guardamos un log
        }
        return [];
    }
    public function UpdateBulk($TableName, $columns, $data) {
        return $this->db->bulkUpdate($TableName, $columns, $data);
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
            $this->LogFile("{$this->GetFHNow('fh')}: insert failed {$this->db->getLastError()}\n\n", "log-insert.txt"); #Guardamos un log
            return NULL;
        }
        return $ids;
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

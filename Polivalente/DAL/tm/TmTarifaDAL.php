<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class TmTarifaDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateRondaVerificacion($list) {
        $ids = $this->db->insertMulti("cm_rondaverificacion", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateRondaVerificacion($list, $id) {
        $this->db->where('RondaVerificacionId', $id);
        if ($this->db->update('cm_rondaverificacion', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetTmTarifas() {
        return $this->db->objectBuilder()->rawQuery("SELECT t.Nombre, t.TarifaId, t.OrigenId, c.Ciudad as Origen, t.DestinoId, cc.Ciudad as Destino, 
        t.OtroId, t.Precio, t.PrecioOtro FROM tm_tarifa as t 
        STRAIGHT_JOIN tm_ciudad as c on t.OrigenId = c.CiudadId
        STRAIGHT_JOIN tm_ciudad as cc on t.DestinoId = cc.CiudadId
        order by c.Ciudad;");
    }
    
    public function GetTarifaBYMaterna($Documento) {
        return $this->db->objectBuilder()->rawQuery("SELECT t.TarifaId,t.Nombre, t.Precio, 0 as PrecioAcompanante, 0 as PrecioMaterna FROM tm_tarifa as t
        STRAIGHT_JOIN tm_materna as m on ((m.MunicipioId = t.OrigenId or m.MunicipioId = t.DestinoId) and m.MunicipioId <> 456)  or (t.OrigenId = 456 and t.DestinoId = 456 and m.MunicipioId = 456)
        where m.Documento = '$Documento'
        UNION ALL 
        SELECT a.TarifaId,a.Nombre, a.Precio,  0 as PrecioAcompanante, 0 as PrecioMaterna FROM tm_materna as m  
        STRAIGHT_JOIN tm_tarifa as t on ((m.MunicipioId = t.OrigenId or m.MunicipioId = t.DestinoId))
        STRAIGHT_JOIN tm_tarifa as a on t.OtroId = a.TarifaId
        where  m.Documento = '$Documento';");#STRAIGHT_JOIN tm_tarifa as t on ((m.MunicipioId = t.OrigenId or m.MunicipioId = t.DestinoId) and m.MunicipioId <> 456)  or (t.OrigenId = 456 and t.DestinoId = 456 and m.MunicipioId = 456)
    }
    
    public function GetTarifaByOrigen($OrigenId) {
        return $this->db->objectBuilder()->rawQuery("SELECT t.TarifaId,t.Nombre, t.Precio, 0 as PrecioAcompanante, 0 as PrecioMaterna FROM tm_tarifa as t
        where ((t.OrigenId = $OrigenId or t.DestinoId = $OrigenId) and $OrigenId <> 456) or (t.OrigenId = $OrigenId and t.DestinoId = $OrigenId) 
        UNION ALL 
        SELECT a.TarifaId,a.Nombre, a.Precio,  0 as PrecioAcompanante, 0 as PrecioMaterna FROM tm_tarifa as t 
        STRAIGHT_JOIN tm_tarifa as a on t.OtroId = a.TarifaId
        where ((t.OrigenId = $OrigenId or t.DestinoId = $OrigenId)  and $OrigenId <> 456) or (t.OrigenId = $OrigenId and t.DestinoId = $OrigenId);");
    }
    
    public function GetTmTarifaByTarifaId($TarifaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT t.TarifaId,t.Nombre, t.Precio FROM tm_tarifa as t 
        where t.TarifaId = $TarifaId
        UNION
        SELECT t.TarifaId,t.Nombre, t.Precio FROM tm_tarifa as t 
        STRAIGHT_JOIN tm_tarifa as a on t.TarifaId = a.OtroId
        where a.TarifaId = $TarifaId;");
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

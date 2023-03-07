<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class ViaticoDAL
{

    private $db;

    public function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
        $this->db->disconnect();
    }

    public function GetDepartamentos(): array
    {
        return $this->db->objectBuilder()->query("SELECT * FROM tm_departamento where Departamento <> 'Ninguno' ORDER BY Departamento;");
    }

    public function GetMunicipioByDptId(string $DptId): array
    {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM tm_ciudad where DepartamentoId = ? ORDER BY Ciudad;", [$DptId]);
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

<?php

include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class LegalizacionDAL
{
  private $db;
  public function __construct()
  {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->safeLoad();
    $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
    $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
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
  /**
   * Bulk update date to table.
   * It used "ON DUPLICATE KEY UPDATE" syntax
   * Which means table must need a primary/unique index.
   * Otherwise it won't work.
   *
   * The first element of array $columns should
   * contain the primary/unque index.
   *
   * Eg: [5, 'Harry']
   * Where 5 belongs to 'id' column and it is
   * the primary/unque index.
   * 
   * @param  string $table
   * @param  array  $columns
   * @param  array  $values
   * 
   * @return int|false
   */
  public function UpdateBulk($TableName, $columns, $data)
  {
    return $this->db->bulkUpdate($TableName, $columns, $data);
  }
  /**
   * Esta funcion recibe una variable $cierre y una $continua para manejar la transaccion
   * si cierre es falso deja abierta la transaccion, 
   * si $continua es False inicia la transaccion, si es True continua la operacion
   * en caso de cualquier error esta funcion tiene el mecanismo para hacer un rollback de los datos
   *
   * @param string $TableName
   * @param array $data
   * @param boolean $cierre
   * @param boolean $continua
   * @return int[]|null
   */
  public function Create(string $TableName, array $Data, bool $Cierre, bool $Continua): ?array
  {
    if (!$Continua && !$Cierre) {
      $this->db->startTransaction();
    }
    $ids = [];
    try {
      $ids = $this->db->insertMulti($TableName,  $Data);
      if (!$Continua && $Cierre) {
        $this->db->commit();
      }
    } catch (Exception $e) {
      $this->db->rollback();
      $this->LogFile("{$this->GetFHNow('fh')}: {$e->getMessage()}\n\n", "log-error.txt"); #Guardamos un log
    }
    if (!$ids) {
      $this->db->rollback();
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

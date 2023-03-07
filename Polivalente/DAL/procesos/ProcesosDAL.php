<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class ProcesosDAL
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

  public function getAll()
  {
    return $this->db->jsonBuilder()->query("SELECT ProcesoId,ProtocoloId, Nombre, SolicitanteId, Estado, OrdenEnCurso, CreatedAt, CreatedBy "
      . "FROM pc_proceso order by ProcesoId;");
  }

  public function getAllByUserId($UserId)
  {
    return $this->db->jsonBuilder()->rawQuery("SELECT pp.ProcesoId, pp.ProtocoloId, pp.SolicitanteId, pp.Nombre, pp.CreatedAt, 
            p.Nombre as Protocolo, pp.Estado, se.Nombre as Sede, ser.Nombre as Servicio FROM pc_proceso as pp
        STRAIGHT_JOIN pc_protocolo as p on pp.ProtocoloId = p.ProtocoloId
        STRAIGHT_JOIN sede as se on pp.SedeId = se.SedeId
        STRAIGHT_JOIN servicio as ser on pp.ServicioId = ser.ServicioId
        where pp.SolicitanteId = $UserId order by pp.ProcesoId desc;");
  }

  public function GetNotasBySolMantId($SolicitudId)
  {
    return $this->db->objectBuilder()->rawQuery("SELECT n.NotaId, n.Fecha, n.Nombres, c.Cargo, n.Descripcion 
        FROM pc_notas as n
        STRAIGHT_JOIN ct_persona as p on n.PersonaId = p.PersonaId
        left join ct_cargo as c on p.CargoId = c.CargoId
        WHERE n.SolicitudId = $SolicitudId;");
  }

  public function GetNotasByProcesoId($ProcesoId)
  {
    return $this->db->objectBuilder()->rawQuery("SELECT n.NotaId, n.Fecha, n.Nombres, c.Cargo, n.Descripcion 
        FROM pc_notas as n
        STRAIGHT_JOIN ct_persona as p on n.PersonaId = p.PersonaId
        left join ct_cargo as c on p.CargoId = c.CargoId
        WHERE n.ProcesoId = $ProcesoId;");
  }

  public function GetAllByVerificadorId($VerificadorId, $Estado)
  {
    return $this->db->objectBuilder()->rawQuery("SELECT pp.ProcesoId, se.Nombre as Sede, 
        ser.Nombre as Servicio, pp.ProtocoloId, pp.SolicitanteId,pp.OrdenEnCurso, 
        pp.Nombre, pp.CreatedAt, pp.Estado, p.Nombre as Protocolo, 
        PC_VerificarSiTurno(pp.OrdenEnCurso, $VerificadorId, pp.ProtocoloId) as IsYouTurn,
        PC_GetEventoSolByProcesoId(pp.ProcesoId) as ReporteId,  
        PC_GetNombreVerificadorActual(pp.ProtocoloId, pp.OrdenEnCurso) as NombreVerificador
        FROM pc_proceso as pp
        STRAIGHT_JOIN pc_protocolo as p on pp.ProtocoloId = p.ProtocoloId
        STRAIGHT_JOIN pc_flujotrabajo as fp on p.ProtocoloId = fp.ProtocoloId AND fp.Estado = 'Activo'
        STRAIGHT_JOIN pc_verificador as v on fp.FlujoTrabajoId = v.FlujoTrabajoId AND v.Estado = 'Activo'
        STRAIGHT_JOIN sede as se on pp.SedeId = se.SedeId
        STRAIGHT_JOIN servicio as ser on pp.ServicioId = ser.ServicioId
        where v.UsuarioId = $VerificadorId and pp.Estado = '$Estado' and pp.CreatedAt >= '2020-11-15'
        group by pp.ProcesoId
        order by pp.ProcesoId desc;");
  }

  public function GetProcesosForAuditoria($Estado)
  {
    return $this->db->objectBuilder()->rawQuery("SELECT pp.ProcesoId, se.Nombre as Sede, 
        ser.Nombre as Servicio, pp.ProtocoloId, pp.SolicitanteId,pp.OrdenEnCurso,
        pp.Nombre, pp.CreatedAt, pp.Estado, p.Nombre as Protocolo,   
        PC_GetEventoSolByProcesoId(pp.ProcesoId) as ReporteId, 
        PC_GetNombreVerificadorActual(pp.ProtocoloId, pp.OrdenEnCurso) as NombreVerificador
        FROM pc_proceso as pp
        STRAIGHT_JOIN pc_protocolo as p on pp.ProtocoloId = p.ProtocoloId
        STRAIGHT_JOIN pc_flujotrabajo as fp on p.ProtocoloId = fp.ProtocoloId
        STRAIGHT_JOIN pc_verificador as v on fp.FlujoTrabajoId = v.FlujoTrabajoId
        STRAIGHT_JOIN sede as se on pp.SedeId = se.SedeId
        STRAIGHT_JOIN servicio as ser on pp.ServicioId = ser.ServicioId
        where pp.Estado = '$Estado' and  pp.CreatedAt >= '2020-11-15'  
        group by pp.ProcesoId
        order by pp.ProcesoId desc;");
  }

  public function GetPrefijoByReporteId(string $ReporteId): array
  {
    return $this->db->objectBuilder()->rawQuery("SELECT PC_GetTipoTableByReporteId($ReporteId) AS Prefijo;");
  }

  public function GetPendientesPorAprobar()
  {
    return $this->db->objectBuilder()->rawQuery("SELECT DISTINCT pp.ProcesoId, u.NombreCompleto, u.Email, u.UsuarioId, se.Nombre as Sede, 
        ser.Nombre as Servicio, pp.ProtocoloId, pp.SolicitanteId,pp.OrdenEnCurso, 
        pp.Nombre, pp.CreatedAt, pp.Estado, p.Nombre as Protocolo, pp.CreatedBy
        FROM pc_proceso as pp
        STRAIGHT_JOIN pc_protocolo as p on pp.ProtocoloId = p.ProtocoloId
        STRAIGHT_JOIN pc_flujotrabajo as fp on p.ProtocoloId = fp.ProtocoloId
        STRAIGHT_JOIN pc_verificador as v on fp.FlujoTrabajoId = v.FlujoTrabajoId
        STRAIGHT_JOIN sede as se on pp.SedeId = se.SedeId
        STRAIGHT_JOIN servicio as ser on pp.ServicioId = ser.ServicioId
        STRAIGHT_JOIN usuario as u on v.UsuarioId = u.UsuarioId
        where pp.OrdenEnCurso = fp.Orden and pp.Estado = 'Activo'
        and pp.CreatedAt < subdate(now(), interval 1 day)
        order by pp.ProcesoId desc;");
  }

  public function GetFlujoTrabajoById($FlujoTrabajoId)
  {
    $this->db->query("SET @Row_numberFT = -1;");
    return $this->db->objectBuilder()->rawQuery("
        SELECT Tabla.* FROM (
                SELECT T.*, @Row_numberFT := @Row_numberFT + 1 as Orden FROM (
                SELECT _ft.FlujoTrabajoId, _ft.ProtocoloId, _ft.Estado, _v.UsuarioId FROM ct_persona as _per
                STRAIGHT_JOIN pc_verificador as _v on _per.UsuarioIntranetId = _v.UsuarioId
                STRAIGHT_JOIN pc_flujotrabajo as _ft  on _ft.FlujoTrabajoId = _v.FlujoTrabajoId 
                WHERE _ft.ProtocoloId = PC_GetProtocoloIdByFlujoTrabajoId($FlujoTrabajoId) AND _ft.Estado = 'Activo' AND _v.Estado = 'Activo' order by _ft.Orden ) as T ) AS Tabla
                WHERE Tabla.FlujoTrabajoId = $FlujoTrabajoId");
  }

  public function GetFlujoTrabajoByOrden($Orden, $ProtocoloId)
  {
    $this->db->query("SET @Row_numberFT = -1;");
    return $this->db->objectBuilder()->rawQuery("SELECT Tabla.* FROM (
        SELECT T.*, @Row_numberFT := @Row_numberFT + 1 as Orden FROM (
        SELECT _ft.FlujoTrabajoId, _ft.ProtocoloId, _ft.Estado, _v.UsuarioId FROM ct_persona as _per
        STRAIGHT_JOIN pc_verificador as _v on _per.UsuarioIntranetId = _v.UsuarioId
        STRAIGHT_JOIN pc_flujotrabajo as _ft  on _ft.FlujoTrabajoId = _v.FlujoTrabajoId 
        WHERE _ft.ProtocoloId = $ProtocoloId AND _ft.Estado = 'Activo' AND _v.Estado = 'Activo' order by _ft.Orden ) as T ) AS Tabla
        WHERE Tabla.Orden = $Orden");
  }

  public function GetAllFlujoTrabajo($ProcesoId)
  {
    return $this->db->objectBuilder()->rawQuery("CALL `polivalente`.`PC_GetFlujoTrabajoByProcesoId`($ProcesoId);");
  }

  public function GetSeguimientoByFlujoTrabajoId($FlujoTrabajoId, $ProcesoId)
  {
    return $this->db->objectBuilder()->rawQuery("SELECT fs.* FROM pc_seguimiento as fs
        where  fs.FlujoTrabajoId = $FlujoTrabajoId and ProcesoId = $ProcesoId;");
  }

  public function GetSeguimientoByUsuarioId($UsuarioId, $FlujoTrabajoId, $OrdenEnCurso, $ProcesoId)
  {
    return $this->db->objectBuilder()->rawQuery("SELECT fs.* FROM pc_seguimiento as fs
        STRAIGHT_JOIN pc_flujotrabajo as ft on fs.FlujoTrabajoId = fs.FlujoTrabajoId
        where  fs.VerificadorId = $UsuarioId  and fs.FlujoTrabajoId = $FlujoTrabajoId and fs.ProcesoId = $ProcesoId and ft.Orden = $OrdenEnCurso and (ft.Estado = 'Activo' or ft.Estado = 'Devolver');");
  }

  public function GetOrdenByVerificadorId($VerificadorId)
  {
    return $this->db->objectBuilder()->rawQuery("SELECT ft.Orden, ft.FlujoTrabajoId FROM pc_verificador as v
        STRAIGHT_JOIN pc_flujotrabajo as ft on ft.FlujoTrabajoId = v.FlujoTrabajoId
        where v.VerificadorId = $VerificadorId;");
  }

  public function GetOrdenByVerificadorId2($VerificadorId, $ProtocoloId)
  {
    $this->db->query("SET @Row_numberFT = -1;");
    return $this->db->objectBuilder()->rawQuery("SELECT Tabla.* FROM (
        SELECT T.FlujoTrabajoId, T.UsuarioId, @Row_numberFT := @Row_numberFT + 1 as Orden FROM (
        SELECT _ft.FlujoTrabajoId, _v.UsuarioId FROM ct_persona as _per
        STRAIGHT_JOIN pc_verificador as _v on _per.UsuarioIntranetId = _v.UsuarioId
        STRAIGHT_JOIN pc_flujotrabajo as _ft  on _ft.FlujoTrabajoId = _v.FlujoTrabajoId 
        WHERE _ft.ProtocoloId = $ProtocoloId AND _ft.Estado = 'Activo' AND _v.Estado = 'Activo' order by _ft.Orden ) as T ) AS Tabla WHERE Tabla.UsuarioId = $VerificadorId");
  }

  public function getAllByNombre($Nombre)
  {
    return $this->db->objectBuilder()->rawQuery("SELECT * from PC_Proceso as c 
                where c.Nombre = '$Nombre'");
  }

  public function CreateProcesos($list)
  {
    $ids = $this->db->insertMulti("PC_Proceso", $list);
    if (!$ids) {
      return 'insert failed: ' . $this->db->getLastError();
    }
    return $ids;
  }

  public function CreateNota($list)
  {
    $ids = $this->db->insertMulti("pc_notas", $list);
    if (!$ids) {
      return 'insert failed: ' . $this->db->getLastError();
    }
    return $ids;
  }

  public function CreateSeguimiento($list)
  {
    $ids = $this->db->insertMulti("PC_Seguimiento", $list);
    if (!$ids) {
      echo 'insert failed: ' . $this->db->getLastError();
    }
    return $ids;
  }

  public function SaveFirma($list)
  {
    $ids = $this->db->insertMulti("pc_firmas", $list);
    if (!$ids) {
      echo 'insert failed: ' . $this->db->getLastError();
    }
    return $ids;
  }

  public function CreateProcesosUsuario($list)
  {
    $ids = $this->db->insertMulti("PC_Proceso", $list);
    if (!$ids) {
      echo 'insert failed: ' . $this->db->getLastError();
    }
    return $ids;
  }

  public function GetProcesos($Nombre)
  {
    $this->db->where("Nombre", $Nombre);
    return $this->db->objectBuilder()->get("PC_Proceso");
  }

  public function GetProcesosByProcesosId($ProcesosId)
  {
    return $this->db->objectBuilder()->query("SELECT p.*, se.Nombre as Sede, ser.Nombre as Servicio, protocolo.Nombre as Protocolo FROM pc_proceso as p
        STRAIGHT_JOIN pc_protocolo as protocolo on p.ProtocoloId = protocolo.ProtocoloId
        STRAIGHT_JOIN Sede as se on p.SedeId = se.SedeId
        STRAIGHT_JOIN Servicio as ser on p.ServicioId = ser.ServicioId
        where  p.ProcesoId = $ProcesosId")[0];
  }

  public function GetFirmasByProcesosId($ProcesoId)
  {
    return $this->db->objectBuilder()->query("CALL PC_getFirmasByProcesoId($ProcesoId);");
  }

  public function GetUsuariosANotificar(string $ProcesoId, string $Prefijo)
  {
    return $this->db->objectBuilder()->query("CALL PC_GetUsuario_{$Prefijo}_ToNotificarByProcesoId($ProcesoId);");
  }

  public function UpdateProcesos($list, $id)
  {
    $this->db->where('ProcesoId', $id);
    if ($this->db->update('PC_Proceso', $list[0])) {
      return $list[0];
    } else {
      return 'update failed: ' . $this->db->getLastError();
    }
  }

  public function UpdateEventoSolicitud($list, $id, $Prefijo)
  {
    $this->db->where('EventoSolicitudId', $id);
    if ($this->db->update("{$Prefijo}_eventosolicitud", $list[0])) {
      return $list[0];
    } else {
      return 'update failed: ' . $this->db->getLastError();
    }
  }

  public function CreateEventoSolicitud($list, $Prefijo)
  {
    $ids = $this->db->insertMulti("{$Prefijo}_eventosolicitud", $list);
    if (!$ids) {
      return 'insert failed: ' . $this->db->getLastError();
    }
    return $ids;
  }

  public function GetPersonaById(string $PersonaId): array
  {
    return $this->db->objectBuilder()->rawQuery("SELECT p.Celular, p.PrimerNombre, u.Email 
        FROM ct_persona as p 
        STRAIGHT_JOIN usuario as u on u.UsuarioId = p.UsuarioIntranetId
        WHERE p.PersonaId = $PersonaId");
  }

  public function GetPersonaBySolicitudId(string $SolicitudId, string $Prefijo): array
  {
    return $this->db->objectBuilder()->rawQuery("SELECT p.Celular, p.PrimerNombre, u.Email FROM {$Prefijo}_solicitud as s 
        STRAIGHT_JOIN ct_persona as p on p.UsuarioIntranetId = s.UsuarioSolicitaId
        STRAIGHT_JOIN usuario as u on u.UsuarioId = p.UsuarioIntranetId
        WHERE s.SolicitudId = $SolicitudId;");
  }

  public function GetSolicitudIdByProcesoId(string $ProcesoId, string $Prefijo): array
  {
    return $this->db->objectBuilder()->rawQuery("SELECT SolicitudId FROM {$Prefijo}_eventosolicitud as e WHERE e.ProcesoId = $ProcesoId;");
  }

  public function GetProcesoIdBySolicitudId(string $SolicitudId, string $Prefijo): array
  {
    return $this->db->objectBuilder()->rawQuery("SELECT ProcesoId FROM {$Prefijo}_eventosolicitud as e WHERE e.SolicitudId = $SolicitudId;");
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

<?php

/**
 * Description of RondaMantDAL
 *
 * @author DESARROLLO2
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class RondaMantDAL {

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

    public function GetDetalleRMById($RondaMantId, $PREFIJO) {
        return $this->db->objectBuilder()->rawQuery("SELECT 
            dr.DetalleRondaMantId, dr.ServicioId, dr.Descripcion, dr.TecnicoResponsable, dr.Cumplimiento, dr.CoordinadorFirmaId, dr.Observaciones,
            CONCAT(p.PrimerNombre, ' ', p.SegundoNombre, ' ', p.PrimerApellido, ' ', p.SegundoApellido) as NombreCoordinador,
            ser.Nombre as Servicio, dr.IsFirmado, p.Firma as FirmaCoordinador, dr.Observaciones, dr.Cumplimiento
        FROM
            {$PREFIJO}_detallerondamant as dr
        INNER JOIN ct_persona as p on p.UsuarioIntranetId = dr.CoordinadorFirmaId
        INNER JOIN Servicio as ser on ser.ServicioId = dr.ServicioId
        WHERE dr.RondaMantId = $RondaMantId");
    }

    public function GetRondaMants($UsuarioId, $PREFIJO) {
        return $this->db->objectBuilder()->rawQuery("SELECT distinct
            r.RondaMantId, r.SedeId, s.Nombre as Sede, r.Fecha, r.Hora, r.Responsable
        FROM
            {$PREFIJO}_rondamant as r
        INNER JOIN sede as s on s.SedeId = r.SedeId
        INNER JOIN servicio as ser on ser.SedeId = r.SedeId
        INNER JOIN serviciousuario as su on su.ServicioId = ser.ServicioId
        WHERE su.UsuarioId = $UsuarioId ORDER BY r.RondaMantId DESC;");
    }

    public function CreateRondaMant($list, $PREFIJO) {
        $ids = $this->db->insertMulti("{$PREFIJO}_RondaMant", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateDetalleRondaMant($query) {
        $this->db->query($query);
    }

    public function CreateDetalleRondaMant($sql) {
        $this->db->query($sql);
    }

}

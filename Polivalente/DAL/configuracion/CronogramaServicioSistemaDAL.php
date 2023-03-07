<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class CronogramaServicioSistemaDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getAllByVigencia($Vigencia) {
        return $this->db->objectBuilder()->query("select s.Nombre as Servicio, s.ServicioId, 
        dc1.Mes as '1', GetReporteIdByDetalleCronograma(dc1.DetalleCronogramaId) as 'r1',
        dc2.Mes as '2', GetReporteIdByDetalleCronograma(dc2.DetalleCronogramaId) as 'r2', 
        dc3.Mes as '3', GetReporteIdByDetalleCronograma(dc3.DetalleCronogramaId) as 'r3', 
        dc4.Mes as '4', GetReporteIdByDetalleCronograma(dc4.DetalleCronogramaId) as 'r4', 
        dc5.Mes as '5', GetReporteIdByDetalleCronograma(dc5.DetalleCronogramaId) as 'r5', 
        dc6.Mes as '6', GetReporteIdByDetalleCronograma(dc6.DetalleCronogramaId) as 'r6', 
        dc7.Mes as '7', GetReporteIdByDetalleCronograma(dc7.DetalleCronogramaId) as 'r7', 
        dc8.Mes as '8', GetReporteIdByDetalleCronograma(dc8.DetalleCronogramaId) as 'r8', 
        dc9.Mes as '9', GetReporteIdByDetalleCronograma(dc9.DetalleCronogramaId) as 'r9', 
        dc10.Mes as '10', GetReporteIdByDetalleCronograma(dc10.DetalleCronogramaId) as 'r10', 
        dc11.Mes as '11', GetReporteIdByDetalleCronograma(dc11.DetalleCronogramaId) as 'r11', 
        dc12.Mes as '12', GetReporteIdByDetalleCronograma(dc12.DetalleCronogramaId) as 'r12'
        from servicio as s
        inner join sistemas_detallecronograma as dc on s.ServicioId = dc.ServicioId
        left join sistemas_detallecronograma as dc1 on s.ServicioId = dc1.ServicioId and dc1.Mes = 1
        left join sistemas_detallecronograma as dc2 on s.ServicioId = dc2.ServicioId and dc2.Mes = 2
        left join sistemas_detallecronograma as dc3 on s.ServicioId = dc3.ServicioId and dc3.Mes = 3
        left join sistemas_detallecronograma as dc4 on s.ServicioId = dc4.ServicioId and dc4.Mes = 4
        left join sistemas_detallecronograma as dc5 on s.ServicioId = dc5.ServicioId and dc5.Mes = 5
        left join sistemas_detallecronograma as dc6 on s.ServicioId = dc6.ServicioId and dc6.Mes = 6
        left join sistemas_detallecronograma as dc7 on s.ServicioId = dc7.ServicioId and dc7.Mes = 7
        left join sistemas_detallecronograma as dc8 on s.ServicioId = dc8.ServicioId and dc8.Mes = 8
        left join sistemas_detallecronograma as dc9 on s.ServicioId = dc9.ServicioId and dc9.Mes = 9
        left join sistemas_detallecronograma as dc10 on s.ServicioId = dc10.ServicioId and dc10.Mes = 10
        left join sistemas_detallecronograma as dc11 on s.ServicioId = dc11.ServicioId and dc11.Mes = 11
        left join sistemas_detallecronograma as dc12 on s.ServicioId = dc12.ServicioId and dc12.Mes = 12
        inner join sistemas_cronograma as c on c.CronogramaId = dc.CronogramaId
        where c.Vigencia = $Vigencia group by s.ServicioId order by s.Nombre");
    }
    
    public function getMantenimientosPendientes() {
        return $this->db->objectBuilder()->rawQuery("SELECT s.Nombre as Servicio, dc.DetalleCronogramaId, dc.Mes, u.Email, u.NombreCompleto, c.Vigencia 
        FROM sistemas_detallecronograma as dc 
        INNER JOIN sistemas_cronograma as c on dc.CronogramaId = c.CronogramaId 
        INNER JOIN servicio as s on dc.ServicioId = s.ServicioId
        INNER JOIN serviciousuario as su on su.ServicioId = s.ServicioId
        INNER JOIN usuario as u on su.UsuarioId = u.UsuarioId
        INNER JOIN ct_persona as p on u.Email = p.Usuario collate latin1_spanish_ci
        WHERE dc.Mes <= MONTH(NOW()) AND (
                SELECT COUNT(*) FROM sistemas_reportedcronograma as rdc WHERE rdc.DetalleCronogramaId = dc.DetalleCronogramaId
        ) = 0");
    }
    
    public function getAllByUserId($UserId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT DISTINCT se.CronogramaServicioSistemaId, se.Nombre FROM serviciousuario as su
        inner join servicio as s on su.ServicioId = s.ServicioId
        inner join sede as se on s.CronogramaServicioSistemaId = se.CronogramaServicioSistemaId
        where su.UsuarioId = $UserId;");
    }
    
    public function CreateCronogramaServicioSistema($list) {
        $ids = $this->db->insertMulti("sistemas_cronograma", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateCronogramaServicioSistemaUsuario($list) {
        $ids = $this->db->insertMulti("sistemas_cronogramaservicio", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function GetCronogramaServicioSistema($Vigencia) {
        $this->db->where("Vigencia", $Vigencia);
        return $this->db->objectBuilder()->get("sistemas_Cronograma");
    }
    
    public function GetCronogramaServicioSistemaByUsuario($CronogramaServicioSistemaId, $UsuarioId) {
        $this->db->where("CronogramaServicioSistemaId", $CronogramaServicioSistemaId);
        $this->db->where("UsuarioId", $UsuarioId);
        return $this->db->objectBuilder()->get("CronogramaServicioSistemaUsuario");
    }
    
    public function GetCronogramaServicioSistemaByCronogramaServicioSistemaId($CronogramaServicioSistemaId) {
        $this->db->where("CronogramaServicioId", $CronogramaServicioSistemaId);
        return $this->db->objectBuilder()->getOne("sistemas_cronogramaservicio");
    }
    
    public function UpdateCronogramaServicioSistema($list, $id) {
        $this->db->where ('CronogramaServicioId', $id);
        if ($this->db->update('sistemas_cronogramaservicio', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}

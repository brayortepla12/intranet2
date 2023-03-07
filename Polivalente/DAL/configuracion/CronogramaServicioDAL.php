<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class CronogramaServicioDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }


    public function getAll() {
        return $this->db->objectBuilder()->query("SELECT cs.*, fm.Nombre as FrecuenciaMantenimiento, ser.Nombre as Servicio, s.Nombre as Sede 
        FROM cronogramaservicio as cs
        inner join frecuenciamantenimiento as fm on cs.FrecuenciaMantenimientoId = fm.FrecuenciaMantenimientoId
        inner join Servicio as ser on cs.ServicioId = ser.ServicioId
        inner join Sede as s on cs.SedeId = s.SedeId;");
    }
    public function getAllByUserId($UserId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT DISTINCT se.CronogramaServicioId, se.Nombre FROM serviciousuario as su
        inner join servicio as s on su.ServicioId = s.ServicioId
        inner join sede as se on s.CronogramaServicioId = se.CronogramaServicioId
        where su.UsuarioId = $UserId;");
    }
    
    public function CreateCronogramaServicio($list, $Prefijo) {
        $list_r = [];
        foreach ($list as $c) {
            $this->db->rawQuery("INSERT INTO `{$Prefijo}_cronogramamp` 
            (`Vigencia`, `1`, `2`, `3`, `4`, `5`, `6`, `7`, `8`, `9`, `10`, `11`, `12`, `ServicioId`, `HojaVidaId`, `Nombre`, `Marca`, `Modelo`, `Serie`, `Ubicacion`, `CreatedBy`) 
            SELECT $c->Vigencia, {$c->_1}, {$c->_2}, {$c->_3}, {$c->_4}, {$c->_5}, {$c->_6}, {$c->_7}, {$c->_8}, {$c->_9}, {$c->_10}, {$c->_11}, {$c->_12},
            $c->ServicioId, '$c->HojaVidaId', '$c->Nombre', '$c->Marca', '$c->Modelo', '$c->Serie', '$c->Ubicacion', '$c->CreatedBy' WHERE NOT EXISTS (
                SELECT HojaVidaId FROM `{$Prefijo}_cronogramamp` WHERE HojaVidaId = $c->HojaVidaId and Vigencia = $c->Vigencia
            );");
            if($this->db->count === 0){
                // no afecto ninguna fila, porque esta hoja de vida ya existe para este periodo
                array_push($list_r, $c);
            }
        }
        return $list_r;
    }
    
    public function CreateCronogramaServicioUsuario($list) {
        $ids = $this->db->insertMulti("CronogramaServicioUsuario", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function GetCronogramaServicio($ServicioId) {
        $this->db->where("ServicioId", $ServicioId);
        return $this->db->objectBuilder()->get("CronogramaServicio");
    }
    
    public function GetCronogramaServicioByUsuario($CronogramaServicioId, $UsuarioId) {
        $this->db->where("CronogramaServicioId", $CronogramaServicioId);
        $this->db->where("UsuarioId", $UsuarioId);
        return $this->db->objectBuilder()->get("CronogramaServicioUsuario");
    }
    
    public function GetCronogramaServicioByCronogramaServicioId($CronogramaServicioId) {
        $this->db->where("CronogramaServicioId", $CronogramaServicioId);
        return $this->db->objectBuilder()->getOne("CronogramaServicio");
    }
    
    public function UpdateCronogramaServicio($list, $id) {
        $this->db->where ('CronogramaServicioId', $id);
        if ($this->db->update('CronogramaServicio', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}

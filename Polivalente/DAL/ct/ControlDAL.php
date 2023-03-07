<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class ControlDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function GetControlWithLimite($Limite) {
        return $this->db->objectBuilder()->rawQuery("select * from ct_control order by ControlId DESC limit $Limite;");
    }
    
    public function GetLastControlInDayLider($PersonaId, $Fecha) {
        return $this->db->objectBuilder()->rawQuery("Select d.ControlId, d.Fecha, d.CanMarcar, d.PrePermiso, d.Dispositivo, d.PermisoId, IsCumpleano($PersonaId) as IsCumpleano ,
        #validaciones
        if ( (d.Fecha >= concat(subdate(date('$Fecha'), interval 1 day), ' 19:00:00') and d.Fecha <= concat(date('$Fecha'), ' 03:30:00')) , 'Salida', d.tipo ) as Tipo ,
        if ( (d.Fecha >= concat(subdate(date('$Fecha'), interval 1 day), ' 19:00:00') and d.Fecha <= concat(date('$Fecha'), ' 03:30:00')), 1, 0) as Nocturno, 
        if(time('$Fecha') >= time(adddate(d.fecha, interval 1 hour)) or d.Tipo = 'Entrada', 
        if(time('$Fecha') <= time(subtime(time(GetHoraFinByFecha(d.TurnoId, d.Fecha)), '00:30:00')) and d.TurnoId is not null, 'Necesita permiso', 'No deberia marcar'), 'No necesita permiso') as EstadoActual
        from (SELECT c.ControlId, c.Fecha, c.Tipo, '$Fecha' >= adddate(c.Fecha, interval 10 second) as CanMarcar, c.PrePermiso, p.TurnoId, c.Dispositivo, c.PermisoId
        FROM ct_control as c 
        STRAIGHT_JOIN ct_persona as p on c.PersonaId = p.PersonaId
                where c.PersonaId = $PersonaId Order by c.ControlId DESC) as d limit 1;");
    }
    public function VerificarTiempo($FechaAnterior, $FechaActual) {
        return $this->db->objectBuilder()->rawQueryOne("Select time('$FechaActual') < addtime(time('$FechaAnterior'), '01:00:00') as Bandera;");
    }
    

    public function GetLastControlInDay($PersonaId, $Fecha) {
        return $this->db->objectBuilder()->rawQuery("Select d.ControlId, d.Fecha, d.CanMarcar, d.PrePermiso, d.Dispositivo, IsCumpleano($PersonaId) as IsCumpleano ,
        #validaciones
        if ( (d.Fecha >= concat(subdate(('$Fecha'), interval 1 day), ' 18:00:00')) , d.tipo, 'Salida') as Tipo ,
        if ( (d.Fecha >= concat(subdate(('$Fecha'), interval 1 day), ' 18:00:00')), 1, 0) as Nocturno, 'Necesita permiso' as EstadoActual
        from (SELECT c.ControlId, c.Fecha, c.Tipo, '$Fecha' >= adddate(c.Fecha, interval 7 second) as CanMarcar, c.PrePermiso, c.Dispositivo FROM ct_control as c 
        where c.PersonaId = $PersonaId Order by c.ControlId DESC limit 1) as d;");
    }
    
    public function GetControlByPersonaIdAndFecha($PersonaId, $Desde, $Hasta) {
        return $this->db->objectBuilder()->rawQuery("select p.PrimerNombre, p.SegundoNombre, p.PrimerApellido, p.SegundoApellido, c.* from ct_control as c 
        STRAIGHT_JOIN ct_persona as p on c.PersonaId = p.PersonaId and p.Estado = 'Activo'
        where p.PersonaId = $PersonaId and c.Fecha >= '$Desde' and c.Fecha <= adddate('$Hasta', interval 1 day) order by Fecha");
    }
    
    public function GetListEmpleados($Dispositivo) {
        return $this->db->objectBuilder()->rawQuery("SELECT t.* FROM (SELECT CONCAT(PrimerNombre, ' ', SegundoNombre, ' ', PrimerApellido, ' ', SegundoApellido) as Nombres, cc.Cargo, p.Foto, p.PersonaId, c.Fecha, c.PermisoId, c.ControlId FROM ct_control as c
        STRAIGHT_JOIN ct_persona as p on c.PersonaId = p.PersonaId 
        left join ct_cargo as cc on p.CargoId = cc.CargoId
        where c.Dispositivo = '$Dispositivo'
        order by c.ControlId DESC limit 5 ) as t order by t.ControlId;");
    }
    
    public function GetEstadoTurnoSalida($PersonaId, $Fecha) {
//        return $this->db->objectBuilder()->rawQuery("SELECT h.HorarioId,h.DiaMes,h.DiaSemana, h.HoraInicio, h.HoraFin, if(time('$Fecha') >= addtime(time(GetHoraFinByFecha(p.TurnoId, '$Fecha')), '00:30:00'), 'Usted esta saliendo muy tarde', 
//        if(time('$Fecha') < time(GetHoraFinByFecha(p.TurnoId, '$Fecha')), 'Usted esta saliendo antes de tiempo.', 'Adios')
//        ) as EstadoSalida FROM ct_persona as p 
//        left join ct_horario as h on h.TurnoId = p.TurnoId and (h.DiaMes = Date('$Fecha') or (p.HasHorarioFijo = 1 and time(h.HoraFin) = time(GetHoraFinByFecha(p.TurnoId, '$Fecha')) and DAYNAME('$Fecha') = h.DiaSemana))
//        where p.PersonaId = $PersonaId;");
        return $this->db->objectBuilder()->rawQuery("SELECT h.HorarioId,h.DiaMes,h.DiaSemana, h.HoraInicio, h.HoraFin, if(time('$Fecha') >= addtime(time(GetHoraFinByFecha(p.TurnoId, '$Fecha')), '00:30:00'), 'Usted esta saliendo muy tarde', 
        if(time('$Fecha') < time(GetHoraFinByFecha(p.TurnoId, '$Fecha')), 'Adios', 'Adios')
        ) as EstadoSalida FROM ct_persona as p 
        left join ct_horario as h on h.TurnoId = p.TurnoId and (h.DiaMes = Date('$Fecha') or (p.HasHorarioFijo = 1 and time(h.HoraFin) = time(GetHoraFinByFecha(p.TurnoId, '$Fecha')) and DAYNAME('$Fecha') = h.DiaSemana))
        where p.PersonaId = $PersonaId;");
    }
    
    public function GetEstadoTurno($PersonaId, $Fecha) {
        return $this->db->objectBuilder()->rawQuery("select h.HorarioId , h.HoraInicio , h.HoraFin , h.DiaSemana , h.DiaMes  ,
        (if(time('$Fecha') <= addtime(time(h.HoraInicio), '00:10:00') or h.HoraInicio is null, 'A Tiempo','Tarde')) as EstadoTurno
        from 
        (
        SELECT p.*
        from ct_persona as p where p.PersonaId = $PersonaId
        ) as Jornada
        left join ct_horario as h on h.TurnoId = Jornada.TurnoId and DAYNAME('$Fecha') = h.DiaSemana and 
        (
        (time('$Fecha') >= time(h.HoraInicio) and time('$Fecha') <= time(h.HoraFin))
        ) and (h.EsteTurnoVence = 0 or h.EsteTurnoVence = 1  and (h.DiaMes = date('$Fecha')))");
    }
    
    public function GetUltimoControl($PersonaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT ControlId, Fecha, Tipo, Dispositivo, PermisoId FROM ct_control where PersonaId = $PersonaId order by ControlId DESC limit 5;");
    }
    
    public function CreateMensaje($list) {
        $ids = $this->db->insertMulti("ct_sms", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateControl($list) {
        $ids = $this->db->insertMulti("ct_control", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function UpdateControl($list, $id) {
        $this->db->where('ControlId', $id);
        $bool = $this->db->update('ct_control', $list[0]);
        if ($bool) {
            return $bool;
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

}

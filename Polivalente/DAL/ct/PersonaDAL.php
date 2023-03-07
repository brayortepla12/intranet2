<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class PersonaDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct() {
        $this->db->disconnect();
    }

    public function CreatePermiso($list) {

        $ids = $this->db->insertMulti("ct_permiso", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateCargo($list) {
        $ids = $this->db->insertMulti("ct_cargo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreatePersona($list) {
        $ids = $this->db->insertMulti("ct_persona", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateHorario($list) {
        $ids = $this->db->insertMulti("ct_horario", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateSolicitudGH($list) {
        $ids = $this->db->insertMulti("ct_SolicitudHorario", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateTurno($list) {
        $ids = $this->db->insertMulti("ct_turno", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function CreateVariable($list) {
        $ids = $this->db->insertMulti("ct_variable", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function UpdateVariable($list, $id) {
        $this->db->where('VariableId', $id);
        if ($this->db->update('ct_variable', $list[0])) {
            return [true];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function UpdateCargo($list, $id) {
        $this->db->where('CargoId', $id);
        if ($this->db->update('ct_cargo', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    
    public function ExistSolicitud($PersonaId, $Mes, $Year) {
//        echo "SELECT SolicitudHorarioId FROM ct_solicitudhorario where JefeId = $PersonaId and Mes = $Mes and Year = $Year and IsRevisado is null;";
        return $this->db->objectBuilder()->rawQuery("SELECT SolicitudHorarioId FROM ct_solicitudhorario where JefeId = $PersonaId and Mes = $Mes and Year = $Year and IsRevisado is null;");
    }
    
    public function GetEstadisticasPByLiderId($JefeId, $Mes, $Year) {
        return $this->db->objectBuilder()->rawQuery("call polivalente.ConsultaBiometricoByLider($JefeId, $Mes, $Year);");
    }

    public function GetHorarioByJefeId($PersonaId, $JefeId, $Mes, $Year) {
        return $this->db->objectBuilder()->rawQuery("select h.HorarioId, DAY(h.DiaMes) as Dia, v.Abreviatura from ct_horario as h 
        inner join ct_turno as t on t.TurnoId = h.TurnoId
        inner join ct_variable as v on h.VariableId = v.VariableId
        where t.ColaboradorId = $PersonaId and t.JefeId = $JefeId 
        and MONTH(h.DiaMes) = $Mes and YEAR(h.DiaMes) = $Year order by DAY(h.DiaMes)");
    }

    public function GetPersonaByUsuario($UsuarioOrPersonaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM ct_persona
        WHERE UsuarioIntranetId = '$UsuarioOrPersonaId'");
    }
    
    public function GetVariablesByUP($UsuarioOrPersonaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT v.VariableId, v.Variable as Nombre, v.Abreviatura, v.FechaInicio, v.FechaFin, v.FechaInicio2, v.FechaFin2 FROM ct_variable as v
        inner join ct_persona as p on UsuarioIntranetId = '$UsuarioOrPersonaId'
        WHERE p.UsuarioIntranetId = v.UsuarioLiderId;");
    }
    
    public function VerificarUsuarioIdRegUser($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT PersonaId FROM ct_persona
        WHERE UsuarioIntranetId = '$UsuarioId'");
    }
    
    

    public function GetCambioHorarios($SedeId, $Mes, $Year) {
        return $this->db->objectBuilder()->rawQuery("SELECT sh.*, CONCAT(p.PrimerNombre, ' ', p.PrimerApellido) as NombreJefe, p.PersonaId, p.UsuarioIntranetId FROM polivalente.ct_solicitudhorario as sh 
        inner join ct_persona as p on sh.JefeId = p.PersonaId
        where sh.Mes = $Mes and `sh`.`Year` = $Year and p.SedeId = $SedeId order by SolicitudHorarioId Desc;");
    }

    public function GetTurnoByJefeId_ColaboradorId($JefeId, $ColaboradorId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM polivalente.ct_turno where JefeId = $JefeId and ColaboradorId = $ColaboradorId;");
    }

    public function DeleteHorarioByDiaMes($DiaMes, $ColaboradorId) {
        $this->db->objectBuilder()->rawQuery("Delete ct_horario from ct_horario 
        INNER JOIN ct_turno ON ct_horario.TurnoId = ct_turno.TurnoId
         where ct_horario.DiaMes = '$DiaMes' and ct_turno.ColaboradorId = $ColaboradorId;");
    }

    public function GetHorarioFijoByColaboradorId($PersonaId) {
        return $this->db->objectBuilder()->rawQuery("select h.DiaMes, h.DiaSemana, h.HoraInicio, h.HoraFin, h.HorarioId from ct_horario as h
        inner join ct_persona as p on p.PersonaId = $PersonaId
        inner join ct_turno as t on t.TurnoId = p.TurnoId
        where h.TurnoId = t.TurnoId and h.EsteTurnoVence = 0");
    }

    public function GetHorarioByColaboradorId($PersonaId, $Mes, $Year) {
        return $this->db->objectBuilder()->rawQuery("select h.DiaMes, (ELT(WEEKDAY(h.DiaMes) + 1, 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom')) AS DIA_SEMANA, h.HoraInicio, h.HoraFin, h.HorarioId, time(h.HoraInicio) from ct_horario as h
        inner join ct_turno as t on h.TurnoId = t.TurnoId
        where t.ColaboradorId = $PersonaId and MONTH(h.DiaMes) = $Mes and YEAR(h.DiaMes) = $Year order by h.DiaMes, time(h.HoraInicio)");
    }

    public function GetVariableByAbrev($Abreviatura) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM ct_variable where Abreviatura = '$Abreviatura';");
    }
    
    public function GetVariableByAbrevByPersona($Abreviatura, $PersonaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT v.* FROM ct_variable as v
        inner join ct_persona as p on v.UsuarioLiderId = p.UsuarioIntranetId where p.UsuarioIntranetId = $PersonaId and v.Abreviatura = '$Abreviatura';");
    }
    
    public function GetVariableByAbrevByUsuario($Abreviatura, $UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM ct_variable where Abreviatura = '$Abreviatura' and UsuarioLiderId = $UsuarioId;");
    }
    
    public function GetPersonaIdByUsuario($Usuario) {
        return $this->db->objectBuilder()->rawQuery("select PersonaId from ct_persona where Usuario = '$Usuario' or PersonaId = '$Usuario';");
    }

    public function GetPermisoByRango_PersonaId($PersonaId, $FechaInicio, $FechaFin) {
        return $this->db->objectBuilder()->rawQuery("SELECT *, 1 as Validar FROM polivalente.ct_control where Fecha >= '$FechaInicio' and Fecha <= '$FechaFin' and PersonaId = $PersonaId and PermisoId = 0;");
    }

    public function UpdatePermiso($list, $id) {
        $this->db->where('PermisoId', $id);
        if ($this->db->update('ct_permiso', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function UpdatePersona($list, $id) {
        $this->db->where('PersonaId', $id);
        if ($this->db->update('ct_persona', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function UpdatePersonaUsuario($list, $id, $UsuarioId = null) {
        if($UsuarioId){
            $this->db->query("UPDATE `polivalente`.`ct_persona` SET `UsuarioIntranetId`=NULL WHERE `UsuarioIntranetId`= $UsuarioId;");
        }
        $this->db->where('PersonaId', $id);
        if ($this->db->update('ct_persona', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    

    public function UpdateCambioHorario($list, $id) {
        $this->db->where('SolicitudHorarioId', $id);
        if ($this->db->update('ct_solicitudhorario', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetPermisoByPersonaId($PersonaId, $Fecha) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM polivalente.ct_permiso where (PersonaId = $PersonaId or IsGeneral= 1) and VBGestionHumana = 1 and FechaInicio <= '$Fecha' and FechaFin >= '$Fecha' and Estado = 'Activo' and (5 < 50 or IsGeneral = 1) order by PermisoId DESC limit 1;");
    }

    public function GetAllHorarios() {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM polivalente.ct_horario where EsteTurnoVence = 0 or (EsteTurnoVence = 1 and MONTH(now()) = MONTH(DiaMes));");
    }

    public function GetPermisosLimite($Limite) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.PermisoId, p.CreatedAt, p.Motivo,p.Cual,p.FechaInicio,p.FechaFin,p.VBGestionHumana,p.IsConsumido,
        p.IsGeneral, p.PersonaId, p.Estado,
        CONCAT(per.PrimerNombre, ' ', per.SegundoNombre, ' ' , per.PrimerApellido, ' ' , per.SegundoApellido) as Nombres FROM polivalente.ct_permiso as p
            inner join ct_persona as per on per.PersonaId = p.PersonaId
        where p.Estado = 'Activo' order by p.PermisoId DESC limit $Limite;");
    }

    public function GetPermisosLimiteA($Limite) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.PermisoId, p.CreatedAt, p.Motivo,p.Cual,p.FechaInicio,p.FechaFin,p.VBGestionHumana,p.IsConsumido,
        p.IsGeneral, p.PersonaId, p.Estado,
        CONCAT(per.PrimerNombre, ' ', per.SegundoNombre, ' ' , per.PrimerApellido, ' ' , per.SegundoApellido) as Nombres, 
        (
        (select count(tc.PermisoId) from (SELECT c.PermisoId from ct_control as c order by ControlId DESC limit 700) as tc where tc.PermisoId = p.PermisoId)
        ) as USO FROM polivalente.ct_permiso as p
            left join ct_persona as per on per.PersonaId = p.PersonaId
        where p.Estado = 'Activo' and p.FechaFin > now() order by p.PermisoId DESC limit $Limite;");
    }

    public function GetUltimoControlByPer($Limite) {
        return $this->db->objectBuilder()->rawQuery("select c.ControlId, c.Fecha, c.PersonaId, c.Dispositivo,c.Tipo,c.PermisoId from ct_control as c order by c.ControlId DESC limit $Limite;");
    }

    public function GetPermisosBySedeIdAndMes($SedeId, $Mes, $Year) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.PermisoId, p.CreatedAt, p.Motivo,p.Cual,
            p.FechaInicio, p.FechaFin, TIMEDIFF(p.FechaFin, p.FechaInicio) as Tiempo, p.VBGestionHumana, p.IsConsumido, 
            CONCAT(per.PrimerNombre, ' ', per.SegundoNombre, ' ' , per.PrimerApellido, ' ' , per.SegundoApellido) as Nombres, c.Cargo 
            FROM polivalente.ct_permiso as p 
            inner join ct_persona as per on per.PersonaId = p.PersonaId
            left join ct_cargo as c on c.CargoId = per.CargoId
        where p.Estado = 'Activo' and per.SedeId = $SedeId and MONTH(p.FechaInicio) = $Mes and  YEAR(p.FechaInicio) = $Year order by p.PermisoId ASC;");
    }

    public function GetPermisos() {
        return $this->db->objectBuilder()->rawQuery("SELECT p.PermisoId, p.CreatedAt, p.Motivo,p.Cual,p.FechaInicio,p.FechaFin,p.VBGestionHumana,p.IsConsumido, CONCAT(per.PrimerNombre, ' ', per.SegundoNombre, ' ' , per.PrimerApellido, ' ' , per.SegundoApellido) as Nombres FROM polivalente.ct_permiso as p 
            inner join ct_persona as per on per.PersonaId = p.PersonaId
        where p.Estado = 'Activo' order by p.PermisoId DESC;");
    }

    public function GetPermisoByLiderId($LiderId, $Mes, $Year) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.*, /*GetCountUsoPermiso(p.PermisoId)*/ 0 as UsoTotal, CONCAT(per.PrimerNombre, ' ', per.SegundoNombre, ' ' , per.PrimerApellido, ' ' , per.SegundoApellido) as Nombres FROM polivalente.ct_permiso as p 
            inner join ct_persona as per on per.PersonaId = p.PersonaId
        where p.LiderId = $LiderId and MONTH(p.CreatedAt) = $Mes and YEAR(p.CreatedAt) = $Year and p.Estado = 'Activo' order by p.PermisoId DESC;");
    }

    public function GetPermisoByPermisoId($PermisoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.*, concat(per.PrimerNombre, ' ', per.SegundoNombre, ' ', per.PrimerApellido, ' ', per.SegundoApellido) as Nombres, per.Cedula, u.Firma as VBJefe , u2.Firma as VBGestionHumana
        FROM polivalente.ct_permiso as p 
        inner join ct_persona as per on p.PersonaId = per.PersonaId COLLATE latin1_spanish_ci
        left join ct_persona as lid on per.JefeId = lid.PersonaId COLLATE latin1_spanish_ci
        left join usuario as u on lid.Usuario = u.NombreUsuario COLLATE latin1_spanish_ci
        left join usuario as u2 on p.UsuarioGHId = u2.UsuarioId COLLATE latin1_spanish_ci
        where p.PermisoId = $PermisoId and p.Estado = 'Activo';");
    }

    public function GetPermisoByCodigoTarjeta($CodigoTarjeta) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.*, concat(per.PrimerNombre, ' ', per.SegundoNombre, ' ', per.PrimerApellido, ' ', per.SegundoApellido) as Nombres, per.Cedula, u.Firma as VBJefe , u2.Firma as VBGestionHumana
        FROM polivalente.ct_permiso as p 
        inner join ct_persona as per on p.PersonaId = per.PersonaId COLLATE latin1_spanish_ci
        left join ct_persona as lid on per.JefeId = lid.PersonaId COLLATE latin1_spanish_ci
        left join usuario as u on per.Usuario = u.NombreUsuario COLLATE latin1_spanish_ci
        left join usuario as u2 on p.UsuarioGHId = u2.UsuarioId COLLATE latin1_spanish_ci
        where (per.CodigoTarjeta = '$CodigoTarjeta' or per.Cedula = '$CodigoTarjeta') and p.Estado = 'Activo'
        order by p.PermisoId DESC limit 1;");
    }

    public function GetPermisoByDocumento($Documento) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.*, concat(per.PrimerNombre, ' ', per.SegundoNombre, ' ', per.PrimerApellido, ' ', per.SegundoApellido) as Nombres, per.Cedula, u.Firma as VBJefe , u2.Firma as VBGestionHumana
        FROM polivalente.ct_permiso as p 
        inner join ct_persona as per on p.PersonaId = per.PersonaId COLLATE latin1_spanish_ci
        inner join ct_persona as lid on per.JefeId = lid.PersonaId COLLATE latin1_spanish_ci
        inner join usuario as u on lid.Usuario = u.NombreUsuario COLLATE latin1_spanish_ci
        left join usuario as u2 on p.UsuarioGHId = u2.UsuarioId COLLATE latin1_spanish_ci
        where per.Cedula = '$Documento' and p.Estado = 'Activo'
        order by p.PermisoId DESC limit 1;");
    }

    public function GetNumeroJefe($JefeId) {
        return $this->db->objectBuilder()->rawQuery("Select Celular from ct_persona where PersonaId = $JefeId;");
    }

    public function GetPersonaByCedula($Cedula) {
        return $this->db->objectBuilder()->rawQuery("Select PrimerNombre, SegundoNombre, PrimerApellido, SegundoApellido, PersonaId, Cedula,
        CodigoTarjeta from ct_persona where Cedula = '$Cedula' or CodigoTarjeta = '$Cedula';");
    }
    
    public function GetPersonaByUsuarioIntranet($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("Select PrimerNombre, SegundoNombre, PrimerApellido, SegundoApellido, PersonaId, Cedula,
        CodigoTarjeta from ct_persona where UsuarioIntranetId = $UsuarioId;");
    }

    public function GetPersonasActivas() {
        return $this->db->objectBuilder()->rawQuery("Select p.PrimerNombre, p.SegundoNombre, p.PrimerApellido, p.SegundoApellido, p.PersonaId,
        p.Cedula, p.CodigoTarjeta, car.Cargo, p.Foto, p.TipoPersona, p.Genero, p.FechaNacimiento, p.TurnoId, p.FormatoCarnet from ct_persona as p
        LEFT JOIN ct_cargo AS car on car.CargoId = p.CargoId
        where p.Estado = 'Activo'
        order by p.PrimerNombre");
        #, (SELECT Tipo FROM ct_control as c
        #where c.PersonaId = p.PersonaId order by ControlId DESC limit 1) as EstadoBiometrico
    }

    public function GetCPersonasActivas() {
        return $this->db->objectBuilder()->rawQuery("Select count(p.PersonaId) as Total from ct_persona as p
        where p.Estado = 'Activo'");
    }
    
    public function GetPersonasLite($Estado) {
        return $this->db->objectBuilder()->rawQuery("Select 
        CONCAT(PrimerNombre, ' ', SegundoNombre,  ' ', PrimerApellido,  ' ', SegundoApellido) as Nombres, PersonaId, Firma  
        from ct_persona 
        order by PrimerNombre, PrimerApellido ");
    }

    public function GetPersonas() {
        return $this->db->objectBuilder()->rawQuery("Select PrimerNombre, SegundoNombre, PrimerApellido, SegundoApellido, PersonaId,
                Cedula, CodigoTarjeta, Estado, TipoPersona, UsuarioIntranetId from ct_persona 
                order by PrimerNombre");
    }

    public function GetHorarioByTurnoId($TurnoId) {
        return $this->db->objectBuilder()->rawQuery("Select HoraInicio, HoraFin, DiaSemana from ct_horario where TurnoId = $TurnoId");
    }

    public function GetTurnosByPersonaIdLite($PersonaId) {
        return $this->db->objectBuilder()->rawQuery("Select t.TurnoId, t.Nombre from ct_turno as t
            inner join ct_persona as p on p.PersonaId = $PersonaId
            where t.TurnoId = p.TurnoId");
    }

    public function GetTurnosByPersonaId($PersonaId) {
        return $this->db->objectBuilder()->rawQuery("Select t.TurnoId, t.Nombre from ct_turno as t
            inner join ct_persona as p on p.PersonaId = $PersonaId and p.HasHorarioFijo = 0
                where (t.ColaboradorId = $PersonaId and (Select count(tt.TurnoId) from ct_turno as tt where tt.ColaboradorId = $PersonaId) > 0)  order by Nombre");
    }

    public function GetTurnos() {
        return $this->db->objectBuilder()->rawQuery("Select TurnoId, Nombre from ct_turno where ColaboradorId is null and JefeId is null order by Nombre");
    }

    public function GetLideres() {
        return $this->db->objectBuilder()->rawQuery("Select CONCAT(PrimerNombre, ' ', SegundoNombre, ' ', PrimerApellido, ' ', SegundoApellido) as Nombres, PersonaId, Cedula, CodigoTarjeta from ct_persona where TipoPersona = 'Lider'");
    }

    public function GetPersonaById($PersonaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.*, p.Email as Correo, CONCAT(j.PrimerNombre, ' ', j.PrimerApellido) as NombreJefe, c.Cargo as NombreCargo FROM ct_persona as p
        left join ct_persona as j on p.JefeId = j.PersonaId
        left join ct_cargo as c on c.CargoId = p.CargoId
        where p.PersonaId = '$PersonaId';");
    }

    public function GetPersonasByLider($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.Cedula,p.CodigoTarjeta,p.PersonaId, p.PrimerNombre, p.PrimerApellido, p.SegundoNombre, p.SegundoApellido FROM ct_persona as p
        inner join ct_persona as l on p.JefeId = l.PersonaId
        where l.UsuarioIntranetId = '$UsuarioId' and p.Estado = 'Activo' order by p.PrimerNombre, p.PrimerApellido;");
    }

    public function GetPersonasByLiderId($LiderId) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.Cedula,p.CodigoTarjeta,p.PersonaId, CONCAT(p.PrimerNombre , ' ', p.PrimerApellido) as Nombres FROM ct_persona as p
        inner join ct_persona as l on p.JefeId = l.PersonaId        
        where l.UsuarioIntranetId = $LiderId and p.Estado = 'Activo' and p.HasHorarioFijo = 0 
        order by p.PrimerNombre, p.PrimerApellido;");
    }

    public function GetPersonasByLiderIdVer2All($LiderId) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.Cedula,p.CodigoTarjeta,p.PersonaId, CONCAT(p.PrimerNombre , ' ', p.PrimerApellido) as Nombres, p.HasHorarioFijo FROM ct_persona as p
        inner join ct_persona as l on p.JefeId = l.PersonaId        
        where (l.Usuario = '$LiderId' or l.PersonaId = '$LiderId') and p.Estado = 'Activo' 
        order by p.HasHorarioFijo, p.SedeId, p.TipoPersona DESC, p.CargoId, p.PrimerNombre, p.PrimerApellido;");
    }

    public function GetLideresAll($Year, $Mes) {
        if ($Mes < 12) {
            $MesSiguiente = $Mes + 1;
            $YearN = $Year;
        } else {
            $MesSiguiente = 1;
            $YearN = $Year + 1;
        }
        //TIME_FORMAT(GetTiempoByMes(t.PersonaId, '$Year-$Mes-01 00:00:00', '$YearN-$MesSiguiente-01 00:00:00'), '%H') as Tiempo
        return $this->db->objectBuilder()->rawQuery("
            select CONCAT(t.PrimerNombre, ' ', t.SegundoNombre, ' ', t.PrimerApellido, ' ', t.SegundoApellido) as Nombres, t.PersonaId, t.PrimerNombre, t.SegundoNombre, t.PrimerApellido, t.SegundoApellido, t.CargoId, cc.Cargo, t.Foto, 
            t.PersonaId,  
             sum(t.Atiempo) T_ATiempo, sum(t.Tarde) as T_Tarde from (
            select J.PersonaId, J.PrimerNombre, J.SegundoNombre, J.PrimerApellido, J.SegundoApellido, J.CargoId, J.Foto, if(J.EstadoTurno = 'A tiempo', count(J.EstadoTurno), 0) Atiempo, if(J.EstadoTurno = 'Tarde' and J.PermisoBySede = 0, count(J.EstadoTurno), 0) Tarde from 
            (
            select Jornada.*,
            if((time(Jornada.Fecha) <= addtime(time(Jornada.HoraInicio), '00:10:00') or (Jornada.PermisoId <> 0 and Jornada.PermisoId is not null)) or Jornada.HoraInicio is null , 'A Tiempo', 'Tarde') as EstadoTurno
            from 
            (
            SELECT p.*, c.Fecha, h.HoraInicio, c.PermisoId, c.PermisoBySede  from ct_persona as p
            left join ct_control as c on c.PersonaId = p.PersonaId and c.Fecha >= '$Year-$Mes-01 00:00:00' and c.Fecha <= '$YearN-$MesSiguiente-01 00:00:00' and c.Tipo = 'Entrada'
            left join ct_horario as h on h.TurnoId = p.TurnoId and DAYNAME(c.Fecha) COLLATE utf8_bin = h.DiaSemana and 
			(
				(time(c.Fecha) >= time(h.HoraInicio) and time(c.Fecha) <= time(h.HoraFin))
			)
            where p.TipoPersona = 'Lider'
            ) as Jornada 
            ) as J 
            group by J.PersonaId, J.EstadoTurno
            ) 
            as t
            left join ct_cargo as cc on t.CargoId = cc.CargoId
            group by t.PersonaId order by t.PrimerNombre, t.PrimerApellido;");
    }

    public function GetColaboradoresByLiderId($Year, $Mes, $LiderId) {
        if ($Mes < 12) {
            $MesSiguiente = $Mes + 1;
            $YearN = $Year;
        } else {
            $MesSiguiente = 1;
            $YearN = $Year + 1;
        }
        //TIME_FORMAT(GetTiempoByMes(t.PersonaId, '$Year-$Mes-01 00:00:00', '$YearN-$MesSiguiente-01 00:00:00'), '%H') as Tiempo

        return $this->db->objectBuilder()->rawQuery("
            select CONCAT(t.PrimerNombre, ' ', t.SegundoNombre, ' ', t.PrimerApellido, ' ', t.SegundoApellido) as Nombres, t.PersonaId, t.PrimerNombre, t.SegundoNombre, t.PrimerApellido, t.SegundoApellido, t.CargoId, cc.Cargo, t.Foto, 
            t.PersonaId,  
             sum(t.Atiempo) T_ATiempo, sum(t.Tarde) as T_Tarde, t.HoraInicio as J1E from (

            select J.PersonaId, J.PrimerNombre, J.SegundoNombre, J.PrimerApellido, J.SegundoApellido, J.CargoId, J.Foto, if(J.EstadoTurno = 'A tiempo', count(J.EstadoTurno), 0) Atiempo, if(J.EstadoTurno = 'Tarde' and J.PermisoBySede = 0, count(J.EstadoTurno), 0) Tarde, J.HoraInicio from 
            (
            select Jornada.*,
            if((time(Jornada.Fecha) <= addtime(time(Jornada.HoraInicio), '00:10:00') or (Jornada.PermisoId <> 0 and Jornada.PermisoId is not null) or Jornada.HoraInicio is null) , 'A Tiempo', 'Tarde') as EstadoTurno
            from 
            (
            SELECT p.*, c.Fecha, h.HoraInicio, c.PermisoId, c.PermisoBySede  from ct_persona as p
            left join ct_control as c on c.PersonaId = p.PersonaId and c.Fecha >= '$Year-$Mes-01 00:00:00' and c.Fecha <= '$YearN-$MesSiguiente-01 00:00:00' and c.Tipo = 'Entrada'
            left join ct_horario as h on h.TurnoId = p.TurnoId and (DAYNAME(c.Fecha) COLLATE utf8_bin = h.DiaSemana and h.EsteTurnoVence = 0 or (h.EsteTurnoVence = 1 and date(c.Fecha) = date(h.DiaMes))) and 
			(
				(time(c.Fecha) >= time(h.HoraInicio) and time(c.Fecha) <= time(h.HoraFin))
			)
            inner join ct_persona as lider on p.JefeId = lider.PersonaId  
            where  p.Estado = 'Activo' and lider.UsuarioIntranetId = $LiderId
            ) as Jornada 
            ) as J 
            group by J.PersonaId, J.EstadoTurno
            ) 
            as t
            left join ct_cargo as cc on t.CargoId = cc.CargoId
            group by t.PersonaId order by t.PrimerNombre, t.PrimerApellido;");
    }

    public function GetColaboradoresByLider($Year, $Mes, $UsuarioId) {
        if ($Mes < 12) {
            $MesSiguiente = $Mes + 1;
            $YearN = $Year;
        } else {
            $MesSiguiente = 1;
            $YearN = $Year + 1;
        }
        //TIME_FORMAT(GetTiempoByMes(t.PersonaId, '$Year-$Mes-01 00:00:00', '$YearN-$MesSiguiente-01 00:00:00'), '%H') as Tiempo

        return $this->db->objectBuilder()->rawQuery("
            select CONCAT(t.PrimerNombre, ' ', t.SegundoNombre, ' ', t.PrimerApellido, ' ', t.SegundoApellido) as Nombres, t.PersonaId, t.PrimerNombre, t.SegundoNombre, t.PrimerApellido, t.SegundoApellido, t.CargoId, cc.Cargo, t.Foto, 
            t.PersonaId,  
             sum(t.Atiempo) T_ATiempo, sum(t.Tarde) as T_Tarde, t.HoraInicio as J1E from (

            select J.PersonaId, J.PrimerNombre, J.SegundoNombre, J.PrimerApellido, J.SegundoApellido, J.CargoId, J.Foto, if(J.EstadoTurno = 'A tiempo', count(J.EstadoTurno), 0) Atiempo, if(J.EstadoTurno = 'Tarde' and J.PermisoBySede = 0, count(J.EstadoTurno), 0) Tarde, J.HoraInicio from 
            (
            select Jornada.*,
            if((time(Jornada.Fecha) <= addtime(time(Jornada.HoraInicio), '00:10:00') or (Jornada.PermisoId <> 0 and Jornada.PermisoId is not null or Jornada.HoraInicio is null) ) , 'A Tiempo', 'Tarde') as EstadoTurno
            from 
            (
            SELECT p.*, c.Fecha, h.HoraInicio, h.HoraFin, c.PermisoId, c.PermisoBySede  from ct_persona as p
            left join ct_control as c on c.PersonaId = p.PersonaId and c.Fecha >= '$Year-$Mes-01 00:00:00' and c.Fecha <= '$YearN-$MesSiguiente-01 00:00:00' and c.Tipo = 'Entrada'
            left join ct_horario as h on h.TurnoId = p.TurnoId and DAYNAME(c.Fecha) COLLATE utf8_bin = h.DiaSemana and 
			(
				(time(c.Fecha) >= time(h.HoraInicio) and time(c.Fecha) <= time(h.HoraFin)) and h.EsteTurnoVence = 0 or (date(c.Fecha) = date(h.DiaMes) and time(c.Fecha) >= time(h.HoraInicio) and time(c.Fecha) <= time(h.HoraFin))
			)
            inner join ct_persona as lider on p.JefeId = lider.PersonaId  
            where  p.Estado = 'Activo' and lider.UsuarioIntranetId = $UsuarioId
            ) as Jornada 
            ) as J 
            group by J.PersonaId, J.EstadoTurno
            ) 
            as t
            left join ct_cargo as cc on t.CargoId = cc.CargoId
            group by t.PersonaId order by t.PrimerNombre, t.PrimerApellido;");
    }

    public function GetColaboradoresAll($Year, $Mes) {
        if ($Mes < 12) {
            $MesSiguiente = $Mes + 1;
            $YearN = $Year;
        } else {
            $MesSiguiente = 1;
            $YearN = $Year + 1;
        }
        //TIME_FORMAT(GetTiempoByMes(t.PersonaId, '$Year-$Mes-01 00:00:00', '$YearN-$MesSiguiente-01 00:00:00'), '%H') as Tiempo
        return $this->db->objectBuilder()->rawQuery("
            select CONCAT(t.PrimerNombre, ' ', t.SegundoNombre, ' ', t.PrimerApellido, ' ', t.SegundoApellido) as Nombres, t.PersonaId, t.PrimerNombre, t.SegundoNombre, t.PrimerApellido, t.SegundoApellido, t.CargoId, cc.Cargo, t.Foto, 
            t.PersonaId,  
             sum(t.Atiempo) T_ATiempo, sum(t.Tarde) as T_Tarde, t.HoraInicio from (
            select J.PersonaId, J.PrimerNombre, J.SegundoNombre, J.PrimerApellido, J.SegundoApellido, J.CargoId, J.Foto, if(J.EstadoTurno = 'A tiempo', count(J.EstadoTurno), 0) Atiempo, if(J.EstadoTurno = 'Tarde', count(J.EstadoTurno), 0) Tarde, J.HoraInicio from 
            (
            select Jornada.*,
            if((time(Jornada.Fecha) <= addtime(time(Jornada.HoraInicio), '00:10:00') or (Jornada.PermisoId > 0 or Jornada.PermisoId is null or Jornada.HoraInicio is null)) , 'A Tiempo', 'Tarde') as EstadoTurno
            from 
            (
            SELECT p.*, c.Fecha, h.HoraInicio, c.PermisoId  from ct_persona as p
            left join ct_control as c on c.PersonaId = p.PersonaId and c.Fecha >= '$Year-$Mes-01 00:00:00' and c.Fecha <= '$YearN-$MesSiguiente-01 00:00:00' and c.Tipo = 'Entrada'
            left join ct_horario as h on h.TurnoId = p.TurnoId and DAYNAME(c.Fecha) COLLATE utf8_bin = h.DiaSemana and 
        
			(
				(time(c.Fecha) >= time(h.HoraInicio) and time(c.Fecha) <= time(h.HoraFin))
			)
            where p.TipoPersona <> 'Lider'
            ) as Jornada 
            ) as J 
            group by J.PersonaId, J.EstadoTurno
            ) 
            as t
            left join ct_cargo as cc on t.CargoId = cc.CargoId
            group by t.PersonaId order by t.PrimerNombre, t.PrimerApellido;");
    }

    public function GetCountLlegadasTarde($PersonaId, $Fecha) {
        //TIME_FORMAT(GetTiempoByMes(t.PersonaId, '$Year-$Mes-01 00:00:00', '$YearN-$MesSiguiente-01 00:00:00'), '%H') as Tiempo
        return $this->db->objectBuilder()->rawQuery("
            select CONCAT(t.PrimerNombre, ' ', t.SegundoNombre, ' ', t.PrimerApellido, ' ', t.SegundoApellido) as Nombres, t.PersonaId, t.PrimerNombre, t.SegundoNombre, t.PrimerApellido, t.SegundoApellido, t.CargoId, cc.Cargo, t.Foto, 
            t.PersonaId,  
             sum(t.Atiempo) T_ATiempo, sum(t.Tarde) as T_Tarde, t.HoraInicio from (
            select J.PersonaId, J.PrimerNombre, J.SegundoNombre, J.PrimerApellido, J.SegundoApellido, J.CargoId, J.Foto, if(J.EstadoTurno = 'A tiempo', count(J.EstadoTurno), 0) Atiempo, if(J.EstadoTurno = 'Tarde', count(J.EstadoTurno), 0) Tarde, J.HoraInicio from 
            (
            select Jornada.*,
            if((time(Jornada.Fecha) <= addtime(time(Jornada.HoraInicio), '00:10:00') or (Jornada.PermisoId > 0 or Jornada.PermisoId is null or Jornada.HoraInicio is null)) , 'A Tiempo', 'Tarde') as EstadoTurno
            from 
            (
            SELECT p.*, c.Fecha, h.HoraInicio, c.PermisoId  from ct_persona as p
            left join ct_control as c on c.PersonaId = p.PersonaId and MONTH(c.Fecha) = MONTH('$Fecha') and c.Tipo = 'Entrada'
            left join ct_horario as h on h.TurnoId = p.TurnoId and DAYNAME(c.Fecha) COLLATE utf8_bin = h.DiaSemana and 
        
			(
				(time(c.Fecha) >= time(h.HoraInicio) and time(c.Fecha) <= time(h.HoraFin))
			)
            where p.PersonaId = $PersonaId
            ) as Jornada 
            ) as J 
            group by J.PersonaId, J.EstadoTurno
            ) 
            as t
            left join ct_cargo as cc on t.CargoId = cc.CargoId
            group by t.PersonaId order by t.PrimerNombre, t.PrimerApellido;");
    }

    public function GetColaboradoresByLiderId_salidas($Year, $Mes, $LiderId) {
        if ($Mes < 12) {
            $MesSiguiente = $Mes + 1;
            $YearN = $Year;
        } else {
            $MesSiguiente = 1;
            $YearN = $Year + 1;
        }
        return $this->db->objectBuilder()->rawQuery("
        select CONCAT(t.PrimerNombre, ' ', t.SegundoNombre, ' ', t.PrimerApellido, ' ', t.SegundoApellido) as Nombres, t.PersonaId, t.PrimerNombre, t.SegundoNombre, t.PrimerApellido, t.SegundoApellido, t.CargoId, cc.Cargo, t.Foto, 
            t.PersonaId,  
             sum(t.Atiempo) T_ATiempo, sum(t.Tarde) as T_Tarde from (
            select J.PersonaId, J.PrimerNombre, J.SegundoNombre, J.PrimerApellido, J.SegundoApellido, J.CargoId, J.Foto, if(J.EstadoTurno = 'A tiempo', count(J.EstadoTurno), 0) Atiempo, if(J.EstadoTurno = 'Tarde' and J.PermisoBySede = 0, count(J.EstadoTurno), 0) Tarde from 
            (
            select Jornada.*,
            if((time(Jornada.Fecha) >= time(Jornada.HoraFin) or (Jornada.PermisoId > 0)) or Jornada.HoraFin is null , 'A Tiempo', 'Tarde') as EstadoTurno
            from 
            (
            SELECT p.*, c.Fecha, GetHoraFinByFecha(p.TurnoId, c.Fecha) as HoraFin, c.PermisoId, c.PermisoBySede  from ct_persona as p
            left join ct_control as c on c.PersonaId = p.PersonaId and c.Fecha >= '$Year-$Mes-01 00:00:00' and c.Fecha <= '$YearN-$MesSiguiente-01 00:00:00' and c.Tipo = 'Salida'
            inner join ct_persona as lider on p.JefeId = lider.PersonaId  
            where  p.Estado = 'Activo' and lider.UsuarioIntranetId = $LiderId
            ) as Jornada 
            ) as J 
            group by J.PersonaId, J.EstadoTurno
            ) 
            as t
            left join ct_cargo as cc on t.CargoId = cc.CargoId
            group by t.PersonaId order by t.PrimerNombre, t.PrimerApellido;");
    }

    public function GetColaboradoresByLider_salidas($Year, $Mes, $NombreUsuario) {
        if ($Mes < 12) {
            $MesSiguiente = $Mes + 1;
            $YearN = $Year;
        } else {
            $MesSiguiente = 1;
            $YearN = $Year + 1;
        }
        return $this->db->objectBuilder()->rawQuery("
        select CONCAT(t.PrimerNombre, ' ', t.SegundoNombre, ' ', t.PrimerApellido, ' ', t.SegundoApellido) as Nombres, t.PersonaId, t.PrimerNombre, t.SegundoNombre, t.PrimerApellido, t.SegundoApellido, t.CargoId, cc.Cargo, t.Foto, 
            t.PersonaId,  
             sum(t.Atiempo) T_ATiempo, sum(t.Tarde) as T_Tarde from (
            select J.PersonaId, J.PrimerNombre, J.SegundoNombre, J.PrimerApellido, J.SegundoApellido, J.CargoId, J.Foto, if(J.EstadoTurno = 'A tiempo', count(J.EstadoTurno), 0) Atiempo, if(J.EstadoTurno = 'Tarde' and J.PermisoBySede = 0, count(J.EstadoTurno), 0) Tarde from 
            (
            select Jornada.*,
            if((time(Jornada.Fecha) >= time(Jornada.HoraFin) or (Jornada.PermisoId > 0)) or Jornada.HoraFin is null , 'A Tiempo', 'Tarde') as EstadoTurno
            from 
            (
            SELECT p.*, c.Fecha, GetHoraFinByFecha(p.TurnoId, c.Fecha) as HoraFin, c.PermisoId, c.PermisoBySede  from ct_persona as p
            left join ct_control as c on c.PersonaId = p.PersonaId and c.Fecha >= '$Year-$Mes-01 00:00:00' and c.Fecha <= '$YearN-$MesSiguiente-01 00:00:00' and c.Tipo = 'Salida'
            inner join ct_persona as lider on p.JefeId = lider.PersonaId  
            where  p.Estado = 'Activo' and lider.Usuario = '$NombreUsuario'
            ) as Jornada 
            ) as J 
            group by J.PersonaId, J.EstadoTurno
            ) 
            as t
            left join ct_cargo as cc on t.CargoId = cc.CargoId
            group by t.PersonaId order by t.PrimerNombre, t.PrimerApellido;");
    }

    public function GetLideresAll_salidas($Year, $Mes) {
        if ($Mes < 12) {
            $MesSiguiente = $Mes + 1;
            $YearN = $Year;
        } else {
            $MesSiguiente = 1;
            $YearN = $Year + 1;
        }
        return $this->db->objectBuilder()->rawQuery("
            select CONCAT(t.PrimerNombre, ' ', t.SegundoNombre, ' ', t.PrimerApellido, ' ', t.SegundoApellido) as Nombres, t.PersonaId, t.PrimerNombre, t.SegundoNombre, t.PrimerApellido, t.SegundoApellido, t.CargoId, cc.Cargo, t.Foto, 
            t.PersonaId,  
             sum(t.Atiempo) T_ATiempo, sum(t.Tarde) as T_Tarde from (
            select J.PersonaId, J.PrimerNombre, J.SegundoNombre, J.PrimerApellido, J.SegundoApellido, J.CargoId, J.Foto, if(J.EstadoTurno = 'A tiempo', count(J.EstadoTurno), 0) Atiempo, if(J.EstadoTurno = 'Tarde' and J.PermisoBySede = 0, count(J.EstadoTurno), 0) Tarde from 
            (
            select Jornada.*,
            if((time(Jornada.Fecha) >= time(Jornada.HoraFin) or (Jornada.PermisoId > 0)) or Jornada.HoraFin is null , 'A Tiempo', 'Tarde') as EstadoTurno
            from 
            (
            SELECT p.*, c.Fecha, GetHoraFinByFecha(p.TurnoId, c.Fecha) as HoraFin, c.PermisoId, c.PermisoBySede  from ct_persona as p
            left join ct_control as c on c.PersonaId = p.PersonaId and c.Fecha >= '$Year-$Mes-01 00:00:00' and c.Fecha <= '$YearN-$MesSiguiente-01 00:00:00' and c.Tipo = 'Salida'
            where  p.Estado = 'Activo' and p.TipoPersona = 'Lider'
            ) as Jornada 
            ) as J 
            group by J.PersonaId, J.EstadoTurno
            ) 
            as t
            left join ct_cargo as cc on t.CargoId = cc.CargoId
            group by t.PersonaId order by t.PrimerNombre, t.PrimerApellido;");
    }

    public function GetColaboradoresAll_salidas($Year, $Mes) {
        if ($Mes < 12) {
            $MesSiguiente = $Mes + 1;
            $YearN = $Year;
        } else {
            $MesSiguiente = 1;
            $YearN = $Year + 1;
        }
        return $this->db->objectBuilder()->rawQuery("
            select CONCAT(t.PrimerNombre, ' ', t.SegundoNombre, ' ', t.PrimerApellido, ' ', t.SegundoApellido) as Nombres, t.PersonaId, t.PrimerNombre, t.SegundoNombre, t.PrimerApellido, t.SegundoApellido, t.CargoId, cc.Cargo, t.Foto, 
            t.PersonaId,  
             sum(t.Atiempo) T_ATiempo, sum(t.Tarde) as T_Tarde from (
            select J.PersonaId, J.PrimerNombre, J.SegundoNombre, J.PrimerApellido, J.SegundoApellido, J.CargoId, J.Foto, if(J.EstadoTurno = 'A tiempo', count(J.EstadoTurno), 0) Atiempo, if(J.EstadoTurno = 'Tarde', count(J.EstadoTurno), 0) Tarde from 
            (
            select Jornada.*,
            if((time(Jornada.Fecha) >= time(Jornada.HoraFin) or (Jornada.PermisoId > 0)) or Jornada.HoraFin is null , 'A Tiempo', 'Tarde') as EstadoTurno
            from 
            (
            SELECT p.*, c.Fecha, GetHoraFinByFecha(p.TurnoId, c.Fecha) as HoraFin, c.PermisoId  from ct_persona as p
            left join ct_control as c on c.PersonaId = p.PersonaId and c.Fecha >= '$Year-$Mes-01 00:00:00' and c.Fecha <= '$YearN-$MesSiguiente-01 00:00:00' and c.Tipo = 'Salida'
            ) as Jornada 
            ) as J 
            group by J.PersonaId, J.EstadoTurno
            ) 
            as t
            left join ct_cargo as cc on t.CargoId = cc.CargoId
            group by t.PersonaId order by t.PrimerNombre, t.PrimerApellido;");
    }

    // <editor-fold defaultstate="collapsed" desc="ES por persona">
    public function GetListado_ES($Year, $Mes, $PersonaId) {
        return $this->db->objectBuilder()->Query("call polivalente.ConsultaBiometrico($PersonaId, $Mes, $Year);");
//        $cadena = $this->generateRandomString() . $PersonaId;
//        echo $cadena;
//        $this->db->objectBuilder()->Query("SET @row_number$cadena = 0;");
//        $this->db->objectBuilder()->Query("SET @Day_no$cadena = 0;");
//        $this->db->objectBuilder()->Query("CREATE TEMPORARY TABLE ct_reporte$cadena (
//       rowNumber INT DEFAULT NULL
//     , Dia INT DEFAULT NULL
//     , ControlId INT DEFAULT NULL 
//     , PersonaId INT DEFAULT NULL 
//     , PermisoId INT DEFAULT NULL 
//     , PermisoBySede INT DEFAULT NULL  
//     , fecha char(10) DEFAULT NULL 
//     , Hora char(8) DEFAULT NULL 
//) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
//        $this->db->objectBuilder()->Query("insert into ct_reporte$cadena (rowNumber,Dia, ControlId,PersonaId, PermisoId, PermisoBySede,fecha,Hora)
//SELECT 
//    @row_number$cadena:=CASE WHEN @Day_no$cadena = day(fecha) THEN @row_number$cadena + 1  ELSE 1 END AS num,
//    @Day_no$cadena:=day(fecha) as Dia, ControlId,PersonaId, PermisoId, PermisoBySede,
//    substring(fecha,1,10) Fecha,
//	time_to_sec(fecha) Hora
//FROM
//    polivalente.ct_control
//WHERE 
//	PersonaId=$PersonaId and MONTH(fecha) = $Mes and YEAR(fecha) = $Year
//ORDER BY Dia;");
//        $this->db->objectBuilder()->Query("CREATE TEMPORARY TABLE ct_reporte1$cadena (
//      fecha char(10) DEFAULT NULL
//     , E1 char(8) DEFAULT NULL 
//     , E1_P int DEFAULT NULL 
//     , E1_PS int DEFAULT NULL  
//     , E1_C int DEFAULT NULL 
//     , S2 char(8) DEFAULT NULL  
//     , S2_P int DEFAULT NULL 
//     , S2_PS int DEFAULT NULL    
//     , S2_C int DEFAULT NULL
//     , E3 char(8) DEFAULT NULL   
//     , E3_P int DEFAULT NULL 
//     , E3_PS int DEFAULT NULL   
//     , E3_C int DEFAULT NULL
//     , S4 char(8) DEFAULT NULL   
//     , S4_P int DEFAULT NULL 
//     , S4_PS int DEFAULT NULL   
//     , S4_C int DEFAULT NULL  
//     , E5 char(8) DEFAULT NULL   
//     , E5_P int DEFAULT NULL 
//     , E5_PS int DEFAULT NULL   
//     , E5_C int DEFAULT NULL   
//     , S6 char(8) DEFAULT NULL   
//     , S6_P int DEFAULT NULL 
//     , S6_PS int DEFAULT NULL    
//     , S6_C int DEFAULT NULL   
//     , E7 char(8) DEFAULT NULL   
//     , E7_P int DEFAULT NULL 
//     , E7_PS int DEFAULT NULL   
//     , E7_C int DEFAULT NULL 
//     , S8 char(8) DEFAULT NULL   
//     , S8_P int DEFAULT NULL 
//     , S8_PS int DEFAULT NULL   
//     , S8_C int DEFAULT NULL 
//) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
//        $this->db->objectBuilder()->Query("
//insert into ct_reporte1$cadena (fecha,E1, E1_P, E1_PS, E1_C)
//select  fecha,Hora, PermisoId, PermisoBySede, ControlId FROM ct_reporte$cadena WHERE rowNumber=1 ;");
//        $this->db->objectBuilder()->Query("
//insert into ct_reporte1$cadena (fecha,S2, S2_P, S2_PS, S2_C)
//select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte$cadena WHERE rowNumber=2 ;");
//        $this->db->objectBuilder()->Query("
//insert into ct_reporte1$cadena (fecha,E3, E3_P, E3_PS, E3_C)
//select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte$cadena WHERE rowNumber=3 ;");
//        $this->db->objectBuilder()->Query("
//insert into ct_reporte1$cadena (fecha,S4, S4_P, S4_PS, S4_C)
//select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte$cadena WHERE rowNumber=4 ;");
//        $this->db->objectBuilder()->Query("
//insert into ct_reporte1$cadena (fecha,E5, E5_P, E5_PS, E5_C)
//select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte$cadena WHERE rowNumber=5 ;");
//        $this->db->objectBuilder()->Query("
//insert into ct_reporte1$cadena (fecha,S6, S6_P, S6_PS, S6_C)
//select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte$cadena WHERE rowNumber=6 ;");
//        $this->db->objectBuilder()->Query("
//insert into ct_reporte1$cadena (fecha,E7, E7_P, E7_PS, E7_C)
//select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte$cadena WHERE rowNumber=7 ;");
//        $this->db->objectBuilder()->Query("
//insert into ct_reporte1$cadena (fecha,S8, S8_P, S8_PS, S8_C)
//select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte$cadena WHERE rowNumber=8 ;");
//        $data = $this->db->objectBuilder()->rawQuery("
//             SELECT tabla$cadena.*,
//ADDTIME(ADDTIME(ifnull(timediff(tabla$cadena.S2, tabla$cadena.E1), '00:00:00') , ifnull(timediff(tabla$cadena.S4, tabla$cadena.E3), '00:00:00') ), ADDTIME(ifnull(timediff(tabla$cadena.S6, tabla$cadena.E5), '00:00:00') , ifnull(timediff(tabla$cadena.S8, tabla$cadena.E7), '00:00:00' ))) as Total
// FROM (select (ELT(WEEKDAY(DATE_FORMAT(t1.fecha, '%Y%m%d')) + 1, 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom')) AS DIA_SEMANA,
//        DATE_FORMAT(t1.fecha, '%Y-%m-%d') as fecha,	
//        SUBSTRING(SEC_TO_TIME(max(t0.E1)),1,8) E1,
//        max(t0.E1_P) E1_P, max(t0.E1_PS) E1_PS, GetDispositivoByControlId(max(E1_C)) E1_C, VerificarEstadoEntrada($PersonaId, SEC_TO_TIME(max(t0.E1)), t0.fecha) as E1_E,
//		SUBSTRING(SEC_TO_TIME(max(t0.S2)),1,8) S2,
//        max(t0.S2_P) S2_P, max(t0.S2_PS) S2_PS, GetDispositivoByControlId(max(S2_C)) S2_C, VerificarEstadoSalida($PersonaId, SEC_TO_TIME(max(t0.S2)), t0.fecha) as S2_E,
//		SUBSTRING(SEC_TO_TIME(max(t0.E3)),1,8) E3,
//        max(t0.E3_P) E3_P, max(t0.E3_PS) E3_PS, GetDispositivoByControlId(max(E3_C)) E3_C, VerificarEstadoEntrada($PersonaId, SEC_TO_TIME(max(t0.E3)), t0.fecha) as E3_E,
//		SUBSTRING(SEC_TO_TIME(max(t0.S4)),1,8) S4,
//       max(t0.S4_P) S4_P, max(t0.S4_PS) S4_PS, GetDispositivoByControlId(max(S4_C)) S4_C, VerificarEstadoSalida($PersonaId, SEC_TO_TIME(max(t0.S4)), t0.fecha) as S4_E,
//		SUBSTRING(SEC_TO_TIME(max(t0.E5)),1,8) E5,
//        max(t0.E5_P) E5_P, max(t0.E5_PS) E5_PS, GetDispositivoByControlId(max(E5_C)) E5_C, VerificarEstadoEntrada($PersonaId, SEC_TO_TIME(max(t0.E5)), t0.fecha) as E5_E,
//		SUBSTRING(SEC_TO_TIME(max(t0.S6)),1,8) S6,
//        max(t0.S6_P) S6_P, max(t0.S6_PS) S6_PS, GetDispositivoByControlId(max(S6_C)) S6_C, VerificarEstadoSalida($PersonaId, SEC_TO_TIME(max(t0.S6)), t0.fecha) as S6_E,
//		SUBSTRING(SEC_TO_TIME(max(t0.E7)),1,8) E7,
//        max(t0.E7_P) E7_P, max(t0.E7_PS) E7_PS, GetDispositivoByControlId(max(E7_C)) E7_C, VerificarEstadoEntrada($PersonaId, SEC_TO_TIME(max(t0.E7)), t0.fecha) as E7_E,
//		SUBSTRING(SEC_TO_TIME(max(t0.S8)),1,8) S8,
//        max(t0.S8_P) S8_P, max(t0.S8_PS) S8_PS, GetDispositivoByControlId(max(S8_C)) S8_C, VerificarEstadoSalida($PersonaId, SEC_TO_TIME(max(t0.S8)), t0.fecha) as S8_E
//        from ct_reporte1$cadena t0
//        right join ct_mes t1 on t0.fecha = t1.fecha
//        where t1.mes=$Mes and annio=$Year
//        group by t1.fecha) as tabla$cadena
//        order by tabla$cadena.fecha;");
////        echo print_r($data);
//        $this->db->objectBuilder()->Query("DROP TEMPORARY TABLE if exists ct_reporte$cadena;");
//        $this->db->objectBuilder()->Query("DROP TEMPORARY TABLE if exists ct_reporte1$cadena;");
//        return $data;
    }

// </editor-fold>
    public function GetESLideres($Year, $Mes, $Dia) {
        $listado = $this->db->objectBuilder()->Query("call polivalente.ListaLideresBiometrico($Dia, $Mes, $Year)");
        return $listado;
    }

    public function GetESColaboradores($Year, $Mes, $Dia) {
        $this->db->objectBuilder()->Query("DROP TEMPORARY TABLE if exists ct_reporte_col;");
        $this->db->objectBuilder()->Query("DROP TEMPORARY TABLE if exists ct_reporte_col1;");
        $this->db->objectBuilder()->Query("CREATE TEMPORARY TABLE ct_reporte_col (
              rowNumber INT DEFAULT NULL
            , Dia INT DEFAULT NULL 
            , PersonaId INT DEFAULT NULL
            , Usuario varchar(200) DEFAULT NULL
            , fecha varchar(10) DEFAULT NULL 
            , Hora varchar(8) DEFAULT NULL 
       );");
        $this->db->objectBuilder()->Query("insert into ct_reporte_col (rowNumber,Dia,PersonaId,Usuario,fecha,Hora)
       SELECT 
           @row_number:=CASE WHEN @Day_no = day(t0.fecha) THEN @row_number + 1  ELSE 1 END AS num,
           @Day_no:=day(t0.fecha) as Dia,
           t0.PersonaId,
           t0.CreatedBy,
           substring(t0.fecha,1,10) Fecha,
           time_to_sec(t0.fecha ) Hora
       FROM
           polivalente.ct_control t0
           inner join polivalente.ct_persona t1 on t0.PersonaId=t1.PersonaId
       WHERE 
                MONTH(t0.fecha)=$Mes and year(fecha)=$Year and t1.TipoPersona<>'Lider'#/substring(fecha,1,10) in ('2019-05-10','2019-05-13')/
       ORDER BY CreatedBy;");
        $this->db->objectBuilder()->Query("CREATE TEMPORARY TABLE ct_reporte_col1 (
             fecha varchar(10) DEFAULT NULL 
            , Usuario varchar(200) DEFAULT NULL
            , E1 varchar(8) DEFAULT NULL 
            , S2 varchar(8) DEFAULT NULL 
            , E3 varchar(8) DEFAULT NULL 
            , S4 varchar(8) DEFAULT NULL 
            , E5 varchar(8) DEFAULT NULL 
            , S6 varchar(8) DEFAULT NULL 
            , E7 varchar(8) DEFAULT NULL 
            , S8 varchar(8) DEFAULT NULL 

       );");
        $this->db->objectBuilder()->Query("
       insert into ct_reporte_col1 (Usuario,E1)
       select usuario, hora from ct_reporte_col where rownumber=1 and dia=$Dia ;");
        $this->db->objectBuilder()->Query("insert into ct_reporte_col1 (Usuario,S2)
       select usuario, hora from ct_reporte_col where rownumber=2 and dia=$Dia;");
        $this->db->objectBuilder()->Query("insert into ct_reporte_col1 (Usuario,E3)
       select usuario, hora from ct_reporte_col where rownumber=3 and dia=$Dia;");
        $this->db->objectBuilder()->Query("insert into ct_reporte_col1 (Usuario,S4)
       select usuario, hora from ct_reporte_col where rownumber=4 and dia=$Dia;");
        $this->db->objectBuilder()->Query("insert into ct_reporte_col1 (Usuario,E5)
       select usuario, hora from ct_reporte_col where rownumber=5 and dia=$Dia;");
        $this->db->objectBuilder()->Query("insert into ct_reporte_col1 (Usuario,S6)
       select usuario, hora from ct_reporte_col where rownumber=6 and dia=$Dia;");
        $this->db->objectBuilder()->Query("insert into ct_reporte_col1 (Usuario,E7)
       select usuario, hora from ct_reporte_col where rownumber=7 and dia=$Dia;");
        $this->db->objectBuilder()->Query("insert into ct_reporte_col1 (Usuario,S8)
       select usuario, hora from ct_reporte_col where rownumber=8 and dia=$Dia;");
        return $this->db->objectBuilder()->rawQuery("select tabla.usuario, tabla.E1, tabla.S2, tabla.E3, tabla.S4, tabla.E5, tabla.S6, tabla.E7, tabla.S8, 
        #timediff(tabla.S2, tabla.E1), timediff(tabla.S4, tabla.E3), timediff(tabla.S6, tabla.E5), timediff(tabla.S8, tabla.E7),
        ADDTIME(ADDTIME(ifnull(timediff(tabla.S2, tabla.E1), '00:00:00') , ifnull(timediff(tabla.S4, tabla.E3), '00:00:00') ), ADDTIME(ifnull(timediff(tabla.S6, tabla.E5), '00:00:00') , ifnull(timediff(tabla.S8, tabla.E7), '00:00:00' ))) as Total
        from (select usuario,	SUBSTRING(SEC_TO_TIME(sum(E1)),1,8) E1,
                                       SUBSTRING(SEC_TO_TIME(sum(S2)),1,8) S2,
                       SUBSTRING(SEC_TO_TIME(sum(E3)),1,8) E3,
                       SUBSTRING(SEC_TO_TIME(sum(S4)),1,8) S4,
                       SUBSTRING(SEC_TO_TIME(sum(E5)),1,8) E5,
                       SUBSTRING(SEC_TO_TIME(sum(S6)),1,8) S6,
                       SUBSTRING(SEC_TO_TIME(sum(E7)),1,8) E7,
                       SUBSTRING(SEC_TO_TIME(sum(S8)),1,8) S8
                       
                       

       from ct_reporte_col1
       group by usuario) as tabla;");
    }

    public function GetListado_Entradas($Year, $Mes, $PersonaId, $TipoTurno) {
        if ($Mes < 12) {
            $MesSiguiente = $Mes + 1;
            $YearN = $Year;
        } else {
            $MesSiguiente = 1;
            $YearN = $Year + 1;
        }
        return $this->db->objectBuilder()->rawQuery("
            Select * from (
            select Jornada.ControlId, Jornada.Fecha, Jornada.HoraInicio,
            if(time(Jornada.Fecha) <= addtime(time(Jornada.HoraInicio), '00:10:00') or (Jornada.PermisoId > 0 or Jornada.PermisoId is null or Jornada.HoraInicio is null) , 'A tiempo','Tarde') as EstadoTurno
            from 
            (

            SELECT p.*, c.ControlId, c.Fecha, h.HoraInicio, c.PermisoId from ct_control as c 
            inner join ct_persona as p on c.PersonaId = p.PersonaId
            left join ct_horario as h on p.TurnoId = h.TurnoId and DAYNAME(c.Fecha) COLLATE utf8_bin = h.DiaSemana and 
        
			(
				(time(c.Fecha) >= time(h.HoraInicio) and time(c.Fecha) <= time(h.HoraFin))
			
			)
            where p.PersonaId = $PersonaId and c.Fecha >= '$Year-$Mes-01 00:00:00' and c.Fecha <= '$YearN-$MesSiguiente-01 00:00:00' and c.Tipo = 'Entrada' 

            ) as Jornada) as t 
            where t.EstadoTurno = '$TipoTurno'");
    }

    public function GetListado_Salidas($Year, $Mes, $PersonaId, $TipoTurno) {
        if ($Mes < 12) {
            $MesSiguiente = $Mes + 1;
            $YearN = $Year;
        } else {
            $MesSiguiente = 1;
            $YearN = $Year + 1;
        }
        return $this->db->objectBuilder()->rawQuery("
            Select * from (
            select Jornada.ControlId, Jornada.Fecha, Jornada.HoraFin,
            if((time(Jornada.Fecha) >= time(Jornada.HoraFin) or (Jornada.PermisoId > 0)) or Jornada.HoraFin is null , 'A tiempo','Tarde') as EstadoTurno
            from 
            (
            SELECT p.*, c.ControlId, c.Fecha, GetHoraFinByFecha(p.TurnoId, c.Fecha) as HoraFin, c.PermisoId from ct_control as c 
            inner join ct_persona as p on c.PersonaId = p.PersonaId
            where p.PersonaId = $PersonaId and c.Fecha >= '$Year-$Mes-01 00:00:00' and c.Fecha <= '$YearN-$MesSiguiente-01 00:00:00' and c.Tipo = 'Salida'
            ) as Jornada) as t 
            where t.EstadoTurno = '$TipoTurno'");
    }

    public function GetPersonaByCodigo($Codigo) {
        return $this->db->objectBuilder()->rawQuery("Select p.TurnoId, p.JefeId, p.TipoPersona, p.PrimerNombre, p.PrimerApellido, p.Genero, 
        p.Estado, CONCAT(p.PrimerNombre, ' ', p.SegundoNombre, ' ', p.PrimerApellido, ' ', p.SegundoApellido) as Nombres,c.Cargo, p.Foto, p.PersonaId, p.FormatoCarnet, p.JefeId 
        from ct_persona as p 
        left join ct_cargo as c on p.CargoId = c.CargoId
        where CodigoTarjeta = '$Codigo'");
    }

    private function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}

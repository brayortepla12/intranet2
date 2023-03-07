DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetCountArticulosByTipo`(_PedidoAlmacenId int, _Tipo varchar(10)) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select count(*) from almacen_articulo as a 
            inner join almacen_itempedido as ip on ip.PedidoAlmacenId = _PedidoAlmacenId
            where a.ArticuloId = ip.ArticuloId and a.ArticuloPara = _Tipo);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetCountUsoPermiso`(PermisoId int) RETURNS int(11)
BEGIN
DECLARE dist INT;
  SET dist = (select if(p.IsGeneral is null or p.IsGeneral = 0, count(c.PermisoId), 0) as TotalUso from ct_permiso as p 
  inner join ct_control as c on c.PermisoId = PermisoId
  where p.PermisoId = PermisoId 
				);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetDateOrdenProduccion`(Fecha datetime, Intervalo varchar(10)) RETURNS datetime
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select  convert(if(Intervalo LIKE '%Dia%', date_add(Fecha, interval SUBSTRING_INDEX(Intervalo, ' ', 1) day), if(Intervalo LIKE '%Hora%', date_add(Fecha, interval SUBSTRING_INDEX(Intervalo, ' ', 1) hour), null)), datetime) as Fecha);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetDispositivoByControlId`(ControlId int) RETURNS varchar(15) CHARSET latin1
BEGIN
DECLARE dist varchar(15);
  SET dist = (SELECT Dispositivo from ct_control as c
            where c.ControlId = ControlId);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetHoraFinByFecha`(TurnoId int, Fecha datetime) RETURNS varchar(15) CHARSET latin1 COLLATE latin1_spanish_ci
BEGIN
DECLARE dist varchar(15);
set @previous_Id = NULL;
  SET dist = (
	select t.HoraFin from(select h.HorarioId, h.TurnoId, @previous_Id, h.HoraInicio, h.HoraFin, 
	  #case when time(h.HoraFin) <= time('2019-06-18 14:07:06') and h.TurnoId != @previous_Id then 'use me' else 'skip me' end as my_action,
	  @previous_Id := h.HorarioId
	from ct_horario as h where h.TurnoId = TurnoId and 
    (((DAYNAME(Fecha) COLLATE utf8_bin = h.DiaSemana and h.EsteTurnoVence = 0) or 
    DATE(Fecha) = DATE(h.DiaMes))) 
	order by time(h.HoraInicio)) as t
	where 
	(time(Fecha) >= time(t.HoraInicio) and time(Fecha) <= addtime(time(t.HoraFin), '01:20:00')) or time(t.HoraFin) <= time('06:00:00')
  );
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetHoraFinById`(HorarioId int) RETURNS varchar(15) CHARSET latin1
BEGIN
DECLARE dist varchar(15);
  SET dist = (select h.HoraFin from ct_horario as h 
				where h.HorarioId = HorarioId);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetHoraInicioById`(HorarioId int) RETURNS varchar(15) CHARSET latin1
BEGIN
DECLARE dist varchar(15);
  SET dist = (select h.HoraInicio from ct_horario as h 
				where h.HorarioId = HorarioId);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteid`(id int, Anno int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where (equipoid = id and (year(Fecha) = Anno - 1 or year(Fecha) = Anno )) and TipoServicio <> 'CALIBRACION');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidByHojaVidaId`(id int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where equipoid = id and EstadoReporte = 'Activo');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidByHojaVidaIdCalibracion`(id int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where equipoid = id and TipoServicio = 'CALIBRACION');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidSistemas`(id int, Anno int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from sistemas_reporte where (equipoid = id and year(Fecha) = Anno ) and TipoServicio <> 'CALIBRACION');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLoteRondaVerificacion`(TipoMedicamentoId int, Fecha date, MedicamentoId int, RondaVerificacionId int) RETURNS varchar(4) CHARSET latin1
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET @ContadorMedicamentos=0;
  SET dist = (
	select max(Consecutivo) from cm_consecutivo as c 
	where 
	(c.TipoMedicamentoId = TipoMedicamentoId 
	and c.Mes = Month(Fecha) and c.Anno = Year(Fecha) 
	and c.MedicamentoId = MedicamentoId and c.RondaVerificacionId = RondaVerificacionId)
  );
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLoteRondaVerificacion_lite`(TipoMedicamentoId int, Fecha date, MedicamentoId int, RondaVerificacionId int) RETURNS varchar(4) CHARSET latin1
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET @ContadorMedicamentos=0;
  SET dist = (
	select max(Consecutivo) from cm_consecutivo as c 
	where 
	(c.TipoMedicamentoId = TipoMedicamentoId 
	and c.Mes = Month(Fecha) and c.Anno = Year(Fecha)  and c.RondaVerificacionId = RondaVerificacionId)
  );
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetMaxPedidoByPlantilla`(UsuarioId int, ArticuloId int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(ip.CantidadSolicitada)   from almacen_itempedido as ip 
		inner join almacen_articulo as a on ip.ArticuloId = a.ArticuloId
		inner join almacen_pedidoalmacen as pa on pa.PedidoAlmacenId = ip.PedidoAlmacenId
		where pa.SolicitanteId= UsuarioId
		and ip.ArticuloId = ArticuloId
		group by a.Nombre);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetTotalSolicitadoByMes`(UsuarioId int, ArticuloId int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select sum(ip.CantidadSolicitada) from almacen_itempedido as ip 
	inner join almacen_articulo as a on ip.ArticuloId = a.ArticuloId
	inner join almacen_pedidoalmacen as pa on pa.PedidoAlmacenId = ip.PedidoAlmacenId
	where pa.SolicitanteId= UsuarioId and pa.Estado <> 'Rechazar'  
	and MONTH(pa.FechaSolicitud) = MONTH(now())
	and ip.CantidadSolicitada > 0
	and ip.ArticuloId = ArticuloId
	group by a.Nombre);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `HaveParto`(MaternaId int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (SELECT if(e.TipoEvento = 'Parto', 1, if(e.TipoEvento = 'Parto Externo',2,0)) FROM polivalente.tm_materna as m
	inner join polivalente.tm_evento as e on e.MaternaId = m.MaternaId
	where (e.TipoEvento = 'Parto' or e.TipoEvento = 'Parto Externo') and m.MaternaId= MaternaId order by e.EventoId DESC limit 1) ;
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `VerificarEstadoEntrada`(PersonaId int, Hora time, Fecha date) RETURNS varchar(15) CHARSET latin1 COLLATE latin1_spanish_ci
BEGIN
DECLARE dist varchar(15);
  SET dist = (select 
        (if(time(Hora) <= addtime(time(h.HoraInicio), '00:10:00') or h.HoraInicio is null, 'A Tiempo','Tarde')) as EstadoTurno
        from 
        (
        SELECT p.*
        from ct_persona as p where p.PersonaId = PersonaId
        ) as Jornada
        left join ct_horario as h on h.TurnoId = Jornada.TurnoId where (DAYNAME(Fecha) = h.DiaSemana) and 
        (
        (time(Hora) >= time(h.HoraInicio) and time(Hora) <= time(h.HoraFin) and h.EsteTurnoVence = 0)
        ) or (h.EsteTurnoVence = 1 and date(Fecha) = date(h.DiaMes) and time(Hora) >= time(h.HoraInicio) and time(Hora) <= time(h.HoraFin))
        );
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `VerificarEstadoSalida`(PersonaId int, Hora time, Fecha date) RETURNS varchar(15) CHARSET latin1 COLLATE latin1_spanish_ci
BEGIN
DECLARE dist varchar(15);
  SET dist = (SELECT if(time(GetHoraFinByFecha(p.TurnoId, concat(convert(Fecha, char(10)), ' ', convert(Hora, char(10))))) <= Hora or Hora is null or p.TurnoId is null, 'A Tiempo','Tarde') as EstadoTurno from ct_persona as p 
            where p.PersonaId = PersonaId limit 1);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `VerificarJornadaByPersonaWithDJ`(PersonaId int, Fecha datetime) RETURNS varchar(20) CHARSET latin1
    DETERMINISTIC
BEGIN 
  DECLARE dist varchar(20);
  SET dist = ((SELECT if( DAYNAME(Fecha) = 'Saturday' or DAYNAME(Fecha) = 'Sunday' , 
	'Jornada 1'
  , if((time(Fecha) >= time(j.J1E) and time(Fecha) <= time(j.J1S)) or ( time(Fecha) < time(j.J1E)), 'Jornada 1', 
		if((time(Fecha) >= time(j.J2E) and time(Fecha) <= time(j.J2S)) or ( time(Fecha) > time(j.J1S) and time(Fecha) < time(j.J2E)), 'Jornada 2', 
		'No Tiene')))
		 as Jornada  FROM ct_persona as j where j.PersonaId = PersonaId));
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `VerificarJornadaByPersonaWithoutDJ`(PersonaId int, Fecha datetime) RETURNS varchar(20) CHARSET latin1
    DETERMINISTIC
BEGIN 
  DECLARE dist varchar(20);
  SET dist = (SELECT 
'Jornada 1' as Jornada  FROM ct_persona as j where j.PersonaId = PersonaId);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetCountArticulosByTipo`(_PedidoAlmacenId int, _Tipo varchar(10)) RETURNS int
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
CREATE DEFINER=`ospino`@`%` FUNCTION `GetDateOrdenProduccion`(Fecha datetime, Intervalo varchar(10)) RETURNS datetime
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select  convert(if(Intervalo LIKE '%Dia%', date_add(Fecha, interval SUBSTRING_INDEX(Intervalo, ' ', 1) day), if(Intervalo LIKE '%Hora%', date_add(Fecha, interval SUBSTRING_INDEX(Intervalo, ' ', 1) hour), null)), datetime) as Fecha);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetDispositivoByControlId`(ControlId int) RETURNS varchar(15) CHARSET latin1 COLLATE latin1_spanish_ci
BEGIN
DECLARE dist varchar(15);
  SET dist = (SELECT Dispositivo from ct_control as c
            where c.ControlId = ControlId);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetHoraFinByFecha`(TurnoId int, Fecha datetime) RETURNS varchar(15) CHARSET utf8mb4 COLLATE utf8mb4_spanish_ci
BEGIN
DECLARE dist varchar(15);
set @previous_Id = NULL;
  SET dist = (
	select t.HoraFin from(select h.HorarioId, h.TurnoId, @previous_Id, h.HoraInicio, h.HoraFin, 
	  
	  @previous_Id := h.HorarioId
	from ct_horario as h where h.TurnoId = TurnoId and 
    (((DAYNAME(Fecha) = h.DiaSemana and h.EsteTurnoVence = 0) or 
    DATE(Fecha) = DATE(h.DiaMes))) 
	order by time(h.HoraInicio)) as t
	where 
	((time(Fecha) >= time(t.HoraInicio) or time(Fecha) >= subtime(time(t.HoraInicio), '01:30:00')) 
    and time(Fecha) <= addtime(time(t.HoraFin), '01:20:00')) or time(t.HoraFin) <= time('06:00:00') limit 1
  );
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetHoraFinById`(HorarioId int) RETURNS varchar(15) CHARSET latin1 COLLATE latin1_spanish_ci
BEGIN
DECLARE dist varchar(15);
  SET dist = (select h.HoraFin from ct_horario as h 
				where h.HorarioId = HorarioId);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetHoraInicioById`(HorarioId int) RETURNS varchar(15) CHARSET latin1 COLLATE latin1_spanish_ci
BEGIN
DECLARE dist varchar(15);
  SET dist = (select h.HoraInicio from ct_horario as h 
				where h.HorarioId = HorarioId);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteid`(id int, Anno int) RETURNS int
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where (equipoid = id and (year(Fecha) = Anno - 1 or year(Fecha) = Anno )) and TipoServicio <> 'CALIBRACION');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidByHojaVidaId`(id int) RETURNS int
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where equipoid = id and EstadoReporte = 'Activo');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidByHojaVidaIdCalibracion`(id int) RETURNS int
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where equipoid = id and TipoServicio = 'CALIBRACION');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidSistemas`(id int, Anno int) RETURNS int
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
CREATE DEFINER=`ospino`@`%` FUNCTION `GetMaxPedidoByPlantilla`(UsuarioId int, ArticuloId int) RETURNS int
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
CREATE DEFINER=`ospino`@`%` FUNCTION `GetPosEsperaTel`(_SolicitudId int) RETURNS int
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  set @rownum=0;
  SET dist = (SELECT t.Contador FROM (SELECT @rownum := @rownum + 1 as Contador, s.SolicitudId FROM polivalente.tel_solicitud as s where s.Estado = 'Activo') as t WHERE t.SolicitudId = _SolicitudId);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetReporteIdByDetalleCronograma`(_DetalleCronograma int, _HojaVidaId int) RETURNS int
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select rdc.ReporteId from sistemas_reportedcronograma as rdc where rdc.DetalleCronogramaId = _DetalleCronograma and rdc.HojaVidaId = _HojaVidaId);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetTiempoByMes`(PersonaId int, Desde datetime, Hasta datetime) RETURNS time
    DETERMINISTIC
BEGIN 
  DECLARE dist time;
  SET dist = (

select  SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(_data.FechaSalida,_data.FechaEntrada)))) from
(
SELECT current_row._row row_entrada, current_row.ControlId as 'Entrada', current_row.Fecha FechaEntrada,  previous_row._row row_salida , previous_row.ControlId as 'Salida', previous_row.Fecha as FechaSalida
FROM (
  SELECT @rownum := @rownum + 1 as _row, c.* 
  FROM ct_control as c, (SELECT @rownum:=0) r
  where c.Fecha >= Desde and c.Fecha <= Hasta and c.PersonaId = PersonaId and c.Tipo = 'Entrada' 
  ORDER BY c.Fecha, c.ControlId
) as current_row
LEFT JOIN (
  SELECT @rownum2 := @rownum2 + 1 as _row, cc.* 
  FROM ct_control as cc , (SELECT @rownum2:=0) r
  where cc.Fecha >= Desde and cc.Fecha <= Hasta and cc.PersonaId = PersonaId  and cc.Tipo = 'Salida'
  ORDER BY cc.Fecha, cc.ControlId
) as previous_row ON
  (current_row.ControlId < previous_row.ControlId) AND (current_row._row > previous_row._row - 1)and previous_row.Fecha > current_row.Fecha and (SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(previous_row.Fecha, current_row.Fecha)))) <= time('08:00:00')  
  
  ) 
  as _data where _data.FechaSalida is not null and _data.FechaEntrada is not null
);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetTotalSolicitadoByMes`(UsuarioId int, ArticuloId int) RETURNS int
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
CREATE DEFINER=`ospino`@`%` FUNCTION `HaveParto`(MaternaId int) RETURNS int
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
CREATE DEFINER=`ospino`@`%` FUNCTION `IsCumpleano`(_PersonaId int) RETURNS int
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select ifnull(month(now()) = month(p.FechaNacimiento) and dayofmonth(now())  = dayofmonth(p.FechaNacimiento), 0) from ct_Persona as p where p.PersonaId = _PersonaId);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `PC_GetEventoSolByProcesoId`(_PROCESOID int) RETURNS int
    DETERMINISTIC
BEGIN 
  DECLARE dist INT;
	SET dist = (
		SELECT t.* FROM (
			SELECT bio.ReporteId FROM biomedicos_eventosolicitud as bio WHERE bio.ProcesoId = _PROCESOID
			UNION ALL
			SELECT sis.ReporteId FROM sistemas_eventosolicitud as sis WHERE sis.ProcesoId = _PROCESOID
			UNION ALL
			SELECT pol.ReporteId FROM pol_eventosolicitud as pol WHERE pol.ProcesoId = _PROCESOID
        ) AS t LIMIT 1
    );
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `PC_GetNombreVerificadorActual`(_ProtocoloId INT, _OrdenEnCurso INT) RETURNS varchar(50) CHARSET latin1 COLLATE latin1_spanish_ci
BEGIN
	DECLARE valor varchar(50);
	SET @RowVerificador = -1;
	SET valor = (SELECT 
    tabla.Nombre
FROM
    (SELECT 
        T.*, @RowVerificador:=@RowVerificador + 1 AS OrdenR
    FROM
        (SELECT 
        CONCAT(_per.PrimerNombre, ' ', _per.PrimerApellido) AS Nombre
    FROM
        ct_persona AS _per
    STRAIGHT_JOIN pc_verificador AS _v ON _per.UsuarioIntranetId = _v.UsuarioId AND _v.Estado = 'Activo'
    STRAIGHT_JOIN pc_flujotrabajo AS _ft ON _ft.FlujoTrabajoId = _v.FlujoTrabajoId AND _ft.Estado = 'Activo'
    WHERE
        _ft.ProtocoloId = _ProtocoloId
            AND _ft.Estado = 'Activo'
    ORDER BY _ft.Orden) AS T) AS tabla
WHERE
    tabla.OrdenR = _OrdenEnCurso
 );
	RETURN valor;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `PC_GetProtocoloIdByFlujoTrabajoId`(_FLUJOTRABAJOID INT) RETURNS int
BEGIN
	DECLARE dist INT;
    SET dist = (SELECT ft.ProtocoloId FROM pc_flujotrabajo as ft where ft.FlujoTrabajoId = _FLUJOTRABAJOID);
RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `PC_GetTipoTableByReporteId`(_REPORTEID int) RETURNS varchar(15) CHARSET latin1 COLLATE latin1_spanish_ci
    DETERMINISTIC
BEGIN 
  DECLARE dist varchar(15);
	SET dist = (
		SELECT 'biomedicos' as Prefijo FROM biomedicos_eventosolicitud WHERE ReporteId = _REPORTEID
		UNION ALL
		SELECT 'sistemas' as Prefijo  FROM sistemas_eventosolicitud WHERE ReporteId = _REPORTEID
		UNION ALL
		SELECT 'pol' as Prefijo  FROM pol_eventosolicitud WHERE ReporteId = _REPORTEID
    );
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `PC_VerificarSiTurno`(_ORDEN int, _USUARIOID int, _PROTOCOLOID int) RETURNS int
BEGIN
	DECLARE dist int;
    SET @Row_number_FT = -1;
    SET dist = (
	SELECT If(Tabla.UsuarioId, 1, 0) FROM (
	SELECT T.FlujoTrabajoId, T.UsuarioId, @Row_number_FT := @Row_number_FT + 1 as Orden FROM (
	SELECT _ft.FlujoTrabajoId, _v.UsuarioId FROM ct_persona as _per
	STRAIGHT_JOIN pc_verificador as _v on _per.UsuarioIntranetId = _v.UsuarioId
	STRAIGHT_JOIN pc_flujotrabajo as _ft  on _ft.FlujoTrabajoId = _v.FlujoTrabajoId 
	WHERE _ft.ProtocoloId = _PROTOCOLOID AND _ft.Estado = 'Activo' AND _v.Estado = 'Activo' order by _ft.Orden ) as T ) AS Tabla
	WHERE Tabla.Orden = _ORDEN AND Tabla.UsuarioId = _USUARIOID);
RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `VerificarEstadoEntrada`(PersonaId int, Hora time, Fecha date) RETURNS varchar(15) CHARSET utf8mb4 COLLATE utf8mb4_spanish_ci
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
        ) or (h.EsteTurnoVence = 1 and date(Fecha) = date(h.DiaMes) and time(Hora) >= time(h.HoraInicio) and time(Hora) <= time(h.HoraFin)) limit 1
        );
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `VerificarEstadoSalida`(PersonaId int, Hora time, Fecha date) RETURNS varchar(15) CHARSET utf8mb4 COLLATE utf8mb4_spanish_ci
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
if((time(Fecha) >= time(j.J1E) and time(Fecha) <= time(j.J1S)) or (time(Fecha) < time(j.J1E)), 'Jornada 1', 
'No Tiene') as Jornada  FROM ct_persona as j where j.PersonaId = PersonaId);
  RETURN dist;
END$$
DELIMITER ;

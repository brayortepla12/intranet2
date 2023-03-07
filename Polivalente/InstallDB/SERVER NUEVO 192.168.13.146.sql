DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetHoraFinByFecha`(TurnoId int, Fecha datetime) RETURNS varchar(15) CHARSET utf8mb4 COLLATE utf8mb4_spanish_ci
BEGIN
DECLARE dist varchar(15);
set @previous_Id = NULL;
  SET dist = (
	select t.HoraFin from(select h.HorarioId, h.TurnoId, @previous_Id, h.HoraInicio, h.HoraFin, 
	  
	  @previous_Id := h.HorarioId
	from ct_horario as h where h.TurnoId = TurnoId and 
    (((DAYNAME(Fecha)  collate utf8mb4_spanish_ci = h.DiaSemana and h.EsteTurnoVence = 0) or 
    DATE(Fecha)  collate utf8mb4_spanish_ci = DATE(h.DiaMes))) 
	order by time(h.HoraInicio)) as t
	where 
	((time(Fecha) >= time(t.HoraInicio) or time(Fecha) >= subtime(time(t.HoraInicio), '01:30:00')) 
    and time(Fecha) <= addtime(time(t.HoraFin), '01:20:00')) or time(t.HoraFin) <= time('06:00:00') limit 1
  );
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
        left join ct_horario as h on h.TurnoId = Jornada.TurnoId where (DAYNAME(Fecha) collate utf8mb4_spanish_ci = h.DiaSemana) and 
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
  SET dist = (SELECT if(time(GetHoraFinByFecha(p.TurnoId, concat(convert(Fecha, char(10))   collate utf8mb4_spanish_ci, ' ', convert(Hora, char(10))   collate utf8mb4_spanish_ci))) <= Hora or Hora is null or p.TurnoId is null, 'A Tiempo','Tarde') as EstadoTurno from ct_persona as p 
            where p.PersonaId = PersonaId limit 1);
  RETURN dist;
END$$
DELIMITER ;

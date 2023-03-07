delimiter $$

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
	((time(Fecha) >= time(t.HoraInicio) or time(Fecha) >= subtime(time(t.HoraInicio), '01:30:00')) 
    and time(Fecha) <= addtime(time(t.HoraFin), '01:20:00')) or time(t.HoraFin) <= time('06:00:00')
  );
  RETURN dist;
END$$


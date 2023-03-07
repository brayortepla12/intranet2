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
            where p.PersonaId = 1483 and c.Fecha >= '2019-05-01 00:00:00' and c.Fecha <= '2019-06-01 00:00:00' and c.Tipo = 'Entrada' 

            ) as Jornada) as t 
            where t.EstadoTurno = 'Entrada'
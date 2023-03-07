select 
        
        (if(time('2019-05-31 14:11:00') <= addtime(time(h.HoraInicio), '00:10:00') or h.HoraInicio is null, 'A Tiempo','Tarde')) as EstadoTurno
        from 
        (
        
        SELECT p.*
        from ct_persona as p where p.PersonaId = 1483 and p.J1E is not null
        ) as Jornada
        left join ct_horario as h on h.TurnoId = Jornada.TurnoId and DAYNAME('2019-05-31 14:11:00') = h.DiaSemana and 
        
        (
			(time('2019-05-31 14:11:00') >= time(h.HoraInicio) and time('2019-05-31 14:11:00') <= time(h.HoraFin))
        
        )
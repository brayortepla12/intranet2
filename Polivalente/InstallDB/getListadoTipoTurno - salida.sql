Select * from (
            select Jornada.ControlId, Jornada.Fecha, Jornada.HoraFin,
            if((time(Jornada.Fecha) >= time(Jornada.HoraFin) or (Jornada.PermisoId > 0)) or Jornada.HoraFin is null , 'A tiempo','Tarde') as EstadoTurno
            from 
            (
            SELECT p.*, c.ControlId, c.Fecha, GetHoraFinByFecha(p.TurnoId, c.Fecha) as HoraFin, c.PermisoId from ct_control as c 
            inner join ct_persona as p on c.PersonaId = p.PersonaId
            where p.PersonaId = 1483 and c.Fecha >= '2019-05-01 00:00:00' and c.Fecha <= '2019-06-01 00:00:00' and c.Tipo = 'Salida'

            ) as Jornada) as t 
            
      
            where t.EstadoTurno = '$TipoTurno'
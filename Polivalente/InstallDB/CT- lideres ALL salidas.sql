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
            where  p.Estado = 'Activo' and p.TipoPersona = 'Lider'
            ) as Jornada 
            ) as J 
            group by J.PersonaId, J.EstadoTurno
            ) 
            as t
            left join ct_cargo as cc on t.CargoId = cc.CargoId
            group by t.PersonaId order by t.PrimerNombre, t.PrimerApellido;
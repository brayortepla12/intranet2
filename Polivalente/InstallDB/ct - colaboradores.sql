select CONCAT(t.PrimerNombre, ' ', t.SegundoNombre, ' ', t.PrimerApellido, ' ', t.SegundoApellido) as Nombres, t.PersonaId, t.PrimerNombre, t.SegundoNombre, t.PrimerApellido, t.SegundoApellido, t.CargoId, cc.Cargo, t.Foto, 
            t.PersonaId,  
             sum(t.Atiempo) T_ATiempo, sum(t.Tarde) as T_Tarde from (

            select J.PersonaId, J.PrimerNombre, J.SegundoNombre, J.PrimerApellido, J.SegundoApellido, J.CargoId, J.Foto, if(J.EstadoTurno = 'A tiempo', count(J.EstadoTurno), 0) Atiempo, if(J.EstadoTurno = 'Tarde', count(J.EstadoTurno), 0) Tarde from 
            (
            select Jornada.*,
            if((time(Jornada.Fecha) <= addtime(time(Jornada.HoraInicio), '00:10:00') and (Jornada.PermisoId = 0 or Jornada.PermisoId is null)) or Jornada.HoraInicio is null , 'A Tiempo', 'Tarde') as EstadoTurno
            from 
            (

            SELECT p.*, c.Fecha, h.HoraInicio, c.PermisoId  from ct_persona as p
            left join ct_control as c on c.PersonaId = p.PersonaId and c.Fecha >= '2019-05-01 00:00:00' and c.Fecha <= '2019-06-01 00:00:00' and c.Tipo = 'Entrada'
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
            group by t.PersonaId order by t.PrimerNombre, t.PrimerApellido;
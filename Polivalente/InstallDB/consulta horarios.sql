SELECT c.*, if(((time(c.Fecha) >= subtime(time(h.Inicio), '01:00:00') and time(c.Fecha) <= addtime(time(h.Inicio), '00:10:00')))  , 'A tiempo', 'Tarde') as EstadoTurno, 
time(c.Fecha), time(h.Inicio), subtime(time(h.Inicio), '01:00:00') FROM polivalente.ct_turno as ct 
inner join ct_persona as p on p.TurnoId = ct.TurnoId
inner join ct_horario as h on h.TurnoId = ct.TurnoId  and time(h.Inicio) >= time('06:00') and time(h.Fin) <= time('12:00') #mañana
inner join ct_control as c on c.PersonaId = p.PersonaId
where p.PersonaId = 1861 and c.Tipo = 'Entrada' and 
((time(c.Fecha) >= time('06:00') and time(c.Fecha) <= time('12:00'))) and (c.Fecha >= '2019-05-01 00:00:00' and c.Fecha <= '2019-06-01 00:00:00')  order by c.Fecha;
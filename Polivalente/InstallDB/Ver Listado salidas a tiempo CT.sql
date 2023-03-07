Select * from (
select Jornada.ControlId, Jornada.Fecha,Jornada.J1S, Jornada.J1E,Jornada.J2S,
if(time(Jornada.Fecha) >= time(Jornada.J1S) and time(Jornada.Fecha) <= time(Jornada.J2E) and Jornada.TJornada = 'Jornada 1', 'A Tiempo', 
if((time(Jornada.Fecha) >= time(Jornada.J1S) and time(Jornada.Fecha) < time(Jornada.J2E)) or (time(Jornada.Fecha) >= time(Jornada.J2S)) and Jornada.TJornada = 'Jornada 2', 'A Tiempo','Tarde')) as EstadoTurno
from 
(

SELECT p.*, c.ControlId, c.Fecha, IF(p.HasDobleJornada,VerificarJornadaByPersonaWithDJ(p.PersonaId, c.Fecha), VerificarJornadaByPersonaWithoutDJ(p.PersonaId, c.Fecha)) as TJornada from ct_control as c 
inner join ct_persona as p on c.PersonaId = p.PersonaId
where p.PersonaId = 55 and c.Fecha >= '2019-05-01 00:00:00' and c.Fecha <= '2019-06-01 00:00:00' and c.Tipo = 'Salida' and (
IF(p.HasDobleJornada,VerificarJornadaByPersonaWithDJ(p.PersonaId, c.Fecha), 'Jornada 1') <> 'No Tiene')

) as Jornada) as t where t.EstadoTurno = 'Tarde'
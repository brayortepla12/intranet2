Select * from (

select Jornada.ControlId, Jornada.Fecha,Jornada.J1E,Jornada.J2E,
if(time(Jornada.Fecha) <= addtime(time(Jornada.J1E), "00:10:00") and Jornada.TJornada = 'Jornada 1', 'A Tiempo', 
if(time(Jornada.Fecha) <= addtime(time(Jornada.J2E), "00:10:00") and Jornada.TJornada = 'Jornada 2', 'A tiempo','Tarde')) as EstadoTurno
from 
(

SELECT p.*, c.ControlId, c.Fecha, IF(p.HasDobleJornada,VerificarJornadaByPersonaWithDJ(p.PersonaId, c.Fecha), VerificarJornadaByPersonaWithoutDJ(p.PersonaId, c.Fecha)) as TJornada from ct_control as c 
inner join ct_persona as p on c.PersonaId = p.PersonaId
where p.PersonaId = 55 and c.Fecha >= '2019-05-01 00:00:00' and c.Fecha <= '2019-06-01 00:00:00' and c.Tipo = 'Entrada' and (
IF(p.HasDobleJornada,VerificarJornadaByPersonaWithDJ(p.PersonaId, c.Fecha), 'Jornada 1') <> 'No Tiene')

) as Jornada) as t where t.EstadoTurno = 'A Tiempo'
select t.PersonaId, t.PrimerNombre, t.SegundoNombre, t.PrimerApellido, t.SegundoApellido, t.CargoId, cc.Cargo, t.Foto, t.J1E, t.J1S, 
if(t.J1E is not null and t.J1S is not null, sum(t.Atiempo), 0) T_ATiempo,  if(t.J1E is not null and t.J1S is not null, sum(t.Tarde), 0) as T_Tarde from (

select J.PersonaId, J.PrimerNombre, J.SegundoNombre, J.PrimerApellido, J.SegundoApellido, J.CargoId, J.Foto, J.J1E, J.J1S, if(J.EstadoTurno = 'A tiempo', count(J.EstadoTurno), 0) Atiempo, if(J.EstadoTurno = 'Tarde', count(J.EstadoTurno), 0) Tarde from (select Jornada.*,
if(time(Jornada.Fecha) >= time(Jornada.J1S) and time(Jornada.Fecha) <= time(Jornada.J2E) and Jornada.TJornada = 'Jornada 1', 'A Tiempo', 
if(time(Jornada.Fecha) >= time(Jornada.J2S) and Jornada.TJornada = 'Jornada 2', 'A tiempo','Tarde')) as EstadoTurno
from 
(

SELECT p.*, c.Fecha, IF(p.HasDobleJornada,VerificarJornadaByPersonaWithDJ(p.PersonaId, c.Fecha), VerificarJornadaByPersonaWithoutDJ(p.PersonaId, c.Fecha)) as TJornada from ct_control as c 
inner join ct_persona as p on c.PersonaId = p.PersonaId
where p.TipoPersona = 'Lider' and c.Fecha >= '2019-05-01 00:00:00' and c.Fecha <= '2019-06-01 00:00:00' and c.Tipo = 'Salida' and (
IF(p.HasDobleJornada,VerificarJornadaByPersonaWithDJ(p.PersonaId, c.Fecha), 'Jornada 1') <> 'No Tiene')




) as Jornada) as J group by J.PersonaId, J.EstadoTurno

) 
as t
left join ct_cargo as cc on t.CargoId = cc.CargoId
group by t.PersonaId;
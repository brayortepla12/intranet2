
USE `polivalente`;
DROP function IF EXISTS `VerificarEstadoEntrada`;

DELIMITER $$
USE `polivalente`$$
CREATE FUNCTION `VerificarEstadoEntrada` (PersonaId int, Hora time, Fecha date)
RETURNS varchar(15)
BEGIN
DECLARE dist varchar(15);
  SET dist = (select 
        (if(time(Hora) <= addtime(time(h.HoraInicio), '00:10:00') or h.HoraInicio is null, 'A Tiempo','Tarde')) as EstadoTurno
        from 
        (
        SELECT p.*
        from ct_persona as p where p.PersonaId = PersonaId
        ) as Jornada
        left join ct_horario as h on h.TurnoId = Jornada.TurnoId and DAYNAME(Fecha) = h.DiaSemana and 
        (
        (time(Hora) >= time(h.HoraInicio) and time(Hora) <= time(h.HoraFin))
        ));
  RETURN dist;
END$$

DELIMITER ;

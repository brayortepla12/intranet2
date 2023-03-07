
USE `polivalente`;
DROP function IF EXISTS `VerificarEstadoSalida`;

DELIMITER $$
USE `polivalente`$$
CREATE FUNCTION `VerificarEstadoSalida` (PersonaId int, Hora time, Fecha date)
RETURNS varchar(15)
BEGIN
DECLARE dist varchar(15);
  SET dist = (SELECT if(GetHoraFinByFecha(p.TurnoId, '2019-06-06 14:05:41') is null or time(GetHoraFinByFecha(p.TurnoId, concat(convert(Fecha, char(10)), ' ', convert(Hora, char(10))))) <= Hora or Hora is null or p.TurnoId is null, 'A Tiempo','Tarde') as EstadoTurno from ct_persona as p 
            where p.PersonaId = PersonaId);
  RETURN dist;
END$$

DELIMITER ; 
 
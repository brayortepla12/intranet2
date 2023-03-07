DELIMITER $$

CREATE DEFINER=`ospino`@`%` FUNCTION `GetHoraFinById`(HorarioId int) RETURNS varchar(15)
BEGIN
DECLARE dist varchar(15);
  SET dist = (select h.HoraFin from ct_horario as h 
				where h.HorarioId = HorarioId);
  RETURN dist;
END$$


DELIMITER ;

DELIMITER $$

CREATE DEFINER=`ospino`@`%` FUNCTION `GetHoraInicioById`(HorarioId int) RETURNS varchar(15)
BEGIN
DECLARE dist varchar(15);
  SET dist = (select h.HoraInicio from ct_horario as h 
				where h.HorarioId = HorarioId);
  RETURN dist;
END$$


DELIMITER ;
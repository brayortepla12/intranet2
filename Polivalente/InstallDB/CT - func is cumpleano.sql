
DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `IsCumpleano`(_PersonaId int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select ifnull(month(now()) = month(p.FechaNacimiento) and dayofmonth(now())  = dayofmonth(p.FechaNacimiento), 0) from ct_Persona as p where p.PersonaId = _PersonaId);
  RETURN dist;
END$$
DELIMITER ;

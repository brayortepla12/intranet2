
USE `polivalente`;
DROP function IF EXISTS `GetDispositivoByControlId`;

DELIMITER $$
USE `polivalente`$$
CREATE FUNCTION `GetDispositivoByControlId` (ControlId int)
RETURNS varchar(15)
BEGIN
DECLARE dist varchar(15);
  SET dist = (SELECT Dispositivo from ct_control as c
            where c.ControlId = ControlId);
  RETURN dist;
END$$

DELIMITER ; 
 
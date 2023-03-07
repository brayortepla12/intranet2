DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetCountUsoPermiso`(PermisoId int) RETURNS INT
BEGIN
DECLARE dist INT;
  SET dist = (select count(*) as TotalUso from ct_control as c where c.PermisoId = PermisoId);
  RETURN dist;
END$$
DELIMITER ;

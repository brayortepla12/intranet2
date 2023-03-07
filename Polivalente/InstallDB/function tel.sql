
DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetPosEsperaTel`(_SolicitudId int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  set @rownum=0;
  SET dist = (SELECT t.Contador FROM (SELECT @rownum := @rownum + 1 as Contador, s.SolicitudId FROM polivalente.tel_solicitud as s where s.Estado = 'Activo') as t WHERE t.SolicitudId = _SolicitudId);
  RETURN dist;
END$$
DELIMITER ;
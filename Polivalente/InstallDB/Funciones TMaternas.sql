
DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `HaveParto`(MaternaId int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (SELECT count(*) FROM polivalente.tm_materna as m
	inner join polivalente.tm_evento as e on e.MaternaId = m.MaternaId
	where (e.TipoEvento = 'Parto' or e.TipoEvento = 'Parto Externo') and m.MaternaId= MaternaId);
  RETURN dist;
END$$
DELIMITER ;




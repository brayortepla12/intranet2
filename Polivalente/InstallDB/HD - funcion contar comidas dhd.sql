USE `polivalente`;
DROP function IF EXISTS `ContarComidasTotal`;

DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `ContarComidasTotal`(_dia int) RETURNS INT
    DETERMINISTIC
BEGIN 
  DECLARE dist INT;
	SET dist = (SELECT sum(T.TD) + sum(T.TMM) + sum(T.TA) + sum(T.TMT) + sum(T.TC) + sum(T.TMN) FROM (
	SELECT dhd.DHDId,
    if(dhd.DesayunoId is not null AND dhd.EDesayuno <> 'Cancelado', 1, 0) As 'TD',
    if(dhd.MMId is not null AND dhd.EMM <> 'Cancelado', 1, 0) As 'TMM',
    if(dhd.AlmuerzoId is not null AND dhd.EAlmuerzo <> 'Cancelado', 1, 0) As 'TA',
    if(dhd.MTId is not null AND dhd.EMT <> 'Cancelado', 1, 0) As 'TMT',
    if(dhd.CenaId is not null AND dhd.ECena <> 'Cancelado', 1, 0) As 'TC',
    if(dhd.MNId is not null AND dhd.EMN <> 'Cancelado', 1, 0) As 'TMN'    
    FROM sa_dhd as dhd 
    inner join sa_hd as h on h.HDId = dhd.HDId
    where dayofmonth(h.Fecha) = _dia
    ) as T);
  RETURN dist;
END$$

DELIMITER ;

USE `polivalente`;
DROP function IF EXISTS `ContarComidasPorHDPorDia`;

DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `ContarComidasPorHDPorDia`(_sector varchar(50), _dia int, _mes int, _annio int) RETURNS INT
    DETERMINISTIC
BEGIN 
  DECLARE dist INT;
	SET dist = (SELECT sum(T.TD) + sum(T.TMM) + sum(T.TA) + sum(T.TMT) + sum(T.TC) + sum(T.TMN) FROM (
	SELECT dhd.DHDId,
    if(dhd.DesayunoId is not null AND dhd.EDesayuno <> 'Cancelado', 1, 0) As 'TD',
    if(dhd.MMId is not null AND dhd.EMM <> 'Cancelado', 1, 0) As 'TMM',
    if(dhd.AlmuerzoId is not null AND dhd.EAlmuerzo <> 'Cancelado', 1, 0) As 'TA',
    if(dhd.MTId is not null AND dhd.EMT <> 'Cancelado', 1, 0) As 'TMT',
    if(dhd.CenaId is not null AND dhd.ECena <> 'Cancelado', 1, 0) As 'TC',
    if(dhd.MNId is not null AND dhd.EMN <> 'Cancelado', 1, 0) As 'TMN'    
    FROM sa_dhd as dhd 
    inner join sa_hd as h on h.HDId = dhd.HDId
    where h.SECTOR = _sector and dayofmonth(h.Fecha) = _dia and month(h.Fecha) = _mes and year(h.Fecha) = _annio
    ) as T);
  RETURN dist;
END$$

DELIMITER ;

USE `polivalente`;
DROP function IF EXISTS `ContarComidasPorHDPorMes`;
DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `ContarComidasPorHDPorMes`(_sector varchar(50), _mes int, _annio int) RETURNS INT
    DETERMINISTIC
BEGIN 
  DECLARE dist INT;
	SET dist = (SELECT sum(T.TD) + sum(T.TMM) + sum(T.TA) + sum(T.TMT) + sum(T.TC) + sum(T.TMN) FROM (
	SELECT dhd.DHDId,
    if(dhd.DesayunoId is not null AND dhd.EDesayuno <> 'Cancelado', 1, 0) As 'TD',
    if(dhd.MMId is not null AND dhd.EMM <> 'Cancelado', 1, 0) As 'TMM',
    if(dhd.AlmuerzoId is not null AND dhd.EAlmuerzo <> 'Cancelado', 1, 0) As 'TA',
    if(dhd.MTId is not null AND dhd.EMT <> 'Cancelado', 1, 0) As 'TMT',
    if(dhd.CenaId is not null AND dhd.ECena <> 'Cancelado', 1, 0) As 'TC',
    if(dhd.MNId is not null AND dhd.EMN <> 'Cancelado', 1, 0) As 'TMN'    
    FROM sa_dhd as dhd 
    inner join sa_hd as h on h.HDId = dhd.HDId
    where h.SECTOR = _sector and month(h.Fecha) = _mes and year(h.Fecha) = _annio
    ) as T);
  RETURN dist;
END$$

DELIMITER ;

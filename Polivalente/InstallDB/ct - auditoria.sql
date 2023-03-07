CREATE TABLE `auditoria_ctrl` (
  `ControlId` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` datetime DEFAULT NULL,
  `PersonaId` int(11) DEFAULT NULL,
  `Dispositivo` varchar(45) DEFAULT NULL,
  `Tipo` varchar(50) DEFAULT NULL,
  `PermisoId` int(11) DEFAULT NULL,
  `Estado` varchar(200) DEFAULT 'Activo',
  `CreatedBy` varchar(200) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'este campo me reviza que no se pueda modificar el registro',
  PRIMARY KEY (`ControlId`),
  KEY `PersonaId` (`PersonaId`)
) ENGINE=MyISAM AUTO_INCREMENT=47266 DEFAULT CHARSET=latin1;



DELIMITER $$

USE `polivalente`$$
DROP TRIGGER IF EXISTS `polivalente`.`Auditoria` $$
DELIMITER ;


delimiter #

create trigger Auditoria after insert on ct_control
for each row
begin
  insert into auditoria_ctrl (`ControlId`,
  `Fecha`,
  `PersonaId`,
  `Dispositivo`,
  `Tipo`,
  `PermisoId`,
  `Estado`,
  `CreatedBy`,
  `CreatedAt`) values (
  new.ControlId, 
  new.Fecha,
  new.PersonaId,
  new.Dispositivo,
  new.Tipo,
  new.PermisoId,
  new.Estado,
  new.CreatedBy,
  new.CreatedAt);
end#

delimiter ;
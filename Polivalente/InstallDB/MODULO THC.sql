INSERT INTO `polivalente`.`modulo` (`Nombre`, `State`) VALUES ('THC', 'thc.enfermeria');
INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'thc.enfermeria', 'Enfermeria', '18');
INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`) VALUES ('ver vista', 'thc.mis_historias', 'Mis Historias');
INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'thc.historias', 'Historias', '18');
UPDATE `polivalente`.`permiso` SET `ModuloId`='18' WHERE `PermisoId`='120';


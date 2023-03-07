DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='1';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='2';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='3';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='4';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='5';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='6';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='7';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='8';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='9';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='10';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='11';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='12';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='13';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='14';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='15';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='16';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='17';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='18';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='19';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='20';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='21';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='22';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='23';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='24';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='25';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='26';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='27';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='28';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='29';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='30';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='31';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='32';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='33';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='34';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='35';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='36';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='37';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='38';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='39';
DELETE FROM `polivalente`.`sa_sector` WHERE `SectorId`='40';

ALTER TABLE `polivalente`.`sa_sector` 
COLLATE = DEFAULT , AUTO_INCREMENT = 1 ;

INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('URGENCIAS OBS ADULTOS', 'URG');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('URGENCIA GENERAL', 'URGA');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('URGENCIA PEDIATRICA', 'URGP');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('ADMISIONES', 'ADMI');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SALA DE CIRUGIA', 'CIR');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('CIRUGIA MATERNIDAD', 'CIRMATER');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION GENERAL MATERNIDAD', 'HOSMATER');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION PRIMER PISO', 'HOSP_1');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION SEGUNDO PISO', 'HOSP_2');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION TERCER PISO BLOQUE B', 'HOSP_3');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION TERCER PISO PEDIATRIA', 'HOSP_3PED');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION TERCER PISO VIP', 'HOSP_3V');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION MATERNIDAD', 'HOSP_M');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION PEDIATRICA', 'HOSP_P');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('OBSERVACION MATERNIDAD', 'OBSMATER');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('OBSERVACION PREPAGADA', 'OBSPRE');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SALA DE PARTO Y RECUPERACION', 'PARTO');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('TODOS LOS SECTORES', 'TODOSECTOR');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI PEDIATRICA', 'UCIPED');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI ADULTOS', 'UCI_A');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI ADULTOS SEGUNDO PISO NUEVA', 'UCI_AD');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI NEONATAL', 'UCI_NEO');


# Asignamos todos los sectores a la empresa 1
INSERT INTO sa_empresasector (EmpresaId, SectorId)
SELECT 1, SectorId
FROM   sa_sector;
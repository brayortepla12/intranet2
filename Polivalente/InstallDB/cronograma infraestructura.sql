
DROP TABLE IF EXISTS pol_ItemCI;
DROP TABLE IF EXISTS pol_DetalleCI;
DROP TABLE IF EXISTS pol_CI;
CREATE TABLE pol_ItemCI (
  `ItemCIId` int(11) NOT NULL primary key AUTO_INCREMENT,
  `Descripcion` varchar(400),
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL
);
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('SEÑALICACION Y AVISO EXTERIOR DE AREAS');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('BARANDAS ESCALERAS');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('CIELO RASO');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('TELEFONOS');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('CUADROS Y CARTELERAS');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('SERVICIO SANITARIO');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('ESCRITORIO Y MUEBLES DE ARCHIVO');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('EXTINTORES');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('ILUMINACION LAMPARA');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('TOMA CORRIENTES INTERRUCTORES');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('LAVAMANOS INOX(2)');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('PAREDES Y PISOS (PINTURA EPOXICA)');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('PINTURA DE ELEMENTOS METALICOS VENTANAS Y PUERTAS');
INSERT INTO `polivalente`.`pol_itemci` (`Descripcion`) VALUES ('SISTEMA ELECTRICO');

CREATE TABLE pol_CI (
  `CIId` int(11) NOT NULL primary key AUTO_INCREMENT,
  `Vigencia` int,
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL
);

CREATE TABLE pol_DetalleCI (
  `DetalleCI` int(11) NOT NULL primary key AUTO_INCREMENT,
  `ServicioId` int NULL,
  `CIId` int NULL,
  `Ene` varchar(2),
  `Feb` varchar(2),
  `Mar` varchar(2),
  `Abr` varchar(2),
  `May` varchar(2),
  `Jun` varchar(2),
  `Jul` varchar(2),
  `Agos` varchar(2),
  `Sept` varchar(2),
  `Oct` varchar(2),
  `Nov` varchar(2),
  `Dic` varchar(2),
  `ItemId` int,
  `Descripcion` varchar(400),
  `Responsable` varchar(250),
  `Periodicidad` varchar(250),
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  FOREIGN KEY (ServicioId) REFERENCES servicio(ServicioId),
  FOREIGN KEY (CIId) REFERENCES pol_CI(CIId)
);
UPDATE `polivalente`.`servicio` SET `Nombre`='TRRC-AFERESIS' WHERE `ServicioId`='176';
UPDATE `polivalente`.`servicio` SET `Nombre`='TRRC-AFERESISCSI' WHERE `ServicioId`='177';

ALTER TABLE `polivalente`.`encabezadopiepagina` 
ADD COLUMN `ElaboroCI` VARCHAR(300) CHARACTER SET 'latin1' NULL DEFAULT NULL AFTER `EncabezadoCI`,
ADD COLUMN `CargoElaboroCI` VARCHAR(200) CHARACTER SET 'latin1' NULL DEFAULT NULL AFTER `ElaboroCI`,
ADD COLUMN `FirmaElaboroCI` MEDIUMBLOB NULL DEFAULT NULL AFTER `CargoElaboroCI`,
ADD COLUMN `RevisoCI` VARCHAR(300) CHARACTER SET 'latin1' NULL DEFAULT NULL AFTER `FirmaElaboroCI`,
ADD COLUMN `CargoRevisoCI` VARCHAR(200) CHARACTER SET 'latin1' NULL DEFAULT NULL AFTER `RevisoCI`,
ADD COLUMN `FirmaRevisoCI` MEDIUMBLOB NULL DEFAULT NULL AFTER `CargoRevisoCI`,
ADD COLUMN `AproboCI` VARCHAR(300) CHARACTER SET 'latin1' NULL DEFAULT NULL AFTER `FirmaRevisoCI`,
ADD COLUMN `CargoAproboCI` VARCHAR(200) CHARACTER SET 'latin1' NULL DEFAULT NULL AFTER `AproboCI`,
ADD COLUMN `FirmaAproboCI` MEDIUMBLOB NULL DEFAULT NULL AFTER `CargoAproboCI`;

UPDATE `polivalente`.`encabezadopiepagina` SET `EncabezadoCI`='<p>  GP-MP-F-14 ver.3 Pagina. 1 de 1<br/>                            \nEmisión 11-06-2014 vigilancia:30-12-2019</p> ', `ElaboroCI`='WILSON BRUGES CAMACHO', `CargoElaboroCI`='COORD. MANT. POLIVALENTE', `RevisoCI`='KEYLA PINEDA VALERA', `CargoRevisoCI`='COORD. ASEG. DE LA CALIDAD', `AproboCI`='JAIME ARCE GARCIA', `CargoAproboCI`='PRESIDENTE' WHERE `EncabezadoPiePaginaId`='1';

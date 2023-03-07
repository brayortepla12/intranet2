drop table if exists cv_Visitante;
drop table if exists cv_Visita;
drop table if exists cv_ServicioAdministrativoJefe;
drop table if exists cv_ServicioAdministrativo;
drop table if exists cv_Servicio;
drop table if exists cv_Sede;
drop table if exists cv_UsuarioTokenFB;

create table if not exists cv_UsuarioTokenFB(
`UsuarioTokenFBId` int primary key auto_increment,
`UsuarioId` int unique,
`Token` text,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`UsuarioTokenFBId`, `UsuarioId`));

create table if not exists cv_Sede(
`SedeId` int primary key auto_increment,
`Nombre` varchar(15),
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`SedeId`));

create table if not exists cv_Servicio(
`ServicioId` int primary key auto_increment,
`Nombre` varchar(50),
`Abrv` varchar(15),
`SedeId` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(SedeId) references cv_Sede(SedeId),
INDEX(`ServicioId`, `SedeId`));

create table if not exists cv_ServicioAdministrativo(
`ServicioId` int primary key auto_increment,
`Nombre` varchar(50),
`SedeId` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(SedeId) references cv_Sede(SedeId),
INDEX(`ServicioId`, `SedeId`));

create table if not exists cv_ServicioAdministrativoJefe(
`ServicioUsuarioId` int primary key auto_increment,
`ServicioId` int,
`UsuarioId` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(ServicioId) references cv_servicioadministrativo(ServicioId),
foreign key(UsuarioId) references usuario(UsuarioId),
INDEX(`ServicioId`, `UsuarioId`));

create table if not exists cv_Visitante(
`VisitanteId` int primary key auto_increment,
`Documento` varchar(20),
`PNombre` varchar(20),
`SNombre` varchar(20),
`PApellido` varchar(20),
`SApellido` varchar(20),
`Celular` varchar(15),
`Estado` enum('Inactivo', 'Activo', 'Betado') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`VisitanteId`));

create table if not exists cv_Visita(
`VisitaId` int primary key auto_increment,
`VisitanteId` int,
`VisitanteDocumento` varchar(20),
`VisitantePNombre` varchar(20),
`VisitanteSNombre` varchar(20),
`VisitantePApellido` varchar(20),
`VisitanteSApellido` varchar(20),
`VisitanteCelular` varchar(15),
`PuertaIngreso` varchar(20),
`FechaIngreso` datetime,
`PuertaEgreso` varchar(20),
`FechaEgreso` datetime,
`FechaAutorizado` datetime,
`FechaAltaAdministrativa` datetime,
`FechaAlta` datetime,
`Idafiliado` varchar(20),
`NoAdmision` varchar(20),
`PacientePNombre` varchar(20),
`PacienteSNombre` varchar(20),
`PacientePApellido` varchar(20),
`PacienteSApellido` varchar(20),
`Descripcion` varchar(30),
`Habcama` varchar(15),
`JefeId` int, -- ct_persona PersonaId
`JefeNombres` varchar(50), -- ct_persona
`ServicioId` int, -- administrativo
`NombreSedeAdministrativo` varchar(25),
`NombreServicioAdministrativo` varchar(10),
`TipoVisita` enum('Asistencial', 'Administrativo') default 'Asistencial',
`GuardaId` int,
`Estado` enum('Egreso', 'Pendiente Por Autorizar', 'Autorizado' , 'Alta Administrativa', 'Ingreso') default 'Ingreso',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`VisitaId`, `VisitanteId`, `VisitanteDocumento`));





INSERT INTO `polivalente`.`cv_sede` (`Nombre`) VALUES ('CIELD');
INSERT INTO `polivalente`.`cv_sede` (`Nombre`) VALUES ('CEMIC');
INSERT INTO `polivalente`.`cv_sede` (`Nombre`) VALUES ('CSI');
INSERT INTO `polivalente`.`cv_sede` (`Nombre`) VALUES ('UICP');
INSERT INTO `polivalente`.`cv_sede` (`Nombre`) VALUES ('AGUACHICA');

#servicios
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('CAPILLA CLD', 'CAPILLACLD', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('CIRUGIA', 'PISO1CIRUG', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('HOSPITALIZACION 4to PISO', 'SGR4', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('HOSPITALIZACION BASICA PISO3', 'HOSPITALIZ', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('HOSPITALIZACION CORONARIA', 'HOSPCOR', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('HOSPITALIZACION MEDICINA INTERNA 2P', 'HOSP2PISO', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('OBSERVACION HOMBRES', 'OBSURGHOM', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('OBSERVACION MUJERES', 'OBSERVAURG', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('TERAPIAS', 'TERAPIAS', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('UCI ADULTO', 'PISO4UCIAD', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('UCI ADULTOS SECTOR 5 PISO', 'UCIADULTO', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('UNIDAD DE CUIDADO CRITICO CARDIOVASCULAR', 'UCCC', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('UNIDAD DE INTERVENCIONISMO CARDIOVASCULAR', 'UNICAR', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('VIP SEGUNDO PISO', 'VIP', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('VIP TERCER PISO', 'VIPPISO3', '1');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('Cemic CIRUGIA', 'CemicCIR', '2');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('CEMIC URGENCIAS', 'CEMICURG', '2');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('HOSPITALIZACION GENERAL CEMIC', 'CEMICHAB', '2');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('OBSERVACION MATERNA CEMIC', 'CEMICHOSG', '2');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('Observacion Pediatrica Piso 2', 'OBSPED2P', '2');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('UCI NEONATAL CEMIC', 'NEONATOSC', '2');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('SI- OBSERVACION 1 PISO', 'SIOBS1P', '3');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('SI- OBSERVACION 2 PISO', 'SIOBS2PSEC', '3');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('SI- PRIMER PISO SEC', 'SIPISO1SEC', '3');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('SI- SEGUNDO PISO BIPERSONAL', 'SIPISO2HOS', '3');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('SI- SEGUNDO PISO UNIPERSONAL', 'SIPISO2UNI', '3');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('SI- TERCER PISO BLOQUE A', 'SIPISO3A', '3');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('SI- TERCER PISO BLOQUE B', 'SIPISO3B', '3');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('SI- UCI ADULTOS', 'SIUCIA', '3');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('SI- UCI ADULTOS PISO 3', 'SIPISO3UCI', '3');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('SI-CIRUGIA', 'SIPISO2CIR', '3');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('SI-URGENCIAS', 'SIPISO1URG', '3');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('CP OBSERVACION PEDIATRICA', 'CPOBSPED', '4');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('HOSPITALIZACION PEDIATRIA PISO 3', 'HOS3PPED', '4');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('HOSPITALIZACION PEDIATRICA PISO 2', 'HOSP2PPED', '4');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('OBSERVACION PEDIATRICA CEIMIC', 'OBSERVACION PEDIATRICA CEIMIC', '4');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('UCI PEDIATRICA', 'UCIPEDIATR', '4');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('AGUACHICA SECTOR UCI ADULTOS', 'AGUCIA', '5');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('AGUACHICA SECTOR UCI NEONATOS', 'AGUCIN', '5');
INSERT INTO `polivalente`.`cv_servicio` (`Nombre`, `Abrv`, `SedeId`) VALUES ('AGUACHICA SECTOR UCI PEDIATRICA', 'AGUCIP', '5');


INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (1,'Gerencia',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (2,'Subgerencia',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (3,'Presidencia',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (4,'Tesoreria',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (5,'Gestion Humana',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (6,'Biomedico',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (7,'Calidad',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (8,'Sistemas',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (9,'Coor. Medico Cientifica',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (10,'Control interno',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (11,'Cartera',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (12,'Seguridad',1,'Activo','','2020-12-11 11:10:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (13,'Sistemas',3,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (14,'Coor. Medico Cientifica',3,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (17,'Referencia',3,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (19,'Correspondencia',3,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (20,'Talleres',3,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (21,'Lavanderia',3,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (22,'Laboratorio',3,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (23,'Capilla',3,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (24,'Servicio Farmaceutico',3,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (25,'Servicio Farmaceutico',1,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (26,'Capilla',1,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (27,'Laboratorio',1,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (28,'Almacen',1,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (29,'Referencia',1,'Activo','','2020-12-11 11:16:50','',NULL);
INSERT INTO `cv_servicioadministrativo` (`ServicioId`,`Nombre`,`SedeId`,`Estado`,`CreatedBy`,`CreatedAt`,`ModifiedBy`,`ModifiedAt`) VALUES (30,'Facturacion',1,'Activo','','2020-12-11 11:16:50','',NULL);



INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('30', '75');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('2', '278');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('3', '66');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('4', '104');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('5', '273');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('6', '184');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('7', '72');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('8', '46');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('9', '279');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('10', '2');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('11', '15');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('12', '130');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('13', '218');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('14', '208');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('16', '277');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('19', '80');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('20', '53');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('22', '194');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('24', '145');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('25', '45');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('27', '217');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('28', '55');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('29', '277');
INSERT INTO `polivalente`.`cv_servicioadministrativojefe` (`ServicioId`, `UsuarioId`) VALUES ('30', '75');

ALTER TABLE `polivalente`.`usuario` 
ADD COLUMN `IsCVGuarda` TINYINT NULL DEFAULT 0 AFTER `TokenFB`,
ADD COLUMN `IsCVJefe` TINYINT NULL DEFAULT 0 AFTER `IsCVGuarda`;

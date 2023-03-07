drop table if exists pol_RondaMant;
create table if not exists pol_RondaMant(
	RondaMantId int primary key auto_increment,
    `SedeId` int,
    `Fecha` date,
    `Hora` date,
    `Responsable` varchar(50),
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table if not exists pol_DetalleRondaMant(
	DetalleRondaMantId int primary key auto_increment,
    `ServicioId` int,
    `Descripcion` varchar(200),
    `TecnicoResponsable` text,
    `Cumplimiento` char(2),
    `CoordinadorFirmaId` varchar(100),
    `Observaciones` text,
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'polivalente.rondaMantAdmin', 'Administrar Ronda Mant. ', '1');

drop table if exists pol_ReporteCronoMP;
drop table if exists pol_cronogramaMP;
create table if not exists pol_cronogramaMP(
	CronogramaMPId int primary key auto_increment,
    `Vigencia` year,
    `1` int,
    `2` int,
    `3` int,
    `4` int,
    `5` int,
    `6` int,
    `7` int,
    `8` int,
    `9` int,
    `10` int,
    `11` int,
    `12` int,
    `ServicioId` int,
    `HojaVidaId` int,
    `Nombre` varchar(100),
    `Marca` varchar(100),
    `Modelo` varchar(100),
    `Serie` varchar(100),
    `Ubicacion` varchar(100),
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table if not exists pol_ReporteCronoMP(
	ReporteCronoMPId int primary key auto_increment,
    `ReporteId` int,
    `CronogramaMPId` int,
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    foreign key (CronogramaMPId) references pol_cronogramaMP(CronogramaMPId)
);

drop table if exists biomedicos_ReporteCronoMP;
drop table if exists biomedicos_cronogramaMP;
create table if not exists biomedicos_cronogramaMP(
	CronogramaMPId int primary key auto_increment,
    `Vigencia` year,
    `1` int,
    `2` int,
    `3` int,
    `4` int,
    `5` int,
    `6` int,
    `7` int,
    `8` int,
    `9` int,
    `10` int,
    `11` int,
    `12` int,
    `ServicioId` int,
    `HojaVidaId` int,
    `Nombre` varchar(100),
    `Marca` varchar(100),
    `Modelo` varchar(100),
    `Serie` varchar(100),
    `Ubicacion` varchar(100),
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table if not exists biomedicos_ReporteCronoMP(
	ReporteCronoMPId int primary key auto_increment,
    `ReporteId` int,
    `CronogramaMPId` int,
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    foreign key (CronogramaMPId) references biomedicos_cronogramaMP(CronogramaMPId)
);


drop table if exists sistemas_ReporteCronoMP;
drop table if exists sistemas_cronogramaMP;
create table if not exists sistemas_cronogramaMP(
	CronogramaMPId int primary key auto_increment,
    `Vigencia` year,
    `1` int,
    `2` int,
    `3` int,
    `4` int,
    `5` int,
    `6` int,
    `7` int,
    `8` int,
    `9` int,
    `10` int,
    `11` int,
    `12` int,
    `ServicioId` int,
    `HojaVidaId` int,
    `Nombre` varchar(100),
    `Marca` varchar(100),
    `Modelo` varchar(100),
    `Serie` varchar(100),
    `Ubicacion` varchar(100),
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table if not exists sistemas_ReporteCronoMP(
	ReporteCronoMPId int primary key auto_increment,
    `ReporteId` int,
    `CronogramaMPId` int,
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    foreign key (CronogramaMPId) references sistemas_cronogramaMP(CronogramaMPId)
);



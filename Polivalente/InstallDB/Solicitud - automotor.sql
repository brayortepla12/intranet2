drop table if exists ambulancia_eventosolicitud;
drop table if exists ambulancia_detallesolicitud;
drop table if exists ambulancia_solicitud;
create table if not exists ambulancia_solicitud(
	SolicitudId int primary key auto_increment,
    `FechaSolicitud` datetime,
	`FechaFinalizacion` datetime DEFAULT NULL,
	`FechaVerificacion` datetime DEFAULT NULL,
	`UsuarioSolicitaId` int(11),
    `NombreUsuarioSolicita` varchar(200),
    `CargoUsuarioSolicita` varchar(45),
	`UsuarioFinalizaId` int(11) DEFAULT NULL,
    `NombreUsuarioFinaliza` varchar(200) DEFAULT NULL,
	`UsuarioVerificaId` int(11) DEFAULT NULL,
    `NombreUsuarioVerifica` varchar(200) DEFAULT NULL,
    `IsVisto` boolean default 0,
    `IsFinalizada` boolean default 0,
    `EstadoSolicitud` varchar(30),
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table if not exists ambulancia_detallesolicitud(
	DetalleSolicitudId int primary key auto_increment,
    `SedeId` int(11) DEFAULT NULL,
	`ServicioId` int(11) DEFAULT NULL,
	`EquipoId` int(11) DEFAULT NULL,
	`EquipoOtro` varchar(50),
	`Ubicacion` varchar(70),
	`HasNotEquipo` boolean default 0,
	`SolicitudId` int(11),
	`Descripcion` text,
	`Foto` text DEFAULT NULL,
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    foreign key (SolicitudId) references ambulancia_solicitud(SolicitudId)
);

create table if not exists ambulancia_eventosolicitud(
	EventoSolicitudId int primary key auto_increment,
    `FechaEvento` datetime,
	`UsuarioEventoId` int(11),
	`NombreBreveEvento` varchar(30),
	`TipoEvento` varchar(50),
	`NombreUsuario` varchar(50),
	`Descripcion` text,
	`Pedido2_0Id` int(11) default null,
	`PedidoId` int(11) default null,
	`PedidoFarmaciaId` int(11) default null,
    `ReporteId` int(11) default null,
    `ReporteExternoId` int(11) default null,
	`ProcesoId` int(11) default null,
	`SolicitudId` int(11),
	`IsInProceso` boolean default 1,
    `TecnicoResponsable` varchar(50),
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    foreign key (SolicitudId) references ambulancia_solicitud(SolicitudId)
);
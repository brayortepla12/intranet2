
drop table if exists pol_eventosolicitud;
drop table if exists pol_detallesolicitud;
drop table if exists pol_solicitud;
create table if not exists pol_solicitud(
	SolicitudId int primary key auto_increment,
    `FechaSolicitud` datetime,
	`FechaFinalizacion` datetime DEFAULT NULL,
	`FechaVerificacion` datetime DEFAULT NULL,
	`UsuarioSolicitaId` int(11),
    `NombreUsuarioSolicita` varchar(200),
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

create table if not exists pol_detallesolicitud(
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
    foreign key (SolicitudId) references pol_solicitud(SolicitudId)
);

create table if not exists pol_eventosolicitud(
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
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    foreign key (SolicitudId) references pol_solicitud(SolicitudId)
);

drop table if exists sistemas_detallesolicitud;
drop table if exists sistemas_eventosolicitud;
drop table if exists sistemas_solicitud;
create table if not exists sistemas_solicitud(
	SolicitudId int primary key auto_increment,
    `FechaSolicitud` datetime,
	`FechaFinalizacion` datetime DEFAULT NULL,
	`FechaVerificacion` datetime DEFAULT NULL,
	`UsuarioSolicitaId` int(11),
    `NombreUsuarioSolicita` varchar(200),
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

create table if not exists sistemas_detallesolicitud(
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
    foreign key (SolicitudId) references sistemas_solicitud(SolicitudId)
);

create table if not exists sistemas_eventosolicitud(
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
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    foreign key (SolicitudId) references sistemas_solicitud(SolicitudId)
);


drop table if exists biomedicos_detallesolicitud;
drop table if exists biomedicos_eventosolicitud;
drop table if exists biomedicos_solicitud;
create table if not exists biomedicos_solicitud(
	SolicitudId int primary key auto_increment,
    `FechaSolicitud` datetime,
	`FechaFinalizacion` datetime DEFAULT NULL,
	`FechaVerificacion` datetime DEFAULT NULL,
	`UsuarioSolicitaId` int(11),
    `NombreUsuarioSolicita` varchar(200),
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

create table if not exists biomedicos_detallesolicitud(
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
    foreign key (SolicitudId) references biomedicos_solicitud(SolicitudId)
);

create table if not exists biomedicos_eventosolicitud(
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
    `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
	`CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`CreatedAt` datetime DEFAULT NULL,
	`ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
	`ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    foreign key (SolicitudId) references biomedicos_solicitud(SolicitudId)
);



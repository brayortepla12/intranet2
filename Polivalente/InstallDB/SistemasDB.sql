drop table if exists Sistemas_reporte;
create table if not exists Sistemas_reporte(
  ReporteId int(11) NOT NULL AUTO_INCREMENT,
  NumeroReporte varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  SedeId int(11) DEFAULT NULL,
  Fecha date DEFAULT NULL,
  ServicioId int(11) DEFAULT NULL,
  Solicitante varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  Ubicacion varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  Responsable varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  TipoServicio varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  EquipoId int(11) DEFAULT NULL,
  FallaReportada text COLLATE latin1_spanish_ci,
  FallaDetectada varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  ProcedimientoRealizado text COLLATE latin1_spanish_ci,
  Observaciones text COLLATE latin1_spanish_ci,
  EstadoFinal varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  ResponsableNombre varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  ResponsableCargo varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  ResponsableFirma mediumblob,
  Recibefecha date DEFAULT NULL,
  RecibeHora time DEFAULT NULL,
  RecibeNombre varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  RecibeCargo varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  RecibeFirma mediumblob,
  TipoReporte varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  ReporteArchivo mediumblob,
  SolicitudId int(11) DEFAULT NULL,
  Estado varchar(200) COLLATE latin1_spanish_ci DEFAULT 'Borrador',
  CreatedBy varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  CreatedAt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  EstadoReporte varchar(45) COLLATE latin1_spanish_ci DEFAULT 'Activo',
  PRIMARY KEY (ReporteId),
  KEY ServicioId (ServicioId),
  KEY SedeId (SedeId),
  KEY EquipoId (EquipoId),
  KEY SolicitudId (SolicitudId),
  FOREIGN KEY (ServicioId) REFERENCES servicio (ServicioId),
  FOREIGN KEY (SedeId) REFERENCES sede (SedeId),
  FOREIGN KEY (EquipoId) REFERENCES sistemas_hojavida (HojaVidaId),
  FOREIGN KEY (SolicitudId) REFERENCES solicitud (SolicitudId)
) ENGINE=InnoDB;

/*drop table if exists Sistemas_reporte;
drop table if exists sistemas_accesorios;
drop table if exists Sistemas_hojavida;

create table if not exists Sistemas_hojavida(
	HojaVidaId int primary key auto_increment,
	SedeId int,
	ServicioId int,
	Ubicacion varchar(300),
	Nombre varchar(100),
	Fabricante varchar(100),
	NSerial varchar(100),
	Tipo varchar(100),
	Modelo varchar(100),
	SO varchar(100),
	SerieSO varchar(100),
    Ram varchar(100),
    Procesador varchar(100),
    DiscoDuro varchar(100),
    RecomendacioneFabricante text,
	Foto mediumblob,
	Estado varchar(200) default 'Activo',
	FechaInstalacion date,
    FechaBaja date,
	FechaCalibracion date,
    FechaUltimoMantenimiento date,
    FrecuenciaMantenimientoId int,
	FrecuenciaCalibracionId int,
    TipoArticulo varchar(100),
	CreatedBy varchar(200),
	CreatedAt timestamp default current_timestamp,
	ModifiedBy varchar(200),
	ModifiedAt datetime,
    foreign key(SedeId) references Sede(SedeId),
    foreign key(ServicioId) references Servicio(ServicioId),
    foreign key(FrecuenciaMantenimientoId) references FrecuenciaMantenimiento(FrecuenciaMantenimientoId),
    foreign key(FrecuenciaCalibracionId) references FrecuenciaMantenimiento(FrecuenciaMantenimientoId)
) ENGINE = INNODB;

create table if not exists Sistemas_accesorios(
	AccesorioId int primary key auto_increment,
	HojaVidaId int,
	Nombre varchar(100),
    Marca varchar(100),
    Modelo varchar(100),
	NSerial varchar(100),
    Cantidad int,
	Estado varchar(200) default 'Activo',
	FechaInstalacion date,
	FechaBaja date,
	CreatedBy varchar(200),
	CreatedAt timestamp default current_timestamp,
	ModifiedBy varchar(200),
	ModifiedAt datetime,
    foreign key(HojaVidaId) references Sistemas_hojavida(HojaVidaId)
) ENGINE = INNODB;

create table if not exists Sistemas_reporte(
	ReporteId int primary key auto_increment,
	EquipoId int,
	FechaReporte date,
    TipoReporte varchar(100),
    Observacion varchar(100),
	Responsable varchar(100),
    SedeId int,
    ServicioId int,
    Ubicacion varchar(100),
    Recibefecha date,
    RecibeHora time,
    RecibeNombre varchar(200),
    RecibeCargo varchar(200),
    RecibeFirma mediumblob,
	Estado varchar(200) default 'Activo',
	CreatedBy varchar(200),
	CreatedAt timestamp default current_timestamp,
	ModifiedBy varchar(200),
	ModifiedAt datetime,
    foreign key(EquipoId) references Sistemas_hojavida(HojaVidaId),
    foreign key(SedeId) references Sede(SedeId),
    foreign key(ServicioId) references Servicio(ServicioId)
) ENGINE = INNODB;*/
/*drop table if exists Sistemas_DetalleRonda;
drop table if exists Sistemas_RondaServicioUsuario;
drop table if exists Sistemas_Ronda;


create table if not exists Sistemas_Ronda(
	RondaId int primary key auto_increment,
	Nombre varchar(200),
    Tipo varchar(200),
	Estado varchar(200) default 'Activo',
	CreatedBy varchar(200),
	CreatedAt timestamp default current_timestamp,
	ModifiedBy varchar(200),
	ModifiedAt datetime
) ENGINE = INNODB;


create table if not exists Sistemas_RondaServicioUsuario(
	RondaServicioUsuarioId int primary key auto_increment,
	ServicioId int,
	UsuarioId int,
	RondaId int,
	Estado varchar(200) default 'Activo',
	CreatedBy varchar(200),
	CreatedAt timestamp default current_timestamp,
	ModifiedBy varchar(200),
	ModifiedAt datetime,
    foreign key(ServicioId) references Servicio(ServicioId),
    foreign key(UsuarioId) references Usuario(UsuarioId),
    foreign key(RondaId) references Sistemas_Ronda(RondaId)
) ENGINE = INNODB;

create table if not exists Sistemas_DetalleRonda(
	DetalleRondaId int primary key auto_increment,
	RondaServicioUsuarioId int,
	Estado varchar(200),
    Fecha varchar(200),
    Observacion1 text,
    Observacion2 text,
	CreatedBy varchar(200),
	CreatedAt timestamp default current_timestamp,
	ModifiedBy varchar(200),
	ModifiedAt datetime,
    foreign key(RondaServicioUsuarioId) references Sistemas_RondaServicioUsuario(RondaServicioUsuarioId)
) ENGINE = INNODB;*/
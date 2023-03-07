drop table if exists Observador_Cuna;
create table if not exists Observador_Cuna(
	CunaId int primary key auto_increment,
    Nombre varchar(100),
    Rtsp varchar(200),
    Estado varchar(250) default 'Activo',
    CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL
)ENGINE=INNODB DEFAULT CHARSET=latin1;

drop table if exists Observador_Token;
create table if not exists Observador_Token(
	TokenCunaId int primary key auto_increment,
    CunaId int,
    Nombres varchar(200),
    Dias int,
    Token text,
    Email varchar(200),
    Estado varchar(250) default 'Activo',
    CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL,
    foreign key(CunaId) references Observador_Cuna(CunaId)
)ENGINE=INNODB DEFAULT CHARSET=latin1;

drop table if exists PedidoAlmacen;
create table if not exists PedidoAlmacen(
	PedidoAlmacenId int primary key auto_increment,
    FechaSolicitud datetime,
    NombreSolicitante varchar(250),
    CargoSolicitante varchar(250),
    Items mediumtext,
    FechaEntrega datetime,
    FechaRecibe datetime,
    SolicitanteId int,
    SedeId int,
    ServicioId int,
    Observacion text,
    NombreRecibe varchar(250),
    NombreEntrega varchar(250),
    Estado varchar(250) default 'Activo',
    CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL,
    foreign key(SolicitanteId) references usuario(UsuarioId),
    foreign key(SedeId) references Sede(SedeId),
    foreign key(ServicioId) references Sede(ServicioId)
);
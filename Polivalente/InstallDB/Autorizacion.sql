
drop table if exists Autorizacion_ItemProtocolo;
drop table if exists Autorizacion_LogEmail;
drop table if exists Autorizacion_EnvioCorreo;

drop table if exists Autorizacion_Protocolo;

create table Autorizacion_Protocolo(
	ProtocoloId int primary key auto_increment,
    Nombre varchar(100),
	Estado varchar(100) DEFAULT 'Activo',
	CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL
) ENGINE = INNODB;

create table Autorizacion_ItemProtocolo(
	ItemProtocoloId int primary key auto_increment,
    ProtocoloId int,
    Nombre varchar(100),
    Email varchar(200),
    Orden int,
    Tiempo int,
    Estado varchar(100) DEFAULT 'Activo',
	CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL,
    foreign key(ProtocoloId) references Autorizacion_Protocolo(ProtocoloId)
) ENGINE = INNODB;

create table Autorizacion_EnvioCorreo(
	EnvioCorreoId int primary key auto_increment,
    EmailSolicitante varchar(200),
    ProtocoloId int,
    Archivos text,
    Mensaje text,
    OrdenEnCurso int,
	Estado varchar(100) DEFAULT 'Activo',
	CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL,
    foreign key(ProtocoloId) references Autorizacion_Protocolo(ProtocoloId)
) ENGINE = INNODB;

create table Autorizacion_LogEmail(
	LogEmailId int primary key auto_increment,
    EnvioCorreoId int,
    Orden int,
	Estado varchar(100) DEFAULT 'Activo',
	CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL,
    foreign key(EnvioCorreoId) references Autorizacion_EnvioCorreo(EnvioCorreoId)
) ENGINE = INNODB;

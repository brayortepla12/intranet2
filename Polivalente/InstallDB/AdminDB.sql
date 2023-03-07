DELIMITER $$
CREATE FUNCTION GetLastReporteid (id int, Anno int) 
RETURNS int
DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where (equipoid = id and (year(Fecha) = Anno - 1 or year(Fecha) = Anno )) and TipoServicio <> 'CALIBRACION');
  RETURN dist;
END$$
DELIMITER ;



DELIMITER $$
CREATE FUNCTION GetLastReporteidByHojaVidaId (id int) 
RETURNS int
DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where equipoid = id);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE FUNCTION GetLastReporteidByHojaVidaIdCalibracion (id int) 
RETURNS int
DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where equipoid = id and TipoServicio = 'CALIBRACION');
  RETURN dist;
END$$
DELIMITER ;

#drop database if exists Biomedico;
#create database if not exists Biomedico collate 'latin1_spanish_ci';
#use Biomedico;
# data
#drop table if exists Reporte;
#drop table if exists Solicitud;
#drop table if exists HojaVida;

#drop table if exists Servicio;

# Seguridad
drop table if exists RolUsuario;
drop table if exists ModuloUsuario;
drop table if exists UsuarioPermiso;
drop table if exists Permiso;
drop table if exists Rol;
drop table if exists ServicioUsuario;
drop table if exists Usuario;
drop table if exists Modulo;


drop table if exists Sede;
drop table if exists FrecuenciaMantenimiento;
drop table if exists Proveedor;
drop table if exists Empresa;
drop table if exists EncabezadoPiePagina;





create table Modulo (
	ModuloId int primary key auto_increment,
    Nombre varchar(100),
    State text,
    img blob,
    codigo_icono varchar(200),
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
) ENGINE = INNODB;

create table Usuario(
	UsuarioId int primary key auto_increment,
    NombreUsuario varchar(100),
    NombreCompleto varchar(200),
    Email varchar(300),
    Contrasena text,
    FechaVecimiento datetime,
    Estado boolean,
    Firma mediumblob,
    Cargo varchar(200),
    
    Url_Foto mediumblob,
    CreatedBy varchar(200),
    FechaCreacion timestamp,
    FechaModificacion datetime default null,
    ModifiedBy varchar(200) default null
) ENGINE = INNODB;

create table Rol(
	RolId int primary key auto_increment,
    Nombre varchar(100),
    FechaCreacion timestamp,
    FechaModificacion datetime default null
) ENGINE = INNODB;

create table Permiso(
	PermisoId int primary key auto_increment,
    Tipo varchar(200),
    Descripcion varchar(200),
    Tabla varchar(200),
    State varchar(200),
    label varchar(200),
    ModuloId int,
    foreign key(ModuloId) references Modulo(ModuloId),
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
)ENGINE = INNODB;


/*Modulo administracion ------------- RELACIONEs*/
create table UsuarioPermiso(
	UsuarioPermisoId int primary key auto_increment,
    UsuarioId int,
    PermisoId int,
    foreign key(UsuarioId) references Usuario(UsuarioId),
    foreign key(PermisoId) references Permiso(PermisoId)
)ENGINE = INNODB;


create table ModuloUsuario(
	Id int primary key auto_increment,
    UsuarioId int,
    ModuloId int,
    foreign key(UsuarioId) references Usuario(UsuarioId),
    foreign key(ModuloId) references Modulo(ModuloId)
) ENGINE = INNODB;

create table RolUsuario(
	Id int primary key auto_increment,
    UsuarioId int,
    RolId int,
    foreign key(UsuarioId) references Usuario(UsuarioId),
    foreign key(RolId) references Rol(RolId)
) ENGINE = INNODB;


/*SEMILLA*/
insert into modulo (Nombre, State) values("Hoja de vida","hoja_vida.ficha_tecnica");
insert into modulo (Nombre, State) values("Configuracion","configuracion.servicios");
insert into modulo (Nombre, State) values("Mantenimiento","mantenimiento.reporte_servicio");

/*usuarios*/
insert into Usuario (NombreUsuario, Contrasena, FechaVecimiento, Estado, Email,NombreCompleto) values("frank","1234",str_to_date("2087/01/03","%Y/%m/%d"),1, "ospi89@hotmail.com", "ANTONIO DE LUQUE");
insert into Usuario (NombreUsuario, Contrasena, FechaVecimiento, Estado, Email,NombreCompleto) values("mena","1234",str_to_date("2087/01/03","%Y/%m/%d"),1, "menamdina@hotmail.com", "CARLOS MENA");
insert into Usuario (NombreUsuario, Contrasena, FechaVecimiento, Estado, Email,Url_Foto,NombreCompleto) values("ospi","1234",str_to_date("2087/01/03","%Y/%m/%d"),1, "zlinker89@gmail.com", "/Biomedico/public_html/fotos_perfiles/ospi.jpg", "FRANKLIN OSPINO");
insert into Usuario (NombreUsuario, Contrasena, FechaVecimiento, Estado, Email,Url_Foto,NombreCompleto) values("lkmorales1@gmail.com","1234",str_to_date("2017/12/18","%Y/%m/%d"),1, "lkmorales1@gmail.com", "/Biomedico/public_html/fotos_perfiles/default-user.png", "LUIS MORALES");

/*Rel usuario Modulo*/
insert into modulousuario (UsuarioId, ModuloId) values (3,1);
insert into modulousuario (UsuarioId, ModuloId) values (3,2);
insert into modulousuario (UsuarioId, ModuloId) values (3,3);
insert into modulousuario (UsuarioId, ModuloId) values (1,1);
insert into modulousuario (UsuarioId, ModuloId) values (1,2);
insert into modulousuario (UsuarioId, ModuloId) values (1,3);
insert into modulousuario (UsuarioId, ModuloId) values (2,1);
insert into modulousuario (UsuarioId, ModuloId) values (2,2);
insert into modulousuario (UsuarioId, ModuloId) values (2,3);

# LUIS
insert into modulousuario (UsuarioId, ModuloId) values (4,1);
insert into modulousuario (UsuarioId, ModuloId) values (4,2);
insert into modulousuario (UsuarioId, ModuloId) values (4,3);

insert into modulousuario (UsuarioId, ModuloId) values (16,1);
insert into modulousuario (UsuarioId, ModuloId) values (16,2);
insert into modulousuario (UsuarioId, ModuloId) values (16,3);
/*ROL*/
insert into Rol (Nombre) values ("Administrador");
/*Rel Rol --- Usuario*/
insert into RolUsuario (RolId, UsuarioId) values (1,1);
insert into RolUsuario (RolId, UsuarioId) values (1,2);
insert into RolUsuario (RolId, UsuarioId) values (1,3);
/*permisos*/
insert into Permiso (State, label, Tipo, ModuloId) values ("hoja_vida.ficha_tecnica", "Crear Equipo","ver vista", 1);
insert into Permiso (State, label, Tipo, ModuloId) values ("hoja_vida.Listado_equipos", "Listado de Equipos","ver vista", 1);
insert into Permiso (State, label, Tipo, ModuloId) values ("mantenimiento.reporte_escaneado", "Reporte externo","ver vista", 1);
insert into Permiso (State, label, Tipo, ModuloId) values ("mantenimiento.reporte_servicio", "Mantenimientos","ver vista", 3);
insert into Permiso (State, label, Tipo, ModuloId) values ("mantenimiento.ver_reporte", "Mantenimientos","ver oculto", 3);
insert into Permiso (State, label, Tipo, ModuloId) values ("mantenimiento.solicitud", "Solicitar","ver vista", 3);

insert into Permiso (State, label, Tipo, ModuloId) values ("configuracion.sedes", "Sedes","ver vista", 2);
insert into Permiso (State, label, Tipo, ModuloId) values ("configuracion.servicios", "Servicios","ver vista", 2);
insert into Permiso (State, label, Tipo, ModuloId) values ("configuracion.proveedores", "Proveedores","ver vista", 2);
insert into Permiso (State, label, Tipo, ModuloId) values ("configuracion.frecuencias", "Frecuencias","ver vista", 2);
insert into Permiso (State, label, Tipo, ModuloId) values ("mantenimiento.solicitudAdmin", "Solicitudes","ver vista", 3); # 11

insert into Permiso (State, label, Tipo, ModuloId) values ("mantenimiento.CronogramaCalibracion", "Calibraciones y calificaciones","ver vista", 3);
insert into Permiso (State, label, Tipo, ModuloId) values ("mantenimiento.CronogramaMantenimiento", "Mantenimiento Preventivo","ver vista", 3);
insert into Permiso (State, label, Tipo, ModuloId) values ("mantenimiento.InventarioEquipos", "Inventario de Equipos","ver vista", 3);
insert into Permiso (State, label, Tipo, ModuloId) values ("mantenimiento.Calibracion", "Calibración","ver vista", 3);# 15
insert into Permiso (State, label, Tipo, ModuloId) values ("mantenimiento.Traslados", "Traslados","ver vista", 3);
insert into Permiso (State, label, Tipo, ModuloId) values ("configuracion.Empresa", "Empresa","ver vista", 2);
insert into Permiso (State, label, Tipo, ModuloId) values ("configuracion.EncabezadoPiePagina", "Encabezados y Pie de Pagina","ver vista", 2);#18
insert into Permiso (State, label, Tipo, ModuloId) values ("hoja_vida.Estadisticas", "Datos Estadisticos","ver vista", 1);
insert into Permiso (State, label, Tipo, ModuloId) values ("configuracion.Usuarios", "Modulo Usuarios","ver vista", 2);#20
insert into Permiso (State, label, Tipo, ModuloId) values ("configuracion.Permisos", "Modulo Permisos","ver vista", 2);
#insert into Permiso (State, label, Tipo, ModuloId) values ("banco_sangre.Chats", "Preguntas y Respuestas","ver vista", 4);
/*REL Permiso -- ROL*/
insert into UsuarioPermiso (UsuarioId,PermisoId) values (2,1);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (2,2);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (2,3);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (2,4);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (2,5);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (2,6);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (2,7);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (2,8);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (2,9);
# ospi
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,1);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,2);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,3);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,4);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,5);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,6);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,7);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,8);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,9);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,10);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,11);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,12);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,13);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,14);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,15);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,16);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,17);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,18);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,19);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,20);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (3,21);

#PRUEBA
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,1);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,2);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,3);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,4);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,5);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,6);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,7);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,8);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,9);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,10);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,11);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,12);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,13);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,14);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,15);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,16);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,17);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,18);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,19);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,20);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (16,21);
# luis
insert into UsuarioPermiso (UsuarioId,PermisoId) values (4,1);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (4,2);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (4,3);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (4,4);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (4,5);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (4,6);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (4,7);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (4,8);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (4,9);
insert into UsuarioPermiso (UsuarioId,PermisoId) values (4,10);

create table Proveedor(
	ProveedorId int primary key auto_increment,
    Nombre varchar(500),
    Documento varchar(100),
    TipoDocumento varchar(10),
    Telefono varchar(200),
    Direccion varchar(200),
    Email varchar(100),
    
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
)ENGINE = INNODB;

create table FrecuenciaMantenimiento(
	FrecuenciaMantenimientoId int primary key auto_increment,
    Nombre varchar(200),
    NumeroMeses int,
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
);


create table Sede(
	SedeId int primary key auto_increment,
    Nombre varchar(300),
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
)ENGINE = INNODB;

create table SedeUsuario(
	SedeUsuarioId int primary key auto_increment,
    SedeId int,
    UsuarioId int,
    foreign key(SedeId) references Sede(SedeId),
    foreign key(UsuarioId) references Usuario(UsuarioId)
)ENGINE = INNODB;

create table Servicio(
	ServicioId int primary key auto_increment,
    Nombre varchar(300),
    SedeId int,
    
    foreign key(SedeId) references Sede(SedeId),
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
)ENGINE = INNODB;

create table ServicioUsuario(
	ServicioUsuarioId int primary key auto_increment,
    UsuarioId int,
    ServicioId int,
    foreign key(ServicioId) references Servicio(ServicioId),
    foreign key(UsuarioId) references Usuario(UsuarioId)
)ENGINE = INNODB;

create table Empresa(
	EmpresaId int primary key auto_increment,
    Nombre varchar(300),
    Nit varchar(100),
    Direccion varchar(300),
    Telefono varchar(200),
    Contacto varchar(200),
    SitioWeb varchar(600),
    Correo varchar(200),
    Logo mediumblob,
    
    #datos del correo para envios
    SMTP varchar(200),
    PuertoSmtp int,
    UsernameSmtp varchar(200),
    PasswordSmtp varchar(200),
    FormatoCorreo mediumtext,
    
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
)ENGINE = INNODB;

create table EncabezadoPiePagina(
	EncabezadoPiePaginaId int primary key auto_increment,
    EncabezadoHojaVida text,
    EncabezadoReporte text,
    Elaboro varchar(300),
    CargoElaboro varchar(200),
    FirmaElaboro mediumblob,
	Reviso varchar(300),
    CargoReviso varchar(200),
    FirmaReviso mediumblob,
    Aprobo varchar(300),
    CargoAprobo varchar(200),
    FirmaAprobo mediumblob,
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
)ENGINE = INNODB;

create table HojaVida(
	HojaVidaId int primary key auto_increment,
    SedeId int,
    ServicioId int,
    FrecuenciaMantenimientoId int,
    Ubicacion varchar(300),
    Equipo varchar(300),
    Marca varchar(300),
    Modelo varchar(300),
    Serie varchar(300),
    Inventario varchar(300),
    RegSanitario varchar(300),
    FechaAdquisicion varchar(300),
    Garantia varchar(300),
    ProveedorId int,
    Riesgo varchar(300),
    Clasificacion varchar(300),
    Voltaje varchar(300),
    Temperatura varchar(300),
    Amperaje varchar(300),
    Frecuencia varchar(300),
    Potencia varchar(300),
    Bateria varchar(300),
    RecomendacioneFabricante text,
    Accesorios text,
    Foto mediumblob,
    Estado varchar(200) default 'Activo',
    FechaInstalacion date,
    FechaCalibracion date,
    FrecuenciaCalibracionId int,
    
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime,
    
    foreign key(SedeId) references Sede(SedeId),
    foreign key(ServicioId) references Servicio(ServicioId),
    foreign key(ProveedorId) references Proveedor(ProveedorId),
    foreign key(FrecuenciaMantenimientoId) references FrecuenciaMantenimiento(FrecuenciaMantenimientoId),
    foreign key(FrecuenciaCalibracionId) references FrecuenciaMantenimiento(FrecuenciaMantenimientoId)
    
)ENGINE = INNODB;



create table Solicitud(
	SolicitudId int primary key auto_increment,
    SedeId int,
    ServicioId int,
    EquipoId int,
    Ubicacion varchar(200),
    Foto mediumblob,
    Descripcion text,
    Estado varchar(200) default 'Activo',
    FechaSolicitud datetime,
    FechaFinalizacion datetime,
    SolicitanteId int,
    
    CreatedBy varchar(200),
    CreatedAt datetime,
    ModifiedBy varchar(200),
    ModifiedAt timestamp default current_timestamp,
    foreign key(ServicioId) references Servicio(ServicioId),
    foreign key(SedeId) references Sede(SedeId),
    foreign key(SolicitanteId) references Usuario(UsuarioId),
    foreign key(EquipoId) references HojaVida(HojaVidaId)
)ENGINE = INNODB;


create table Reporte(
	ReporteId int primary key auto_increment,
    NumeroReporte varchar(200),
    SedeId int,
    Fecha date,
    ServicioId int,
    Solicitante varchar(200),
    Ubicacion varchar(200),
    Responsable varchar(200),
    TipoServicio varchar(200),
    EquipoId int,
    FallaReportada text,
    FallaDetectada varchar(200),
    ProcedimientoRealizado text,
    MedidasAplicadas varchar(200),
    TotalRepuesto varchar (200),
    Observaciones text,
    EstadoFinal varchar(200),
    Repuestos text,
    
    ResponsableNombre varchar(200),
    ResponsableCargo varchar(200),
    ResponsableFirma mediumblob,
    
    Recibefecha date,
    RecibeHora time,
    RecibeNombre varchar(200),
    RecibeCargo varchar(200),
    RecibeFirma mediumblob,
    
    TipoReporte varchar(200),
    ReporteArchivo mediumblob,
    
    SolicitudId int,
    Estado varchar(200) default "Borrador",
    
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime,
    foreign key(ServicioId) references Servicio(ServicioId),
    foreign key(SedeId) references Sede(SedeId),
    foreign key(EquipoId) references HojaVida(HojaVidaId),
    foreign key(SolicitudId) references Solicitud(SolicitudId)
)ENGINE = INNODB;



/* SEMILLA */
insert into Sede(Nombre) values ('CIELD');
insert into Sede(Nombre) values ('CSI');

insert into Servicio(Nombre,SedeId) values ("UCI ADULTO 2", 1);
/*insert into Servicio(Nombre,SedeId) values ("UCI", 1);
insert into Servicio(Nombre,SedeId) values ("UCI Pediatrica", 2);*/
/*REL USUARIo*/
insert into ServicioUsuario(ServicioId,UsuarioId) values (1,1);
insert into ServicioUsuario(ServicioId,UsuarioId) values (1,16);
insert into ServicioUsuario(ServicioId,UsuarioId) values (2,16);
insert into ServicioUsuario(ServicioId,UsuarioId) values (3,16);
insert into ServicioUsuario(ServicioId,UsuarioId) values (4,16);
insert into ServicioUsuario(ServicioId,UsuarioId) values (5,16);
/*insert into ServicioUsuario(ServicioId,UsuarioId) values (1,2);
insert into ServicioUsuario(ServicioId,UsuarioId) values (1,4);
insert into ServicioUsuario(ServicioId,UsuarioId) values (1,3);
insert into ServicioUsuario(ServicioId,UsuarioId) values (2,3);
insert into ServicioUsuario(ServicioId,UsuarioId) values (3,3);*/

INSERT INTO Proveedor(Nombre) values ("NOVAMEDICA");

insert into FrecuenciaMantenimiento(Nombre, NumeroMeses) values ("TRIMESTRAL", 3);
insert into FrecuenciaMantenimiento(Nombre, NumeroMeses) values ("CUATRIMESTRAL", 4);
insert into FrecuenciaMantenimiento(Nombre, NumeroMeses) values ("SEMESTRAL", 6);
insert into FrecuenciaMantenimiento(Nombre, NumeroMeses) values ("ANUAL", 12);
insert into FrecuenciaMantenimiento(Nombre, NumeroMeses) values ("NO APLICA", 12);


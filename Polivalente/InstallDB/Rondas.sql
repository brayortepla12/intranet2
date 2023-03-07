drop table if exists ActividadesUsuario;
drop table if exists ActividadesRonda;
drop table if exists Ronda;

create table if not exists Ronda(
	RondaId int primary key auto_increment,
    Fecha datetime,
    NombreJefeArea varchar(300),
    Realizo varchar(300),
    SedeId int,
    ServicioId int,
    Observaciones text,
    ObservacionSeguimiento text,
    Cumplimiento varchar(45),
    foreign key(SedeId) references Sede(SedeId),
    foreign key(ServicioId) references Servicio(ServicioId),
    Estado varchar(200) default 'Activo',
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
);

create table if not exists ActividadesRonda(
	ActividadesRondaId int primary key auto_increment,
    Tipo varchar(100),
    Equipo varchar(300),
    Descripcion varchar(300),
    RondaId int,
    foreign key(RondaId) references Ronda(RondaId),
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
);

create table if not exists ActividadesUsuario(
	ActividadesUsuarioId int primary key auto_increment,
    ActividadesRondaId int,
    UsuarioId int,
    foreign key(ActividadesRondaId) references ActividadesRonda(ActividadesRondaId),
    foreign key(UsuarioId) references Usuario(UsuarioId),
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
);
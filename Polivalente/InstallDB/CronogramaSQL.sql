drop table if exists Cronograma;

create table if not exists Cronograma(
	CronogramaId int primary key auto_increment,
    Nombre varchar(200),
    Inicio varchar(200),
    SedeId int,
    ServicioId int,
    HojaVidaId int,
    FrecuenciaMantenimientoId int,
    foreign key(SedeId) references Sede(SedeId),
    foreign key(ServicioId) references servicio(ServicioId),
    foreign key(HojaVidaId) references HojaVida(HojaVidaId),
    foreign key(FrecuenciaMantenimientoId) references FrecuenciaMantenimiento(FrecuenciaMantenimientoId),
    CreatedBy varchar(200),
    CreatedAt timestamp default current_timestamp,
    ModifiedBy varchar(200),
    ModifiedAt datetime
)
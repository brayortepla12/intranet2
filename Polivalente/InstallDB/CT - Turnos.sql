drop table if exists ct_Turno;
CREATE TABLE  ct_Turno  (
   TurnoId  int(11) NOT NULL AUTO_INCREMENT primary key,
   Nombre  varchar(50),
   Estado  varchar(200) DEFAULT 'Activo',
   CreatedBy  varchar(200) DEFAULT NULL,
   CreatedAt  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   ModifiedBy  varchar(200) DEFAULT NULL,
   ModifiedAt  datetime DEFAULT NULL
);

drop table if exists ct_Horario;
CREATE TABLE  ct_Horario  (
   HorarioId  int(11) NOT NULL AUTO_INCREMENT primary key,
   HoraInicio  varchar(50),
   HoraFin  varchar(50),
   DiaSemana  varchar(50),
   TurnoId int,
   Estado  varchar(200) DEFAULT 'Activo',
   CreatedBy  varchar(200) DEFAULT NULL,
   CreatedAt  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   ModifiedBy  varchar(200) DEFAULT NULL,
   ModifiedAt  datetime DEFAULT NULL,
   foreign key (TurnoId) references ct_Turno(TurnoId)
);
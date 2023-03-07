drop table if exists ct_Persona;
CREATE TABLE `ct_persona` (
  `PersonaId` int(11) NOT NULL AUTO_INCREMENT,
  `PrimerNombre` varchar(100) DEFAULT NULL,
  `SegundoNombre` varchar(100) DEFAULT NULL,
  `PrimerApellido` varchar(100) DEFAULT NULL,
  `SegundoApellido` varchar(100) DEFAULT NULL,
  `Genero` varchar(5) DEFAULT NULL,
  `Cedula` varchar(45) DEFAULT NULL,
  `Servicio` varchar(45) DEFAULT NULL,
  `FechaNacimiento` date DEFAULT NULL,
  `Cargo` varchar(50) DEFAULT NULL,
  `CodigoTarjeta` varchar(50) DEFAULT NULL,
  `Foto` mediumblob,
  `Estado` varchar(200) DEFAULT 'Activo',
  `CreatedBy` varchar(200) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`PersonaId`)
);

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
   Inicio  varchar(50),
   Fin  varchar(50),
   TurnoId int,
   Estado  varchar(200) DEFAULT 'Activo',
   CreatedBy  varchar(200) DEFAULT NULL,
   CreatedAt  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   ModifiedBy  varchar(200) DEFAULT NULL,
   ModifiedAt  datetime DEFAULT NULL,
   foreign key (TurnoId) references ct_Turno(TurnoId)
);

drop table if exists ct_Control;
CREATE TABLE  ct_Control  (
   ControlId  int(11) NOT NULL AUTO_INCREMENT primary key,
   Fecha  datetime,
   PersonaId int,
   Tipo varchar(50),
   Estado  varchar(200) DEFAULT 'Activo',
   CreatedBy  varchar(200) DEFAULT NULL,
   CreatedAt  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   ModifiedBy  varchar(200) DEFAULT NULL,
   ModifiedAt  datetime DEFAULT NULL,
   foreign key (PersonaId) references ct_Persona(PersonaId)
);

drop table if exists ct_PersonaTurno;
CREATE TABLE  ct_PersonaTurno  (
   PersonaTurnolId  int(11) NOT NULL AUTO_INCREMENT primary key,
   PersonaId int,
   TurnoId int,
   Estado  varchar(200) DEFAULT 'Activo',
   CreatedBy  varchar(200) DEFAULT NULL,
   CreatedAt  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   ModifiedBy  varchar(200) DEFAULT NULL,
   ModifiedAt  datetime DEFAULT NULL,
   foreign key (TurnoId) references ct_Turno(TurnoId),
   foreign key (PersonaId) references ct_Persona(PersonaId)
);

#INSERT INTO `polivalente`.`ct_persona` (`PersonaId`, `Nombres`, `Cargo`, `CodigoTarjeta`, `Foto`, `Estado`, `CreatedAt`) VALUES ('1', 'Franklin Ospino De luque', 'Desarrollador', '0010876812', '/Polivalente/public_html/fotos_perfiles/ospi.jpg', 'Activo', '2019-04-24 10:26:55');
#INSERT INTO `polivalente`.`ct_persona` (`PersonaId`, `Nombres`, `Cargo`, `CodigoTarjeta`, `Foto`, `Estado`, `CreatedAt`) VALUES ('2', 'Carlos Augusto Mena Medina', 'Asesor de presidencia', '0011282552', '/Polivalente/public_html/fotos_perfiles/mena.jpg', 'Activo', '2019-04-24 11:01:44');




drop table if exists thc_thistoria;
drop table if exists thc_notas;
drop table if exists thc_trazabilidad;
drop table if exists thc_grupousuario;
drop table if exists thc_historia;
drop table if exists thc_grupo;
CREATE TABLE thc_historia (
  `HistoriaId` int(11) NOT NULL AUTO_INCREMENT primary key,
  `NOADMISION` varchar(20),
  `PNOMBRE` varchar(100),
  `SNOMBRE` varchar(100),
  `PAPELLIDO` varchar(100),
  `SAPELLIDO` varchar(100),
  `TIPO_DOC` varchar(10),
  `IDAFILIADO` varchar(20),
  `FECHAALTAADMINISTRATIVA` datetime,
  `FECHAALTAMED` datetime,
  `CERRADA` varchar(200),
  `EPS` varchar(200),
  `SECTOR` varchar(200),
  `Estado` varchar(10)  DEFAULT 'Activo',
  `CreatedBy` varchar(30)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(30)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL
);

CREATE TABLE thc_grupo (
  `GrupoId` int(11) NOT NULL AUTO_INCREMENT primary key,
  `Nombre` varchar(30),
  `CanShow` tinyint default 1,
  `Estado` varchar(10)  DEFAULT 'Activo',
  `CreatedBy` varchar(30)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(30)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL
);

CREATE TABLE thc_grupousuario (
  `GrupoUsuarioId` int(11) NOT NULL AUTO_INCREMENT primary key,
  `UsuarioId` int,
  `GrupoId` int,
  FOREIGN KEY (GrupoId) REFERENCES thc_grupo(GrupoId),
  FOREIGN KEY (UsuarioId) REFERENCES usuario(UsuarioId),
  `Estado` varchar(10)  DEFAULT 'Activo',
  `CreatedBy` varchar(30)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(30)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL
);

CREATE TABLE thc_thistoria (
  `THistoriaId` int(11) NOT NULL AUTO_INCREMENT primary key,
  `UsuarioId` int,
  `UsuarioRecibeId` int default null,
  `NombreUsuario` varchar(200),
  `GrupoId` int,
  `HistoriaId` int,
  `Fecha` datetime,
  `FechaFin` datetime default null,
  `IsEntrega` tinyint default 0,
  `IsRecibido` tinyint default 0,
  FOREIGN KEY (GrupoId) REFERENCES thc_grupousuario(GrupoId),
  FOREIGN KEY (UsuarioId) REFERENCES usuario(UsuarioId),
  FOREIGN KEY (HistoriaId) REFERENCES thc_historia(HistoriaId),
  `Estado` varchar(50)  DEFAULT 'Activo',
  `CreatedBy` varchar(30)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(30)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL
);

CREATE TABLE thc_notas (
  `NotasId` int(11) NOT NULL AUTO_INCREMENT primary key,
  `UsuarioId` int,
  `GrupoId` int,
  `HistoriaId` int,
  `Fecha` date,
  Observacion text,
  FOREIGN KEY (GrupoId) REFERENCES thc_grupousuario(GrupoId),
  FOREIGN KEY (UsuarioId) REFERENCES usuario(UsuarioId),
  FOREIGN KEY (HistoriaId) REFERENCES thc_historia(HistoriaId),
  `Estado` varchar(10)  DEFAULT 'Activo',
  `CreatedBy` varchar(30)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(30)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL
);

INSERT INTO `polivalente`.`thc_grupo` (`Nombre`) VALUES ('Facturación');
INSERT INTO `polivalente`.`thc_grupo` (`Nombre`) VALUES ('Auditoria');
INSERT INTO `polivalente`.`thc_grupo` (`Nombre`) VALUES ('Radicación');
INSERT INTO `polivalente`.`thc_grupo` (`Nombre`) VALUES ('Autorización');
INSERT INTO `polivalente`.`thc_grupo` (`Nombre`) VALUES ('Archivo');
INSERT INTO `polivalente`.`thc_grupo` (`Nombre`, `CanShow`) VALUES ('Enfermeria', 0);

INSERT INTO `polivalente`.`thc_grupousuario` (`GrupoUsuarioId`, `UsuarioId`, `GrupoId`, `Estado`, `CreatedAt`) VALUES ('1', '3', '1', 'Activo', '2020-03-12 10:08:04');
INSERT INTO `polivalente`.`thc_grupousuario` (`GrupoUsuarioId`, `UsuarioId`, `GrupoId`, `Estado`, `CreatedAt`) VALUES ('2', '50', '1', 'Activo', '2020-03-12 10:08:04');
INSERT INTO `polivalente`.`thc_grupousuario` (`GrupoUsuarioId`, `UsuarioId`, `GrupoId`, `Estado`, `CreatedAt`) VALUES ('3', '51', '1', 'Activo', '2020-03-12 10:08:04');

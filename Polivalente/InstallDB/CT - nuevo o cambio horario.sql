drop table if exists ct_SolicitudHorario;

CREATE TABLE ct_SolicitudHorario (
  `SolicitudHorarioId` int(11) NOT NULL AUTO_INCREMENT,
  `JefeId` int,
  `Mes` int,
  `Year` int,
  `IsRevisado` tinyint,
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`SolicitudHorarioId`)
);
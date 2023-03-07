drop table if exists ct_variable;
CREATE TABLE ct_variable (
  `VariableId` int(11) NOT NULL primary key AUTO_INCREMENT,
  `Variable` varchar(200),
  `Abreviatura` varchar(4),
  `FechaInicio` varchar(200),
  `FechaFin` varchar(200),
  `IsDoble` tinyint default 0,
  `FechaInicio2` varchar(200),
  `FechaFin2` varchar(200),
  `Estado` varchar(200) COLLATE latin1_spanish_ci DEFAULT 'Activo',
  `CreatedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL
);
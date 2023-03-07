Drop table if exists Sistemas_cronogramaservicio;
CREATE TABLE `Sistemas_cronogramaservicio` (
  `CronogramaServicioId` int(11) NOT NULL AUTO_INCREMENT,
  `FrecuenciaMantenimientoId` int(11) DEFAULT NULL,
  `SedeId` int(11) DEFAULT NULL,
  `ServicioId` int(11) DEFAULT NULL,
  `Observaciones` text,
  `MesInicial` int(11) DEFAULT NULL,
  `Vigencia` int(11) DEFAULT NULL,
  `Historico` longtext,
  `Estado` varchar(200) DEFAULT 'Activo',
  `CreatedBy` varchar(200) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`CronogramaServicioId`),
  KEY `SedeId` (`SedeId`),
  KEY `ServicioId` (`ServicioId`),
  KEY `FrecuenciaMantenimientoId` (`FrecuenciaMantenimientoId`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

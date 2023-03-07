drop table if exists sistemas_ReporteDCronograma;
drop table if exists sistemas_DetalleCronograma;
drop table if exists sistemas_Cronograma;

CREATE TABLE sistemas_Cronograma (
  `CronogramaId` int(11) NOT NULL AUTO_INCREMENT,
  `Vigencia` int,
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`CronogramaId`)
);

CREATE TABLE sistemas_DetalleCronograma (
  `DetalleCronogramaId` int(11) NOT NULL AUTO_INCREMENT,
  `CronogramaId` int,
  `ServicioId` int,
  `Mes` int,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`DetalleCronogramaId`),
  FOREIGN KEY (ServicioId) REFERENCES servicio(ServicioId),
  FOREIGN KEY (CronogramaId) REFERENCES sistemas_Cronograma(CronogramaId)
);

CREATE TABLE sistemas_ReporteDCronograma (
  `ReporteDCronogramaId` int(11) NOT NULL AUTO_INCREMENT,
  `DetalleCronogramaId` int,
  `ReporteId` int,
  `HojaVidaId` int,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ReporteDCronogramaId`),
  FOREIGN KEY (DetalleCronogramaId) REFERENCES sistemas_DetalleCronograma(DetalleCronogramaId)
);

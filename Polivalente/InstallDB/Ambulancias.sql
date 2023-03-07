/*drop table if exists ambulancia_hojavida;

CREATE TABLE `ambulancia_hojavida` (
  `HojaVidaId` int(11) NOT NULL AUTO_INCREMENT,
  `SedeId` int(11) DEFAULT NULL,
  `ServicioId` int(11) DEFAULT NULL,
  `Placa` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `Marca` varchar(300) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Linea` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `Modelo` varchar(300) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ClaseVehiculo` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `TipoCarroceria` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `LicenciaTransito` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `Soat` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `Cilindrada` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `Color` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `Motor` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `Serie` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `Combustible` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `Capacidad` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `Foto` mediumblob,
  `Estado` varchar(200) COLLATE latin1_spanish_ci DEFAULT 'Activo',
  `CreatedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`HojaVidaId`),
  FOREIGN KEY (`SedeId`) REFERENCES `ambulancia_sede` (`SedeId`),
  FOREIGN KEY (`ServicioId`) REFERENCES `ambulancia_servicio` (`ServicioId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;*/
/*drop table if exists ambulancia_detallereporte;
drop table if exists ambulancia_reporte;
drop table if exists ambulancia_detalle;

CREATE TABLE `ambulancia_detalle` (
  `DetalleId` int(11) NOT NULL AUTO_INCREMENT,
  `FrecuenciaKm` int(11),
  `Descripcion` varchar(200),
  `Estado` varchar(200) COLLATE latin1_spanish_ci DEFAULT 'Activo',
  `CreatedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`DetalleId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

CREATE TABLE `ambulancia_reporte` (
  `ReporteId` int(11) NOT NULL AUTO_INCREMENT,
  `SedeId` int(11) DEFAULT NULL,
  `HojaVidaId` int(11) DEFAULT NULL,
  `Fecha` date,
  `LastKm` int(11),
  `Km` int(11),
  `Descripcion` TEXT,
  `Notas` TEXT,
  `Estado` varchar(200) COLLATE latin1_spanish_ci DEFAULT 'Activo',
  `CreatedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`ReporteId`),
  FOREIGN KEY (`SedeId`) REFERENCES `sede` (`SedeId`),
  FOREIGN KEY (`HojaVidaId`) REFERENCES `ambulancia_hojavida` (`HojaVidaId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

CREATE TABLE `ambulancia_detallereporte` (
  `DetalleReporteId` int(11) NOT NULL AUTO_INCREMENT,
  `ReporteId` int(11) DEFAULT NULL,
  `DetalleId` int(11) DEFAULT NULL,
  `Estado` varchar(200) COLLATE latin1_spanish_ci DEFAULT 'Activo',
  `CreatedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`DetalleReporteId`),
  FOREIGN KEY (`ReporteId`) REFERENCES `ambulancia_reporte` (`ReporteId`),
  FOREIGN KEY (`DetalleId`) REFERENCES `ambulancia_detalle` (`DetalleId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;*/

drop table if exists ambulancia_km;
CREATE TABLE `ambulancia_km` (
  `KmId` int(11) NOT NULL AUTO_INCREMENT,
  `ReporteId` int(11) DEFAULT NULL,
  `HojaVidaId` int(11) DEFAULT NULL,
  `Km` int(11) DEFAULT NULL,
  `KmAnterior` int(11) DEFAULT NULL,
  `Estado` varchar(200) COLLATE latin1_spanish_ci DEFAULT 'Activo',
  `CreatedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`KmId`),
  FOREIGN KEY (`ReporteId`) REFERENCES `ambulancia_reporte` (`SedeId`),
  FOREIGN KEY (`HojaVidaId`) REFERENCES `ambulancia_hojavida` (`HojaVidaId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

ALTER TABLE `polivalente`.`ambulancia_reporte` 
ADD COLUMN `TipoMantenimiento` VARCHAR(45) NULL AFTER `HojaVidaId`;

DROP TABLE IF EXISTS bs_SensorTemperatura;
CREATE TABLE bs_SensorTemperatura (
  `SensorTemperaturaId` int(11) NOT NULL primary key AUTO_INCREMENT,
  `Fecha` datetime,
  `Temperatura` varchar(15),
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL
);
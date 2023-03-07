CREATE TABLE registro_de_logs (
  `LogId` int(11) NOT NULL AUTO_INCREMENT primary key,
  `log` mediumtext,
  `fecha` date,
  `hora` time
);
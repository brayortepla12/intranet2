drop table if exists Persona;
CREATE TABLE Persona (
  `PersonaId` int(11) primary key NOT NULL AUTO_INCREMENT,
  `Nombres` varchar(160),
  `Documento` varchar(20),
  `Telefono` varchar(20),
  `Email` varchar(50),
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);


drop table if exists Contacto;
CREATE TABLE Contacto (
  `ContactoId` int(11) primary key NOT NULL AUTO_INCREMENT,
  `Nombres` varchar(100),
  `Apellidos` varchar(100),
  `Email` varchar(50),
  `Asunto` varchar(200),
  `Mensaje` text,
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

drop table if exists sistemas_Consecutivo;
drop table if exists sistemas_DetalleActa;
drop table if exists sistemas_Acta;
CREATE TABLE sistemas_Acta (
  `ActaId` int(11) NOT NULL AUTO_INCREMENT,
  `NumeroActa` int,
  `Fecha` date,
  `ServicioId` int NULL,
  `RecibeId` int NULL,
  `ResponsableId` int,
  `Descripcion` text,
  `Motivo` text,
  `TipoActa` varchar(45),
  `Nota` text,
  MensajeIntroductorio text,
  `Area` varchar(200),
  `Destino` varchar(200),
  `RecibeN` varchar(200),
  `RecibeC` varchar(200),
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`ActaId`),
  FOREIGN KEY (ServicioId) REFERENCES servicio(ServicioId),
  FOREIGN KEY (RecibeId) REFERENCES usuario(UsuarioId),
  FOREIGN KEY (ResponsableId) REFERENCES usuario(UsuarioId)
);


CREATE TABLE sistemas_DetalleActa (
  `DetalleActaId` int(11) NOT NULL AUTO_INCREMENT,
  ActaId int(11) DEFAULT NULL,
  `Cantidad` int,
  `HojaVidaId` int null,
  `Elemento` varchar(50),
  `Marca` varchar(50),
  `Modelo` varchar(20),
  `Serial` varchar(20),
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`DetalleActaId`),
  FOREIGN KEY (ActaId) REFERENCES sistemas_acta(ActaId)
);

CREATE TABLE sistemas_Consecutivo (
  `ConsecutivoId` int(11) NOT NULL AUTO_INCREMENT,
  `Consecutivo` int,
  `TipoActa` varchar(10),
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ConsecutivoId`)
);

INSERT INTO `polivalente`.`sistemas_consecutivo` (`ConsecutivoId`, `Consecutivo`, `TipoActa`, `CreatedAt`) VALUES ('1', '0', 'Entrega', '2019-08-20 08:35:28');
INSERT INTO `polivalente`.`sistemas_consecutivo` (`ConsecutivoId`, `Consecutivo`, `TipoActa`, `CreatedAt`) VALUES ('2', '0', 'Salida', '2019-08-20 08:35:28');
INSERT INTO `polivalente`.`sistemas_consecutivo` (`ConsecutivoId`, `Consecutivo`, `TipoActa`, `CreatedAt`) VALUES ('3', '0', 'Baja', '2019-08-20 08:35:28');



ALTER TABLE `polivalente`.`encabezadopiepagina` 
ADD COLUMN `EncabezadoSistemas_ActaBaja` TEXT NULL AFTER `ModifiedAt`,
ADD COLUMN `EncabezadoSistemas_ActaEntrega` TEXT NULL AFTER `EncabezadoSistemas_ActaBaja`,
ADD COLUMN `EncabezadoSistemas_ActaSalida` TEXT NULL AFTER `EncabezadoSistemas_ActaEntrega`;

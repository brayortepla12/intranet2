drop table if exists ambulancia_SolicitudMantenimiento;
CREATE TABLE ambulancia_SolicitudMantenimiento (
  `SolicitudMantenimientoId` int(11) NOT NULL AUTO_INCREMENT,
  HojaVidaId int(11) NULL DEFAULT NULL,
  `Descripcion` text,
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`SolicitudMantenimientoId`),
  FOREIGN KEY (HojaVidaId) REFERENCES ambulancia_hojavida(HojaVidaId)
);

drop table if exists ambulancia_Proveedor;
CREATE TABLE ambulancia_Proveedor (
  `ProveedorId` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(200),
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL
);

drop table if exists ambulancia_Item;
CREATE TABLE ambulancia_Item (
  `ItemId` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(500),
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL
);

drop table if exists ambulancia_Factura;
CREATE TABLE ambulancia_Factura (
  `FacturaId` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ProveedorId int(11) DEFAULT NULL,
  SolicitudMantenimientoId int(11) DEFAULT NULL,
  `UrlArchivo` varchar(500),
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  FOREIGN KEY (SolicitudMantenimientoId) REFERENCES ambulancia_solicitudmantenimiento(SolicitudMantenimientoId),
  FOREIGN KEY (ProveedorId) REFERENCES ambulancia_Proveedor(ProveedorId)
);

drop table if exists ambulancia_DetalleFactura;
CREATE TABLE ambulancia_DetalleFactura (
  `DetalleFacturaId` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  FacturaId int(11) DEFAULT NULL,
  ItemId int(11) DEFAULT NULL,
  `Cant` int,
  `Precio` decimal(10,2),
  `Estado` varchar(200)  DEFAULT 'Activo',
  `CreatedBy` varchar(200)  DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200)  DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  FOREIGN KEY (FacturaId) REFERENCES ambulancia_Factura(FacturaId),
  FOREIGN KEY (ItemId) REFERENCES ambulancia_Item(ItemId)
);

ALTER TABLE `polivalente`.`ambulancia_reporte` 
ADD COLUMN `SolicitudMantenimientoId` INT NULL DEFAULT NULL AFTER `SedeId`;
ALTER TABLE `polivalente`.`ambulancia_solicitudmantenimiento` 
ADD COLUMN `TipoSolicitud` VARCHAR(45) NULL AFTER `SolicitudMantenimientoId`;


drop table if exists almacen_itempedido;
drop table if exists almacen_pedidoalmacen;
CREATE TABLE almacen_pedidoalmacen (
  PedidoAlmacenId int primary key auto_increment,
  FechaSolicitud datetime,
  NombreSolicitante varchar(200),
  CargoSolicitante varchar(200),
  FechaEntrega datetime,
  FechaRecibe datetime,
  SedeId int,
  ServicioId int,
  SolicitanteId int,
  Observacion text,
  NombreRecibe varchar(200),
  NombreEntrega varchar(200),
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  foreign key(SedeId) references Sede(SedeId),
  foreign key(ServicioId) references Servicio(ServicioId),
  foreign key(SolicitanteId) references Usuario(UsuarioId)
) ENGINE=InnoDB;

CREATE TABLE almacen_itempedido (
  ItemPedidoId int primary key auto_increment,
  ArticuloId int,
  CantidadSolicitada int,
  CantidadEntregada int,
  Pendiente text,
  PedidoAlmacenId int,
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  foreign key(PedidoAlmacenId) references almacen_pedidoalmacen(PedidoAlmacenId)
) ENGINE=InnoDB;
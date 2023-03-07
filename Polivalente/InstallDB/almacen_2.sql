drop table if exists Almacen_RelacionCosto;
drop table if exists Almacen_Articulo;

CREATE TABLE Almacen_Articulo (
  ArticuloId int primary key auto_increment,
  CodigoKrystalos varchar(45),
  NombreKrystalos varchar(100) DEFAULT NULL,
  Nombre varchar(100),
  GrupoId int, 
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime,
  foreign key(GrupoId) references Almacen_Grupo(GrupoId)
) ENGINE=InnoDB;


CREATE TABLE Almacen_RelacionCosto (
  RelacionCostoId int primary key auto_increment,
  ArticuloId int,
  Cantidad int,
  SedeId int,
  ServicioId int,
  UsuarioId int,
  DiasConsumo int,
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  foreign key(ArticuloId) references Almacen_Articulo(ArticuloId),
  foreign key(SedeId) references Sede(SedeId),
  foreign key(ServicioId) references Servicio(ServicioId),
  foreign key(UsuarioId) references Usuario(UsuarioId)
) ENGINE=InnoDB;






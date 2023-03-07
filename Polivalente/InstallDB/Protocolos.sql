drop table if exists PC_Anexo;
drop table if exists PC_Seguimiento;
drop table if exists PC_Proceso;
drop table if exists PC_Verificador;
drop table if exists PC_FlujoTrabajo;
drop table if exists PC_Protocolo;


CREATE TABLE pc_protocolo (
  ProtocoloId int(11) NOT NULL AUTO_INCREMENT,
  Nombre varchar(100) DEFAULT NULL,
  Formulario mediumtext,
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  PRIMARY KEY (ProtocoloId)
) ENGINE=InnoDB;


CREATE TABLE pc_flujotrabajo (
  FlujoTrabajoId int(11) NOT NULL AUTO_INCREMENT,
  ProtocoloId int(11) DEFAULT NULL,
  Estado varchar(100) DEFAULT 'Activo',
  Orden int(11) DEFAULT NULL,
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  PRIMARY KEY (FlujoTrabajoId),
  KEY ProtocoloId (ProtocoloId),
  CONSTRAINT pc_flujotrabajo_ibfk_1 FOREIGN KEY (ProtocoloId) REFERENCES pc_protocolo (ProtocoloId)
) ENGINE=InnoDB;



CREATE TABLE pc_verificador (
  VerificadorId int(11) NOT NULL AUTO_INCREMENT,
  SedeId int(11) DEFAULT NULL,
  ServicioId int(11) DEFAULT NULL,
  UsuarioId int(11) DEFAULT NULL,
  FlujoTrabajoId int(11) DEFAULT NULL,
  Estado varchar(45) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  PRIMARY KEY (VerificadorId),
  KEY SedeId (SedeId),
  KEY ServicioId (ServicioId),
  KEY UsuarioId (UsuarioId),
  KEY FlujoTrabajoId (FlujoTrabajoId),
  CONSTRAINT pc_verificador_ibfk_1 FOREIGN KEY (SedeId) REFERENCES sede (SedeId),
  CONSTRAINT pc_verificador_ibfk_2 FOREIGN KEY (ServicioId) REFERENCES servicio (ServicioId),
  CONSTRAINT pc_verificador_ibfk_3 FOREIGN KEY (UsuarioId) REFERENCES usuario (UsuarioId),
  CONSTRAINT pc_verificador_ibfk_4 FOREIGN KEY (FlujoTrabajoId) REFERENCES pc_flujotrabajo (FlujoTrabajoId)
) ENGINE=InnoDB;




CREATE TABLE pc_proceso (
  ProcesoId int(11) NOT NULL AUTO_INCREMENT,
  SolicitanteId int(11) DEFAULT NULL,
  Nombre varchar(200) DEFAULT NULL,
  ProtocoloId int(11) DEFAULT NULL,
  SedeId int(11) DEFAULT NULL,
  ServicioId int(11) DEFAULT NULL,
  DatosFormulario longblob,
  OrdenEnCurso int(11) DEFAULT '0',
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  PRIMARY KEY (ProcesoId),
  KEY ProtocoloId (ProtocoloId),
  KEY SolicitanteId (SolicitanteId),
  KEY SedeId (SedeId),
  KEY ServicioId (ServicioId),
  CONSTRAINT pc_proceso_ibfk_1 FOREIGN KEY (ProtocoloId) REFERENCES pc_protocolo (ProtocoloId),
  CONSTRAINT pc_proceso_ibfk_2 FOREIGN KEY (SolicitanteId) REFERENCES usuario (UsuarioId),
  CONSTRAINT pc_proceso_ibfk_3 FOREIGN KEY (SedeId) REFERENCES Sede (SedeId),
  CONSTRAINT pc_proceso_ibfk_4 FOREIGN KEY (ServicioId) REFERENCES Servicio (ServicioId)
) ENGINE=InnoDB;

CREATE TABLE pc_seguimiento (
  SeguimientoId int(11) NOT NULL AUTO_INCREMENT,
  FlujoTrabajoId int(11) DEFAULT NULL,
  ProcesoId int(11) DEFAULT NULL,
  FirmaVerificador mediumblob,
  VerificadorId int(11) DEFAULT NULL,
  VB tinyint(1) DEFAULT NULL,
  DatosAnexos longblob,
  Observacion text,
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  PRIMARY KEY (SeguimientoId),
  KEY FlujoTrabajoId (FlujoTrabajoId),
  KEY ProcesoId (ProcesoId),
  KEY VerificadorId (VerificadorId),
  CONSTRAINT pc_seguimiento_ibfk_1 FOREIGN KEY (FlujoTrabajoId) REFERENCES pc_flujotrabajo (FlujoTrabajoId),
  CONSTRAINT pc_seguimiento_ibfk_2 FOREIGN KEY (ProcesoId) REFERENCES pc_proceso (ProcesoId),
  CONSTRAINT pc_seguimiento_ibfk_3 FOREIGN KEY (VerificadorId) REFERENCES usuario (UsuarioId)
) ENGINE=InnoDB;


CREATE TABLE pc_anexo (
  AnexoId int(11) NOT NULL AUTO_INCREMENT,
  VerificadorId int(11) DEFAULT NULL,
  FlujoTrabajoId int(11) DEFAULT NULL,
  Anexo longblob,
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  PRIMARY KEY (AnexoId),
  KEY FlujoTrabajoId (FlujoTrabajoId),
  KEY VerificadorId (VerificadorId),
  CONSTRAINT pc_anexo_ibfk_1 FOREIGN KEY (FlujoTrabajoId) REFERENCES pc_flujotrabajo (FlujoTrabajoId),
  CONSTRAINT pc_anexo_ibfk_2 FOREIGN KEY (VerificadorId) REFERENCES usuario (UsuarioId)
) ENGINE=InnoDB;


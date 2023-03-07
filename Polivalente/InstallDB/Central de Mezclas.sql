drop table if exists cm_DispositivoMedicoByRonda;
drop table if exists cm_DetalleRondaVerificacion;
drop table if exists cm_rondaverificacion;
drop table if exists cm_DetalleOrdenRR;
drop table if exists cm_OrdenRR;
drop table if exists cm_DispositivoMedico;
drop table if exists cm_medicamento;
drop table if exists cm_tipomedicamento;

CREATE TABLE cm_tipomedicamento(
  TipoMedicamentoId int(11) primary key AUTO_INCREMENT,
  Nombre varchar(200),
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE cm_medicamento(
  MedicamentoId int(11) primary key AUTO_INCREMENT,
  TipoMedicamentoId int,
  Nombre varchar(200),
  NombreAbreviado varchar(18),
  Concentracion float,
  TemperaturaUso varchar(200),
  Recostituyente varchar(200),
  TipoMedicamento varchar(200),
  VolumenReconstitucion float, #mL
  VolumenFinal float, #mL
  TiempoEstabilidad int,
  FechaLimiteUso2_8 int,
  FechaLimiteUso20_25 int,
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  foreign key(TipoMedicamentoId) references cm_tipomedicamento(TipoMedicamentoId)
) ENGINE=InnoDB;

CREATE TABLE cm_DispositivoMedico(
  DispositivoMedicoId int(11) primary key AUTO_INCREMENT,
  Nombre varchar(200),
  Concentracion float,
  MedidaConcentracion varchar(10),
  Laboratorio varchar(200),
  Lote varchar(200),
  FechaVencimiento date,
  RegistroInvima varchar(200),
  IsVehiculo bool default 0,
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;



CREATE TABLE cm_rondaverificacion(
  RondaVerificacionId int(11) primary key AUTO_INCREMENT,
  Fecha date,
  DireccionTecnicaId int DEFAULT 3,
  DireccionTecnica bool DEFAULT False,
  ACalidadId int DEFAULT 3,
  ACalidad bool DEFAULT False,
  QFarmaceuticoId int DEFAULT 3,
  QFarmaceutico bool DEFAULT False,
  AFarmaciaId int DEFAULT 3,
  AFarmacia bool DEFAULT False,
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE cm_DetalleRondaVerificacion(
  DetalleRondaVerificacionId int(11) primary key AUTO_INCREMENT,
  RondaVerificacionId int,
  Sector varchar(200),
  PNombre varchar(200),
  SNombre varchar(200),
  PApellido varchar(200),
  SApellido varchar(200),
  IdAfiliado varchar(200),
  NoAdmision varchar(200),
  MedicamentoId int,
  VehiculoId int,
  Dosis float,
  Volumen float,
  Cantidad float,
  EstadoPaciente varchar(100) DEFAULT 'Nuevo',
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  foreign key(RondaVerificacionId) references cm_rondaverificacion(RondaVerificacionId),
  foreign key(MedicamentoId) references cm_medicamento(MedicamentoId),
  foreign key(VehiculoId) references cm_dispositivomedico(DispositivoMedicoId)
) ENGINE=InnoDB;

CREATE TABLE cm_DispositivoMedicoByRonda(
  DispositivoMedicoByRondaId int(11) primary key AUTO_INCREMENT,
  DispositivoMedicoId int,
  MedicamentoId int,
  RondaVerificacionId int,
  Cantidad int,
  foreign key(MedicamentoId) references cm_medicamento(MedicamentoId),
  foreign key(DispositivoMedicoId) references cm_DispositivoMedico(DispositivoMedicoId),
  foreign key(RondaVerificacionId) references cm_rondaverificacion(RondaVerificacionId),
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE cm_OrdenRR(
  OrdenRRId int(11) primary key AUTO_INCREMENT,
  Fecha date,
  TipoMedicamento varchar(50),
  DireccionTecnicaId int,
  DireccionTecnica bool DEFAULT False,
  AProduccionId int DEFAULT 3,
  AProduccion bool DEFAULT False,
  AFarmaciaId int DEFAULT 3,
  AFarmacia bool DEFAULT False,
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE cm_DetalleOrdenRR(
  DetalleOrdenRR int(11) primary key AUTO_INCREMENT,
  OrdenRRId int,
  MedicamentoId int,
  LoteFabricante varchar(200),
  LotePTerminado varchar(200),
  FechaVencimientoFabricante date,
  FechaVencimiento date,
  Cantidad int,
  VehiculoId int,
  foreign key(VehiculoId) references cm_dispositivomedico(DispositivoMedicoId),
  foreign key(OrdenRRId) references cm_OrdenRR(OrdenRRId),
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;

ALTER TABLE `polivalente`.`cm_medicamento` 
ADD COLUMN `FormaFarmaceutica` VARCHAR(45) NULL DEFAULT 'RECONSTITUYENTE' AFTER `FechaLimiteUso20_25`,
ADD COLUMN `Laboratorio` VARCHAR(45) NULL AFTER `FormaFarmaceutica`,
ADD COLUMN `RegInvima` VARCHAR(45) NULL AFTER `Laboratorio`,
ADD COLUMN `Codigo` VARCHAR(45) NULL AFTER `RegInvima`;



/*SEED*/
INSERT INTO cm_tipomedicamento (Nombre) VALUES ("Esteriles");
INSERT INTO cm_tipomedicamento (Nombre) VALUES ("Antibioticos");
/*MEDICAMENTOS */
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `FechaLimiteUso2_8`, `FechaLimiteUso20_25`, `Estado`, `CreatedAt`) VALUES ('1', '1', 'CAFEINA', 'CAFEINA', '500', '20 - 25 °C', 'N/A', '0', '2', '1', '24', '0', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `FechaLimiteUso2_8`, `FechaLimiteUso20_25`, `Estado`, `CreatedAt`) VALUES ('2', '1', 'METILPREDNISOLONA', 'METILPREDN', '500', '20 - 25 °C', 'N/A', '5', '5', '1', '0', '0', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `FechaLimiteUso2_8`, `FechaLimiteUso20_25`, `Estado`, `CreatedAt`) VALUES ('3', '1', 'METOCLOPRAMIDA', 'METOCLOPRA', '10', '20 - 25 °C', 'N/A', '0', '2', '1', '0', '0', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `FechaLimiteUso2_8`, `FechaLimiteUso20_25`, `Estado`, `CreatedAt`) VALUES ('4', '1', 'FUROSEMIDA', 'FUROSEMIDA', '20', '20 - 25 °C', 'N/A', '0', '2', '1', '0', '0', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `FechaLimiteUso2_8`, `FechaLimiteUso20_25`, `Estado`, `CreatedAt`) VALUES ('5', '1', 'DEXAMETASONA', 'DEXAMETASO', '8', '20 - 25 °C', 'N/A', '0', '2', '1', '0', '0', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `FechaLimiteUso2_8`, `FechaLimiteUso20_25`, `Estado`, `CreatedAt`) VALUES ('6', '1', 'RANITIDINA', 'RANITIDINA', '50', '20 - 25 °C', 'N/A', '0', '2', '1', '0', '0', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `FechaLimiteUso2_8`, `FechaLimiteUso20_25`, `Estado`, `CreatedAt`) VALUES ('7', '1', 'DIPIRONA', 'DIPIRONA', '1000', '20 - 25 °C', 'N/A', '0', '2', '1', '0', '0', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('8', '2', 'AMIKACINA', 'AMK', '500', '20 - 25 °C', 'N/A', '0', '2', '1', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('9', '2', 'ANFO', 'ANFO', '50', '20 - 25 °C', 'N/A', '10', '10', '2', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('10', '2', 'CFZ', 'CFZ', '1000', '20 - 25 °C', 'N/A', '5', '5.6', '3', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('11', '2', 'CFP', 'CFP', '1000', '20 - 25 °C', 'N/A', '5', '6', '4', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('12', '2', 'CFX', 'CFX', '1000', '20 - 25 °C', 'N/A', '5', '5.6', '5', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('13', '2', 'CLR', 'CLR', '500', '20 - 25 °C', 'N/A', '10', '10', '6', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('14', '2', 'CLD', 'CLD', '600', '20 - 25 °C', 'N/A', '0', '4', '7', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('15', '2', 'GNT', 'GNT', '160', '20 - 25 °C', 'N/A', '0', '2', '8', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('16', '2', 'MTZ', 'MTZ', '500', '20 - 25 °C', 'N/A', '0', '100', '9', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('17', '2', 'VAN', 'VAN', '500', '20 - 25 °C', 'N/A', '10', '10', '10', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('18', '2', 'FLZ', 'FLZ', '200', '20 - 25 °C', 'N/A', '0', '100', '11', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('19', '2', 'COL', 'COL', '150', '20 - 25 °C', 'N/A', '5', '5', '12', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('20', '2', 'MRP', 'MRP', '1000', '20 - 25 °C', 'N/A', '10', '10', '13', 'Activo', '2018-09-24 16:02:34');
INSERT INTO `polivalente`.`cm_medicamento` (`MedicamentoId`, `TipoMedicamentoId`, `Nombre`, `NombreAbreviado`, `Concentracion`, `TemperaturaUso`, `Recostituyente`, `VolumenReconstitucion`, `VolumenFinal`, `TiempoEstabilidad`, `Estado`, `CreatedAt`) VALUES ('21', '2', 'PPT', 'PPT', '4500', '20 - 25 °C', 'N/A', '15', '17.5', '14', 'Activo', '2018-09-24 16:02:34');

INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima, IsVehiculo)VALUES("SSN 25 Ml", 0.90,"% mg", "","","","",1);
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima, IsVehiculo)VALUES("SSN 50 Ml", 0.90,"% mg", "","","","",1);
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima, IsVehiculo)VALUES("SSN 100 Ml", 0.90,"% mg", "","","","",1);
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima, IsVehiculo)VALUES("SSN 250 Ml", 0.90,"% mg", "","","","",1);
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima, IsVehiculo)VALUES("SSN 500 Ml", 0.90,"% mg", "","","","",1);
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima, IsVehiculo)VALUES("SSN 1000 Ml", 0.90,"% mg", "","","","",1);
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima, IsVehiculo)VALUES("DAD 5% 250Ml", 0.90,"% mg", "","","","",1);
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima, IsVehiculo)VALUES("DAD 5% 500Ml", 0.90,"% mg", "","","","",1);
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima, IsVehiculo)VALUES("DAD 10% 250Ml", 0.90,"% mg", "","","","",1);
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima, IsVehiculo)VALUES("DAD 10% 500Ml", 0.90,"% mg", "","","","",1);
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima, IsVehiculo)VALUES("DAD 50% 500Ml", 0.90,"% mg", "","","","",1);
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima)VALUES("AGUA ESTERIL 500 mL", null,"", "","","","");
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima)VALUES("JERINGA 1 mL", null,"", "","","","");
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima)VALUES("JERINGA 2 mL", null,"", "","","","");
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima)VALUES("JERINGA 3 mL", null,"", "","","","");
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima)VALUES("JERINGA 5 mL", null,"", "","","","");
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima)VALUES("JERINGA 10 mL", null,"", "","","","");
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima)VALUES("JERINGA 20 mL", null,"", "","","","");
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima)VALUES("JERINGA 50 mL", null,"", "","","","");
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima)VALUES("AGUJA HIPODERMICA #16", null,"", "","","","");
INSERT INTO cm_dispositivomedico(Nombre,Concentracion,MedidaConcentracion,Laboratorio,Lote,FechaVencimiento,RegistroInvima)VALUES("AGUJA HIPODERMICA #18", null,"", "","","","");
INSERT INTO `polivalente`.`cm_dispositivomedico` (`Nombre`, `Concentracion`, `MedidaConcentracion`, `IsVehiculo`) VALUES ('BOLSA EVA 150 mL', 'N/A', 'N/A', '1');
INSERT INTO `polivalente`.`cm_dispositivomedico` (`Nombre`, `Concentracion`, `MedidaConcentracion`, `IsVehiculo`) VALUES ('BOLSA EVA 250 mL', 'N/A', 'N/A', '1');
INSERT INTO `polivalente`.`cm_dispositivomedico` (`Nombre`, `Concentracion`, `MedidaConcentracion`, `IsVehiculo`) VALUES ('BOLSA EVA 500 mL', 'N/A', 'N/A', '1');
INSERT INTO `polivalente`.`cm_dispositivomedico` (`Nombre`, `Concentracion`, `MedidaConcentracion`, `IsVehiculo`) VALUES ('BOLSA EVA 1000 mL', 'N/A', 'N/A', '1');
INSERT INTO `polivalente`.`cm_dispositivomedico` (`Nombre`, `Concentracion`, `MedidaConcentracion`, `IsVehiculo`) VALUES ('BOLSA EVA 2000 mL', 'N/A', 'N/A', '1');

ALTER TABLE `polivalente`.`cm_medicamento` 
ADD COLUMN `ViaAdministracion` VARCHAR(45) NULL DEFAULT 'Oral' AFTER `Codigo`;

ALTER TABLE `polivalente`.`cm_medicamento` 
ADD COLUMN `IsInRonda` TINYINT NULL DEFAULT 1 AFTER `ViaAdministracion`;

INSERT INTO `polivalente`.`cm_medicamento`
(
`TipoMedicamentoId`,
`Nombre`,
`NombreAbreviado`,
`Concentracion`,
`TemperaturaUso`,
`Recostituyente`,
`VolumenReconstitucion`,
`VolumenFinal`,
`TiempoEstabilidad`,
`FechaLimiteUso2_8`,
`FechaLimiteUso20_25`,
`FormaFarmaceutica`,
`Laboratorio`,
`RegInvima`,
`Codigo`,
`TipoMedicamento`,
`Estado`,
`CreatedBy`,
`CreatedAt`,
`ModifiedBy`,
`ModifiedAt`, IsInRonda)
VALUES
('2', 'ACETAMINOFEN', 'ACETAMINOFEN', '30', 'N/A', 'N/A', '0', '0', '0', '0', '0', 'JARABE', 'LAFRANCOL	', '2012M-0001556R1', '8A7070', 'Liquidos', 'Activo', NULL, '2018-11-19 11:41:41', NULL, NULL, false);
INSERT INTO `polivalente`.`cm_medicamento`
(
`TipoMedicamentoId`,
`Nombre`,
`NombreAbreviado`,
`Concentracion`,
`TemperaturaUso`,
`Recostituyente`,
`VolumenReconstitucion`,
`VolumenFinal`,
`TiempoEstabilidad`,
`FechaLimiteUso2_8`,
`FechaLimiteUso20_25`,
`FormaFarmaceutica`,
`Laboratorio`,
`RegInvima`,
`Codigo`,
`TipoMedicamento`,
`Estado`,
`CreatedBy`,
`CreatedAt`,
`ModifiedBy`,
`ModifiedAt`, IsInRonda)
VALUES
('2', 'ESPIRINOLACTONA', 'ESPIRINOLACTONA', '30', 'N/A', 'N/A', '0', '0', '0', '0', '0', 'TABLETA', 'ANGLOPHARMA	', '2012M-0001556R1', '8A7070', 'Solidos', 'Activo', NULL, '2018-11-19 11:41:41', NULL, NULL, false);




drop table if exists ambulancia_NotificarUsuario;
CREATE TABLE ambulancia_NotificarUsuario (
  `NotificarUsuarioId` int(11) NOT NULL AUTO_INCREMENT,
  UsuarioId int(11) DEFAULT NULL,
  `Estado` varchar(200) COLLATE latin1_spanish_ci DEFAULT 'Activo',
  `CreatedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`NotificarUsuarioId`),
  FOREIGN KEY (UsuarioId) REFERENCES usuario(UsuarioId)
);

INSERT INTO `polivalente`.`ambulancia_notificarusuario` (`UsuarioId`) VALUES ('3');
INSERT INTO `polivalente`.`ambulancia_notificarusuario` (`UsuarioId`) VALUES ('2');
INSERT INTO `polivalente`.`ambulancia_notificarusuario` (`UsuarioId`) VALUES ('66');
INSERT INTO `polivalente`.`ambulancia_notificarusuario` (`UsuarioId`) VALUES ('29');
INSERT INTO `polivalente`.`ambulancia_notificarusuario` (`UsuarioId`) VALUES ('53');


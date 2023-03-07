drop table if exists ct_sms;
CREATE TABLE ct_sms (
  `SMSId` int(11) NOT NULL AUTO_INCREMENT,
  PersonaId int(11) DEFAULT NULL,
  ColaboradorId int(11) DEFAULT NULL,
  `Mensaje` varchar(200),
  `Estado` varchar(200) COLLATE latin1_spanish_ci DEFAULT 'Activo',
  `CreatedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`SMSId`),
  FOREIGN KEY (PersonaId) REFERENCES ct_persona(PersonaId),
  FOREIGN KEY (ColaboradorId) REFERENCES ct_persona(PersonaId)
);

--
-- Table structure for table `detalle`
--
SHOW ENGINE INNODB STATUS;
DROP TABLE IF EXISTS detalle;
drop table if exists RondaAmbiental;
create table RondaAmbiental(
	RondaAmbientalId int primary key auto_increment,
    startsAt date DEFAULT NULL,
    endsAt date DEFAULT NULL,
    ServicioId int,
    FormatoId int,
    Observacion text,
    Estado varchar(200) DEFAULT 'Activo',
	CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL,
    foreign key(ServicioId) references Servicio(ServicioId),
    foreign key(FormatoId) references Formato(FormatoId)
) ENGINE=INNODB DEFAULT CHARSET=latin1;


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE detalle (
  DetalleId int primary key AUTO_INCREMENT,
  ItemFormatoId int,
  RondaAmbientalId int,
  Po int(11) DEFAULT NULL,
  Observacion text,
  Estado varchar(200) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL,
  foreign key(ItemFormatoId) references ItemFormato(ItemFormatoId),
  foreign key(RondaAmbientalId) references RondaAmbiental(RondaAmbientalId)
) ENGINE=INNODB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle`
--

LOCK TABLES `detalle` WRITE;
/*!40000 ALTER TABLE `detalle` DISABLE KEYS */;
 ALTER TABLE `detalle` ENABLE KEYS ;
UNLOCK TABLES;





--
-- Table structure for table `formato`
--

DROP TABLE IF EXISTS `formato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formato` (
  `FormatoId` int(11) NOT NULL AUTO_INCREMENT,
  `Identificador` varchar(200) DEFAULT NULL,
  `Titulo` varchar(200) DEFAULT NULL,
  `Estado` varchar(200) DEFAULT 'Activo',
  `CreatedBy` varchar(200) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`FormatoId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formato`
--

LOCK TABLES `formato` WRITE;
/*!40000 ALTER TABLE `formato` DISABLE KEYS */;
INSERT INTO `formato` VALUES (1,'1','ACONDICIONAMIENTO','Activo','DIDIER VARON','2017-12-23 13:31:02',NULL,NULL),(2,'2','MANIPULACION Y SEGREGACION DE RESIDUOS','Activo','DIDIER VARON ','2017-12-23 13:56:27',NULL,NULL);
/*!40000 ALTER TABLE `formato` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formatoservicio`
--

DROP TABLE IF EXISTS `formatoservicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formatoservicio` (
  `FormatoServicioId` int(11) NOT NULL AUTO_INCREMENT,
  `FormatoId` int(11) DEFAULT NULL,
  `ServicioId` int(11) DEFAULT NULL,
  `Estado` varchar(200) DEFAULT 'Activo',
  `CreatedBy` varchar(200) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`FormatoServicioId`),
  foreign key(FormatoId) references Formato(FormatoId),
  foreign key(ServicioId) references Servicio(ServicioId)
) ENGINE=INNODB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formatoservicio`
--

LOCK TABLES `formatoservicio` WRITE;
/*!40000 ALTER TABLE `formatoservicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `formatoservicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemformato`
--

DROP TABLE IF EXISTS `itemformato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemformato` (
  `ItemFormatoId` int(11) NOT NULL AUTO_INCREMENT,
  `FormatoId` int(11) DEFAULT NULL,
  `Identificador` varchar(200) DEFAULT NULL,
  `Descripcion` text,
  `Pe` int(11) DEFAULT NULL,
  `Estado` varchar(200) DEFAULT 'Activo',
  `CreatedBy` varchar(200) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(200) DEFAULT NULL,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`ItemFormatoId`),
  foreign key(FormatoId) references Formato(FormatoId)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemformato`
--

LOCK TABLES `itemformato` WRITE;
/*!40000 ALTER TABLE `itemformato` DISABLE KEYS */;
INSERT INTO `itemformato` VALUES (1,1,'1.1','el servicio cuenta con el tiode cancas segun la norma segun la norma para la disposicion de los residuos solidos generados ',15,'Activo','DIDIER VARON','2017-12-23 13:36:54',NULL,NULL),(2,1,'1.2','las canecas reutilizable cuentan con las blsas segun color(Rojas,Verdes,Grises) deacuerdo a lo establesido con el codigo de colores ',15,'Activo','DIDIER VARON','2017-12-23 13:43:17',NULL,NULL),(3,1,'1.3','las canecas se colocan lo mas serca posible del punto de generacion',10,'Activo','DIDIER VARON','2017-12-23 13:44:49',NULL,NULL),(4,1,'1.4','los materiales corto punzantes se descarta en guardianes de paredes rigidas',11,'Activo','DIDIER VARON','2017-12-23 13:45:58',NULL,NULL),(5,1,'1.5','los guardianes para disponer el material corto punzantes cuenten con descargadores o trampas de agujas ',12,'Activo','DIDIER VARON','2017-12-23 13:47:42',NULL,NULL),(6,1,'1.6','los guardianes para disponer los materiales corto punzantes  estan fijos en la pared de forma que no se caigan o se volteen',15,'Activo','DIDIER VARON','2017-12-23 13:49:35',NULL,NULL),(7,1,'1.7','los recipientes destinados para la segregacion mantienen buenas condiciones de higiene',11,'Activo','DIDIER VARON','2017-12-23 13:50:45',NULL,NULL),(8,1,'1.8','los resipientes destinados para la segregacion de los residuos se encuetran en buen estado ',11,'Activo','DIDIER VARON','2017-12-23 13:51:58',NULL,NULL),(9,2,'2.1','el personal asisencial desecha los residuos en el respectivo resipiente deacuerdo a sus caractericticas de peligrosidad ',15,'Activo','DIDIER VARON','2017-12-23 14:03:21',NULL,NULL),(10,2,'2.2','la capacidad de almacenamiento de los recipientes reutilizables se utilizan en forma adecuada segun la norma',10,'Activo','DIDIER VARON','2017-12-23 14:04:43',NULL,NULL),(11,2,'2.3','los recipientes para dispones materiales corto punzantes son utilizados y rotulados adecuadamente',15,'Activo','DIDIER VARON','2017-12-23 14:08:14',NULL,NULL),(12,2,'2.4','el personal asistencial no hace reencapuchamiento de agujas',NULL,'Activo','DIDIER VARON','2017-12-23 14:08:14',NULL,NULL),(13,2,'2.5','otros tipos de residuos corto punzantes o asimilables a estos se disponen a tal manera que se evite cortes u otras ',NULL,'Activo','DIDIER VARON','2017-12-23 14:08:14',NULL,NULL),(14,2,'2.6','los residuos quimicos ',NULL,'Activo','DIDIER VARON','2017-12-23 14:08:14',NULL,NULL),(15,2,'2.7',NULL,NULL,'Activo','DIDIER VARON','2017-12-23 14:08:14',NULL,NULL),(16,80,'2.8',NULL,NULL,'Activo','DIDIER VARON','2017-12-23 14:08:14',NULL,NULL);
/*!40000 ALTER TABLE `itemformato` ENABLE KEYS */;
UNLOCK TABLES;

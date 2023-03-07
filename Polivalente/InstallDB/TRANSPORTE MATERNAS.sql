
drop table if exists TM_DetalleEvento;
drop table if exists TM_Evento;
drop table if exists tm_materna;
drop table if exists TM_EPS;
drop table if exists TM_Lider;
drop table if exists TM_Tarifa;
DROP TABLE IF EXISTS TM_ciudad;

--
-- Table structure for table departamento
--

DROP TABLE IF EXISTS TM_departamento;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE TM_departamento (
  DepartamentoId int(11) NOT NULL AUTO_INCREMENT,
  Departamento varchar(200) DEFAULT NULL,
  PRIMARY KEY (DepartamentoId)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table departamento
--

LOCK TABLES TM_departamento WRITE;
/*!40000 ALTER TABLE TM_departamento DISABLE KEYS */;
INSERT INTO TM_departamento VALUES (1,'Amazonas'),(2,'Antioquia'),(3,'Arauca'),(4,'Atlántico'),(5,'Bolívar'),(6,'Boyacá'),(7,'Caldas'),(8,'Caquetá'),(9,'Casanare'),(10,'Cauca'),(11,'Cesar'),(12,'Chocó'),(13,'Cundinamarca'),(14,'Córdoba'),(15,'Guainía'),(16,'Guaviare'),(17,'Huila'),(18,'La Guajira'),(19,'Magdalena'),(20,'Meta'),(21,'Nariño'),(22,'Norte de Santander'),(23,'Putumayo'),(24,'Quindío'),(25,'Risaralda'),(26,'San Andrés y Providencia'),(27,'Santander'),(28,'Sucre'),(29,'Tolima'),(30,'Valle del Cauca'),(31,'Vaupés'),(32,'Vichada'),(33,'Ninguno');
/*!40000 ALTER TABLE TM_departamento ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table ciudad
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE TM_ciudad (
  CiudadId int(11) NOT NULL AUTO_INCREMENT,
  Ciudad varchar(200) DEFAULT NULL,
  DepartamentoId int(11) DEFAULT NULL,
  PRIMARY KEY (CiudadId),
  KEY DepartamentoId (DepartamentoId),
  CONSTRAINT ciudad_ibfk_1 FOREIGN KEY (DepartamentoId) REFERENCES TM_departamento (DepartamentoId)
) ENGINE=InnoDB AUTO_INCREMENT=1106 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table ciudad
--

LOCK TABLES TM_ciudad WRITE;
/*!40000 ALTER TABLE TM_ciudad DISABLE KEYS */;
INSERT INTO TM_ciudad VALUES (1,'Leticia',1),(2,'Puerto Nariño',1),(3,'Abejorral',2),(4,'Abriaquí',2),(5,'Alejandría',2),(6,'Amagá',2),(7,'Amalfi',2),(8,'Andes',2),(9,'Angelópolis',2),(10,'Angostura',2),(11,'Anorí',2),(12,'Anzá',2),(13,'Apartadó',2),(14,'Arboletes',2),(15,'Argelia',2),(16,'Armenia',2),(17,'Barbosa',2),(18,'Bello',2),(19,'Belmira',2),(20,'Betania',2),(21,'Betulia',2),(22,'Briceño',2),(23,'Buriticá',2),(24,'Cáceres',2),(25,'Caicedo',2),(26,'Caldas',2),(27,'Campamento',2),(28,'Cañasgordas',2),(29,'Caracolí',2),(30,'Caramanta',2),(31,'Carepa',2),(32,'Carolina del Príncipe',2),(33,'Caucasia',2),(34,'Chigorodó',2),(35,'Cisneros',2),(36,'Ciudad Bolívar',2),(37,'Cocorná',2),(38,'Concepción',2),(39,'Concordia',2),(40,'Copacabana',2),(41,'Dabeiba',2),(42,'Donmatías',2),(43,'Ebéjico',2),(44,'El Bagre',2),(45,'El Carmen de Viboral',2),(46,'El Peñol',2),(47,'El Retiro',2),(48,'El Santuario',2),(49,'Entrerríos',2),(50,'Envigado',2),(51,'Fredonia',2),(52,'Frontino',2),(53,'Giraldo',2),(54,'Girardota',2),(55,'Gómez Plata',2),(56,'Granada',2),(57,'Guadalupe',2),(58,'Guarne',2),(59,'Guatapé',2),(60,'Heliconia',2),(61,'Hispania',2),(62,'Itagüí',2),(63,'Ituango',2),(64,'Jardín',2),(65,'Jericó',2),(66,'La Ceja',2),(67,'La Estrella',2),(68,'La Pintada',2),(69,'La Unión',2),(70,'Liborina',2),(71,'Maceo',2),(72,'Marinilla',2),(73,'Medellín',2),(74,'Montebello',2),(75,'Murindó',2),(76,'Mutatá',2),(77,'Nariño',2),(78,'Nechí',2),(79,'Necoclí',2),(80,'Olaya',2),(81,'Peque',2),(82,'Pueblorrico',2),(83,'Puerto Berrío',2),(84,'Puerto Nare',2),(85,'Puerto Triunfo',2),(86,'Remedios',2),(87,'Rionegro',2),(88,'Sabanalarga',2),(89,'Sabaneta',2),(90,'Salgar',2),(91,'San Andrés de Cuerquia',2),(92,'San Carlos',2),(93,'San Francisco',2),(94,'San Jerónimo',2),(95,'San José de la Montaña',2),(96,'San Juan de Urabá',2),(97,'San Luis',2),(98,'San Pedro de Urabá',2),(99,'San Pedro de los Milagros',2),(100,'San Rafael',2),(101,'San Roque',2),(102,'San Vicente',2),(103,'Santa Bárbara',2),(104,'Santa Fe de Antioquia',2),(105,'Santa Rosa de Osos',2),(106,'Santo Domingo',2),(107,'Segovia',2),(108,'Sonsón',2),(109,'Sopetrán',2),(110,'Támesis',2),(111,'Tarazá',2),(112,'Tarso',2),(113,'Titiribí',2),(114,'Toledo',2),(115,'Turbo',2),(116,'Uramita',2),(117,'Urrao',2),(118,'Valdivia',2),(119,'Valparaíso',2),(120,'Vegachí',2),(121,'Venecia',2),(122,'Vigía del Fuerte',2),(123,'Yalí',2),(124,'Yarumal',2),(125,'Yolombó',2),(126,'Yondó',2),(127,'Zaragoza',2),(128,'Arauca',3),(129,'Arauquita',3),(130,'Cravo Norte',3),(131,'Fortul',3),(132,'Puerto Rondón',3),(133,'Saravena',3),(134,'Tame',3),(135,'Baranoa',4),(136,'Barranquilla',4),(137,'Campo de la Cruz',4),(138,'Candelaria',4),(139,'Galapa',4),(140,'Juan de Acosta',4),(141,'Luruaco',4),(142,'Malambo',4),(143,'Manatí',4),(144,'Palmar de Varela',4),(145,'Piojó',4),(146,'Polonuevo',4),(147,'Ponedera',4),(148,'Puerto Colombia',4),(149,'Repelón',4),(150,'Sabanagrande',4),(151,'Sabanalarga',4),(152,'Santa Lucía',4),(153,'Santo Tomás',4),(154,'Soledad',4),(155,'Suán',4),(156,'Tubará',4),(157,'Usiacurí',4),(158,'Achí',5),(159,'Altos del Rosario',5),(160,'Arenal',5),(161,'Arjona',5),(162,'Arroyohondo',5),(163,'Barranco de Loba',5),(164,'Brazuelo de Papayal',5),(165,'Calamar',5),(166,'Cantagallo',5),(167,'Cartagena de Indias',5),(168,'Cicuco',5),(169,'Clemencia',5),(170,'Córdoba',5),(171,'El Carmen de Bolívar',5),(172,'El Guamo',5),(173,'El Peñón',5),(174,'Hatillo de Loba',5),(175,'Magangué',5),(176,'Mahates',5),(177,'Margarita',5),(178,'María la Baja',5),(179,'Mompós',5),(180,'Montecristo',5),(181,'Morales',5),(182,'Norosí',5),(183,'Pinillos',5),(184,'Regidor',5),(185,'Río Viejo',5),(186,'San Cristóbal',5),(187,'San Estanislao',5),(188,'San Fernando',5),(189,'San Jacinto del Cauca',5),(190,'San Jacinto',5),(191,'San Juan Nepomuceno',5),(192,'San Martín de Loba',5),(193,'San Pablo',5),(194,'Santa Catalina',5),(195,'Santa Rosa',5),(196,'Santa Rosa del Sur',5),(197,'Simití',5),(198,'Soplaviento',5),(199,'Talaigua Nuevo',5),(200,'Tiquisio',5),(201,'Turbaco',5),(202,'Turbaná',5),(203,'Villanueva',5),(204,'Zambrano',5),(205,'Almeida',6),(206,'Aquitania',6),(207,'Arcabuco',6),(208,'Belén',6),(209,'Berbeo',6),(210,'Betéitiva',6),(211,'Boavita',6),(212,'Boyacá',6),(213,'Briceño',6),(214,'Buenavista',6),(215,'Busbanzá',6),(216,'Caldas',6),(217,'Campohermoso',6),(218,'Cerinza',6),(219,'Chinavita',6),(220,'Chiquinquirá',6),(221,'Chíquiza',6),(222,'Chiscas',6),(223,'Chita',6),(224,'Chitaraque',6),(225,'Chivatá',6),(226,'Chivor',6),(227,'Ciénega',6),(228,'Cómbita',6),(229,'Coper',6),(230,'Corrales',6),(231,'Covarachía',6),(232,'Cubará',6),(233,'Cucaita',6),(234,'Cuítiva',6),(235,'Duitama',6),(236,'El Cocuy',6),(237,'El Espino',6),(238,'Firavitoba',6),(239,'Floresta',6),(240,'Gachantivá',6),(241,'Gámeza',6),(242,'Garagoa',6),(243,'Guacamayas',6),(244,'Guateque',6),(245,'Guayatá',6),(246,'Güicán',6),(247,'Iza',6),(248,'Jenesano',6),(249,'Jericó',6),(250,'La Capilla',6),(251,'La Uvita',6),(252,'La Victoria',6),(253,'Labranzagrande',6),(254,'Macanal',6),(255,'Maripí',6),(256,'Miraflores',6),(257,'Mongua',6),(258,'Monguí',6),(259,'Moniquirá',6),(260,'Motavita',6),(261,'Muzo',6),(262,'Nobsa',6),(263,'Nuevo Colón',6),(264,'Oicatá',6),(265,'Otanche',6),(266,'Pachavita',6),(267,'Páez',6),(268,'Paipa',6),(269,'Pajarito',6),(270,'Panqueba',6),(271,'Pauna',6),(272,'Paya',6),(273,'Paz del Río',6),(274,'Pesca',6),(275,'Pisba',6),(276,'Puerto Boyacá',6),(277,'Quípama',6),(278,'Ramiriquí',6),(279,'Ráquira',6),(280,'Rondón',6),(281,'Saboyá',6),(282,'Sáchica',6),(283,'Samacá',6),(284,'San Eduardo',6),(285,'San José de Pare',6),(286,'San Luis de Gaceno',6),(287,'San Mateo',6),(288,'San Miguel de Sema',6),(289,'San Pablo de Borbur',6),(290,'Santa María',6),(291,'Santa Rosa de Viterbo',6),(292,'Santa Sofía',6),(293,'Santana',6),(294,'Sativanorte',6),(295,'Sativasur',6),(296,'Siachoque',6),(297,'Soatá',6),(298,'Socha',6),(299,'Socotá',6),(300,'Sogamoso',6),(301,'Somondoco',6),(302,'Sora',6),(303,'Soracá',6),(304,'Sotaquirá',6),(305,'Susacón',6),(306,'Sutamarchán',6),(307,'Sutatenza',6),(308,'Tasco',6),(309,'Tenza',6),(310,'Tibaná',6),(311,'Tibasosa',6),(312,'Tinjacá',6),(313,'Tipacoque',6),(314,'Toca',6),(315,'Togüí',6),(316,'Tópaga',6),(317,'Tota',6),(318,'Tunja',6),(319,'Tununguá',6),(320,'Turmequé',6),(321,'Tuta',6),(322,'Tutazá',6),(323,'Úmbita',6),(324,'Ventaquemada',6),(325,'Villa de Leyva',6),(326,'Viracachá',6),(327,'Zetaquira',6),(328,'Aguadas',7),(329,'Anserma',7),(330,'Aranzazu',7),(331,'Belalcázar',7),(332,'Chinchiná',7),(333,'Filadelfia',7),(334,'La Dorada',7),(335,'La Merced',7),(336,'Manizales',7),(337,'Manzanares',7),(338,'Marmato',7),(339,'Marquetalia',7),(340,'Marulanda',7),(341,'Neira',7),(342,'Norcasia',7),(343,'Pácora',7),(344,'Palestina',7),(345,'Pensilvania',7),(346,'Riosucio',7),(347,'Risaralda',7),(348,'Salamina',7),(349,'Samaná',7),(350,'San José',7),(351,'Supía',7),(352,'Victoria',7),(353,'Villamaría',7),(354,'Viterbo',7),(355,'Albania',8),(356,'Belén de los Andaquíes',8),(357,'Cartagena del Chairá',8),(358,'Curillo',8),(359,'El Doncello',8),(360,'El Paujil',8),(361,'Florencia',8),(362,'La Montañita',8),(363,'Milán',8),(364,'Morelia',8),(365,'Puerto Rico',8),(366,'San José del Fragua',8),(367,'San Vicente del Caguán',8),(368,'Solano',8),(369,'Solita',8),(370,'Valparaíso',8),(371,'Aguazul',9),(372,'Chámeza',9),(373,'Hato Corozal',9),(374,'La Salina',9),(375,'Maní',9),(376,'Monterrey',9),(377,'Nunchía',9),(378,'Orocué',9),(379,'Paz de Ariporo',9),(380,'Pore',9),(381,'Recetor',9),(382,'Sabanalarga',9),(383,'Sácama',9),(384,'San Luis de Palenque',9),(385,'Támara',9),(386,'Tauramena',9),(387,'Trinidad',9),(388,'Villanueva',9),(389,'Yopal',9),(390,'Almaguer',10),(391,'Argelia',10),(392,'Balboa',10),(393,'Bolívar',10),(394,'Buenos Aires',10),(395,'Cajibío',10),(396,'Caldono',10),(397,'Caloto',10),(398,'Corinto',10),(399,'El Tambo',10),(400,'Florencia',10),(401,'Guachené',10),(402,'Guapí',10),(403,'Inzá',10),(404,'Jambaló',10),(405,'La Sierra',10),(406,'La Vega',10),(407,'López de Micay',10),(408,'Mercaderes',10),(409,'Miranda',10),(410,'Morales',10),(411,'Padilla',10),(412,'Páez',10),(413,'Patía',10),(414,'Piamonte',10),(415,'Piendamó',10),(416,'Popayán',10),(417,'Puerto Tejada',10),(418,'Puracé',10),(419,'Rosas',10),(420,'San Sebastián',10),(421,'Santa Rosa',10),(422,'Santander de Quilichao',10),(423,'Silvia',10),(424,'Sotará',10),(425,'Suárez',10),(426,'Sucre',10),(427,'Timbío',10),(428,'Timbiquí',10),(429,'Toribío',10),(430,'Totoró',10),(431,'Villa Rica',10),(432,'Aguachica',11),(433,'Agustín Codazzi',11),(434,'Astrea',11),(435,'Becerril',11),(436,'Bosconia',11),(437,'Chimichagua',11),(438,'Chiriguaná',11),(439,'Curumaní',11),(440,'El Copey',11),(441,'El Paso',11),(442,'Gamarra',11),(443,'González',11),(444,'La Gloria (Cesar)',11),(445,'La Jagua de Ibirico',11),(446,'La Paz',11),(447,'Manaure Balcón del Cesar',11),(448,'Pailitas',11),(449,'Pelaya',11),(450,'Pueblo Bello',11),(451,'Río de Oro',11),(452,'San Alberto',11),(453,'San Diego',11),(454,'San Martín',11),(455,'Tamalameque',11),(456,'Valledupar',11),(457,'Acandí',12),(458,'Alto Baudó',12),(459,'Bagadó',12),(460,'Bahía Solano',12),(461,'Bajo Baudó',12),(462,'Bojayá',12),(463,'Cantón de San Pablo',12),(464,'Cértegui',12),(465,'Condoto',12),(466,'El Atrato',12),(467,'El Carmen de Atrato',12),(468,'El Carmen del Darién',12),(469,'Istmina',12),(470,'Juradó',12),(471,'Litoral de San Juan',12),(472,'Lloró',12),(473,'Medio Atrato',12),(474,'Medio Baudó',12),(475,'Medio San Juan',12),(476,'Nóvita',12),(477,'Nuquí',12),(478,'Quibdó',12),(479,'Río Iró',12),(480,'Río Quito',12),(481,'Riosucio',12),(482,'San José del Palmar',12),(483,'Sipí',12),(484,'Tadó',12),(485,'Unión Panamericana',12),(486,'Unguía',12),(487,'Agua de Dios',13),(488,'Albán',13),(489,'Anapoima',13),(490,'Anolaima',13),(491,'Apulo',13),(492,'Arbeláez',13),(493,'Beltrán',13),(494,'Bituima',13),(495,'Bogotá',13),(496,'Bojacá',13),(497,'Cabrera',13),(498,'Cachipay',13),(499,'Cajicá',13),(500,'Caparrapí',13),(501,'Cáqueza',13),(502,'Carmen de Carupa',13),(503,'Chaguaní',13),(504,'Chía',13),(505,'Chipaque',13),(506,'Choachí',13),(507,'Chocontá',13),(508,'Cogua',13),(509,'Cota',13),(510,'Cucunubá',13),(511,'El Colegio',13),(512,'El Peñón',13),(513,'El Rosal',13),(514,'Facatativá',13),(515,'Fómeque',13),(516,'Fosca',13),(517,'Funza',13),(518,'Fúquene',13),(519,'Fusagasugá',13),(520,'Gachalá',13),(521,'Gachancipá',13),(522,'Gachetá',13),(523,'Gama',13),(524,'Girardot',13),(525,'Granada',13),(526,'Guachetá',13),(527,'Guaduas',13),(528,'Guasca',13),(529,'Guataquí',13),(530,'Guatavita',13),(531,'Guayabal de Síquima',13),(532,'Guayabetal',13),(533,'Gutiérrez',13),(534,'Jerusalén',13),(535,'Junín',13),(536,'La Calera',13),(537,'La Mesa',13),(538,'La Palma',13),(539,'La Peña',13),(540,'La Vega',13),(541,'Lenguazaque',13),(542,'Machetá',13),(543,'Madrid',13),(544,'Manta',13),(545,'Medina',13),(546,'Mosquera',13),(547,'Nariño',13),(548,'Nemocón',13),(549,'Nilo',13),(550,'Nimaima',13),(551,'Nocaima',13),(552,'Pacho',13),(553,'Paime',13),(554,'Pandi',13),(555,'Paratebueno',13),(556,'Pasca',13),(557,'Puerto Salgar',13),(558,'Pulí',13),(559,'Quebradanegra',13),(560,'Quetame',13),(561,'Quipile',13),(562,'Ricaurte',13),(563,'San Antonio del Tequendama',13),(564,'San Bernardo',13),(565,'San Cayetano',13),(566,'San Francisco',13),(567,'San Juan de Rioseco',13),(568,'Sasaima',13),(569,'Sesquilé',13),(570,'Sibaté',13),(571,'Silvania',13),(572,'Simijaca',13),(573,'Soacha',13),(574,'Sopó',13),(575,'Subachoque',13),(576,'Suesca',13),(577,'Supatá',13),(578,'Susa',13),(579,'Sutatausa',13),(580,'Tabio',13),(581,'Tausa',13),(582,'Tena',13),(583,'Tenjo',13),(584,'Tibacuy',13),(585,'Tibirita',13),(586,'Tocaima',13),(587,'Tocancipá',13),(588,'Topaipí',13),(589,'Ubalá',13),(590,'Ubaque',13),(591,'Ubaté',13),(592,'Une',13),(593,'Útica',13),(594,'Venecia',13),(595,'Vergara',13),(596,'Vianí',13),(597,'Villagómez',13),(598,'Villapinzón',13),(599,'Villeta',13),(600,'Viotá',13),(601,'Yacopí',13),(602,'Zipacón',13),(603,'Zipaquirá',13),(604,'Ayapel',14),(605,'Buenavista',14),(606,'Canalete',14),(607,'Cereté',14),(608,'Chimá',14),(609,'Chinú',14),(610,'Ciénaga de Oro',14),(611,'Cotorra',14),(612,'La Apartada',14),(613,'Lorica',14),(614,'Los Córdobas',14),(615,'Momil',14),(616,'Montelíbano',14),(617,'Montería',14),(618,'Moñitos',14),(619,'Planeta Rica',14),(620,'Pueblo Nuevo',14),(621,'Puerto Escondido',14),(622,'Puerto Libertador',14),(623,'Purísima',14),(624,'Sahagún',14),(625,'San Andrés de Sotavento',14),(626,'San Antero',14),(627,'San Bernardo del Viento',14),(628,'San Carlos',14),(629,'San José de Uré',14),(630,'San Pelayo',14),(631,'Tierralta',14),(632,'Tuchín',14),(633,'Valencia',14),(634,'Inírida',15),(635,'Calamar',16),(636,'El Retorno',16),(637,'Miraflores',16),(638,'San José del Guaviare',16),(639,'Acevedo',17),(640,'Agrado',17),(641,'Aipe',17),(642,'Algeciras',17),(643,'Altamira',17),(644,'Baraya',17),(645,'Campoalegre',17),(646,'Colombia',17),(647,'El Pital',17),(648,'Elías',17),(649,'Garzón',17),(650,'Gigante',17),(651,'Guadalupe',17),(652,'Hobo',17),(653,'Íquira',17),(654,'Isnos',17),(655,'La Argentina',17),(656,'La Plata',17),(657,'Nátaga',17),(658,'Neiva',17),(659,'Oporapa',17),(660,'Paicol',17),(661,'Palermo',17),(662,'Palestina',17),(663,'Pitalito',17),(664,'Rivera',17),(665,'Saladoblanco',17),(666,'San Agustín',17),(667,'Santa María',17),(668,'Suaza',17),(669,'Tarqui',17),(670,'Tello',17),(671,'Teruel',17),(672,'Tesalia',17),(673,'Timaná',17),(674,'Villavieja',17),(675,'Yaguará',17),(676,'Albania',18),(677,'Barrancas',18),(678,'Dibulla',18),(679,'Distracción',18),(680,'El Molino',18),(681,'Fonseca',18),(682,'Hatonuevo',18),(683,'La Jagua del Pilar',18),(684,'Maicao',18),(685,'Manaure',18),(686,'Riohacha',18),(687,'San Juan del Cesar',18),(688,'Uribia',18),(689,'Urumita',18),(690,'Villanueva',18),(691,'Algarrobo',19),(692,'Aracataca',19),(693,'Ariguaní',19),(694,'Cerro de San Antonio',19),(695,'Chibolo',19),(696,'Chibolo',19),(697,'Ciénaga',19),(698,'Concordia',19),(699,'El Banco',19),(700,'El Piñón',19),(701,'El Retén',19),(702,'Fundación',19),(703,'Guamal',19),(704,'Nueva Granada',19),(705,'Pedraza',19),(706,'Pijiño del Carmen',19),(707,'Pivijay',19),(708,'Plato',19),(709,'Pueblo Viejo',19),(710,'Remolino',19),(711,'Sabanas de San Ángel',19),(712,'Salamina',19),(713,'San Sebastián de Buenavista',19),(714,'San Zenón',19),(715,'Santa Ana',19),(716,'Santa Bárbara de Pinto',19),(717,'Santa Marta',19),(718,'Sitionuevo',19),(719,'Tenerife',19),(720,'Zapayán',19),(721,'Zona Bananera',19),(722,'Acacías',20),(723,'Barranca de Upía',20),(724,'Cabuyaro',20),(725,'Castilla la Nueva',20),(726,'Cubarral',20),(727,'Cumaral',20),(728,'El Calvario',20),(729,'El Castillo',20),(730,'El Dorado',20),(731,'Fuente de Oro',20),(732,'Granada',20),(733,'Guamal',20),(734,'La Macarena',20),(735,'La Uribe',20),(736,'Lejanías',20),(737,'Mapiripán',20),(738,'Mesetas',20),(739,'Puerto Concordia',20),(740,'Puerto Gaitán',20),(741,'Puerto Lleras',20),(742,'Puerto López',20),(743,'Puerto Rico',20),(744,'Restrepo',20),(745,'San Carlos de Guaroa',20),(746,'San Juan de Arama',20),(747,'San Juanito',20),(748,'San Martín',20),(749,'Villavicencio',20),(750,'Vista Hermosa',20),(751,'Aldana',21),(752,'Ancuyá',21),(753,'Arboleda',21),(754,'Barbacoas',21),(755,'Belén',21),(756,'Buesaco',21),(757,'Chachagüí',21),(758,'Colón',21),(759,'Consacá',21),(760,'Contadero',21),(761,'Córdoba',21),(762,'Cuaspud',21),(763,'Cumbal',21),(764,'Cumbitara',21),(765,'El Charco',21),(766,'El Peñol',21),(767,'El Rosario',21),(768,'El Tablón',21),(769,'El Tambo',21),(770,'Francisco Pizarro',21),(771,'Funes',21),(772,'Guachucal',21),(773,'Guaitarilla',21),(774,'Gualmatán',21),(775,'Iles',21),(776,'Imués',21),(777,'Ipiales',21),(778,'La Cruz',21),(779,'La Florida',21),(780,'La Llanada',21),(781,'La Tola',21),(782,'La Unión',21),(783,'Leiva',21),(784,'Linares',21),(785,'Los Andes',21),(786,'Magüí Payán',21),(787,'Mallama',21),(788,'Mosquera',21),(789,'Nariño',21),(790,'Olaya Herrera',21),(791,'Ospina',21),(792,'Pasto',21),(793,'Policarpa',21),(794,'Potosí',21),(795,'Providencia',21),(796,'Puerres',21),(797,'Pupiales',21),(798,'Ricaurte',21),(799,'Roberto Payán',21),(800,'Samaniego',21),(801,'San Bernardo',21),(802,'San José de Albán',21),(803,'San Lorenzo',21),(804,'San Pablo',21),(805,'San Pedro de Cartago',21),(806,'Sandoná',21),(807,'Santa Bárbara',21),(808,'Santacruz',21),(809,'Sapuyes',21),(810,'Taminango',21),(811,'Tangua',21),(812,'Tumaco',21),(813,'Túquerres',21),(814,'Yacuanquer',21),(815,'Ábrego',22),(816,'Arboledas',22),(817,'Bochalema',22),(818,'Bucarasica',22),(819,'Cáchira',22),(820,'Cácota',22),(821,'Chinácota',22),(822,'Chitagá',22),(823,'Convención',22),(824,'Cúcuta',22),(825,'Cucutilla',22),(826,'Duranía',22),(827,'El Carmen',22),(828,'El Tarra',22),(829,'El Zulia',22),(830,'Gramalote',22),(831,'Hacarí',22),(832,'Herrán',22),(833,'La Esperanza',22),(834,'La Playa de Belén',22),(835,'Labateca',22),(836,'Los Patios',22),(837,'Lourdes',22),(838,'Mutiscua',22),(839,'Ocaña',22),(840,'Pamplona',22),(841,'Pamplonita',22),(842,'Puerto Santander',22),(843,'Ragonvalia',22),(844,'Salazar de Las Palmas',22),(845,'San Calixto',22),(846,'San Cayetano',22),(847,'Santiago',22),(848,'Santo Domingo de Silos',22),(849,'Sardinata',22),(850,'Teorama',22),(851,'Tibú',22),(852,'Toledo',22),(853,'Villa Caro',22),(854,'Villa del Rosario',22),(855,'Colón',23),(856,'Mocoa',23),(857,'Orito',23),(858,'Puerto Asís',23),(859,'Puerto Caicedo',23),(860,'Puerto Guzmán',23),(861,'Puerto Leguízamo',23),(862,'San Francisco',23),(863,'San Miguel',23),(864,'Santiago',23),(865,'Sibundoy',23),(866,'Valle del Guamuez',23),(867,'Villagarzón',23),(868,'Armenia',24),(869,'Buenavista',24),(870,'Calarcá',24),(871,'Circasia',24),(872,'Córdoba',24),(873,'Filandia',24),(874,'Génova',24),(875,'La Tebaida',24),(876,'Montenegro',24),(877,'Pijao',24),(878,'Quimbaya',24),(879,'Salento',24),(880,'Apía',25),(881,'Balboa',25),(882,'Belén de Umbría',25),(883,'Dosquebradas',25),(884,'Guática',25),(885,'La Celia',25),(886,'La Virginia',25),(887,'Marsella',25),(888,'Mistrató',25),(889,'Pereira',25),(890,'Pueblo Rico',25),(891,'Quinchía',25),(892,'Santa Rosa de Cabal',25),(893,'Santuario',25),(894,'Providencia y Santa Catalina Islas',26),(895,'San Andrés',26),(896,'Aguada',27),(897,'Albania',27),(898,'Aratoca',27),(899,'Barbosa',27),(900,'Barichara',27),(901,'Barrancabermeja',27),(902,'Betulia',27),(903,'Bolívar',27),(904,'Bucaramanga',27),(905,'Cabrera',27),(906,'California',27),(907,'Capitanejo',27),(908,'Carcasí',27),(909,'Cepitá',27),(910,'Cerrito',27),(911,'Charalá',27),(912,'Charta',27),(913,'Chima',27),(914,'Chipatá',27),(915,'Cimitarra',27),(916,'Concepción',27),(917,'Confines',27),(918,'Contratación',27),(919,'Coromoro',27),(920,'Curití',27),(921,'El Carmen de Chucurí',27),(922,'El Guacamayo',27),(923,'El Peñón',27),(924,'El Playón',27),(925,'El Socorro',27),(926,'Encino',27),(927,'Enciso',27),(928,'Florián',27),(929,'Floridablanca',27),(930,'Galán',27),(931,'Gámbita',27),(932,'Girón',27),(933,'Guaca',27),(934,'Guadalupe',27),(935,'Guapotá',27),(936,'Guavatá',27),(937,'Güepsa',27),(938,'Hato',27),(939,'Jesús María',27),(940,'Jordán',27),(941,'La Belleza',27),(942,'La Paz',27),(943,'Landázuri',27),(944,'Lebrija',27),(945,'Los Santos',27),(946,'Macaravita',27),(947,'Málaga',27),(948,'Matanza',27),(949,'Mogotes',27),(950,'Molagavita',27),(951,'Ocamonte',27),(952,'Oiba',27),(953,'Onzaga',27),(954,'Palmar',27),(955,'Palmas del Socorro',27),(956,'Páramo',27),(957,'Piedecuesta',27),(958,'Pinchote',27),(959,'Puente Nacional',27),(960,'Puerto Parra',27),(961,'Puerto Wilches',27),(962,'Rionegro',27),(963,'Sabana de Torres',27),(964,'San Andrés',27),(965,'San Benito',27),(966,'San Gil',27),(967,'San Joaquín',27),(968,'San José de Miranda',27),(969,'San Miguel',27),(970,'San Vicente de Chucurí',27),(971,'Santa Bárbara',27),(972,'Santa Helena del Opón',27),(973,'Simacota',27),(974,'Suaita',27),(975,'Sucre',27),(976,'Suratá',27),(977,'Tona',27),(978,'Valle de San José',27),(979,'Vélez',27),(980,'Vetas',27),(981,'Villanueva',27),(982,'Zapatoca',27),(983,'Buenavista',28),(984,'Caimito',28),(985,'Chalán',28),(986,'Colosó',28),(987,'Corozal',28),(988,'Coveñas',28),(989,'El Roble',28),(990,'Galeras',28),(991,'Guaranda',28),(992,'La Unión',28),(993,'Los Palmitos',28),(994,'Majagual',28),(995,'Morroa',28),(996,'Ovejas',28),(997,'Sampués',28),(998,'San Antonio de Palmito',28),(999,'San Benito Abad',28),(1000,'San Juan de Betulia',28),(1001,'San Marcos',28),(1002,'San Onofre',28),(1003,'San Pedro',28),(1004,'Sincé',28),(1005,'Sincelejo',28),(1006,'Sucre',28),(1007,'Tolú',28),(1008,'Tolú Viejo',28),(1009,'Alpujarra',29),(1010,'Alvarado',29),(1011,'Ambalema',29),(1012,'Anzoátegui',29),(1013,'Armero',29),(1014,'Ataco',29),(1015,'Cajamarca',29),(1016,'Carmen de Apicalá',29),(1017,'Casabianca',29),(1018,'Chaparral',29),(1019,'Coello',29),(1020,'Coyaima',29),(1021,'Cunday',29),(1022,'Dolores',29),(1023,'El Espinal',29),(1024,'Falán',29),(1025,'Flandes',29),(1026,'Fresno',29),(1027,'Guamo',29),(1028,'Herveo',29),(1029,'Honda',29),(1030,'Ibagué',29),(1031,'Icononzo',29),(1032,'Lérida',29),(1033,'Líbano',29),(1034,'Mariquita',29),(1035,'Melgar',29),(1036,'Murillo',29),(1037,'Natagaima',29),(1038,'Ortega',29),(1039,'Palocabildo',29),(1040,'Piedras',29),(1041,'Planadas',29),(1042,'Prado',29),(1043,'Purificación',29),(1044,'Rioblanco',29),(1045,'Roncesvalles',29),(1046,'Rovira',29),(1047,'Saldaña',29),(1048,'San Antonio',29),(1049,'San Luis',29),(1050,'Santa Isabel',29),(1051,'Suárez',29),(1052,'Valle de San Juan',29),(1053,'Venadillo',29),(1054,'Villahermosa',29),(1055,'Villarrica',29),(1056,'Alcalá',30),(1057,'Andalucía',30),(1058,'Ansermanuevo',30),(1059,'Argelia',30),(1060,'Bolívar',30),(1061,'Buenaventura',30),(1062,'Buga',30),(1063,'Bugalagrande',30),(1064,'Caicedonia',30),(1065,'Cali',30),(1066,'Calima',30),(1067,'Candelaria',30),(1068,'Cartago',30),(1069,'Dagua',30),(1070,'El Águila',30),(1071,'El Cairo',30),(1072,'El Cerrito',30),(1073,'El Dovio',30),(1074,'Florida',30),(1075,'Ginebra',30),(1076,'Guacarí',30),(1077,'Jamundí',30),(1078,'La Cumbre',30),(1079,'La Unión',30),(1080,'La Victoria',30),(1081,'Obando',30),(1082,'Palmira',30),(1083,'Pradera',30),(1084,'Restrepo',30),(1085,'Riofrío',30),(1086,'Roldanillo',30),(1087,'San Pedro',30),(1088,'Sevilla',30),(1089,'Toro',30),(1090,'Trujillo',30),(1091,'Tuluá',30),(1092,'Ulloa',30),(1093,'Versalles',30),(1094,'Vijes',30),(1095,'Yotoco',30),(1096,'Yumbo',30),(1097,'Zarzal',30),(1098,'Carurú',31),(1099,'Mitú',31),(1100,'Taraira',31),(1101,'Cumaribo',32),(1102,'La Primavera',32),(1103,'Puerto Carreño',32),(1104,'Santa Rosalía',32),(1105,'San José de Oriente',11),(1106,'Ninguno',33),(1107,'Aguas Blancas',11),(1108,'La Mata',11);
/*!40000 ALTER TABLE TM_ciudad ENABLE KEYS */;
UNLOCK TABLES;


CREATE TABLE TM_Tarifa(
  TarifaId int(11) primary key AUTO_INCREMENT,
  Nombre varchar(200),
  OrigenId int,
  DestinoId int,
  Precio float,
  OtroId int null,
  PrecioOtro float default 0,
  foreign key(OrigenId) references tm_ciudad(CiudadId),
  foreign key(DestinoId) references tm_ciudad(CiudadId),
  foreign key(OtroId) references TM_Tarifa(TarifaId),
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE TM_EPS(
  EPSId int(11) primary key AUTO_INCREMENT,
  Nombre varchar(200),
  TipoEPS varchar(20),
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;


CREATE TABLE TM_Lider(
  LiderId int(11) primary key AUTO_INCREMENT,
  Nombres varchar(250),
  Documento varchar(30),
  Telefono varchar(100),
  MunicipioId int,
  foreign key(MunicipioId) references tm_ciudad(CiudadId),
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE tm_materna(
  MaternaId int(11) primary key AUTO_INCREMENT,
  Nombres varchar(250),
  Documento varchar(30),
  Telefono varchar(100),
  MunicipioId int,
  EdadGestacional int,
  LiderId int,
  FechaUltimaRegla date,
  EdadUltimaEcografia int,
  FechaProbableParto date,
  EPSId int,
  FechaRegistro datetime,
  foreign key(MunicipioId) references tm_ciudad(CiudadId),
  foreign key(EPSId) references TM_EPS(EPSId),
  foreign key(LiderId) references TM_Lider(LiderId),
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE TM_Evento(
  EventoId int(11) primary key AUTO_INCREMENT,
  MaternaId int,
  Acompanante bool,
  Procedimiento varchar(200),
  FechaParto datetime,
  FechaRegistro datetime,
  Comentario varchar(500),
  foreign key(MaternaId) references tm_materna(MaternaId),
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE TM_DetalleEvento(
  DetalleEventoId int(11) primary key AUTO_INCREMENT,
  EventoId int,
  TarifaId int,
  Precio float,
  Color varchar(10),
  foreign key(EventoId) references TM_Evento(EventoId),
  foreign key(TarifaId) references TM_Tarifa(TarifaId),
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;


/* SEMILLA */
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("TAXI", 1106,1106,6000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE PAILITAS A VALLEDUPAR", 448,456,30000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE LA GLORIA A VALLEDUPAR", 444,456,40000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE LA GLORIA A LA MATA", 444,1108,6000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE TAMALAMEQUE A VALLEDUPAR", 455,456,35000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE AGUSTÍN CODAZZI A VALLEDUPAR", 433,456,12000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE LA PAZ A VALLEDUPAR", 446,456,5000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio, OtroId, PrecioOtro) VALUES ("DE SAN JOSÉ DE ORIENTE A VALLEDUPAR", 1105,456,8000, 1, 5000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio, OtroId, PrecioOtro) VALUES ("DE MANAURE BALCÓN DEL CESAR A VALLEDUPAR", 447,456,8000, 1, 5000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio, OtroId) VALUES ("DE SAN DIEGO A VALLEDUPAR", 453,456,5500, 1);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio, OtroId) VALUES ("DE AGUAS BLANCAS A VALLEDUPAR", 1107,456,6000, 1);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE BOSCONIA A VALLEDUPAR", 436,456,15000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE ASTREA A VALLEDUPAR", 434,456,30000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DEL PASO A VALLEDUPAR", 441,456,25000);

INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE VALLEDUPAR A PAILITAS", 456,448,30000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE VALLEDUPAR A LA GLORIA",456, 444,40000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE LA MATA A VALLEDUPAR",1108, 444,6000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE VALLEDUPAR A TAMALAMEQUE",456, 455,35000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE VALLEDUPAR A AGUSTÍN CODAZZI",456, 433,12000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE VALLEDUPAR A LA PAZ",456, 446,5000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio, OtroId, PrecioOtro) VALUES ("DE VALLEDUPAR A SAN JOSÉ DE ORIENTE",456, 1105,8000, 1, 5000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio, OtroId, PrecioOtro) VALUES ("DE VALLEDUPAR A MANAURE BALCÓN DEL CESAR",456, 447,8000, 1, 5000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio, OtroId) VALUES ("DE VALLEDUPAR A SAN DIEGO",456, 453,5500, 1);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio, OtroId) VALUES ("DE VALLEDUPAR A AGUAS BLANCAS",456, 1107,6000, 1);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE VALLEDUPAR A BOSCONIA",456, 436,15000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DE VALLEDUPAR A ASTREA",456, 434,30000);
INSERT INTO tm_tarifa(Nombre, OrigenId, DestinoId, Precio) VALUES ("DEL VALLEDUPAR A PASO",456, 441,25000);

INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('2', 'COMFAMILIAR CARTAGENA', 'Contributiva', 'Activo', '2018-10-24 11:14:02');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('3', 'COMFABOY', 'Contributiva', 'Activo', '2018-10-24 11:14:02');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('4', 'COLSUBSIDIO', 'Contributiva', 'Activo', '2018-10-24 11:14:02');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('5', 'COMFACOR EPS-S', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('6', 'COMFACHOCO EPSS', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('7', 'COMFAGUAJIRA', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('8', 'COMFAMILIAR HUILA', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('9', 'COMFAMILIAR NARINO', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('10', 'COMFASUCRE EPS-S', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('11', 'COMFAORIENTE EPS', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('12', 'COMFACUNDI', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('13', 'CAJACOPI EPS', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('14', 'EMPRESAS PUBLICAS DE MEDELLIN E.S.P', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('15', 'FERROCARRILES NACIONALES', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('16', 'ALIANSALUD E.P.S.', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('17', 'SALUD TOTAL S.A.', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('18', 'CAFESALUD E.P.S S.A.', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('19', 'E.P.S SANITAS', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('20', 'COMPENSAR E.P.S', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('21', 'E.P.S  SURAMERICANA S.A', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('22', 'COMFENALCO VALLE DEL CAUCA', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('23', 'SALUDCOOP E.P.S', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('24', 'COOMEVA E.P.S S.A.', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('25', 'FAMISANAR E.P.S.', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('26', 'E.P.S  S.O.S.', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('27', 'CRUZ BLANCA E.P.S', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('28', 'SALUDVIDA S.A. E.P.S', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('29', 'SALUD COLOMBIA E.P.S S.A.', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('30', 'RED SALUD ATENCION HUMANA E.P.S S.A.', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('31', 'NUEVA E.P.S S.A.', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('32', 'SAVIA SALUD EPS', 'Contributiva', 'Activo', '2018-10-24 11:14:03');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('33', 'NUEVA E.P.S S.A. MOVILIDAD', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('34', 'COOSALUD S.A.S', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('35', 'MEDIMAS EPS S.A.S', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('36', 'MEDIMAS EPS S.A.S MOVILIDAD', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('37', 'FUNDACIÓN SALUD MÍA EPS', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('38', 'CAFESALUD EPS-S MOVILIDAD', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('39', 'CONVIDA', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('40', 'CAPRESOCA EPS', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('41', 'SALUDVIDA EPS S.A. MOVILIDAD', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('42', 'CAPITAL SALUD EPSS S.A.S.', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('43', 'DUSAKAWI EPSI', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('44', 'MANEXKA EPS INDIGENA', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('45', 'ASOCIACION INDIGENA DEL CAUCA', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('46', 'ANASWAYUU', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('47', 'MALLAMAS', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('48', 'PIJAOS SALUD EPSI', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('49', 'EMDISALUD', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('50', 'MUTUAL SER', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('51', 'EMSSANAR E.S.S', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('52', 'COOSALUD S.A.S MOVILIDAD', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('53', 'COMPARTA EPS-S', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('54', 'ASMET SALUD EPS S.A.S', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('55', 'AMBUQ', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('56', 'ECOOPSOS EPS S.A.S', 'Contributiva', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('57', 'COMFAMA', 'Subsidiada', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('58', 'COMFAMILIAR CARTAGENA', 'Subsidiada', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('59', 'COMFABOY', 'Subsidiada', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('60', 'COMFACOR', 'Subsidiada', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('61', 'COMFAMILIAR DE LA GUAJIRA', 'Subsidiada', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('62', 'COMFAMILIAR HUILA', 'Subsidiada', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('63', 'COMFAMILIAR DE NARIÐO', 'Subsidiada', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('64', 'COMFASUCRE', 'Subsidiada', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('65', 'COMFAORIENTE', 'Subsidiada', 'Activo', '2018-10-24 11:14:04');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('66', 'COMFAORIENTE_CCF050', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('67', 'COMFACUNDI', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('68', 'CAJACOPI ATLANTICO', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('69', 'COMFACHOCO', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('70', 'CONVIDA', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('71', 'CAPRESOCA', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('72', 'DUSAKAWI', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('73', 'MANEXKA', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('74', 'A.I.C.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('75', 'ANAS WAYUU', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('76', 'MALLAMAS', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('77', 'PIJAOSALUD', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('78', 'CAFESALUD EPS', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('79', 'SALUDVIDA S.A .E.P.S', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('80', 'ALIANSALUD E.P.S. S.A.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('81', 'SALUD TOTAL', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('82', 'CAFESALUD', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('83', 'SANITAS E.P.S. S.A.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('84', 'COMPENSAR E.P.S.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('85', 'EPS Y MEDICINA PREPAGADA SURAMERICANA S.A', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('86', 'COMFENALCO VALLE  E.P.S.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('87', 'E.P.S.  Saludcoop', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('88', 'COOMEVA E.P.S.  S.A.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('89', 'E.P.S.  FAMISANAR  LTDA.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('90', 'EPS Servicio Occidental de Salud  S.A. - EPS S.O.S. S.A.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('91', 'CRUZ BLANCA  EPS S.A.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('92', 'SALUDVIDA', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('93', 'CAPITAL SALUD', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('94', 'LA NUEVA EPS S.A.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('95', 'SAVIA SALUD', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('96', 'NUEVA EMPRESA PROMOTORA DE SALUD S.A. NUEVA EPS S.A.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('97', 'COOSALUD EPS S.A.', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('98', 'MEDIMAS MOV', 'Subsidiada', 'Activo', '2018-10-24 11:14:05');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('99', 'MEDIMAS', 'Subsidiada', 'Activo', '2018-10-24 11:14:06');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('100', 'EMDISALUD', 'Subsidiada', 'Activo', '2018-10-24 11:14:06');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('101', 'COOSALUD EPS S.A.', 'Subsidiada', 'Activo', '2018-10-24 11:14:06');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('102', 'ASMET SALUD', 'Subsidiada', 'Activo', '2018-10-24 11:14:06');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('103', 'AMBUQ', 'Subsidiada', 'Activo', '2018-10-24 11:14:06');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('104', 'ECOOPSOS', 'Subsidiada', 'Activo', '2018-10-24 11:14:06');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('105', 'EMSSANAR', 'Subsidiada', 'Activo', '2018-10-24 11:14:06');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('106', 'COMPARTA', 'Subsidiada', 'Activo', '2018-10-24 11:14:06');
INSERT INTO `polivalente`.`tm_eps` (`EPSId`, `Nombre`, `TipoEPS`, `Estado`, `CreatedAt`) VALUES ('107', 'MUTUAL SER', 'Subsidiada', 'Activo', '2018-10-24 11:14:06');

ALTER TABLE `polivalente`.`tm_ciudad` 
ADD COLUMN `Estado` VARCHAR(45) NULL DEFAULT 'Activo' AFTER `DepartamentoId`;


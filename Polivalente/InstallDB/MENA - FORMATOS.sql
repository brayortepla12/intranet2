#INSERT INTO `polivalente`.`modulo` (`Nombre`, `State`) VALUES ('Telefono', 'tel.Solicitud');
#INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'tel.Solicitud', 'Solicitudes', '21');

drop table if exists tel_Entrega;
drop table if exists tel_Solicitud;
drop table if exists tel_Telefonos;
drop table if exists tel_Inventario;

create table if not exists tel_Inventario(
`InventarioId` int primary key auto_increment,
`Marca` varchar(50),
`Modelo` varchar(20),
`Operador` varchar(20),
`Color` varchar(20),
`IMEI1` varchar(25),
`IMEI2` varchar(25),
`Stock` int default 1,
`Estado` enum('Inactivo', 'Activo', 'Suspendido', 'Baja') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`InventarioId`));

INSERT INTO `polivalente`.`tel_inventario` (`Marca`, `Modelo`, `Operador`, `Color`, `IMEI1`, `IMEI2`, `Stock`) VALUES ('Samsung', ' A10s LITE', 'Movistar', 'Negro', '353414113100073', '353414113100070', '1');
INSERT INTO `polivalente`.`tel_inventario` (`Marca`, `Modelo`, `Operador`, `Color`, `IMEI1`, `IMEI2`, `Stock`) VALUES ('Samsung', ' A10s LITE', 'Movistar', 'Negro', '353414113098921', '353414113098928', '1');
INSERT INTO `polivalente`.`tel_inventario` (`Marca`, `Modelo`, `Operador`, `Color`, `IMEI1`, `IMEI2`, `Stock`) VALUES ('Samsung', ' A10s LITE', 'Movistar', 'Negro', '353414113099119', '353414113099116', '1');


create table if not exists tel_Telefonos(
`TelefonoId` int primary key auto_increment,
`LiderTelefonoId` int,
`Responsable` varchar(150),
`Telefono` varchar(20),
`Plan` varchar(10),
`Estado` enum('Inactivo', 'Activo', 'Suspendido') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(LiderTelefonoId) references ct_persona(PersonaId),
INDEX(`TelefonoId`));

create table if not exists tel_Solicitud(
`SolicitudId` int primary key auto_increment,
`Fecha` datetime,
`RSolicitaId` int,
`USolicitaId` int,
`Usuario` varchar(50),
`TelefonoId` int,
`DescripcionDano` text,
`UrlImagen` varchar(50),
`Estado` enum('Inactivo', 'Activo', 'Cancelada', 'Rechazada', 'Finalizada') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(TelefonoId) references tel_Telefonos(TelefonoId),
foreign key(RSolicitaId) references ct_persona(PersonaId),
INDEX(`SolicitudId`));

create table if not exists tel_Entrega(
`EntregaId` int primary key auto_increment,
`Fecha` datetime,
`Marca` varchar(50),
`Modelo` varchar(20),
`Operador` varchar(20),
`Color` varchar(20),
`IMEI1` varchar(25),
`IMEI2` varchar(25),
`Descripcion` varchar(200),
`TelefonoId` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(TelefonoId) references tel_telefonos(TelefonoId),
INDEX(`EntregaId`));

/*
-- Query: SELECT * FROM Formato.tel_Telefonos
LIMIT 0, 1000

-- Date: 2020-09-02 15:37
*/
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (1,'1861','MARIA P. MARTINEZ','3173319449','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (2,'1861','amanda ochoa - el páso','3106305680','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (3,'1861','ADA ROSA CIRUGIA','3175861463','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (4,'1861','JOSE VANEGAS','3183498306','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (5,'1861','LENNIS FLOREZ','3153473280','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (6,'1861','AUDITORES CSI','3173693358','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (7,'1861','AMBULANCIA','3158808384','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (8,'1861','MABELIS','3186295384','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (9,'1861','VIVIAN VARGAS','3168305187','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (10,'1861','ANGELA TORRES','3154943517','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (11,'1861','TABLE LIBRE','3166945023','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (12,'1861','CENTRAL DE MEZCLAS','3173833210','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (13,'1861','ARNALDO POLO','3157005546','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (14,'1861','EDGARDO MARTINEZ','3153476591','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (15,'1861','delibeth ardila','3106306382','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (16,'1861','CALIDAD KEYLA TABLE 10','3183352988','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (17,'1861','LUIS ROMANI','3175180292','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (18,'1861','DIANA CASTILLO','3153468895','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (19,'1861','AURA','3154981083','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (20,'1861','JOSE VANEGAS-ADMON PEDIATRIA','3162471854','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (21,'1861','JEFE KEYLA','3153352943','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (22,'1861','JOSE VANEGAS - cemic','3153470860','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (23,'1861','YURANI table','3153105034','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (24,'1861','gestion humana csi','3175150939','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (25,'1861','LISSA LOPEZ CALDERON','3153546567','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (26,'1861','LEIDYS ALONSO (3153540447)','3232300352','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (27,'1861','MARIA CLAUDIA BARANDICA','3168306975','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (28,'1861','GESTION HUMANA','3152446911','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (29,'1861','JULIO CASTRO','3163575591','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (30,'1861','JAIME ARCE','3163587039','H6Y');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (31,'1861','MARIA JOSE AÑEZ','3155306777','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (32,'1861','JAIME ARCE (MODEMS)','3175008109','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (33,'1861','MAGNERIS VILLEGAS','3157237026','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (34,'1861','AUTORIZACIONES CEMIC','3135211678','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (35,'1861','RRHH dra meyra','3164243082','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (36,'1861','RODOLFO LARGO','3182527150','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (37,'1861','AMBULANCIA','3167425988','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (38,'1861','YURANIS FERNANDEZ','3174330326','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (39,'1861','miredis ardila - maaure','3102220550','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (40,'1861','libre','3104092276','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (41,'1861','ING HERNAN','3155221055','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (42,'1861','JURIDICA','3117453951','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (43,'1861','JACKELINE BRAVO LOPEZ','3182212020','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (44,'1861','jhon camacho','3175105001','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (45,'1861','CEX YADIRA','3153477615','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (46,'1861','LAINEKER','3162257386','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (47,'1861','ING MARIA TERESA','3176474514','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (48,'1861','ISABEL CUBILLOS','3183591658','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (49,'1861','REFERENCIA','3168307181','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (50,'1861','ATILIO','3155262011','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (51,'1861','JEFE ALBA','3153475476','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (52,'1861','susana arias - pailitas','3106364511','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (53,'1861','LLISETH LOPEZ','3175114817','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (54,'1861','OLIVA ACOSTA DE ARCE','3167446458','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (55,'1861','lina oviedo - atrea','3106305011','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (56,'1861','ALICIA GARCIA DE ARCE','3153472877','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (57,'1861','LUIGIS LUQUEZ','3157819756','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (58,'1861','clara soto - lapaz','3185229673','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (59,'1861','mantenimiento RENE','3163330341','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (60,'1861','AMIRA SEQUEIRA','3176686389','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (61,'1861','JORGE','3165234410','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (62,'1861','RAFAEL ULLOA','3153472873','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (63,'1861','EVELIN MOVIL GUERRA - BANCO','3205651407','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (64,'1861','AUTORIZACIONES','3174030100','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (65,'1861','JAIME ARCE','3158771182','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (66,'1861','OSCAR PEREZ','3176486104','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (67,'1861','AMBULANCIA','3174362544','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (68,'1861','ANA NOGUERA','3153511882','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (69,'1861','JEFE JOHANA','3158745594','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (70,'1861','PAOLA PAYARES ALVAREZ','3153475722','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (71,'1861','JEFE VANESSA','3166909311','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (72,'1861','ING WILSON','3153469231','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (73,'1861','SRA OLIVA (TABLE)','3183060077','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (74,'1861','CENTRAL DE MEZCLAS','3154875736','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (75,'1861','RECEPCION BANCO','3204065528','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (76,'1861','CARMEN ANILLO','3153467668','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (77,'1861','table libre','3183441933','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (78,'1861','NASLY VELAZCO','3158778830','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (79,'1861','AUTORIZACIONES CSI','3176405293','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (80,'1861','MAGNERIS (MODEM)','3153105097','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (81,'1861','ALEXA BOLAÑOS','3153472299','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (82,'1861','WILLIAM AREVALO','3154924181','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (83,'1861','SARAY','3173695673','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (84,'1861','KEYLA PINEDA','3175162844','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (85,'1861','JOHANA PEREZ','3153468708','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (86,'1861','MARIA PAULINA MARTINEZ (TABLE)','3183732878','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (87,'1861','DELIS','3158811917','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (88,'1861','Yuliana Cabarca','3153467278','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (89,'1861','YEIMI','3175101551','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (90,'1861','region salud','3215091443','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (91,'1861','AMBULANCIA','3174401002','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (92,'1861','BLANCA GUERRERO','3158767755','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (93,'1861','ING MISAEL CRESPO','3117453940','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (94,'1861','JOSE VANEGAS','3176697131','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (95,'1861','IBIS RODRIGUEZ','3165212130','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (96,'1861','AMBULANCIA','3153471357','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (97,'1861','GRANITOS DE FELICIDAD','3205651405','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (98,'1861','EULDER BLANCO','3153476387','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (99,'1861','zuledis','3158622043','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (100,'1861','AUDITORIA LAURA','3153478707','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (101,'1861','JOAN RUBIO','3174347067','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (102,'1861','rafael iseda-codacci','3106303391','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (103,'1861','SRA ANA (MODEM)','3154876027','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (104,'1861','CARLOS tel','3176686392','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (105,'1861','ASISTENTE COORDINACION MEDICA CSI','3153469088','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (106,'1861','GREYSIS MUÑOZ','3155400039','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (107,'1861','Sandra Rubio.','3188136002','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (108,'1861','AMALFI CUJIA','3168300736','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (109,'1861','SHIRLY','3188135005','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (110,'1861','YANIRIS GALINDO','3153476806','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (111,'1861','MARIA CLAUDIA MORILLO GERENTE','3105755777','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (112,'1861','LILIANA MIELES','3164822549','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (113,'1861','CARLOS tel (TABLE)','3174401812','H7M');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (114,'1861','REFERENCIA','3157236783','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (115,'1861','DRA KATIA','3105756942','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (116,'1861','SHIRLEVI BIENESTAR','3175050484','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (117,'1861','AMYELITH COBO','3155304022','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (118,'1861','ercilia feija - la gloria','3164216244','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (119,'1861','JOHANNA NIEBLES','3117453959','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (120,'1861','SUNILDA QUINTERO','3183330160','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (121,'1861','JOSE VANEGAS','3155362403','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (122,'1861','LISETH','3153476059','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (123,'1861','GREYS','3168779358','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (124,'1861','DRA ANGELA','3153477850','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (125,'1861','ana escobar - mareigua','3106028603','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (126,'1861','ANGIE','3153538752','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (127,'1861','LIZETH MESA','3168301174','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (128,'1861','SEYDIS GARCIA','3174344769','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (129,'1861','AMBULANCIA','3166919485','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (130,'1861','CARTERA CLD','3174276514','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (131,'1861','KEYLA  CARRILLO','3153477731','H7I');
INSERT INTO `tel_Telefonos` (`TelefonoId`,`LiderTelefonoId`,`Responsable`,`Telefono`,`Plan`) VALUES (132,'1861','BANCO DE SANGRE modem','3164802523','H7M');

INSERT INTO `polivalente`.`tel_entrega` (`Fecha`, `Marca`, `Modelo`, `Operador`, `Color`, `IMEI1`, `IMEI2`, `Descripcion`, `TelefonoId`) VALUES ('2020-07-02', 'Samsung', 'A10s', 'Movistar', 'Negro', '353414113099150', '353415113099157', 'Se entrega con cargador, usb y manoslibres', 62);


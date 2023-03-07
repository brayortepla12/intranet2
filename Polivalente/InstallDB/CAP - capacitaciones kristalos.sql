drop table if exists cap_ResumenVideo;
drop table if exists cap_Respuesta;
drop table if exists cap_Sesion;
drop table if exists cap_Opcion;
drop table if exists cap_Pregunta;
drop table if exists cap_Video;
drop table if exists cap_Categoria;
drop table if exists cap_Agenda;

create table if not exists cap_Agenda(
`AgendaId` int primary key auto_increment,
`PrimerNombre` varchar(50),
`SegundoNombre` varchar(50),
`PrimerApellido` varchar(50),
`SegundoApellido` varchar(50),
`FechaInicio` datetime,
`FechaFin` datetime,
`PersonaId` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`AgendaId`));


create table if not exists cap_Sesion(
`SesionId` int primary key auto_increment,
`PrimerNombre` varchar(50),
`SegundoNombre` varchar(50),
`PrimerApellido` varchar(50),
`SegundoApellido` varchar(50),
`Cargo` varchar(50),
`Fecha` datetime,
`PersonaId` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`SesionId`));


create table if not exists cap_Categoria(
`CategoriaId` int primary key auto_increment,
`Nombre` varchar(50),
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`CategoriaId`));

create table if not exists cap_Video(
`VideoId` int primary key auto_increment,
`UrlVideo` text,
`Duracion` varchar(10),
`Formato` varchar(20),
`CategoriaId` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(CategoriaId) references cap_Categoria(CategoriaId),
INDEX(`CategoriaId`, `VideoId`));

create table if not exists cap_Pregunta(
`PreguntaId` int primary key auto_increment,
`Pregunta` text,
`VideoId` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(VideoId) references cap_Video(VideoId),
INDEX(`PreguntaId`, `VideoId`));

create table if not exists cap_Opcion(
`OpcionId` int primary key auto_increment,
`Opcion` text,
`PreguntaId` int,
`EsCorrecta` tinyint default 0,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(PreguntaId) references cap_Pregunta(PreguntaId),
INDEX(`OpcionId`, `PreguntaId`));

create table if not exists cap_Respuesta(
`RepuestaId` int primary key auto_increment,
`EsCorrecta` tinyint default 0,
`PreguntaId` int,
`OpcionId` int,
`PersonaId` int,
`SesionId` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(PreguntaId) references cap_Pregunta(PreguntaId),
foreign key(OpcionId) references cap_Opcion(OpcionId),
foreign key(PersonaId) references ct_persona(PersonaId),
foreign key(SesionId) references cap_Sesion(SesionId),
INDEX(`RepuestaId`, `OpcionId`, `SesionId`));


create table if not exists cap_ResumenVideo(
`ResumenVideoId` int primary key auto_increment,
`TiempoActual` varchar(10),
`Duracion` varchar(10),
`VideoId` int,
`PersonaId` int,
`SesionId` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(VideoId) references cap_Pregunta(VideoId),
foreign key(PersonaId) references ct_persona(PersonaId),
foreign key(SesionId) references cap_Sesion(SesionId),
INDEX(`ResumenVideoId`, `VideoId`, `PersonaId`, `SesionId`));
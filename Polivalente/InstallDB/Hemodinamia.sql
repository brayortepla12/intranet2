#drop table if exists Hemodinamia_AntecedentePsicosocial;
#drop table if exists Hemodinamia_AntecedentePersonal;
drop table if exists Hemodinamia_HistoricaClinica;
drop table if exists Hemodinamia_Paciente;

create table Hemodinamia_Paciente(
	PacienteId int primary key auto_increment,
    Nombres varchar(300),
    Documento varchar(200),
    Edad int,
    EstadoCivil varchar(200),
    Religion varchar(200),
    Procedencia varchar(200),
    DireccionActual varchar(300),
    Telefono varchar(200),
    Ocupacion varchar(200),
    RegimenSeguridadSocial varchar(200),
    Entidad varchar(200),
    TEsposo_a varchar(200),
    TMadre_Padre varchar(200),
    THermano_a varchar(200),
    TAmigo_a varchar(200),
    THijo_a varchar(200),
    CodigoQR text,
    Estado varchar(100) DEFAULT 'Activo',
	CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL
) ENGINE = INNODB;

create table Hemodinamia_HistoricaClinica(
	HistoricaClinicaId int primary key auto_increment,
    TratamientoFarmacologicoActual text,
    AntecedentesFamiliares text,
    FactoresRiesgoCardiovasculares text,
    ManejoActual text,
    #AntecedentePersonal
    Patologicos text,
    EnfermedadesInfancia text,
    EnfermedadesAdultez text,
    Quirurgicos text,
    Hospitalizaciones text,
    Transfusiones text,
    Toxicos text,
    Alergicos text,
    #AntecedentePsicosocial
    Alimenticios text,
    Cigarrillo text,
    Alcohol text,
    Drogas text,
    PacienteId int,
    Estado varchar(100) DEFAULT 'Activo',
	CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL,
    foreign key(PacienteId) references Hemodinamia_Paciente(PacienteId)
) ENGINE = INNODB;


/*
create table Hemodinamia_AntecedentePersonal(
	AntecedentePersonalId int primary key auto_increment,
    Patológicos text,
    EnfermedadesInfancia text,
    EnfermedadesAdultez text,
    Quirurgicos text,
    Hospitalizaciones text,
    Transfusiones text,
    Toxicos text,
    Alergicos text,
    HistoricaClinicaId int,
    Estado varchar(100) DEFAULT 'Activo',
	CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL,
    foreign key(HistoricaClinicaId) references Hemodinamia_HistoricaClinica(HistoricaClinicaId)
) ENGINE = INNODB;*/
/*
create table Hemodinamia_AntecedentePsicosocial(
	AntecedentePsicosocialId int primary key auto_increment,
    Alimenticios text,
    Cigarrillo text,
    Alcohol text,
    Drogas text,
    HistoricaClinicaId int,
    Estado varchar(100) DEFAULT 'Activo',
	CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT NULL,
    foreign key(HistoricaClinicaId) references Hemodinamia_HistoricaClinica(HistoricaClinicaId)
) ENGINE = INNODB;*/



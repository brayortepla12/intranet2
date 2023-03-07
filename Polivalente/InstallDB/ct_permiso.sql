ALTER TABLE `polivalente`.`ct_persona` 
ADD COLUMN `Usuario` VARCHAR(70) NULL AFTER `Foto`;

ALTER TABLE `polivalente`.`ct_control` 
ADD COLUMN `PermisoId` INT NULL AFTER `Tipo`;

drop table if exists ct_permiso;
create table if not exists ct_permiso(
	PermisoId int primary key auto_increment,
    PersonaId int,
    LiderId int,
    UsuarioGHId int,
    VBGestionHumana tinyint,
    VBJefeArea tinyint default 1,
    IsConsumido int,
    Otro tinyint,
    Motivo varchar(200),
    FechaInicio datetime,
    FechaFin datetime,
    Estado varchar(250) default 'Activo',
    CreatedBy varchar(200) DEFAULT NULL,
	CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	ModifiedBy varchar(200) DEFAULT NULL,
	ModifiedAt datetime DEFAULT CURRENT_TIMESTAMP on update current_timestamp,
    foreign key (PersonaId) references ct_persona(PersonaId),
    foreign key (LiderId) references ct_persona(PersonaId)
);
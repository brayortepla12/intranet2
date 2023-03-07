DELIMITER $$
CREATE DEFINER=`ospino`@`%` PROCEDURE `ConsultaBiometrico`(in $PersonaId int, in $Mes int, in $Anno int)
BEGIN

DROP TEMPORARY TABLE if exists ct_reporte;
DROP TEMPORARY TABLE if exists ct_reporte1;
SET @row_number = 0;
SET @Day_no = 0;


CREATE TEMPORARY TABLE ct_reporte (
       rowNumber INT DEFAULT NULL
     , Dia INT DEFAULT NULL
     , ControlId INT DEFAULT NULL 
     , PersonaId INT DEFAULT NULL 
     , PermisoId INT DEFAULT NULL 
     , PermisoBySede INT DEFAULT NULL  
     , fecha char(10) DEFAULT NULL 
     , Hora char(8) DEFAULT NULL 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

insert into ct_reporte (rowNumber,Dia, ControlId,PersonaId, PermisoId, PermisoBySede,fecha,Hora)
SELECT 
    @row_number:=CASE WHEN @Day_no = day(fecha) THEN @row_number + 1  ELSE 1 END AS num,
    @Day_no:=day(fecha) as Dia, ControlId,PersonaId, PermisoId, PermisoBySede,
    substring(fecha,1,10) Fecha,
	time_to_sec(fecha) Hora
FROM
    polivalente.ct_control
WHERE 
	PersonaId=$PersonaId and MONTH(fecha) = $Mes and YEAR(fecha) = $Anno
	#GROUP BY fecha
ORDER BY Dia, Hora;


CREATE TEMPORARY TABLE ct_reporte1 (
      fecha char(10) DEFAULT NULL
     , E1 char(8) DEFAULT NULL 
     , E1_P int DEFAULT NULL 
     , E1_PS int DEFAULT NULL  
     , E1_C int DEFAULT NULL 
     , S2 char(8) DEFAULT NULL  
     , S2_P int DEFAULT NULL 
     , S2_PS int DEFAULT NULL    
     , S2_C int DEFAULT NULL
     , E3 char(8) DEFAULT NULL   
     , E3_P int DEFAULT NULL 
     , E3_PS int DEFAULT NULL   
     , E3_C int DEFAULT NULL
     , S4 char(8) DEFAULT NULL   
     , S4_P int DEFAULT NULL 
     , S4_PS int DEFAULT NULL   
     , S4_C int DEFAULT NULL  
     , E5 char(8) DEFAULT NULL   
     , E5_P int DEFAULT NULL 
     , E5_PS int DEFAULT NULL   
     , E5_C int DEFAULT NULL   
     , S6 char(8) DEFAULT NULL   
     , S6_P int DEFAULT NULL 
     , S6_PS int DEFAULT NULL    
     , S6_C int DEFAULT NULL   
     , E7 char(8) DEFAULT NULL   
     , E7_P int DEFAULT NULL 
     , E7_PS int DEFAULT NULL   
     , E7_C int DEFAULT NULL 
     , S8 char(8) DEFAULT NULL   
     , S8_P int DEFAULT NULL 
     , S8_PS int DEFAULT NULL   
     , S8_C int DEFAULT NULL 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



insert into ct_reporte1 (fecha,E1, E1_P, E1_PS, E1_C)
select  fecha,Hora, PermisoId, PermisoBySede, ControlId FROM ct_reporte WHERE rowNumber=1 ;
        
insert into ct_reporte1 (fecha,S2, S2_P, S2_PS, S2_C)
select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte WHERE rowNumber=2 ;
        
insert into ct_reporte1 (fecha,E3, E3_P, E3_PS, E3_C)
select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte WHERE rowNumber=3 ;
        
insert into ct_reporte1 (fecha,S4, S4_P, S4_PS, S4_C)
select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte WHERE rowNumber=4 ;
        
insert into ct_reporte1 (fecha,E5, E5_P, E5_PS, E5_C)
select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte WHERE rowNumber=5 ;
        
insert into ct_reporte1 (fecha,S6, S6_P, S6_PS, S6_C)
select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte WHERE rowNumber=6 ;
        
insert into ct_reporte1 (fecha,E7, E7_P, E7_PS, E7_C)
select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte WHERE rowNumber=7 ;
        
insert into ct_reporte1 (fecha,S8, S8_P, S8_PS, S8_C)
select  fecha, Hora, PermisoId, PermisoBySede, ControlId  FROM ct_reporte WHERE rowNumber=8 ;


SELECT tabla.*,
ADDTIME(ADDTIME(ifnull(timediff(tabla.S2, tabla.E1), '00:00:00') , ifnull(timediff(tabla.S4, tabla.E3), '00:00:00') ), ADDTIME(ifnull(timediff(tabla.S6, tabla.E5), '00:00:00') , ifnull(timediff(tabla.S8, tabla.E7), '00:00:00' ))) as Total
 FROM (select (ELT(WEEKDAY(DATE_FORMAT(t1.fecha, '%Y%m%d')) + 1, 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom')) AS DIA_SEMANA,
        DATE_FORMAT(t1.fecha, '%Y-%m-%d') as fecha,	
        SUBSTRING(SEC_TO_TIME(max(t0.E1)),1,8) E1,
        max(t0.E1_P) E1_P, max(t0.E1_PS) E1_PS, GetDispositivoByControlId(max(E1_C)) E1_C, VerificarEstadoEntrada($PersonaId, SEC_TO_TIME(max(t0.E1)), t0.fecha) as E1_E,
		SUBSTRING(SEC_TO_TIME(max(t0.S2)),1,8) S2,
        max(t0.S2_P) S2_P, max(t0.S2_PS) S2_PS, GetDispositivoByControlId(max(S2_C)) S2_C, VerificarEstadoSalida($PersonaId, SEC_TO_TIME(max(t0.S2)), t0.fecha) as S2_E,
		SUBSTRING(SEC_TO_TIME(max(t0.E3)),1,8) E3,
        max(t0.E3_P) E3_P, max(t0.E3_PS) E3_PS, GetDispositivoByControlId(max(E3_C)) E3_C, VerificarEstadoEntrada($PersonaId, SEC_TO_TIME(max(t0.E3)), t0.fecha) as E3_E,
		SUBSTRING(SEC_TO_TIME(max(t0.S4)),1,8) S4,
       max(t0.S4_P) S4_P, max(t0.S4_PS) S4_PS, GetDispositivoByControlId(max(S4_C)) S4_C, VerificarEstadoSalida($PersonaId, SEC_TO_TIME(max(t0.S4)), t0.fecha) as S4_E,
		SUBSTRING(SEC_TO_TIME(max(t0.E5)),1,8) E5,
        max(t0.E5_P) E5_P, max(t0.E5_PS) E5_PS, GetDispositivoByControlId(max(E5_C)) E5_C, VerificarEstadoEntrada($PersonaId, SEC_TO_TIME(max(t0.E5)), t0.fecha) as E5_E,
		SUBSTRING(SEC_TO_TIME(max(t0.S6)),1,8) S6,
        max(t0.S6_P) S6_P, max(t0.S6_PS) S6_PS, GetDispositivoByControlId(max(S6_C)) S6_C, VerificarEstadoSalida($PersonaId, SEC_TO_TIME(max(t0.S6)), t0.fecha) as S6_E,
		SUBSTRING(SEC_TO_TIME(max(t0.E7)),1,8) E7,
        max(t0.E7_P) E7_P, max(t0.E7_PS) E7_PS, GetDispositivoByControlId(max(E7_C)) E7_C, VerificarEstadoEntrada($PersonaId, SEC_TO_TIME(max(t0.E7)), t0.fecha) as E7_E,
		SUBSTRING(SEC_TO_TIME(max(t0.S8)),1,8) S8,
        max(t0.S8_P) S8_P, max(t0.S8_PS) S8_PS, GetDispositivoByControlId(max(S8_C)) S8_C, VerificarEstadoSalida($PersonaId, SEC_TO_TIME(max(t0.S8)), t0.fecha) as S8_E
        from ct_reporte1 t0
        right join ct_mes t1 on t0.fecha = t1.fecha
        where t1.mes=$Mes and annio= $Anno
        group by t1.fecha) as tabla
        order by tabla.fecha;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` PROCEDURE `ConsultaBiometricoByLider`(in $LiderId int, in $Mes int, in $Anno int)
BEGIN

	DROP TEMPORARY TABLE if exists ct_reporte_col;
    DROP TEMPORARY TABLE if exists ct_reporte_col1;
    
    CREATE TEMPORARY TABLE ct_reporte_col (
              rowNumber INT DEFAULT NULL
            , Dia INT DEFAULT NULL 
			, ControlId INT DEFAULT NULL 
			, PermisoId INT DEFAULT NULL 
			, PermisoBySede INT DEFAULT NULL 
            , PersonaId INT DEFAULT NULL
            , Usuario varchar(200) DEFAULT NULL
            , fecha varchar(10) DEFAULT NULL 
            , Hora varchar(8) DEFAULT NULL 
       );
        insert into ct_reporte_col (rowNumber,Dia, ControlId, PermisoId, PermisoBySede,PersonaId,Usuario,fecha,Hora)
       SELECT 
           @row_number:=CASE WHEN @Day_no = day(t0.fecha) THEN @row_number + 1  ELSE 1 END AS num,
           @Day_no:=day(t0.fecha) as Dia,
           ControlId, PermisoId, PermisoBySede,
           t0.PersonaId,
           Concat(t1.PrimerNombre, ' ', t1.SegundoNombre, ' ', t1.PrimerApellido, ' ', t1.SegundoApellido),
           substring(t0.fecha,1,10) Fecha,
           time_to_sec(t0.fecha ) Hora
       FROM
           polivalente.ct_control t0
           inner join polivalente.ct_persona t1 on t0.PersonaId=t1.PersonaId
           inner join polivalente.ct_persona j on t1.JefeId=j.PersonaId
       WHERE 
                MONTH(t0.fecha)=$Mes and year(t0.fecha)=$Anno and j.UsuarioIntranetId = $LiderId 
       ORDER BY t1.PrimerNombre;
        CREATE TEMPORARY TABLE ct_reporte_col1 (
             fecha varchar(10) DEFAULT NULL 
            , Usuario varchar(200) DEFAULT NULL
            , Dia int DEFAULT NULL
			, E1 char(8) DEFAULT NULL 
			, E1_P int DEFAULT NULL 
			, E1_PS int DEFAULT NULL  
			, E1_C int DEFAULT NULL 
			, S2 char(8) DEFAULT NULL  
			, S2_P int DEFAULT NULL 
			, S2_PS int DEFAULT NULL    
			, S2_C int DEFAULT NULL
			, E3 char(8) DEFAULT NULL   
			, E3_P int DEFAULT NULL 
			, E3_PS int DEFAULT NULL   
			, E3_C int DEFAULT NULL
			, S4 char(8) DEFAULT NULL   
			, S4_P int DEFAULT NULL 
			, S4_PS int DEFAULT NULL   
			, S4_C int DEFAULT NULL  
			, E5 char(8) DEFAULT NULL   
			, E5_P int DEFAULT NULL 
			, E5_PS int DEFAULT NULL   
			, E5_C int DEFAULT NULL   
			, S6 char(8) DEFAULT NULL   
			, S6_P int DEFAULT NULL 
			, S6_PS int DEFAULT NULL    
			, S6_C int DEFAULT NULL   
			, E7 char(8) DEFAULT NULL   
			, E7_P int DEFAULT NULL 
			, E7_PS int DEFAULT NULL   
			, E7_C int DEFAULT NULL 
			, S8 char(8) DEFAULT NULL   
			, S8_P int DEFAULT NULL 
			, S8_PS int DEFAULT NULL   
			, S8_C int DEFAULT NULL  

       );
        
       insert into ct_reporte_col1 (Usuario, Dia,E1, E1_P, E1_PS, E1_C)
       select usuario, Dia, hora, PermisoId, PermisoBySede, ControlId  from ct_reporte_col where rownumber=1;
        insert into ct_reporte_col1 (Usuario,Dia,S2, S2_P, S2_PS, S2_C)
       select usuario, Dia, hora, PermisoId, PermisoBySede, ControlId  from ct_reporte_col where rownumber=2;
        insert into ct_reporte_col1 (Usuario,Dia,E3, E3_P, E3_PS, E3_C)
       select usuario,Dia, hora, PermisoId, PermisoBySede, ControlId  from ct_reporte_col where rownumber=3;
        insert into ct_reporte_col1 (Usuario,Dia,S4, S4_P, S4_PS, S4_C)
       select usuario,Dia, hora, PermisoId, PermisoBySede, ControlId  from ct_reporte_col where rownumber=4;
        insert into ct_reporte_col1 (Usuario,Dia,E5, E5_P, E5_PS, E5_C)
       select usuario,Dia, hora, PermisoId, PermisoBySede, ControlId from ct_reporte_col where rownumber=5;
        insert into ct_reporte_col1 (Usuario,Dia,S6, S6_P, S6_PS, S6_C)
       select usuario,Dia, hora, PermisoId, PermisoBySede, ControlId  from ct_reporte_col where rownumber=6;
        insert into ct_reporte_col1 (Usuario,Dia,E7, E7_P, E7_PS, E7_C)
       select usuario,Dia, hora, PermisoId, PermisoBySede, ControlId  from ct_reporte_col where rownumber=7;
        insert into ct_reporte_col1 (Usuario,Dia,S8, S8_P, S8_PS, S8_C)
       select usuario,Dia, hora, PermisoId, PermisoBySede, ControlId from ct_reporte_col where rownumber=8;
        select usuario, Dia,	SUBSTRING(SEC_TO_TIME(max(E1)),1,8) E1,
                                       SUBSTRING(SEC_TO_TIME(max(S2)),1,8) S2,
                       SUBSTRING(SEC_TO_TIME(max(E3)),1,8) E3,
                       SUBSTRING(SEC_TO_TIME(max(S4)),1,8) S4,
                       SUBSTRING(SEC_TO_TIME(max(E5)),1,8) E5,
                       SUBSTRING(SEC_TO_TIME(max(S6)),1,8) S6,
                       SUBSTRING(SEC_TO_TIME(max(E7)),1,8) E7,
                       SUBSTRING(SEC_TO_TIME(max(S8)),1,8) S8
                       ,max(E1_PS) E1_PS, GetDispositivoByControlId(max(E1_C)) E1_C
                       ,max(S2_PS) S2_PS, GetDispositivoByControlId(max(S2_C)) S2_C
                       ,max(E3_PS) E3_PS, GetDispositivoByControlId(max(E3_C)) E3_C
                       ,max(S4_PS) S4_PS, GetDispositivoByControlId(max(S4_C)) S4_C
                       ,max(E5_PS) E5_PS, GetDispositivoByControlId(max(E5_C)) E5_C
                       ,max(S6_PS) S6_PS, GetDispositivoByControlId(max(S6_C)) S6_C
                       ,max(E7_PS) E7_PS, GetDispositivoByControlId(max(E7_C)) E7_C
                       ,max(S8_PS) S8_PS, GetDispositivoByControlId(max(S8_C)) S8_C
       from ct_reporte_col1 group by Dia,Usuario order by Dia,Usuario;
       
       DROP TEMPORARY TABLE if exists ct_reporte_col;
		DROP TEMPORARY TABLE if exists ct_reporte_col1;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` PROCEDURE `ListaLideresBiometrico`(in $Dia int, in $Mes int, in $Year int)
BEGIN
        
        DROP TEMPORARY TABLE if exists ct_reporte_lider;
        DROP TEMPORARY TABLE if exists ct_reporte_lider1;
        DROP TEMPORARY TABLE if exists ct_reporte_lider;
        DROP TEMPORARY TABLE if exists ct_reporte_lider1;
        CREATE TEMPORARY TABLE ct_reporte_lider (
              rowNumber INT DEFAULT NULL
            , Dia INT DEFAULT NULL 
            , PersonaId INT DEFAULT NULL
            , Usuario varchar(200) DEFAULT NULL
            , fecha varchar(10) DEFAULT NULL 
            , Hora varchar(8) DEFAULT NULL 
       );
        insert into ct_reporte_lider (rowNumber,Dia,PersonaId,Usuario,fecha,Hora)
       SELECT 
           @row_number:=CASE WHEN @Day_no = day(t0.fecha) THEN @row_number + 1  ELSE 1 END AS num,
           @Day_no:=day(t0.fecha) as Dia,
           t0.PersonaId,
           CONCAT(t1.PrimerNombre, " ", t1.SegundoNombre, " ", t1.PrimerApellido, " ", t1.SegundoApellido),
           substring(t0.fecha,1,10) Fecha,
           time_to_sec(t0.fecha ) Hora
       FROM
           polivalente.ct_control t0
           inner join polivalente.ct_persona t1 on t0.PersonaId=t1.PersonaId
       WHERE 
                MONTH(t0.fecha)=$Mes and year(fecha)=$Year and t1.TipoPersona="Lider" and t1.Estado = "Activo"
       ORDER BY t1.PrimerNombre;
        CREATE TEMPORARY TABLE ct_reporte_lider1 (
             fecha varchar(10) DEFAULT NULL 
            , Usuario varchar(200) DEFAULT NULL
            , E1 varchar(8) DEFAULT NULL 
            , S2 varchar(8) DEFAULT NULL 
            , E3 varchar(8) DEFAULT NULL 
            , S4 varchar(8) DEFAULT NULL 
            , E5 varchar(8) DEFAULT NULL 
            , S6 varchar(8) DEFAULT NULL 
            , E7 varchar(8) DEFAULT NULL 
            , S8 varchar(8) DEFAULT NULL 

       );
        
       insert into ct_reporte_lider1 (Usuario,E1)
       select usuario, hora from ct_reporte_lider where rownumber=1 and dia=$Dia ;
        insert into ct_reporte_lider1 (Usuario,S2)
       select usuario, hora from ct_reporte_lider where rownumber=2 and dia=$Dia;
        insert into ct_reporte_lider1 (Usuario,E3)
       select usuario, hora from ct_reporte_lider where rownumber=3 and dia=$Dia;
        insert into ct_reporte_lider1 (Usuario,S4)
       select usuario, hora from ct_reporte_lider where rownumber=4 and dia=$Dia;
        insert into ct_reporte_lider1 (Usuario,E5)
       select usuario, hora from ct_reporte_lider where rownumber=5 and dia=$Dia;
        insert into ct_reporte_lider1 (Usuario,S6)
       select usuario, hora from ct_reporte_lider where rownumber=6 and dia=$Dia;
        insert into ct_reporte_lider1 (Usuario,E7)
       select usuario, hora from ct_reporte_lider where rownumber=7 and dia=$Dia;
        insert into ct_reporte_lider1 (Usuario,S8)
       select usuario, hora from ct_reporte_lider where rownumber=8 and dia=$Dia;
        
		select tabla.usuario, tabla.E1, tabla.S2, tabla.E3, tabla.S4, tabla.E5, tabla.S6, tabla.E7, tabla.S8, 
        #timediff(tabla.S2, tabla.E1), timediff(tabla.S4, tabla.E3), timediff(tabla.S6, tabla.E5), timediff(tabla.S8, tabla.E7),
        ADDTIME(ADDTIME(ifnull(timediff(tabla.S2, tabla.E1), "00:00:00") , ifnull(timediff(tabla.S4, tabla.E3), "00:00:00") ), ADDTIME(ifnull(timediff(tabla.S6, tabla.E5), "00:00:00") , ifnull(timediff(tabla.S8, tabla.E7), "00:00:00" ))) as Total
        from (select usuario,	SUBSTRING(SEC_TO_TIME(sum(E1)),1,8) E1,
                                       SUBSTRING(SEC_TO_TIME(sum(S2)),1,8) S2,
                       SUBSTRING(SEC_TO_TIME(sum(E3)),1,8) E3,
                       SUBSTRING(SEC_TO_TIME(sum(S4)),1,8) S4,
                       SUBSTRING(SEC_TO_TIME(sum(E5)),1,8) E5,
                       SUBSTRING(SEC_TO_TIME(sum(S6)),1,8) S6,
                       SUBSTRING(SEC_TO_TIME(sum(E7)),1,8) E7,
                       SUBSTRING(SEC_TO_TIME(sum(S8)),1,8) S8
                       
                       

       from ct_reporte_lider1
       group by usuario) as tabla;
        DROP TEMPORARY TABLE if exists ct_reporte_lider;
        DROP TEMPORARY TABLE if exists ct_reporte_lider1;
        
        END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` PROCEDURE `PC_getFirmasByProcesoId`(_PROCESOID int)
BEGIN
	SELECT f.Fecha, f.Nombres collate latin1_bin as Nombres, f.Cargo collate latin1_bin as Cargo, f.Firma, f.SeguimientoId, f.Orden 
        from pc_firmas as f
        WHERE f.ProcesoId = _PROCESOID AND Estado = 'Activo' GROUP BY f.Nombres, f.Orden
        
        UNION ALL
        
        SELECT * from (
        SELECT 
            '' as Fecha,
            CONCAT(per.PrimerNombre collate latin1_bin,
                    ' ',
                    per.PrimerApellido collate latin1_bin) as Nombres,
            c.Cargo collate latin1_bin as Cargo, 
            NULL as Firma,
            NULL as SeguimientoId,
            ft.Orden
        FROM
            pc_flujotrabajo AS ft
                STRAIGHT_JOIN
            pc_proceso AS p ON p.ProtocoloId = ft.ProtocoloId
                STRAIGHT_JOIN
            pc_verificador AS v ON v.FlujoTrabajoId = ft.FlujoTrabajoId
                STRAIGHT_JOIN
            ct_persona AS per ON v.UsuarioId = per.UsuarioIntranetId
                LEFT JOIN
            ct_cargo AS c ON c.CargoId = per.CargoId
        WHERE
            p.ProcesoId = _PROCESOID and ft.Orden >= p.OrdenEnCurso and ft.Estado = 'Activo' and v.Estado = 'Activo'
         ORDER BY ft.Orden) as t ORDER BY Orden;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` PROCEDURE `PC_GetFlujoTrabajoByProcesoId`(_PROCESOID int)
BEGIN
	SET @RowNFT = -1;
SELECT 
    T.*, @RowNFT:=@RowNFT + 1 AS Orden
FROM
    (SELECT 
        fp.FlujoTrabajoId,
            fp.ProtocoloId,
            fp.Estado,
            protocolo.Nombre AS Protocolo
    FROM
        pc_flujotrabajo AS fp
    STRAIGHT_JOIN pc_proceso AS p ON fp.ProtocoloId = p.ProtocoloId
    STRAIGHT_JOIN pc_protocolo AS protocolo ON fp.ProtocoloId = protocolo.ProtocoloId
    WHERE
        p.ProcesoId = _PROCESOID
            AND fp.Estado <> 'Inactivo'
    ORDER BY fp.Orden) AS T;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` PROCEDURE `PC_GetUsuario_biomedicos_ToNotificarByProcesoId`(_PROCESOID int)
BEGIN
	SET @ROW_FT = -1;
SELECT 
    tabla.*
FROM
    (SELECT 
        t.*, @ROW_FT:=@ROW_FT + 1 AS Orden
    FROM
        (SELECT 
            CONCAT(per.PrimerNombre COLLATE latin1_bin, ' ', per.PrimerApellido COLLATE latin1_bin) AS Nombres,
            per.PersonaId,
            per.Celular,
            u.Email,
            c.Cargo COLLATE latin1_bin AS Cargo
    FROM
        pc_flujotrabajo AS ft
    STRAIGHT_JOIN pc_proceso AS p ON p.ProtocoloId = ft.ProtocoloId
    STRAIGHT_JOIN pc_verificador AS v ON v.FlujoTrabajoId = ft.FlujoTrabajoId
    STRAIGHT_JOIN ct_persona AS per ON v.UsuarioId = per.UsuarioIntranetId
    STRAIGHT_JOIN usuario AS u ON v.UsuarioId = u.UsuarioId
    LEFT JOIN ct_cargo AS c ON c.CargoId = per.CargoId
    WHERE
        p.ProcesoId = _PROCESOID
            AND ft.Orden >= p.OrdenEnCurso
            AND ft.Estado = 'Activo'
            AND v.Estado = 'Activo'
    ORDER BY ft.Orden) AS t
    ORDER BY Orden) AS tabla
    STRAIGHT_JOIN pc_proceso as pro on pro.ProcesoId = _PROCESOID
WHERE
    tabla.Orden <= pro.OrdenEnCurso
UNION ALL
SELECT 
    CONCAT(per.PrimerNombre COLLATE latin1_bin,
            ' ',
            per.PrimerApellido COLLATE latin1_bin) AS Nombres,
    per.PersonaId,
    per.Celular,
    u.Email,
    c.Cargo COLLATE latin1_bin AS Cargo, '-' AS Orden
FROM
    biomedicos_eventosolicitud AS e
        INNER JOIN
    biomedicos_solicitud AS s ON e.SolicitudId = s.SolicitudId
        INNER JOIN
    usuario AS u ON u.UsuarioId = s.UsuarioSolicitaId
        INNER JOIN
    ct_persona AS per ON u.UsuarioId = per.UsuarioIntranetId
        LEFT JOIN
    ct_cargo AS c ON c.CargoId = per.CargoId
WHERE
    e.ProcesoId = _PROCESOID;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` PROCEDURE `PC_GetUsuario_pol_ToNotificarByProcesoId`(_PROCESOID int)
BEGIN
	SET @ROW_FT = -1;
SELECT 
    tabla.*
FROM
    (SELECT 
        t.*, @ROW_FT:=@ROW_FT + 1 AS Orden
    FROM
        (SELECT 
            CONCAT(per.PrimerNombre COLLATE latin1_bin, ' ', per.PrimerApellido COLLATE latin1_bin) AS Nombres,
            per.PersonaId,
            per.Celular,
            u.Email,
            c.Cargo COLLATE latin1_bin AS Cargo
    FROM
        pc_flujotrabajo AS ft
    STRAIGHT_JOIN pc_proceso AS p ON p.ProtocoloId = ft.ProtocoloId
    STRAIGHT_JOIN pc_verificador AS v ON v.FlujoTrabajoId = ft.FlujoTrabajoId
    STRAIGHT_JOIN ct_persona AS per ON v.UsuarioId = per.UsuarioIntranetId
    STRAIGHT_JOIN usuario AS u ON v.UsuarioId = u.UsuarioId
    LEFT JOIN ct_cargo AS c ON c.CargoId = per.CargoId
    WHERE
        p.ProcesoId = _PROCESOID
            AND ft.Orden >= p.OrdenEnCurso
            AND ft.Estado = 'Activo'
            AND v.Estado = 'Activo'
    ORDER BY ft.Orden) AS t
    ORDER BY Orden) AS tabla
    STRAIGHT_JOIN pc_proceso as pro on pro.ProcesoId = _PROCESOID
WHERE
    tabla.Orden <= pro.OrdenEnCurso
UNION ALL
SELECT 
    CONCAT(per.PrimerNombre COLLATE latin1_bin,
            ' ',
            per.PrimerApellido COLLATE latin1_bin) AS Nombres,
    per.PersonaId,
    per.Celular,
    u.Email,
    c.Cargo COLLATE latin1_bin AS Cargo, '-' AS Orden
FROM
    pol_eventosolicitud AS e
        INNER JOIN
    pol_solicitud AS s ON e.SolicitudId = s.SolicitudId
        INNER JOIN
    usuario AS u ON u.UsuarioId = s.UsuarioSolicitaId
        INNER JOIN
    ct_persona AS per ON u.UsuarioId = per.UsuarioIntranetId
        LEFT JOIN
    ct_cargo AS c ON c.CargoId = per.CargoId
WHERE
    e.ProcesoId = _PROCESOID;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` PROCEDURE `PC_GetUsuario_sistemas_ToNotificarByProcesoId`(_PROCESOID int)
BEGIN
	SET @ROW_FT = -1;
SELECT 
    tabla.*
FROM
    (SELECT 
        t.*, @ROW_FT:=@ROW_FT + 1 AS Orden
    FROM
        (SELECT 
            CONCAT(per.PrimerNombre COLLATE latin1_bin, ' ', per.PrimerApellido COLLATE latin1_bin) AS Nombres,
            per.PersonaId,
            per.Celular,
            u.Email,
            c.Cargo COLLATE latin1_bin AS Cargo
    FROM
        pc_flujotrabajo AS ft
    STRAIGHT_JOIN pc_proceso AS p ON p.ProtocoloId = ft.ProtocoloId
    STRAIGHT_JOIN pc_verificador AS v ON v.FlujoTrabajoId = ft.FlujoTrabajoId
    STRAIGHT_JOIN ct_persona AS per ON v.UsuarioId = per.UsuarioIntranetId
    STRAIGHT_JOIN usuario AS u ON v.UsuarioId = u.UsuarioId
    LEFT JOIN ct_cargo AS c ON c.CargoId = per.CargoId
    WHERE
        p.ProcesoId = _PROCESOID
            AND ft.Orden >= p.OrdenEnCurso
            AND ft.Estado = 'Activo'
            AND v.Estado = 'Activo'
    ORDER BY ft.Orden) AS t
    ORDER BY Orden) AS tabla
    STRAIGHT_JOIN pc_proceso as pro on pro.ProcesoId = _PROCESOID
WHERE
    tabla.Orden <= pro.OrdenEnCurso
UNION ALL
SELECT 
    CONCAT(per.PrimerNombre COLLATE latin1_bin,
            ' ',
            per.PrimerApellido COLLATE latin1_bin) AS Nombres,
    per.PersonaId,
    per.Celular,
    u.Email,
    c.Cargo COLLATE latin1_bin AS Cargo, '-' AS Orden
FROM
    sistemas_eventosolicitud AS e
        INNER JOIN
    sistemas_solicitud AS s ON e.SolicitudId = s.SolicitudId
        INNER JOIN
    usuario AS u ON u.UsuarioId = s.UsuarioSolicitaId
        INNER JOIN
    ct_persona AS per ON u.UsuarioId = per.UsuarioIntranetId
        LEFT JOIN
    ct_cargo AS c ON c.CargoId = per.CargoId
WHERE
    e.ProcesoId = _PROCESOID;
END$$
DELIMITER ;

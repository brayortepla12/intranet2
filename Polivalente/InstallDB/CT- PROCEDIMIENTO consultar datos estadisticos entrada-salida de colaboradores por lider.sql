USE `polivalente`;
DROP procedure IF EXISTS `ConsultaBiometricoByLider`;
SET GLOBAL log_bin_trust_function_creators = 1;
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
           t0.CreatedBy,
           substring(t0.fecha,1,10) Fecha,
           time_to_sec(t0.fecha ) Hora
       FROM
           polivalente.ct_control t0
           inner join polivalente.ct_persona t1 on t0.PersonaId=t1.PersonaId
           inner join polivalente.ct_persona j on t1.JefeId=j.PersonaId
       WHERE 
                MONTH(t0.fecha)=$Mes and year(t0.fecha)=$Anno and j.UsuarioIntranetId = $LiderId #/substring(fecha,1,10) in ('2019-05-10','2019-05-13')/
       ORDER BY CreatedBy;
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

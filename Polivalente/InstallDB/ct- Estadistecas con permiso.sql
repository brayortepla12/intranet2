SET @row_number = 1;
SET @Day_no = 1;
DROP TEMPORARY TABLE if exists ct_reporte;

DROP TEMPORARY TABLE if exists ct_reporte1;
CREATE TEMPORARY TABLE ct_reporte (
       rowNumber INT DEFAULT NULL
     , Dia INT DEFAULT NULL
     , ControlId INT DEFAULT NULL 
     , PersonaId INT DEFAULT NULL 
     , PermisoId INT DEFAULT NULL 
     , fecha char(10) DEFAULT NULL 
     , Hora char(8) DEFAULT NULL 
);

insert into ct_reporte (rowNumber,Dia, ControlId,PersonaId, PermisoId,fecha,Hora)
SELECT 
    @row_number:=CASE WHEN @Day_no = day(fecha) THEN @row_number + 1  ELSE 1 END AS num,
    @Day_no:=day(fecha) as Dia, ControlId,PersonaId, PermisoId,
    substring(fecha,1,10) Fecha,
	time_to_sec(fecha) Hora
FROM
    polivalente.ct_control
WHERE 
	PersonaId=1596 and MONTH(fecha) = 6 and YEAR(fecha) = 2019
ORDER BY Dia;

CREATE TEMPORARY TABLE ct_reporte1 (
      fecha char(10) DEFAULT NULL
     , E1 char(8) DEFAULT NULL 
     , E1_P int DEFAULT NULL  
     , E1_C int DEFAULT NULL 
     , S2 char(8) DEFAULT NULL  
     , S2_P int DEFAULT NULL   
     , S2_C int DEFAULT NULL
     , E3 char(8) DEFAULT NULL   
     , E3_P int DEFAULT NULL   
     , E3_C int DEFAULT NULL
     , S4 char(8) DEFAULT NULL   
     , S4_P int DEFAULT NULL  
     , S4_C int DEFAULT NULL  
     , E5 char(8) DEFAULT NULL   
     , E5_P int DEFAULT NULL  
     , E5_C int DEFAULT NULL   
     , S6 char(8) DEFAULT NULL   
     , S6_P int DEFAULT NULL   
     , S6_C int DEFAULT NULL   
     , E7 char(8) DEFAULT NULL   
     , E7_P int DEFAULT NULL  
     , E7_C int DEFAULT NULL 
     , S8 char(8) DEFAULT NULL   
     , S8_P int DEFAULT NULL   
     , S8_C int DEFAULT NULL 
);

insert into ct_reporte1 (fecha,E1, E1_P, E1_C)
select  fecha,Hora, PermisoId, ControlId FROM ct_reporte WHERE rowNumber=1 ;

insert into ct_reporte1 (fecha,S2, S2_P, S2_C)
select  fecha, Hora, PermisoId, ControlId  FROM ct_reporte WHERE rowNumber=2 ;

insert into ct_reporte1 (fecha,E3, E3_P, E3_C)
select  fecha, Hora, PermisoId, ControlId  FROM ct_reporte WHERE rowNumber=3 ;

insert into ct_reporte1 (fecha,S4, S4_P, S4_C)
select  fecha, Hora, PermisoId, ControlId  FROM ct_reporte WHERE rowNumber=4 ;

insert into ct_reporte1 (fecha,E5, E5_P, E5_C)
select  fecha, Hora, PermisoId, ControlId  FROM ct_reporte WHERE rowNumber=5 ;

insert into ct_reporte1 (fecha,S6, S6_P, S6_C)
select  fecha, Hora, PermisoId, ControlId  FROM ct_reporte WHERE rowNumber=6 ;

insert into ct_reporte1 (fecha,E7, E7_P, E7_C)
select  fecha, Hora, PermisoId, ControlId  FROM ct_reporte WHERE rowNumber=7 ;

insert into ct_reporte1 (fecha,S8, S8_P, S8_C)
select  fecha, Hora, PermisoId, ControlId  FROM ct_reporte WHERE rowNumber=8 ;

SELECT tabla.*,
ADDTIME(ADDTIME(ifnull(timediff(tabla.S2, tabla.E1), '00:00:00') , ifnull(timediff(tabla.S4, tabla.E3), '00:00:00') ), ADDTIME(ifnull(timediff(tabla.S6, tabla.E5), '00:00:00') , ifnull(timediff(tabla.S8, tabla.E7), '00:00:00' ))) as Total
 FROM (select (ELT(WEEKDAY(t1.fecha) + 1, 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom')) AS DIA_SEMANA,
        t1.fecha,	
        SUBSTRING(SEC_TO_TIME(sum(t0.E1)),1,8) E1,
        max(t0.E1_P) E1_P, GetDispositivoByControlId(max(E1_C)) E1_C, VerificarEstadoEntrada(65, SEC_TO_TIME(sum(t0.E1)), t0.fecha) as E1_E,
		SUBSTRING(SEC_TO_TIME(sum(t0.S2)),1,8) S2,
        max(t0.S2_P) S2_P, GetDispositivoByControlId(max(S2_C)) S2_C, VerificarEstadoSalida(65, SEC_TO_TIME(sum(t0.S2)), t0.fecha) as S2_E,
		SUBSTRING(SEC_TO_TIME(sum(t0.E3)),1,8) E3,
        max(t0.E3_P) E3_P, GetDispositivoByControlId(max(E3_C)) E3_C, VerificarEstadoEntrada(65, SEC_TO_TIME(sum(t0.E3)), t0.fecha) as E3_E,
		SUBSTRING(SEC_TO_TIME(sum(t0.S4)),1,8) S4,
        max(t0.S4_P) S4_P, GetDispositivoByControlId(max(S4_C)) S4_C, VerificarEstadoSalida(65, SEC_TO_TIME(sum(t0.S4)), t0.fecha) as S4_E,
		SUBSTRING(SEC_TO_TIME(sum(t0.E5)),1,8) E5,
        max(t0.E5_P) E5_P, GetDispositivoByControlId(max(E5_C)) E5_C, VerificarEstadoEntrada(65, SEC_TO_TIME(sum(t0.E5)), t0.fecha) as E5_E,
		SUBSTRING(SEC_TO_TIME(sum(t0.S6)),1,8) S6,
        max(t0.S6_P) S6_P, GetDispositivoByControlId(max(S6_C)) S6_C, VerificarEstadoSalida(65, SEC_TO_TIME(sum(t0.S6)), t0.fecha) as S6_E,
		SUBSTRING(SEC_TO_TIME(sum(t0.E7)),1,8) E7,
        max(t0.E7_P) E7_P, GetDispositivoByControlId(max(E7_C)) E7_C, VerificarEstadoEntrada(65, SEC_TO_TIME(sum(t0.E7)), t0.fecha) as E7_E,
		SUBSTRING(SEC_TO_TIME(sum(t0.S8)),1,8) S8,
        max(t0.S8_P) S8_P, GetDispositivoByControlId(max(S8_C)) S8_C, VerificarEstadoSalida(65, SEC_TO_TIME(sum(t0.S8)), t0.fecha) as S8_E
        from ct_reporte1 t0
        right join ct_mes t1 on t0.fecha COLLATE latin1_spanish_ci = t1.fecha COLLATE latin1_spanish_ci
        where t1.mes=6 and annio=2019
        group by t1.fecha) as tabla;
        
      
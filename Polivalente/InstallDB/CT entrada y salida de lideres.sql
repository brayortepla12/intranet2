/*
 imprime entrada y salida por lideres
 */

	
DROP TEMPORARY TABLE if exists ct_reporte_lider;
DROP TEMPORARY TABLE if exists ct_reporte_lider1;

SET SQL_SAFE_UPDATES = 0;
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
    t0.CreatedBy,
    substring(t0.fecha,1,10) Fecha,
    time_to_sec(t0.fecha ) Hora
FROM
    polivalente.ct_control t0
    inner join polivalente.ct_persona t1 on t0.PersonaId=t1.PersonaId
WHERE 
	 MONTH(t0.fecha)=5 and year(fecha)=2019 and t1.TipoPersona='Lider'#/substring(fecha,1,10) in ('2019-05-10','2019-05-13')/
ORDER BY CreatedBy;




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

/* inserto el resiltado en la segunda tabla para poder tabular la fecha */
#/select  usuario,  CASE WHEN rowNumber = 1 THEN sum(Hora) ELSE 0 END AS E1 from ct_reporte where personaid='71' and dia=16 group by usuario;/

insert into ct_reporte_lider1 (Usuario,E1)
select usuario, hora from ct_reporte_lider where rownumber=1 and dia=16 ;

insert into ct_reporte_lider1 (Usuario,S2)
select usuario, hora from ct_reporte_lider where rownumber=2 and dia=16;

insert into ct_reporte_lider1 (Usuario,E3)
select usuario, hora from ct_reporte_lider where rownumber=3 and dia=16;

insert into ct_reporte_lider1 (Usuario,S4)
select usuario, hora from ct_reporte_lider where rownumber=4 and dia=16;

insert into ct_reporte_lider1 (Usuario,E5)
select usuario, hora from ct_reporte_lider where rownumber=5 and dia=16;

insert into ct_reporte_lider1 (Usuario,S6)
select usuario, hora from ct_reporte_lider where rownumber=6 and dia=16;

insert into ct_reporte_lider1 (Usuario,E7)
select usuario, hora from ct_reporte_lider where rownumber=7 and dia=16;

insert into ct_reporte_lider1 (Usuario,S8)
select usuario, hora from ct_reporte_lider where rownumber=8 and dia=16;

/--------------------------------------------------------------------------/

/* imprimo la tabla con el resultado */
select usuario,	SUBSTRING(SEC_TO_TIME(sum(E1)),1,8) E1,
				SUBSTRING(SEC_TO_TIME(sum(S2)),1,8) S2,
                SUBSTRING(SEC_TO_TIME(sum(E3)),1,8) E3,
                SUBSTRING(SEC_TO_TIME(sum(S4)),1,8) S4,
                SUBSTRING(SEC_TO_TIME(sum(E5)),1,8) E5,
                SUBSTRING(SEC_TO_TIME(sum(S6)),1,8) S6,
                SUBSTRING(SEC_TO_TIME(sum(E7)),1,8) E7,
                SUBSTRING(SEC_TO_TIME(sum(S8)),1,8) S8
                
from ct_reporte_lider1
group by usuario;



/*borro las tablas temporales */
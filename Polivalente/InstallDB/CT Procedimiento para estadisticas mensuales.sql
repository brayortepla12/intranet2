USE `polivalente`;
DROP procedure IF EXISTS `pro_biometrico_reporte`;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` PROCEDURE `pro_biometrico_reporte`(in idusuario int, in Mes int, in Anno int)
BEGIN

DROP TEMPORARY TABLE if exists ct_reporte;
DROP TEMPORARY TABLE if exists ct_reporte1;
DROP TEMPORARY TABLE if exists _temp1;

CREATE TEMPORARY TABLE ct_reporte (
       rowNumber INT DEFAULT NULL
     , Dia INT DEFAULT NULL 
     , PersonaId INT DEFAULT NULL 
     , fecha char(10) DEFAULT NULL 
     , Hora char(8) DEFAULT NULL 
);
insert into ct_reporte (rowNumber,Dia,PersonaId,fecha,Hora)
SELECT 
    @row_number:=CASE WHEN @Day_no = day(fecha) THEN @row_number + 1  ELSE 1 END AS num,
    @Day_no:=day(fecha) as Dia,PersonaId,
    substring(fecha,1,10) Fecha,
	time_to_sec(fecha) Hora
FROM
    polivalente.ct_control
WHERE 
	PersonaId=idusuario and MONTH(fecha) = Mes and YEAR(fecha) = Anno/*substring(fecha,1,10) in ('2019-05-10','2019-05-13')*/
ORDER BY Dia;

/* select * from ct_reporte; */

CREATE TEMPORARY TABLE ct_reporte1 (
      fecha char(10) DEFAULT NULL 
     , E1 char(8) DEFAULT NULL 
     , S2 char(8) DEFAULT NULL 
     , E3 char(8) DEFAULT NULL 
     , S4 char(8) DEFAULT NULL 
     , E5 char(8) DEFAULT NULL 
     , S6 char(8) DEFAULT NULL 
     , E7 char(8) DEFAULT NULL 
     , S8 char(8) DEFAULT NULL 
     
);
/*select * from ct_reporte1;*/

insert into ct_reporte1 (fecha,E1)
select  fecha, Hora FROM ct_reporte WHERE rowNumber=1 ;

insert into ct_reporte1 (fecha,S2)
select  fecha,Hora FROM ct_reporte WHERE rowNumber=2 ;

insert into ct_reporte1 (fecha,E3)
select  fecha, Hora FROM ct_reporte WHERE rowNumber=3 ;

insert into ct_reporte1 (fecha,S4)
select  fecha, Hora FROM ct_reporte WHERE rowNumber=4 ;

insert into ct_reporte1 (fecha,E5)
select  fecha, Hora FROM ct_reporte WHERE rowNumber=5 ;

insert into ct_reporte1 (fecha,S6)
select  fecha, Hora FROM ct_reporte WHERE rowNumber=6 ;

insert into ct_reporte1 (fecha,E7)
select  fecha, Hora FROM ct_reporte WHERE rowNumber=7 ;

insert into ct_reporte1 (fecha,S8)
select  fecha, Hora FROM ct_reporte WHERE rowNumber=8 ;

/*select fecha,sum(E1) ,sum(S2),sum(E3),sum(S4),sum(E5),sum(S6),sum(E7),sum(S8)
from ct_reporte1
group by fecha;
*/


CREATE temporary table _temp1(
	Id int primary key auto_increment,
    Fecha date,
    E1 time,
    S2 time,
    E3 time,
    S4 time,
    E5 time,
    S6 time,
    E7 time,
    S8 time,
    suma varchar(200)
  );
  
 INSERT INTO _temp1 (Fecha, E1,S2,E3,S4,E5,S6,E7,S8, suma) (select fecha as Fecha,	
				SEC_TO_TIME(sum(E1)) E1,
				SEC_TO_TIME(sum(S2)) S2,
                SEC_TO_TIME(sum(E3)) E3,
                SEC_TO_TIME(sum(S4)) S4,
                SEC_TO_TIME(sum(E5)) E5,
                SEC_TO_TIME(sum(S6)) S6,
                SEC_TO_TIME(sum(E7)) E7,
                SEC_TO_TIME(sum(S8)) S8, 
                Convert(SEC_TO_TIME(sum(E1)), char(20))
                
from ct_reporte1
group by fecha);



/* select fecha,e1,s2  from ct_reporte1 where fecha='2019-05-02'*/
	


END$$
DELIMITER ;

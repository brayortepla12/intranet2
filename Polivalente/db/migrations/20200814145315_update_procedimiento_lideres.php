<?php

use Phinx\Migration\AbstractMigration;

class UpdateProcedimientoLideres extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->execute('USE `polivalente`;
        DROP procedure IF EXISTS `ListaLideresBiometrico`;');
        $this->execute('
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
                ');
    }
}

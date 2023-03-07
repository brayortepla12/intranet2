<?php

use Phinx\Migration\AbstractMigration;

class HdCancelarPedido extends AbstractMigration
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
        $this->execute("ALTER TABLE `polivalente`.`sa_dhd` 
        ADD COLUMN `CancelarDesayuno` TINYINT NULL DEFAULT 0 AFTER `ModifiedAt`,
        ADD COLUMN `MotivoCD` VARCHAR(100) DEFAULT '' AFTER `CancelarDesayuno`,
        ADD COLUMN `CancelarMM` TINYINT NULL DEFAULT 0 AFTER `MotivoCD`,
        ADD COLUMN `MotivoCMM` VARCHAR(100) DEFAULT '' AFTER `CancelarMM`,
        ADD COLUMN `CancelarAlmuerzo` TINYINT NULL DEFAULT 0 AFTER `MotivoCMM`,
        ADD COLUMN `MotivoCA` VARCHAR(100) DEFAULT '' AFTER `CancelarAlmuerzo`,
        ADD COLUMN `CancelarMT` TINYINT NULL DEFAULT 0 AFTER `MotivoCA`,
        ADD COLUMN `MotivoCMT` VARCHAR(100) DEFAULT '' AFTER `CancelarMT`,
        ADD COLUMN `CancelarCena` TINYINT NULL DEFAULT 0 AFTER `MotivoCMT`,
        ADD COLUMN `MotivoCC` VARCHAR(100) DEFAULT '' AFTER `CancelarCena`,
        ADD COLUMN `CancelarMN` TINYINT NULL DEFAULT 0 AFTER `MotivoCC`,
        ADD COLUMN `MotivoCMN` VARCHAR(100) DEFAULT '' AFTER `CancelarMN`;
        
        ALTER TABLE `polivalente`.`sa_dhd` 
        ADD COLUMN `FechaCD` datetime NULL AFTER `ModifiedAt`,
        ADD COLUMN `FechaCMM` datetime NULL AFTER `FechaCD`,
        ADD COLUMN `FechaCA` datetime NULL AFTER `FechaCMM`,
        ADD COLUMN `FechaCMT` datetime NULL AFTER `FechaCA`,
        ADD COLUMN `FechaCC` datetime NULL AFTER `FechaCMT`,
        ADD COLUMN `FechaCMN` datetime NULL AFTER `FechaCC`;
        
        ALTER TABLE `polivalente`.`sa_dhd` 
        ADD COLUMN `ResponsableCD` varchar(50) DEFAULT '' NULL AFTER `ModifiedAt`,
        ADD COLUMN `ResponsableCMM` varchar(50) DEFAULT '' NULL AFTER `FechaCD`,
        ADD COLUMN `ResponsableCA` varchar(50) DEFAULT '' NULL AFTER `FechaCMM`,
        ADD COLUMN `ResponsableCMT` varchar(50) DEFAULT '' NULL AFTER `FechaCA`,
        ADD COLUMN `ResponsableCC` varchar(50) DEFAULT '' NULL AFTER `FechaCMT`,
        ADD COLUMN `ResponsableCMN` varchar(50) DEFAULT '' NULL AFTER `FechaCC`;
        
        
        USE `polivalente`;
        DROP function IF EXISTS `ContarComidasPorHDPorDia`;
        
        
        DELIMITER $$
        USE `polivalente`$$
        CREATE FUNCTION `ContarComidasPorHDPorDia`(_sector varchar(50), _dia int, _mes int, _annio int) RETURNS int(11)
            DETERMINISTIC
        BEGIN 
          DECLARE dist INT;
            SET dist = (SELECT sum(T.TD) + sum(T.TMM) + sum(T.TA) + sum(T.TMT) + sum(T.TC) + sum(T.TMN) FROM (
            SELECT dhd.DHDId,
            if(dhd.DesayunoId is not null AND dhd.EDesayuno <> 'Cancelado' AND dhd.CancelarDesayuno = 0, 1, 0) As 'TD',
            if(dhd.MMId is not null AND dhd.EMM <> 'Cancelado' AND dhd.CancelarMM = 0, 1, 0) As 'TMM',
            if(dhd.AlmuerzoId is not null AND dhd.EAlmuerzo <> 'Cancelado' AND dhd.CancelarAlmuerzo = 0, 1, 0) As 'TA',
            if(dhd.MTId is not null AND dhd.EMT <> 'Cancelado' AND dhd.CancelarMT = 0, 1, 0) As 'TMT',
            if(dhd.CenaId is not null AND dhd.ECena <> 'Cancelado' AND dhd.CancelarCena = 0, 1, 0) As 'TC',
            if(dhd.MNId is not null AND dhd.EMN <> 'Cancelado' AND dhd.CancelarMN = 0, 1, 0) As 'TMN'    
            FROM sa_dhd as dhd 
            inner join sa_hd as h on h.HDId = dhd.HDId
            where h.SECTOR = _sector and dayofmonth(h.Fecha) = _dia and month(h.Fecha) = _mes and year(h.Fecha) = _annio
            ) as T);
          RETURN dist;
        END$$
        
        DELIMITER ;
        
        
        USE `polivalente`;
        DROP function IF EXISTS `ContarComidasPorHDPorMes`;
        
        DELIMITER $$
        USE `polivalente`$$
        CREATE FUNCTION `ContarComidasPorHDPorMes`(_sector varchar(50), _mes int, _annio int) RETURNS int(11)
            DETERMINISTIC
        BEGIN 
          DECLARE dist INT;
            SET dist = (SELECT sum(T.TD) + sum(T.TMM) + sum(T.TA) + sum(T.TMT) + sum(T.TC) + sum(T.TMN) FROM (
            SELECT dhd.DHDId,
            if(dhd.DesayunoId is not null AND dhd.EDesayuno <> 'Cancelado' AND dhd.CancelarDesayuno = 0, 1, 0) As 'TD',
            if(dhd.MMId is not null AND dhd.EMM <> 'Cancelado' AND dhd.CancelarMM = 0, 1, 0) As 'TMM',
            if(dhd.AlmuerzoId is not null AND dhd.EAlmuerzo <> 'Cancelado' AND dhd.CancelarAlmuerzo = 0, 1, 0) As 'TA',
            if(dhd.MTId is not null AND dhd.EMT <> 'Cancelado' AND dhd.CancelarMT = 0, 1, 0) As 'TMT',
            if(dhd.CenaId is not null AND dhd.ECena <> 'Cancelado' AND dhd.CancelarCena = 0, 1, 0) As 'TC',
            if(dhd.MNId is not null AND dhd.EMN <> 'Cancelado' AND dhd.CancelarMN = 0, 1, 0) As 'TMN'    
            FROM sa_dhd as dhd 
            inner join sa_hd as h on h.HDId = dhd.HDId
            where h.SECTOR = _sector and month(h.Fecha) = _mes and year(h.Fecha) = _annio
            ) as T);
          RETURN dist;
        END$$
        
        DELIMITER ;");
    }
}

<?php

/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";require __DIR__ . "/../../vendor/autoload.php";

class ReferenciaDAL {
    private $db;
    
    public function __construct(){
//        if (!extension_loaded('sqlsvr')) {
//            dl("php_pdo_sqlsrv_56_nts.dll");
//            dl("php_pdo_sqlsrv_56_ts.dll");
//            dl("php_sqlsrv_56_nts.dll");
//            dl("php_sqlsrv_56_ts.dll");
//        }
       $this->db = new SQLSRV_DataBase("CIELD2017", "CLD_123456", "MANTENIMIENTO", "192.168.8.125");
    }
    
    public function GetReferenciasByObj($Obj) {
        $query = "SELECT * FROM [MANTENIMIENTO].[dbo].[Referencia] WHERE Movil = '$Obj->Movil' AND Conductor = '$Obj->Conductor' AND Auxiliar = '$Obj->Auxiliar' AND 
            Paciente = '$Obj->Paciente' AND TipoTraslado = '$Obj->TipoTraslado' AND Variable = '$Obj->Variable'";
        return $this->db->get_row($query);
    }
    
    public function GetReferenciasBetweenDates($Year, $Mes) {
        $query = "SELECT * FROM [MANTENIMIENTO].[dbo].[Referencia] WHERE year(Fecha) = $Year and
            Month(Fecha) = $Mes order by idReferencia DESC;";
        return $this->db->get_results($query, 'array');
    }
    
    public function GetReferenciasBetweenDatesByMovil($Movil,$From, $To) {
        $query = "SELECT * FROM [MANTENIMIENTO].[dbo].[Referencia] WHERE convert(varchar(10),Fecha,120) >= '" . $From ."' and
            convert(varchar(10),Fecha,120) <= '" . $To  . "' and Movil = '$Movil' order By idReferencia";
        return $this->db->get_results($query, 'array');
    }
    
    public function GetReferenciasBetweenConductor($From, $To,$HoraInicial,$HoraFinal) {
        $query = "SELECT Conductor, COUNT(Conductor) as ContConductor FROM [MANTENIMIENTO].[dbo].[Referencia] where convert(varchar(10),Fecha,120) >= '$From' and
                    convert(varchar(10),Fecha,120) <= '$To' and (DATEPART(HOUR,convert(varchar(10),Fecha,120)) >= $HoraInicial or DATEPART(HOUR,convert(varchar(10),Fecha,120)) < ($HoraFinal - 1)) group by Conductor order by ContConductor desc;";
        return $this->db->get_results($query, 'array');
    }
    public function GetReferenciasBetweenAuxiliar($From, $To,$HoraInicial,$HoraFinal) {
        $query = "SELECT Auxiliar, COUNT(Auxiliar) as ContAuxiliar FROM [MANTENIMIENTO].[dbo].[Referencia] where convert(varchar(10),Fecha,120) >= '$From' and
                    convert(varchar(10),Fecha,120) <= '$To' and (DATEPART(HOUR,convert(varchar(10),Fecha,120)) >= $HoraInicial or DATEPART(HOUR,convert(varchar(10),Fecha,120)) < ($HoraFinal - 1)) group by Auxiliar order by ContAuxiliar desc;";
        return $this->db->get_results($query, 'array');
    }
    
    public function GetReferenciasByDay($Day, $Mes, $Anno) {
        $query = "SELECT DISTINCT Paciente, Admision, Variable FROM [MANTENIMIENTO].[dbo].[Referencia] WHERE DATEPART(day, convert(varchar(10),Fecha,120)) = '". $Day ."' and DATEPART(month, convert(varchar(10),Fecha,120)) = '". $Mes ."' and DATEPART(year, convert(varchar(10),Fecha,120)) = '". $Anno ."'";
        return $this->db->get_results($query, 'array');
    }
    
    public function GetReferenciasByMonth($Mes, $Anno) {
        $query = "SELECT DISTINCT Paciente, Admision, Variable FROM [MANTENIMIENTO].[dbo].[Referencia] WHERE DATEPART(month, convert(varchar(10),Fecha,120)) = '". $Mes ."' and DATEPART(year, convert(varchar(10),Fecha,120)) = '". $Anno ."'";
        return $this->db->get_results($query, 'array');
    }
    
    public function GetReferenciasByYear($Anno) {
        $query = "SELECT DISTINCT Paciente, Admision, Variable FROM [MANTENIMIENTO].[dbo].[Referencia] WHERE DATEPART(year, convert(varchar(10),Fecha,120)) = '". $Anno ."'";
        return $this->db->get_results($query, 'array');
    }
    
    
    
    // <editor-fold defaultstate="collapsed" desc="Historicos">
    public function GetHistorico($Auxiliar) {
        $query = "Select * from [MANTENIMIENTO].[dbo].[Referencia] where DATEDIFF(HOUR,createdat,getdate())< 20 and Auxiliar = '" . strtoupper($Auxiliar) . "' order by createdat desc";
        return $this->db->get_results($query, 'array');
    }


    public function GetHistoricoConductor($Conductor) {
        $query = "Select * from [MANTENIMIENTO].[dbo].[Referencia] where DATEDIFF(HOUR,createdat,getdate())< 20 and Conductor = '" . strtoupper($Conductor) . "' order by createdat desc";
        return $this->db->get_results($query, 'array');
    }


    public function GetHistoricoAdministrativo() {
        $query = "Select TOP(20) * from [MANTENIMIENTO].[dbo].[Referencia] order by createdat desc";
        return $this->db->get_results($query, 'array');
    }

// </editor-fold>
    public function CreateReferencia($obj) {
        return $this->db->insert("[MANTENIMIENTO].[dbo].[Referencia]", $obj);
    }
    
    // <editor-fold defaultstate="collapsed" desc="Resumen">
    public function GetResumenByMonth($Mes, $anno, $nombre, $tipousuario) {
        $query = "SELECT variable, SUM(cantidad)Cont FROM [MANTENIMIENTO].[dbo].[Referencia] WHERE "
                . "DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' and " . strtolower($tipousuario) . " = '" . strtoupper($nombre) . "' group by variable ";
//        echo $query;
        return $this->db->get_results($query, 'array');
    }


    public function GetResumenByMonthAdministrativo($Mes, $anno) {
        $query = "SELECT variable, SUM(cantidad)Cont FROM [MANTENIMIENTO].[dbo].[Referencia] WHERE "
                . "DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' group by variable ";
        return $this->db->get_results($query, 'array');
    }

    public function GetResumenByDiaAdministrativo($Day, $Mes, $anno) {
        $query = "SELECT variable, SUM(cantidad)Cont FROM [MANTENIMIENTO].[dbo].[Referencia] WHERE "
                . "DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' group by variable ";
        return $this->db->get_results($query, 'array');
    }

// </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Graficas">
    public function GetCvsVByMonthAdministrativo($Mes, $anno) {
        $query = "select distinct conductor ,
                            (SELECT SUM(cantidad) #901 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='#901' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "'
                            and r1.Conductor=r0.Conductor
                            group by variable) #901 
                            ,
                            (SELECT SUM(cantidad) SOAT 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='SOAT' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Conductor=r0.Conductor
                            group by variable) SOAT 
                            ,
                            (SELECT SUM(cantidad) MERCADEO 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='MERCADEO' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Conductor=r0.Conductor
                            group by variable) MERCADEO
                            ,
                            (SELECT SUM(cantidad) COBRABLE 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='COBRABLE' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Conductor=r0.Conductor
                            group by variable) COBRABLE
                            ,
                            (SELECT SUM(cantidad) NO_COBRABLE 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='NO COBRABLE' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Conductor=r0.Conductor
                            group by variable) NO_COBRABLE    
                            ,
                            (SELECT SUM(cantidad) INTERNO 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='INTERNO' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "'
                            and r1.Conductor=r0.Conductor
                            group by variable) INTERNO  
                    from Referencia r0 inner join usuarios as u on r0.conductor = u.nombre where u.estado = 1";
        return $this->db->get_results($query, 'array');
    }


    public function GetCvsVByMonthAdministrativoAux($Mes, $anno) {
        $query = "select distinct Auxiliar ,
                            (SELECT SUM(cantidad) #901 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='#901' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "'
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) #901 
                            ,
                            (SELECT SUM(cantidad) SOAT 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='SOAT' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) SOAT 
                            ,
                            (SELECT SUM(cantidad) MERCADEO 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='MERCADEO' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) MERCADEO
                            ,
                            (SELECT SUM(cantidad) COBRABLE 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='COBRABLE' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) COBRABLE
                            ,
                            (SELECT SUM(cantidad) NO_COBRABLE 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='NO COBRABLE' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) NO_COBRABLE    
                            ,
                            (SELECT SUM(cantidad) INTERNO 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='INTERNO' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "'
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) INTERNO  
                    from Referencia r0 inner join usuarios as u on r0.Auxiliar = u.nombre where u.estado = 1";
        return $this->db->get_results($query, 'array');
    }


    public function GetCvsVByDayAdministrativo($Day, $Mes, $anno) {
        $query = "select distinct conductor ,
                            (SELECT SUM(cantidad) #901 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='#901' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "'
                            and r1.Conductor=r0.Conductor
                            group by variable) #901 
                            ,
                            (SELECT SUM(cantidad) SOAT 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='SOAT' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Conductor=r0.Conductor
                            group by variable) SOAT 
                            ,
                            (SELECT SUM(cantidad) MERCADEO 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='MERCADEO' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Conductor=r0.Conductor
                            group by variable) MERCADEO
                            ,
                            (SELECT SUM(cantidad) COBRABLE 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='COBRABLE' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Conductor=r0.Conductor
                            group by variable) COBRABLE
                            ,
                            (SELECT SUM(cantidad) NO_COBRABLE 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='NO COBRABLE' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Conductor=r0.Conductor
                            group by variable) NO_COBRABLE    
                            ,
                            (SELECT SUM(cantidad) INTERNO 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='INTERNO' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "'
                            and r1.Conductor=r0.Conductor
                            group by variable) INTERNO  
                    from Referencia r0 inner join usuarios as u on r0.conductor = u.nombre where u.estado = 1";
        return $this->db->get_results($query, 'array');
    }


    public function GetCvsVByDayAdministrativoAux($Day, $Mes, $anno) {
        $query = "select distinct Auxiliar ,
                            (SELECT SUM(cantidad) #901 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='#901' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "'
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) #901 
                            ,
                            (SELECT SUM(cantidad) SOAT 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='SOAT' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) SOAT 
                            ,
                            (SELECT SUM(cantidad) MERCADEO 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='MERCADEO' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) MERCADEO
                            ,
                            (SELECT SUM(cantidad) COBRABLE 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='COBRABLE' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) COBRABLE
                            ,
                            (SELECT SUM(cantidad) NO_COBRABLE 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='NO COBRABLE' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "' 
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) NO_COBRABLE    
                            ,
                            (SELECT SUM(cantidad) INTERNO 
                            FROM [MANTENIMIENTO].[dbo].[Referencia] as r1 
                            WHERE variable='INTERNO' and DATEPART(day, convert(varchar(10),Fecha,120)) = '" . $Day . "' and DATEPART(month, convert(varchar(10),Fecha,120)) = '" . $Mes . "' and DATEPART(year, convert(varchar(10),Fecha,120)) = '" . $anno . "'
                            and r1.Auxiliar=r0.Auxiliar
                            group by variable) INTERNO  
                    from Referencia r0 inner join usuarios as u on r0.Auxiliar = u.nombre where u.estado = 1";
        return $this->db->get_results($query, 'array');
    }

// </editor-fold>

}

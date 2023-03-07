<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class SMSCTDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function GetSMSByMes($Mes, $Year) {
        return $this->db->objectBuilder()->rawQuery("select sms.SMSId, 
        concat(lid.PrimerNombre, ' ', lid.PrimerApellido) as NombresJefe, 
        concat(c.PrimerNombre, ' ', c.PrimerApellido) as NombresColaborador, sms.Mensaje, sms.CreatedAt from ct_sms as sms
        inner join ct_persona as lid on lid.PersonaId = sms.PersonaId
        inner join ct_persona as c on c.PersonaId = sms.ColaboradorId
        where MONTH(sms.CreatedAt) = $Mes and YEAR(sms.CreatedAt) = $Year order by sms.SMSId DESC");
    }

}

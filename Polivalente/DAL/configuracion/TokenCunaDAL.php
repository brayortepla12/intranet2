<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class TokenCunaDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getAll() {
        return $this->db->jsonBuilder()->get("Observador_Token");
    }
    
    public function getAllByUserId($UserId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT DISTINCT se.TokenCunaId, se.Nombre FROM serviciousuario as su
                inner join servicio as s on su.ServicioId = s.ServicioId
                inner join sede as se on s.TokenCunaId = se.TokenCunaId
                where su.UsuarioId = $UserId;");
    }
    
    public function CreateTokenCuna($list) {
        $ids = $this->db->insertMulti("Observador_Token", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function GetTokenCuna($Nombre) {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("Observador_Token");
    }
    
    public function UpdateTokenCuna($list, $id) {
        $this->db->where ('TokenCunaId', $id);
        if ($this->db->update('Observador_Token', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}

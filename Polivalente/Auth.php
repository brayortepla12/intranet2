<?php
require_once __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

class Auth
{
  /**
   * Crear JWT
   *
   * @param Object $Data
   * @param array|null $Roles
   * @return string|null
   */
  public function CrearToken(Object $Data, ?array $Roles): ?string
  {
    $secret_key = "Franklin";
    $issuer_claim = "CIELD"; // this can be the servername
    $audience_claim = "THE_AUDIENCE";
    $issuedat_claim = time(); // issued at
    // $notbefore_claim = $issuedat_claim + 10; //not before in seconds
    $expire_claim = $issuedat_claim + 60 * 86400; // expire time in seconds
    $token = array(
      "iss" => $issuer_claim,
      "aud" => $audience_claim,
      "iat" => $issuedat_claim,
      // "nbf" => $notbefore_claim,
      "exp" => $expire_claim,
      "data" => array(
        "id" => $Data->PersonaId,
        "UserId" => $Data->UsuarioId,
        "Nombres" => $Data->NombreCompleto,
        "Roles" => json_encode($Roles)
      )
    );
    return JWT::encode($token, $secret_key);
  }

  public function DecodeJWT(String $jwt): Object
  {
    try {
      return JWT::decode($jwt, "Franklin", array('HS256'));
    } catch (\Firebase\JWT\ExpiredException $e) {
      // esta linea lanza una excepcion para que el JS(Angular 11) cierre la sesion y redirija al login
      $this->LogFile("{$this->GetFHNow('fh')}: {$e->getMessage()}\n\n", "log-auth.txt"); #Guardamos un log
      header('HTTP/1.0 401 Unauthorized');
      die('You are not allowed to access this file.');
    }
  }

  /**
     * Obtener fechas
     *
     * @param string $F Fecha|fh|h
     * @return string
     */
    private function GetFHNow(string $F = "Fecha"): string
    {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        $format = '';
        switch ($F) {
            case 'f':
                $format = 'Y-m-d';
                break;
            case 'fh':
                $format = 'Y-m-d H:i:s';
                break;

            default:
                $format = 'H:i:s';
                break;
        }
        return $datetime->format($format);
    }
    public function LogFile(string $Msg, string $fileName)
    {
        $fp = fopen(__DIR__ . "/$fileName", 'a+');
        fwrite($fp, $Msg);
        fclose($fp);
    }
}

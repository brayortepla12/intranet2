<?php

require __DIR__ . "/vendor/autoload.php";

use Gamegos\JWT\Validator;
use Gamegos\JWT\Token;
use Gamegos\JWT\Encoder;
use Gamegos\JWT\Exception\JWTException;

// <editor-fold defaultstate="collapsed" desc="base">
//$jwtString = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJzb21lb25lQGV4YW1wbGUuY29tIiwiZXhwIjoxNDkwMTEzOTUxfQ.0hUOdm9E56iU9-zn1L9klWHD2NON0H1Y42s90ydUHdM';
//
//$key = 'ospi89@hotmail.com';
//
//
//try {
//    $validator = new Validator();
//    $token = $validator->validate($jwtString, $key);
//    if (gettype($token) == "string") {
//        echo $token;
//    }else{
//        echo json_encode($token->getClaims());
//    echo json_encode($token->getHeaders());
//    }
//    
//} catch (JWTException $e) {
//    printf("Invalid Token:\n  %s\n", $e->getMessage());
//    //$e->getToken();
//}
//
////$key = 'some-secret-for-hmac';
//$alg = 'HS256';
////
//$token = new Token();
//$token->setClaim('sub', 'someone@example.com'); // alternatively you can use $token->setSubject('someone@example.com') method
//$token->setClaim('exp', time() + 60*5);
//
//$encoder = new Encoder();
//$encoder->encode($token, $key, $alg);
//
//printf("<br><br><br><br>JWT TOKEN: %s\n", $token->getJWT());
// </editor-fold>
class Security {

    public function GenerateToken($Nombre, $key, $Dias, $Roles) { // la llave es el correo, asi cada usuario tendra una llave y un token
        $token = new Token();
        $token->setClaim('sub', $Nombre); // alternatively you can use $token->setSubject('someone@example.com') method
        $token->setClaim('exp', time() + ($Dias * 60 * 60 * 24));
        $token->setClaim('data', json_encode($Roles));
        $alg = 'HS256';
        $encoder = new Encoder();
        $encoder->encode($token, $key, $alg);
        return $token->getJWT();
    }
    
    public function GenerateToken_PC($Nombre, $key, $ProcesoId, $UsuarioId, $Dias) { // la llave es el correo, asi cada usuario tendra una llave y un token
        $token = new Token();
        $token->setClaim('procesoid', $ProcesoId);
        $token->setClaim('usuarioid', $UsuarioId);
        $token->setClaim('sub', $Nombre); // alternatively you can use $token->setSubject('someone@example.com') method
        $token->setClaim('exp', time() + ($Dias * 60 * 60 * 24));
        $alg = 'HS256';
        $encoder = new Encoder();
        $encoder->encode($token, $key, $alg);
        return $token->getJWT();
    }
    
    
    public function GenerateTokenHoras($Nombre, $key, $horas) { // la llave es el correo, asi cada usuario tendra una llave y un token
        $token = new Token();
        $token->setClaim('sub', $Nombre); // alternatively you can use $token->setSubject('someone@example.com') method
        $token->setClaim('exp', time() + ($horas * 60 * 60 * 5));
        $alg = 'HS256';
        $encoder = new Encoder();
        $encoder->encode($token, $key, $alg);
        return $token->getJWT();
    }

    public function validateToken($jwtString, $key) { // la llave es el correo, asi cada usuario tendra una llave y un token
        try {
            $validator = new Validator();
            $token = $validator->validate($jwtString, $key);
            //print_r($token->getClaims());
            //print_r($token->getHeaders());
            if (gettype($token) == "string") {
                return $token;
            } else {
                return $token->getClaims();
//                return $token->getHeaders();
            }
        } catch (JWTException $e) {
            printf("Invalid Token:\n  %s\n", $e->getMessage());
            //$e->getToken();
        }
    }

    function encrypt($string, $key) {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }

    function decrypt($string, $key) {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }

}

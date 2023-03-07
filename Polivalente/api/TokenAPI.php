<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '\..\Security.php';
include_once dirname(__FILE__) . '/../BLL/seguridad/UsuarioBLL.php';

class TokenAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                if (isset($_GET["TokenId"])) {
                    echo json_encode($Helper->GetTokenById($_GET["TokenId"]));
                } else if (isset($_GET["TokenPolivalente"]) && isset($_GET["Email"])) {
                     $Helper = new Security();
                     $Token = $Helper->validateToken($_GET["TokenPolivalente"], $_GET["Email"]);
                     $hu = new UsuarioBLL();
                     //if (is_array($Token)) {
                         $Usuario = $hu->GetPermisoFacturacion($_GET["Email"]);
                         $this->utf8_encode_deep($Usuario);
                         echo json_encode($Usuario);
                     //} else {
                         //echo json_encode([]);
                     //}
                 } else if (isset($_GET["Token-vue"]) && isset($_GET["Email-vue"])) {
                     $Helper = new Security();
                     $Token = $Helper->validateToken($_GET["Token-vue"], $_GET["Email-vue"]);
                     if (is_array($Token)) {
                         echo true;
                     } else {
                         echo false;
                     }
                 } else {
                    echo "Hola esta es una prueba";
                } 
                break;
            case 'POST'://inserta
                if (isset($_POST["Token"])) {
                    $Helper = new Security();
                    echo json_encode($Helper->validateToken($_POST["Token"], "Biomedico_123458"));
                } else if (isset($_POST["TokenCuna"])) {
                    $Helper = new Security();
                    echo json_encode($Helper->validateToken($_POST["TokenCuna"], "Neonatos_123456789"));
                } else if (isset($_POST["TokenPolivalente"]) && isset($_POST["Email"])) {
                    $Helper = new Security();
                    $Token = $Helper->validateToken($_POST["TokenPolivalente"], $_POST["Email"]);
                    $hu = new UsuarioBLL();
                    if (is_array($Token)) {
                        $Usuario = Array($hu->GetPermisoFacturacion($_POST["Email"]));
                        $this->utf8_encode_deep($Usuario);
                        echo json_encode($Usuario);
                    } else {
                        echo json_encode([]);
                    }
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $m = new ModuloDTO();
                $m = $this->Mapper($_PUT);
                echo json_encode($m);
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                $m = new ModuloDTO();
                $m = $this->Mapper($_DELETE);
                echo json_encode($m);
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }
    public function utf8_encode_deep(&$input) {
        if (is_string($input)) {
            $input = utf8_encode($input);
        } else if (is_array($input)) {
            foreach ($input as &$value) {
                $this->utf8_encode_deep($value);
            }

            unset($value);
        } else if (is_object($input)) {
            $vars = array_keys(get_object_vars($input));

            foreach ($vars as $var) {
                $this->utf8_encode_deep($input->$var);
            }
        }
    }
}

//end class

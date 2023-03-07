<?php  require_once "ErrorHandler.php";

include_once dirname(__FILE__) . '/../BLL/Autorizacion/EmailAutorizacionBLL.php';
include_once dirname(__FILE__) . '/../BLL/Autorizacion/EnvioCorreoBLL.php';    
    

$ech = new EnvioCorreoBLL();

$Helper = new EmailAutorizacionBLL();
//echo print_r($list);
if (isset($_POST["EnvioCorreo"])) {
    $list = json_decode($_POST['EnvioCorreo']);
    $list->Archivos = array();
    foreach ($_FILES as $f) {
        $uploadfile = "../email_files/";
        $archivo = $f["name"];
        $target_file = $uploadfile . $archivo;

        if (move_uploaded_file($f["tmp_name"], $target_file)) {
            $obj = new stdClass();
            $obj->target_file = $target_file;
            $obj->archivo = $archivo;
            array_push($list->Archivos, $obj);
        }
    }
//    print_r($list);
    echo json_encode($ech->CreateEnvioCorreo($list));
//    echo json_encode($Helper->EnviarEmail($list));
}

function generateRandomString($length = 15) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

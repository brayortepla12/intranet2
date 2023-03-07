<?php  require_once "ErrorHandler.php";    

include_once dirname(__FILE__) . '/../BLL/formulario/ReporteBLL.php';

$target_dir = "../upload_files/";
//print_r($_FILES);
//echo $_POST['Objeto'];
$Fecha =  new DateTime();
$archivo = generateRandomString() . basename($_FILES["file"]["name"]);
$target_file = $target_dir . $archivo;
move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

$Helper = new ReporteBLL();
$list = json_decode($_POST['Objeto']);
//echo print_r($list);
echo json_encode($Helper->CreateReporteExterno($list, $archivo));

function generateRandomString($length = 15) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
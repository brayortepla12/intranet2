<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/Autorizacion/LogEmailDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/AutorizacionBLL.php';
require_once dirname(__FILE__) . '/EnvioCorreoBLL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require_once dirname(__FILE__) . '/../../BLL/seguridad/UsuarioBLL.php';

header('Content-Type: application/JSON');
$db = new LogEmailDAL();
$ah = new AutorizacionBLL();
$ech = new EnvioCorreoBLL();
$EnviosPendientes = $ech->GetAll();
//echo json_encode($EnviosPendientes);
foreach ($EnviosPendientes as $ep) {
    $list = $ech->getToSendEmailByOrden($ep->EnvioCorreoId, $ep->OrdenEnCurso);
    $Count = 0;
    $Siguiente = $ech->GetSiguiente($ep->ProtocoloId, $Count);
    while (count($Siguiente) == 0) {
        $Count++;
        $Siguiente = $ech->GetSiguiente($ep->ProtocoloId, $Count);
    }
//    echo print_r($Siguiente);
    $eh = new sendMail();
    $e = new EmpresaBLL();
    $EmpresaObj = $e->GetEmpresa();
    $files = json_decode($ep->Archivos);
    echo phpversion();
    echo $eh->NotificarAutorizacion($EmpresaObj, $Siguiente[0]->Nombre, $Siguiente[0]->Email, $files, "ASUNTO de PRUEBA ");
}
//$ItemsProtocolo = $ah->GetItemProtocoloById($EnvioCorreo->ProtocoloId);
//if (count($ItemsProtocolo) > 0) {
//    $id = $db->CreateLogEmail($this->MAPToLogEmail($EnvioCorreoId, $ItemsProtocolo[0]->Orden, $EnvioCorreo->CreatedBy));
//    if (count($id) > 0) {
//        $eh = new sendMail();
//        $e = new EmpresaBLL();
//        $EmpresaObj = $e->GetEmpresa();
//        $eh->EnviarEmail_Autorizacion($EmpresaObj, $ItemsProtocolo[0]->Nombre, $ItemsProtocolo[0]->Email, $EnvioCorreo->Archivos, "ASUNTO de PRUEBA ");
//        return $id;
//    }
//} else {
//    return "No se han podido enviar el email";
//}

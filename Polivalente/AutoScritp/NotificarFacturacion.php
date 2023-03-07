<?php
require_once dirname(__FILE__) . '/../BLL/facturacion/NFacturacionFBBLL.php';
$hp = new NFacturacionFBBLL();
echo $hp->NotificarALLUsuario();

<?php
require_once dirname(__FILE__) . '/../BLL/procesos/ProcesosBLL.php';
$hp = new ProcesosBLL();

echo $hp->NotificarProcesoPendiente();

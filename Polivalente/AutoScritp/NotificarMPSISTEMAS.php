<?php
require_once dirname(__FILE__) . '/../BLL/configuracion/CronogramaServicioSistemaBLL.php';
$ha = new CronogramaServicioSistemaBLL();
echo $ha->EnviarNotificacionMantenimiento();

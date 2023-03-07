<?php  require_once "ErrorHandler.php";
    require_once "CronogramaServicioSistemaAPI.php";        
    
    $CronogramaServicioSistemaAPI = new CronogramaServicioSistemaAPI();
    $CronogramaServicioSistemaAPI->API();
?>
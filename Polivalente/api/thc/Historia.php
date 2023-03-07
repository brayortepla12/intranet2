<?php  require_once dirname(__FILE__) . "/../ErrorHandler.php";   
     
    require_once "HistoriaAPI.php";
    $HistoriaAPI = new HistoriaAPI();
    $HistoriaAPI->API();
?>
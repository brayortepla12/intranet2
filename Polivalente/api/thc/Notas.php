<?php  require_once dirname(__FILE__) . "/../ErrorHandler.php";   
     
    require_once "NotasAPI.php";
    $NotasAPI = new NotasAPI();
    $NotasAPI->API();
?>
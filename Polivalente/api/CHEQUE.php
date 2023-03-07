<?php  require_once "ErrorHandler.php";
    require_once "CHEQUEAPI.php";   
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: false");
    header("Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE, CONNECT, OPTIONS, TRACE, PATCH");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With,Date,Server,X-Powered-By,Access-Control-Allow-Origin,Access-Control-Allow-Methods,Access-Control-Allow-Headers,Content-Length,Keep-Alive,Connection,Content-Type");
    header("Access-Control-Expose-Headers: Date, Server, X-Powered-By, Access-Control-Allow-Origin, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Content-Length, Keep-Alive, Connection, Content-Type");
    $CHEQUEAPI = new CHEQUEAPI();
    $CHEQUEAPI->API();
?>
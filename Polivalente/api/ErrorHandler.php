<?php

require_once "ErrorHandler.php";
include_once dirname(__FILE__) . '/../BLL/Error/ErrorBLL.php';

function LogDeErrores($numeroDeError, $descripcion, $fichero, $linea, $contexto = "") {
    $contexto = json_encode($contexto);
    $contexto = str_replace("\"", "`", $contexto);
    $contexto = str_replace("'", "`", $contexto);
    $descripcion = str_replace("\"", "", $descripcion);
    $descripcion = str_replace("'", "", $descripcion);
    $tz_object = new DateTimeZone('America/Bogota');
    $datetime = new DateTime();
    $datetime->setTimezone($tz_object);
    $eh = new ErrorBLL();
    $consulta = "INSERT INTO registro_de_logs (";
    $consulta .= "log, ";
    $consulta .= "fecha, ";
    $consulta .= "hora";
    $consulta .= ") VALUES (";
    $consulta .= "'" . "Error: [" . $numeroDeError . "] " . $descripcion . " " . $fichero . " " . $linea . " " . $contexto . "', ";
    $consulta .= "'" . $datetime->format("Y-m-d") . "', ";
    $consulta .= "'" . $datetime->format("H:i:s") . "');";
    if($numeroDeError != 8192 && preg_match('#^fsockopen():#', $descripcion) === 0){
        $eh->LogDeErrores($consulta);
    }
}

function LogDeErrores2($Exception) {
    $tz_object = new DateTimeZone('America/Bogota');
    $datetime = new DateTime();
    $datetime->setTimezone($tz_object);
    $eh = new ErrorBLL();
    $consulta = "INSERT INTO registro_de_logs (";
    $consulta .= "log, ";
    $consulta .= "fecha, ";
    $consulta .= "hora";
    $consulta .= ") VALUES (";
    $consulta .= "'" . "Error: " . $Exception->getMessage() . " " . str_replace("'", "\"", $Exception->getTraceAsString())  ."', ";
    $consulta .= "'" . $datetime->format("Y-m-d") . "', ";
    $consulta .= "'" . $datetime->format("H:i:s") . "');";
    $eh->LogDeErrores($consulta);
}

function fatal_handler() {
    $tz_object = new DateTimeZone('America/Bogota');
    $datetime = new DateTime();
    $datetime->setTimezone($tz_object);
    $error = error_get_last();
    $eh = new ErrorBLL();
    $consulta = "INSERT INTO registro_de_logs (";
    $consulta .= "log, ";
    $consulta .= "fecha, ";
    $consulta .= "hora";
    $consulta .= ") VALUES (";
    $consulta .= "'" . "Error: " . $error . "', ";
    $consulta .= "'" . $datetime->format("Y-m-d") . "', ";
    $consulta .= "'" . $datetime->format("H:i:s") . "');";
    $eh->LogDeErrores($consulta);
}

set_error_handler("LogDeErrores");
set_exception_handler("LogDeErrores2");

//register_shutdown_function("fatal_handler");



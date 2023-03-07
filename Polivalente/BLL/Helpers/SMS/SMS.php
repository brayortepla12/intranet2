<?php

//echo base64_encode("Usuario:Clave");
$basic = base64_encode("emerlaura:Colombia2019");
try {
    $data = array(
              'from' => 'Franklin',
              'to' => ['573046449579'],
              'text' => 'Hola esta es una prueba de envio de SMS - CIELD',
              );

$data_string = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://107.20.199.106/sms/1/text/single");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Basic $basic" , 
        "Content-Type: application/json",
        "Accept: application/json"
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    echo $result;
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}

/*
"from":"InfoSMS",
   "to":"41793026727",
   "text":"My first SMS."
 * 
 *  */
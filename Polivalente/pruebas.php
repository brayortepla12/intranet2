<?php
#echo 'extension mysqli -> ' . extension_loaded('mysqli');



function srcData($image)
{
  // Descargamos la imagen
  $ch = curl_init($image);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
  $image = curl_exec($ch); 
  curl_close($ch);
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  // reads your image's data and convert it to base64
  $data = base64_encode($image);
  // Create the image's SRC:  "data:{mime};base64,{data};"
  return 'data: ' . finfo_buffer($finfo, $image) . ';base64,' . $data;
}


function last_month_day($Mes, $Ano) {
  $month = $Mes;
  $year = $Ano;
  $day = date("d", mktime(0, 0, 0, $month + 1, 0, $year));
  return $day;
}


for ($m=1; $m <= 12; $m++) { 
  PrintInsert($m);
}

function PrintInsert(string $MOTH)
{
  $YEAR = 2022;
  $ULTIMO_DIA = last_month_day($MOTH,$YEAR);
  for ($DIA=1; $DIA <= $ULTIMO_DIA; $DIA++) { 
    $diap = str_pad($DIA,2,"0",STR_PAD_LEFT);
    $mesp = str_pad($MOTH,2,"0",STR_PAD_LEFT);
    echo "INSERT INTO `polivalente`.`ct_mes`(`Mes`,
    `annio`,
    `Dia`,
    `Fecha`)
    VALUES (
    $MOTH,
    $YEAR,
    $DIA,
    '$YEAR-$mesp-$diap')
    ;
    <br>";
  }
}
// echo srcData("http://190.131.221.26:8080/Polivalente/persona_firma/548.jpeg");
<?php
require_once dirname(__FILE__) . '/../BLL/ambulancia/HojaVidaAmbulanciaBLL.php';
$ha = new HojaVidaAmbulanciaBLL();
echo $ha->EmailMovilesProxVencerSoatTec();

<?php

if (isset($_GET["NombreEquipo"])) {
    $Nombre = $_GET["NombreEquipo"];
    echo "<script type='text/javascript'> window.open('cld://$Nombre', '_blank');
        </script>";
}


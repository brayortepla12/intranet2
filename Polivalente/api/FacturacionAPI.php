<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/facturacion/MesActualBLL.php';
include_once dirname(__FILE__) . '/../BLL/facturacion/FacturadoHoyBLL.php';
include_once dirname(__FILE__) . '/../BLL/facturacion/FacturadoEPSMesBLL.php';
include_once dirname(__FILE__) . '/../BLL/facturacion/AcostadosBLL.php';
include_once dirname(__FILE__) . '/../BLL/facturacion/FacturasEmitidasBLL.php';
include_once dirname(__FILE__) . '/../BLL/facturacion/CensosBLL.php';
include_once dirname(__FILE__) . '/../BLL/facturacion/RadicacionBLL.php';    
    
class FacturacionAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                if (isset($_GET["MesActual"]) && isset($_GET["Mes"]) && isset($_GET["Anno"])) {
                    $Helper = new MesActualBLL();
                    echo json_encode($Helper->GetMesActual($_GET["Mes"], $_GET["Anno"]));
                }else if (isset($_GET["FacturadoHoy"])) {
                    $Helper = new FacturadoHoyBLL();
                    echo json_encode($Helper->GetFacturadoHoy());
                }else if (isset($_GET["FacturadoMes_FB"]) && isset($_GET["FacturadoAnno_FB"])) {
                    $Helper = new MesActualBLL();
                    $o = $Helper->GetTotalFacturadoMES($_GET["FacturadoMes_FB"],$_GET["FacturadoAnno_FB"]);
                    echo json_encode($o);
                }else if (isset($_GET["FacturadoEPSMES"]) && isset($_GET["Mes"]) && isset($_GET["Anno"])) {
                    $Helper = new FacturadoEPSMesBLL();
                    $listado = $Helper->GetFacturadoEPSMes($_GET["Mes"], $_GET["Anno"]);
                    $this->utf8_encode_deep($listado);
                    echo json_encode($listado);
                }else if (isset($_GET["Acostados"])) {
                    $Helper = new AcostadosBLL();
                    $listado = $Helper->GetAcostados();
                    $this->utf8_encode_deep($listado);
                    echo json_encode($listado);
                }else if (isset($_GET["CensoPorSector"])) {
                    $Helper = new CensosBLL();
                    $listado = $Helper->GetCensoPorSector();
                    $this->utf8_encode_deep($listado);
                    echo json_encode($listado);
                }else if (isset($_GET["CensoPorEPS"])) {
                    $Helper = new CensosBLL();
                    $listado = $Helper->GetCensoPorEPS();
                    $this->utf8_encode_deep($listado);
                    echo json_encode($listado);
                }else if (isset($_GET["CensoPorPeriodo"]) && isset($_GET["Anno"]) && isset($_GET["Mes"])) {
                    $Helper = new CensosBLL();
                    $listado = $Helper->GetCensoPorPeriodo($_GET["Anno"], $_GET["Mes"]);
                    $this->utf8_encode_deep($listado);
                    echo json_encode($listado);
                }else if (isset($_GET["Radicacion"]) && isset($_GET["Anno"]) && isset($_GET["Mes"])) {
                    $Helper = new RadicacionBLL();
                    $listado = $Helper->GetRadicacion($_GET["Mes"], $_GET["Anno"]);
                    $this->utf8_encode_deep($listado);
                    echo json_encode($listado);
                }else if (isset($_GET["FacturasEmitidas"]) && isset($_GET["TIPO"])) {
                    $Helper = new FacturasEmitidasBLL();
                    if ($_GET["TIPO"] == 'Mes') {
                        $listado = $Helper->GetFacturasEmitidasMes();
                    }else if($_GET["TIPO"] == 'Hoy'){
                        $listado = $Helper->GetFacturasEmitidasHoy();
                    }
                    $this->utf8_encode_deep($listado);
                    echo json_encode($listado);
                }else {
                    echo "Error";
                }

                break;
            case 'POST'://inserta
                if (isset($_POST["FacturacionEmail"])) {
                    $Facturacion = json_decode($_POST["Facturacion"]);
                    $Helper = new FacturacionBLL();
                    echo json_encode($Helper->CreateFacturacion($Facturacion));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Facturacion"]) && isset($_PUT["ID"])) {
                    $Facturacion = json_decode($_PUT["Facturacion"]);
                    $id = $_PUT["ID"];
                    $Helper = new FacturacionBLL();
                    echo json_encode($Helper->UpdateFacturacion($Facturacion, $id));
                }

                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                $m = new ModuloDTO();
                $m = $this->Mapper($_DELETE);
                echo json_encode($m);
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

    public function utf8_encode_deep(&$input) {
        if (is_string($input)) {
            $input = utf8_encode($input);
        } else if (is_array($input)) {
            foreach ($input as &$value) {
                $this->utf8_encode_deep($value);
            }

            unset($value);
        } else if (is_object($input)) {
            $vars = array_keys(get_object_vars($input));

            foreach ($vars as $var) {
                $this->utf8_encode_deep($input->$var);
            }
        }
    }

}

//end class

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
require_once dirname(__FILE__) . '\..\Security.php';
include_once dirname(__FILE__) . '/../BLL/facturacion/RadicacionBLL.php';
require_once dirname(__FILE__) . '/../BLL/configuracion/ExcelBLL.php';
require_once dirname(__FILE__) . '/../BLL/facturacion/CompartirBLL.php';

class FacturacionExcelAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                if (isset($_GET["TokenValid"])) {
                    $s = new Security();
                    $sec = $s->validateToken($_GET["TokenValid"], "Frank_123458");
                    if (is_array($sec)) {
                        if (isset($_GET["MesActual"]) && isset($_GET["Mes"]) && isset($_GET["Anno"])) {
                            $Helper = new MesActualBLL();
                            $listado = $Helper->GetMesActual($_GET["Mes"], $_GET["Anno"]);
                            $h = new ExcelBLL();
                            $h->BuildExcel_MesActual($listado);
                        } else if (isset($_GET["FacturadoHoy"])) {
                            $Helper = new FacturadoHoyBLL();
                            $h = new ExcelBLL();
                            $h->BuildExcel_FacturadoHoy($Helper->GetFacturadoHoy());
                        } else if (isset($_GET["FacturadoEPSMES"]) && isset($_GET["Mes"]) && isset($_GET["Anno"])) {
                            $Helper = new FacturadoEPSMesBLL();
                            $listado = $Helper->GetFacturadoEPSMes($_GET["Mes"], $_GET["Anno"]);
                            $this->utf8_encode_deep($listado);
                            $h = new ExcelBLL();
                            $h->BuildExcel_FacturadoEPS($listado);
                        } else if (isset($_GET["Acostados"])) {
                            $Helper = new AcostadosBLL();
                            $listado = $Helper->GetAcostados();
                            $this->utf8_encode_deep($listado);
                            $h = new ExcelBLL();
                            $h->BuildExcel_Acostados($listado);
                        } else if (isset($_GET["CensoPorSector"])) {
                            $Helper = new CensosBLL();
                            $listado = $Helper->GetCensoPorSector();
                            $this->utf8_encode_deep($listado);
                            $h = new ExcelBLL();
                            $h->BuildExcel_CensoPorSector($listado);
                        } else if (isset($_GET["CensoPorEPS"])) {
                            $Helper = new CensosBLL();
                            $listado = $Helper->GetCensoPorEPS();
                            $this->utf8_encode_deep($listado);
                            $h = new ExcelBLL();
                            $h->BuildExcel_CensoPorEPS($listado);
                        } else if (isset($_GET["CensoPorPeriodo"]) && isset($_GET["Anno"]) && isset($_GET["Mes"])) {
                            $Helper = new CensosBLL();
                            $listado = $Helper->GetCensoPorPeriodo($_GET["Anno"], $_GET["Mes"]);
                            $this->utf8_encode_deep($listado);
                            $h = new ExcelBLL();
                            $h->BuildExcel_CensoPorPeriodo($listado);
                        } else if (isset($_GET["Radicacion"]) && isset($_GET["Anno"]) && isset($_GET["Mes"])) {
                            $Helper = new RadicacionBLL();
                            $listado = $Helper->GetRadicacion($_GET["Mes"], $_GET["Anno"]);
                            $this->utf8_encode_deep($listado);
                            $h = new ExcelBLL();
                            $h->BuildExcel_Radicacion($listado);
                        } else if (isset($_GET["FacturasEmitidas"]) && isset($_GET["TIPO"])) {
                            $Helper = new FacturasEmitidasBLL();
                            if ($_GET["TIPO"] == 'Mes') {
                                $listado = $Helper->GetFacturasEmitidasMes();
                                $this->utf8_encode_deep($listado);
                                $h = new ExcelBLL();
                                $h->BuildExcel_FacturasEmitidasMes($listado);
                            } else if ($_GET["TIPO"] == 'Hoy') {
                                $listado = $Helper->GetFacturasEmitidasHoy();
                                $this->utf8_encode_deep($listado);
                                $h = new ExcelBLL();
                                $h->BuildExcel_FacturasEmitidasHoy($listado);
                            }
                        }else {
                            echo "Error";
                        }
                    } else {
                        echo 'Token Vencido';
                        header("Location: http://190.131.221.26:8080/Polivalente/"); /* Redirect browser */
                        exit();
                    }
                } else if (isset($_GET["De"]) && isset($_GET["Para"]) && isset($_GET["Url"])) {
                    $Helper = new CompartirBLL();
                    $url = $_GET["Url"];
                    $url = str_replace("' '", "&", $url);
                    #echo $url;
                    $Helper->Compartir($_GET["De"], $_GET["Para"], $url);
                } else if (isset($_GET["NotificarPresidencia"])) {
                    echo "aqui es";
                } else {
                    echo "error";
                }

                break;
            case 'POST'://inserta
                if (isset($_POST["FacturacionExcel"])) {
                    $FacturacionExcel = json_decode($_POST["FacturacionExcel"]);
                    $Helper = new FacturacionExcelBLL();
                    echo json_encode($Helper->CreateFacturacionExcel($FacturacionExcel));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["FacturacionExcel"]) && isset($_PUT["ID"])) {
                    $FacturacionExcel = json_decode($_PUT["FacturacionExcel"]);
                    $id = $_PUT["ID"];
                    $Helper = new FacturacionExcelBLL();
                    echo json_encode($Helper->UpdateFacturacionExcel($FacturacionExcel, $id));
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

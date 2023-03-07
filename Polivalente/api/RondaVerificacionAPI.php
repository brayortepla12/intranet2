<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/cm/RondaVerificacionBLL.php';
include_once dirname(__FILE__) . '/../BLL/cm/DetalleRondaVerificacionBLL.php';
class RondaVerificacionAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new RondaVerificacionBLL();
                if (isset($_GET["UsuarioId"])) {
                    echo $Helper->GetRondaVerificaciones($_GET["UsuarioId"]);
                } else if (isset($_GET["RondaVerificacionId"]) && isset($_GET["TipoMedicamentoId"]) && isset($_GET["Sector"]) && isset($_GET["Fecha"]) && isset($_GET["TipoRonda"])) {
                    $Rondas = $Helper->GetRondaVerificacionById_TipoMedicamento($_GET["RondaVerificacionId"], $_GET["TipoMedicamentoId"], $_GET["Sector"], $_GET["Fecha"], $_GET["TipoRonda"]);
                    $this->utf8_encode_deep($Rondas);
                    echo json_encode($Rondas);
                } else if (isset($_GET["Lite"])) {
                    echo json_encode($Helper->GetAllRondas_Lite());
                } else if (isset($_GET["RondaVerificacionId_preview"]) && isset($_GET["MedicamentoId_preview"]) && isset($_GET["Fecha_preview"])) {
                    echo json_encode($Helper->RondaVerificacion_preview($_GET["RondaVerificacionId_preview"], $_GET["MedicamentoId_preview"], $_GET["Fecha_preview"]));
                } else if (isset($_GET["RondaVerificacionId_c"]) && isset($_GET["MedicamentoId_c"]) && isset($_GET["Fecha_c"])) {
                    echo json_encode($Helper->GetConsecutivos($_GET["RondaVerificacionId_c"], $_GET["MedicamentoId_c"], $_GET["Fecha_c"]));
                } else if (isset($_GET["RondaVerificacionId_preview2"]) && isset($_GET["MedicamentoId_preview2"])) {
                    echo json_encode($Helper->RondaVerificacion_preview_productoInicial($_GET["RondaVerificacionId_preview2"], $_GET["MedicamentoId_preview2"]));
                } else if (isset($_GET["Mes_estadisticas"]) && isset($_GET["Year_estadisticas"]) && isset($_GET["Estado_estadisticas"])) {
                    echo json_encode($Helper->GetEstadisticas($_GET["Mes_estadisticas"], $_GET["Year_estadisticas"], $_GET["Estado_estadisticas"]));
                } else if (isset($_GET["Insertar_Precio"])) {
                    echo json_encode($Helper->UpdatePrecioMedicamento());
                } else if (isset($_GET["Mes_estadisticas_ex"]) && isset($_GET["Year_estadisticas_ex"]) && isset($_GET["Estado_estadisticas_ex"])) {
                    $Helper->GetEstadisticasExcel($_GET["Mes_estadisticas_ex"], $_GET["Year_estadisticas_ex"], $_GET["Estado_estadisticas_ex"]);
                } else if (isset($_GET["IdAfiliado"]) && isset($_GET["MedicamentoId"])) {
                    echo json_encode($Helper->GetHistoricoByPacienteAndMedicamentoId($_GET["IdAfiliado"], $_GET["MedicamentoId"]));
                }  else {
                    echo "Error te equivocaste :)";
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["RondaVerificacion"])) {
                    $Helper = new RondaVerificacionBLL();
                    $Obj = json_decode($_POST["RondaVerificacion"]);
                    echo json_encode($Helper->CreateRondaVerificacion($Obj));
                }else if (isset($_POST["ConsecutivoRonda"])) {
                    $Helper = new RondaVerificacionBLL();
                    $Obj = json_decode($_POST["ConsecutivoRonda"]);
//                    echo json_encode($Obj);
                    echo json_encode($Helper->CreateConsecutivoRondaVerificacion($Obj[0]));
                } else {
                    echo "invalido.";
                }

//                echo "invalido.";
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new RondaVerificacionBLL();
                if (isset($_PUT["RondaVerificacion"])) {
                    $Obj = json_decode($_PUT["RondaVerificacion"]);
                    echo json_encode($Helper->UpdateRondaVerificacion($Obj));
                }else if (isset($_PUT["DireccionTecnica"])) {
                    $Obj = json_decode($_PUT["DireccionTecnica"]);
                    echo json_encode($Helper->UpdateRondaVerificacion_DireccionTecnica($Obj));
                }else if (isset($_PUT["ACalidad"])) {
                    $Obj = json_decode($_PUT["ACalidad"]);
                    echo json_encode($Helper->UpdateRondaVerificacion_ACalidad($Obj));
                }else if (isset($_PUT["QFarmaceutico"])) {
                    $Obj = json_decode($_PUT["QFarmaceutico"]);
                    echo json_encode($Helper->UpdateRondaVerificacion_QFarmaceutico($Obj));
                }else if (isset($_PUT["AFarmacia"])) {
                    $Obj = json_decode($_PUT["AFarmacia"]);
                    echo json_encode($Helper->UpdateRondaVerificacion_AFarmacia($Obj));
                }else if(isset($_PUT["VerificarDetalle"])){
                    $Helper = new DetalleRondaVerificacionBLL();
                    $Obj = json_decode($_PUT["VerificarDetalle"]);
                    echo json_encode($Helper->VerificarDetalleRondaVerificacion($Obj));
                }else if(isset($_PUT["SelectUsuario"])){
                    $Helper = new RondaVerificacionBLL();
                    $Obj = json_decode($_PUT["SelectUsuario"]);
                    echo json_encode($Helper->SelectUsuariosRondaVerificacion($Obj[0]));
                }  
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if(isset($_DELETE["EliminarDetalle"])){
                    $Helper = new DetalleRondaVerificacionBLL();
                    $Obj = json_decode($_DELETE["EliminarDetalle"]);
                    echo json_encode($Helper->EliminarDetalleRondaVerificacion($Obj));
                }else if(isset($_DELETE["Eliminar_DMR"])){
                    $Helper = new DetalleRondaVerificacionBLL();
                    $Obj = json_decode($_DELETE["Eliminar_DMR"]);
                    echo json_encode($Helper->EliminarDMR($Obj));
                }  

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

<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/ambulancia/ReferenciaBLL.php';
class ReferenciaAPI {
    
    public function API(){
        header('Content-Type: application/JSON');                
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
        case 'GET'://consulta
            $Helper = new ReferenciaBLL();
            if (isset ( $_GET["Auxiliar"] )) {
                echo json_encode($Helper->GetHistorico($_GET["Auxiliar"]));
            }else if (isset ( $_GET["Conductor"] )){
                echo json_encode($Helper->GetHistoricoConductor($_GET["Conductor"]));
            }else if (isset ( $_GET["nombre"] ) && isset ( $_GET["Mes"] ) && isset ( $_GET["Anno"] ) &&  isset ( $_GET["tipousuario"] )){
                echo json_encode($Helper->GetResumenByMonth($_GET["Mes"],$_GET["Anno"],$_GET["nombre"],$_GET["tipousuario"]));
            }else if (isset ( $_GET["nombre2"] ) && isset ( $_GET["Mes2"] ) && isset ( $_GET["Anno2"] )){
                echo json_encode($Helper->GetResumenByMonthAdministrativo($_GET["Mes2"],$_GET["Anno2"]));
            }else if (isset ( $_GET["Administrativo"] )){
                echo json_encode($Helper->GetHistoricoAdministrativo());
            }else if (isset ( $_GET["Administrativo2"] )&& isset ( $_GET["Mes3"] ) && isset ( $_GET["Anno3"] )){
                echo json_encode($Helper->GetCvsVByMonthAdministrativo($_GET["Mes3"],$_GET["Anno3"])); // Conductor por mes
            }else if (isset ( $_GET["Administrativo4"] ) && isset ( $_GET["Mes4"] ) && isset ( $_GET["Anno4"] )){
                echo json_encode($Helper->GetCvsVByMonthAdministrativoAux($_GET["Mes4"],$_GET["Anno4"])); // Auxiliar por mes
            }else if (isset ( $_GET["Administrativo6"] ) && isset ( $_GET["Day6"] ) && isset ( $_GET["Mes6"] ) && isset ( $_GET["Anno6"] )){
                echo json_encode($Helper->GetCvsVByDayAdministrativo($_GET["Day6"], $_GET["Mes6"],$_GET["Anno6"])); // conductor por dia
            }else if (isset ( $_GET["Administrativo5"] ) && isset ( $_GET["Day5"] ) && isset ( $_GET["Mes5"] ) && isset ( $_GET["Anno5"] )){
                echo json_encode($Helper->GetCvsVByDayAdministrativoAux($_GET["Day5"], $_GET["Mes5"],$_GET["Anno5"])); // Auxiliar por dia
            }else if (isset ( $_GET["Administrativo2d"] ) && isset ( $_GET["Day2d"] ) && isset ( $_GET["Mes2d"] ) && isset ( $_GET["Anno2d"] )){
                echo json_encode($Helper->GetResumenByDiaAdministrativo($_GET["Day2d"], $_GET["Mes2d"],$_GET["Anno2d"])); // Resumen por dia
            }else if (isset ( $_GET["AdministrativoF"] ) && isset ( $_GET["DayF"] ) && isset ( $_GET["MesF"] ) && isset ( $_GET["AnnoF"] )){
                echo json_encode($Helper->GetTotalFactura($_GET["DayF"], $_GET["MesF"],$_GET["AnnoF"])); // Resumen por dia
            }else if (isset ( $_GET["Anno_total"] )){
                echo json_encode($Helper->GetTotalFacturaInYear($_GET["Anno_total"])); // Resumen por dia
            }else if (isset ( $_GET["From"] ) && isset ( $_GET["To"] )){
                $Helper->GetReferenciaBetween($_GET["From"], $_GET["To"]);
//                echo json_encode($Helper->GetReferenciaBetween($_GET["From"], $_GET["To"])); // Resumen entre dos fechas
            }else if (isset ( $_GET["Year"] ) && isset ( $_GET["Mes"] )){
                echo json_encode($Helper->GetReferenciaByRango($_GET["Year"], $_GET["Mes"]));
//                echo json_encode($Helper->GetReferenciaBetween($_GET["From"], $_GET["To"])); // Resumen entre dos fechas
            }else if (isset ( $_GET["From2"] ) && isset ( $_GET["To2"] ) && isset ( $_GET["HoraInicial"] ) && isset ( $_GET["HoraFinal"] )){
                $Helper->GetReferenciaBetweenHours($_GET["From2"], $_GET["To2"], $_GET["HoraInicial"], $_GET["HoraFinal"]);
//                echo json_encode($Helper->GetReferenciaBetween($_GET["From"], $_GET["To"])); // Resumen entre dos fechas
            }else{
                echo "Error";
            }
            break;     
        case 'POST'://inserta
            if (isset ( $_POST["ListadoItems"] )) {
                $ListadoItems = json_decode($_POST["ListadoItems"]);
                
//                echo $ListadoItems[0]->Fecha;
                $Helper = new ReferenciaBLL();
                echo json_encode($Helper->CreateReferencia($ListadoItems));
            }else{
                echo "error";
            }
            break;                
        case 'PUT'://actualiza
            parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_PUT);
            $m = new ModuloDTO();
            $m = $this->Mapper($_PUT);
            echo json_encode($m);
            break;      
        case 'DELETE'://elimina
            parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_DELETE);
            $m = new ModuloDTO();
            $m = $this->Mapper($_DELETE);
            echo json_encode($m);
            break;
        default://metodo NO soportado
            echo 'METODO NO SOPORTADO';
            break;
        }
    }
    
    
}//end class

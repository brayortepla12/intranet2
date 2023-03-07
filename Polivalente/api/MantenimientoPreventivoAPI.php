<?php
include_once dirname(__FILE__) . '/../BLL/formulario/MantenimientoPreventivoBLL.php';
class MantenimientoPreventivoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new MantenimientoPreventivoBLL();
                if(isset($_GET["Cuenta"]) && isset($_GET["UsuarioId"])){
                    echo json_encode($Helper->GetNEquiposByServicio($_GET["UsuarioId"]));
                }else if(isset($_GET["UsuarioId"])){
                    echo json_encode($Helper->GetAllMantenimientoPreventivos($_GET["UsuarioId"]));
                }
                break;
            case 'POST'://inserta
                $Helper = new MantenimientoPreventivoBLL();
                if (isset($_POST["Servicios"]) && isset($_POST["Year"])) {
                    $Servicios = json_decode($_POST["Servicios"]);
                    echo json_encode($Helper->GetAllMantenimientoPreventivosByServicio($Servicios[0],$_POST["Year"]));
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["MantenimientoPreventivoId"])) {
                   $Helper = new MantenimientoPreventivoBLL();
                   echo json_encode($Helper->CambiarEstadoMantenimientoPreventivo($_PUT["MantenimientoPreventivoId"]));
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

}

//end class

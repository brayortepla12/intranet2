<?php
include_once dirname(__FILE__) . '/../BLL/formulario/MantenimientoPreventivoSistemaBLL.php';
class MantenimientoPreventivoSistemaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new MantenimientoPreventivoSistemaBLL();
                if(isset($_GET["Cuenta"]) && isset($_GET["UsuarioId"])){
                    echo json_encode($Helper->GetNEquiposByServicio($_GET["UsuarioId"]));
                }else if(isset($_GET["UsuarioId"])){
                    echo json_encode($Helper->GetAllMantenimientoPreventivoSistemas($_GET["UsuarioId"]));
                }
                break;
            case 'POST'://inserta
                $Helper = new MantenimientoPreventivoSistemaBLL();
                if (isset($_POST["SedeId"]) && isset($_POST["ServicioId"]) && isset($_POST["Vigencia"]) && isset($_POST["Mes"])) {
                    echo json_encode($Helper->GetAllMantenimientoPreventivoSistemasBySede_2($_POST["SedeId"], $_POST["ServicioId"],$_POST["Vigencia"],$_POST["Mes"]));
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["MantenimientoPreventivoSistemaId"])) {
                   $Helper = new MantenimientoPreventivoSistemaBLL();
                   echo json_encode($Helper->CambiarEstadoMantenimientoPreventivoSistema($_PUT["MantenimientoPreventivoSistemaId"]));
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

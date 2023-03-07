<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../../BLL/thc/GrupoBLL.php';
class GrupoAPI {
    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new GrupoBLL();
                if(isset($_GET["Grupos"])){
                    echo json_encode($Helper->GetGrupos());
                }else if(isset($_GET["GrupoId"])){
                    echo json_encode($Helper->GetUsuariosByGrupoId($_GET["GrupoId"]));
                }else{
                    echo "error";
                }
                break;
            case 'POST'://inserta
                $Helper = new GrupoBLL();
                if (isset($_POST["Grupo"])) {
                    $Acta = json_decode($_POST["Grupo"]);
                    echo json_encode($Helper->CreateGrupo($Acta));
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["GrupoId"])) {
                   $Helper = new GrupoBLL();
                   echo json_encode($Helper->CambiarEstadoGrupo($_PUT["GrupoId"]));
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

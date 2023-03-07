<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/configuracion/GrupoBLL.php';

class GrupoAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consulta
                $Helper = new GrupoBLL();
                if (isset($_GET["UserId"])) {
                    echo $Helper->GetAllByUserId($_GET["UserId"]);
                }else if (isset($_GET["UsuarioId_enf"])) {
                    echo $Helper->IsInEnfermeria($_GET["UsuarioId_enf"]);
                }else{
                    echo $Helper->GetAll();
                }
                break;
            case 'POST'://inserta
                if (isset($_POST["Grupo"])) {
                    $Grupo = json_decode($_POST["Grupo"]);
                    $Helper = new GrupoBLL();
                    echo json_encode($Helper->CreateGrupo($Grupo));
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Grupo"]) && isset($_PUT["ID"])) {
                    $Grupo = json_decode($_PUT["Grupo"]);
                    $id = $_PUT["ID"];
                    $Helper = new GrupoBLL();
                    echo json_encode($Helper->UpdateGrupo($Grupo, $id));
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

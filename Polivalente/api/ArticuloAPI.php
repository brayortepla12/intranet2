<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/almacen/ArticuloBLL.php';

class ArticuloAPI
{

    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET': //consulta
                $Helper = new ArticuloBLL();
                if (isset($_GET["UserId"])) {
                    echo json_encode($Helper->GetAllByUserId($_GET["UserId"]));
                } else if (isset($_GET["Usuario_sede"])) {
                    echo json_encode($Helper->GetAllBySedeId($_GET["Usuario_sede"]));
                } else if (isset($_GET["ArticuloId"])) {
                    echo json_encode($Helper->GetAllById($_GET["ArticuloId"]));
                } else if (isset($_GET["ServicioId_p"]) && isset($_GET["UserId_p"]) && isset($_GET["Tipo_p"])) {
                    echo json_encode($Helper->GetAllByPlantilla($_GET["ServicioId_p"], $_GET["UserId_p"], $_GET["Tipo_p"]));
                } else if (isset($_GET["Tipo"])) {
                    echo json_encode($Helper->GetAllByTipo($_GET["Tipo"]));
                } else {
                    echo json_encode($Helper->GetAll());
                }
                break;
            case 'POST': //inserta
                if (isset($_POST["Articulo"])) {
                    $Articulo = json_decode($_POST["Articulo"]);
                    $Helper = new ArticuloBLL();
                    echo json_encode($Helper->CreateArticulo($Articulo));
                } else if (isset($_POST["UsuarioArticulo"])) {
                    $Articulo = json_decode($_POST["UsuarioArticulo"]);
                    $Helper = new ArticuloBLL();
                    echo $Helper->AsignarArticuloUsuario($Articulo);
                } else {
                    echo "invalido.";
                }
                break;
            case 'PUT': //actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["Articulo"])) {
                    $Articulo = json_decode($_PUT["Articulo"]);
                    $Helper = new ArticuloBLL();
                    echo json_encode($Helper->UpdateArticulo($Articulo));
                }
                break;
            case 'DELETE': //elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                if (isset($_DELETE["ArticuloId"])) {
                    $Helper = new ArticuloBLL();
                    echo json_encode($Helper->DeleteArticulo($_DELETE["ArticuloId"]));
                }
                break;
            default: //metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }
}

//end class

<?php

/**
 * @author Franklin ospino
 */
include_once __DIR__ . '/../../BLL/viatico/ViaticoBLL.php';
require_once __DIR__ . '/../../BLL/viatico/PreSolDto.php';
require_once __DIR__ . '/../../BLL/viatico/SolDto.php';

class ViaticoAPI
{

    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET': //consulta
                $Helper = new ViaticoBLL();
                if (isset($_GET["Dpt"])) {
                    echo json_encode($Helper->GetDepartamentos());
                } else if (isset($_GET["DptId"])) {
                    echo json_encode($Helper->GetMunicipioByDptId($_GET["DptId"]));
                } else if (isset($_GET["UsuarioSolicitaId"])) {
                    echo json_encode($Helper->GetSolicitudesByUsuarioSolicitaId($_GET["UsuarioSolicitaId"]));
                } else if (isset($_GET["Conceptos"])) {
                    echo json_encode($Helper->GetConceptos());
                } else if (isset($_GET["Mes_ps"]) && isset($_GET["Year_ps"])) {
                    echo json_encode($Helper->GetPreSolicitudes());
                } else if (isset($_GET["Mes_auth"]) && isset($_GET["Year_auth"])) {
                    echo json_encode($Helper->GetPreSolicitudesAuth());
                } else if (isset($_GET["PresolicitudId"])) {
                    echo json_encode($Helper->GetPresolicitudById($_GET["PresolicitudId"]));
                } else if (isset($_GET["Dato_per"])) {
                    echo json_encode($Helper->GetPersonaByNombreOrCedula($_GET["Dato_per"]));
                } else if (isset($_GET["SolicitudId_pdf"])) {
                    $Helper->GetSolPDF($_GET["SolicitudId_pdf"], True);
                } else {
                    echo "invalido";
                }
                break;
            case 'POST': //inserta
                if (isset($_POST["PreSol"])) {
                    $Helper = new ViaticoBLL();
                    $Obj = json_decode($_POST["PreSol"]);
                    $PreSol = $this->cast('PreSolDto', $Obj);
                    echo json_encode($Helper->CreatePreSolViatico($PreSol));
                } else if (isset($_POST["CompletarSol"])) {
                    $Helper = new ViaticoBLL();
                    $Obj = json_decode($_POST["CompletarSol"]);
                    $CSol = $this->cast('SolDto', $Obj);
                    echo json_encode($Helper->CreateSolicitudCompletaViatico($CSol));
                } else {
                    echo "error";
                }
                break;
            case 'PUT': //actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                if (isset($_PUT["VistoBueno"])) {
                    $Helper = new ViaticoBLL();
                    $Obj = json_decode($_PUT["VistoBueno"]);
                    echo json_encode($Helper->VistoBueno($Obj));
                }
                break;
            case 'DELETE': //elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                break;
            default: //metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

    private function cast(string $destination, object $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination, $value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }
}

//end class

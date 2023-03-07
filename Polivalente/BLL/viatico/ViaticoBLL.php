<?php
include_once __DIR__ . '/../../DAL/viatico/ViaticoDAL.php';
require_once __DIR__ . '/../Helpers/Mail/sendMail.php';
require_once __DIR__ . '/../configuracion/EmpresaBLL.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/PreSolDto.php';
require_once __DIR__ . '/SolDto.php';
require_once __DIR__ . '/../../Auth.php';
require_once __DIR__ . '/../Helpers/PDF/PDF.php';

use Spatie\Async\Pool;

class ViaticoBLL
{
  private $db;
  public function __construct()
  {
    $this->db = new ViaticoDAL();
  }

  public function GetDepartamentos(): array
  {
    $dpts = $this->db->GetDepartamentos();
    return ["data" => $dpts];
  }

  public function GetMunicipioByDptId(string $DptId): array
  {
    $dpts = $this->db->GetMunicipioByDptId($DptId);
    return ["data" => $dpts];
  }

  public function CreatePreSolViatico(PreSolDto $PreSol): array
  {
    $id = $this->db->Create("via_presolicitud", [
      (array) $PreSol
    ]);
    if (!empty($id) && is_array($id)) {
      # Notificar usuario de proceso AUTORIZACION
      $PreSol = (object) $PreSol;
      $pool = Pool::create();
      $pool->add(function () use ($PreSol) {
        $pdf = new PDF();
        $ArchivoPDF = $pdf->GetPreSolicitud($PreSol, false);
        $this->NotificarUsuarioAutorizacion($PreSol->ResPrimerNombre . ' ' . $PreSol->ResPrimerApellido, $ArchivoPDF);
      });
      $pool->add(function () use ($PreSol) {
        # Actualizar Tercero
        $this->ActualizarOCrearTercero($PreSol);
      });
      $pool->wait();
      return ["data" => "Se han guardado los datos satisfactoriamente"];
    }
    return ["error" => "Hubo un error al momento de guardar"];
  }

  /**
   * AUTORIZAR SOLICITUD
   *
   * @param object $VistoBueno
   * @return array
   */
  public function VistoBueno(object $VistoBueno): array
  {
    /**
     * @var PerViaDto[] $Persona
     */
    $Persona = $this->db->Search("SELECT p.PersonaId, p.PrimerNombre, p.SegundoNombre, p.PrimerApellido, p.SegundoApellido, c.Cargo, p.Firma
    FROM ct_persona as p 
    LEFT JOIN ct_cargo as c on p.CargoId = c.CargoId
    WHERE p.PersonaId = {$VistoBueno->PersonaId}");
    $Prefijo = $VistoBueno->Prefijo;
    $Columns = [
      "SolicitudId",
      $Prefijo . "PersonaId",
      $Prefijo . "PrimerNombre",
      $Prefijo . "SegundoNombre",
      $Prefijo . "PrimerApellido",
      $Prefijo . "SegundoApellido",
      $Prefijo . "Cargo",
      $Prefijo . "Firma",
      "Aprobacion" . $Prefijo,
      'Fecha' . $Prefijo
    ];
    $Data = [
      $VistoBueno->SolicitudId,
      $Persona[0]->PersonaId,
      $Persona[0]->PrimerNombre,
      $Persona[0]->SegundoNombre,
      $Persona[0]->PrimerApellido,
      $Persona[0]->SegundoApellido,
      $Persona[0]->Cargo,
      $this->ImgToDataUri($Persona[0]->Firma),
      True,
      $this->GetFHNow('fh')
    ];
    if ($VistoBueno->IsUltimo) {
      array_push($Columns, "ResFirma");
      array_push($Data, $VistoBueno->ResFirma);
      array_push($Columns, "Estado");
      array_push($Data, "Entregado");
    } else {
      array_push($Columns, "OrdenEncurso");
      array_push($Data, (int)$VistoBueno->OrdenEncurso + 1);
    }
    # Actualizar Solicitud
    $num = 0;
    if (!empty($Persona) && is_array($Persona)) {
      $num = $this->db->UpdateBulk("via_Solicitud", $Columns, [$Data]);
    }
    if ($VistoBueno->IsUltimo) {
      # Notificar Beneficiario
      $pool = Pool::create();
      $pool->add(function () use ($VistoBueno) {
        $pdfString = $this->GetSolPDF($VistoBueno->SolicitudId, False);
        $this->NotificarUsuarioProceso($VistoBueno, [$pdfString]);
      })->catch(function (Exception $e) {
        $this->CatchError($e);
      });
      $pool->wait();
    }
    return $num > 0 ? ["data" => "Se han actualizado los datos satisfactoriamente"] : ["error" => "Hubo un error al momento de guardar"];
  }

  public function CreateSolicitudCompletaViatico(SolDto $CSol): array
  {
    $hs = new Auth();
    $jwt = $this->getBearerToken();
    $result = $hs->DecodeJWT($jwt);
    $Consecutivo = $this->GetConsecutivo();
    $Conceptos = $CSol->Conceptos;
    if (is_object($result) && !empty($Consecutivo)) {
      $SolId = $this->CreateSol($CSol, $result, $Consecutivo);
      if (!empty($SolId) && is_array($SolId)) {
        $pool = Pool::create();
        $pool->add(function () use ($SolId, $CSol, $Conceptos, $result, $Consecutivo) {
          # Crear Conceptos
          $this->CreateConceptos($SolId[0], $Conceptos, $result->data->Nombres);
        })->catch(function (Exception $e) {
          $this->CatchError($e);
        });
        $pool->add(function () use ($SolId, $CSol, $Conceptos, $result, $Consecutivo) {
          # Actualizamos el consecutivo
          $Consecutivo[0]->Consecutivo++;
          $this->UpdateConsecutivo($Consecutivo[0]);
        })->catch(function (Exception $e) {
          $this->CatchError($e);
        });
        $pool->add(function () use ($SolId, $CSol, $Conceptos, $result, $Consecutivo) {
          # Actualizar el tercero
          $this->ActualizarOCrearTercero($CSol);
        })->catch(function (Exception $e) {
          $this->CatchError($e);
        });
        $pool->add(function () use ($SolId, $CSol, $Conceptos, $result, $Consecutivo) {
          # Generar PDF SOLicitud y Notificar
          $pdf = new PDF();
          $pdfString = $pdf->GetCompSolicitud($CSol, $Consecutivo[0]->Consecutivo - 1, $Conceptos, true, false);
          $CSol->SolicitudId = $SolId[0];
          $this->NotificarUsuarioProceso($CSol, [$pdfString]);
        })->catch(function (Exception $e) {
          $this->CatchError($e);
        });
        $pool->wait();
      }
      return ["data" => "Se han guardado los datos satisfactoriamente"];
    } else {
        header("HTTP/1.1 401 Unauthorized");
        exit;
    }
    
    return ["error" => "Hubo un error al momento de guardar los datos"];
  }
  public function CreateSol(SolDto $CSol, object $result, array $Consecutivo) : ?array
  {
    $CSol->CreatedBy = $result->data->Nombres;
    $CSol->CreatedAt = $this->GetFHNow('fh'); 
    $CSol->Consecutivo = $Consecutivo[0]->Consecutivo;
    unset($CSol->Conceptos); # retiramos la propiedad conceptos
    $Data = $CSol;
    return $this->db->Create("via_solicitud", [
      (array) $Data
    ]);
  }

  /**
   * Crear Conceptos multiples
   *
   * @param string $SolicitudId
   * @param ConceptoDto[] $Conceptos
   * @return void
   */
  public function CreateConceptos(string $SolicitudId, array $Conceptos, string $CreatedBy) : array
  {
    $ConceptosData = [];
    foreach ($Conceptos as $c) {
      if ($c->Valor !== 0) {
        $c->SolicitudId = $SolicitudId;
        $c->CreatedBy = $CreatedBy;
        $c->CreatedAt = $this->GetFHNow('fh');
        array_push($ConceptosData, (array) $c);
      }
    }
    return $this->db->Create("via_detallesolicitud", 
      $ConceptosData
    );
  }

  public function NotificarUsuarioProceso(object $Solicitud, array $Adjuntos): void
  {
    $queryUAuth = "SELECT u.Email FROM via_flujotrabajo as ft 
    INNER JOIN via_solicitud as s on s.ProcesoId = ft.ProcesoId
    INNER JOIN usuario as u on u.UsuarioId = ft.UsuarioVerificaId
    WHERE s.SolicitudId = {$Solicitud->SolicitudId} AND ft.Orden = s.OrdenEncurso;";
    $u = $this->db->Search($queryUAuth);
    if (!empty($u)) {
      $Contenido = "<p>Se ha generado una solicitud de viatico</p>
        <br>
        <br>
        <small>Este enlace vencera en <strong>10 dias</strong> luego de la recepción del mismo.</small>";
      $Asunto = "Solicitud de viatico para revisar";
      $this->EnviarEmail([$Solicitud->ResCorreo, $u[0]->Email], $Asunto, $Contenido, $Adjuntos); #TODO: $u[0]->Email
    }
  }

  public function NotificarUsuarioAutorizacion(string $Nombres, string $pdfString): void
  {
    $Contenido = "<p>Se ha generado una solicitud de viatico</p>
      <br>
      <br>
      <small>Este enlace vencera en <strong>10 dias</strong> luego de la recepción del mismo.</small>";
    $Asunto = "Solicitud de viatico para $Nombres";
    $this->EnviarEmail(["ospi89@hotmail.com", "Liderthumanocsi@clinicaintegral.com.co"], $Asunto, $Contenido, [$pdfString]); #, "Liderthumanocsi@clinicaintegral.com.co"
  }

  public function GetConceptos() : array
  {
    $query = "SELECT ConceptoId, Concepto, 1 Dias, 0 Valor, Legalizable FROM via_concepto;";
    return ["data" => $this->db->Search($query)];
  }

  public function GetConsecutivo() : array
  {
    $query = "SELECT * FROM via_consecutivo;";
    return $this->db->Search($query);
  }

  public function UpdateConsecutivo(object $Consecutivo)
  {
    $Columns = ["ConsecutivoId", "Consecutivo", "ModifiedAt"];
    $Data = [$Consecutivo->ConsecutivoId, $Consecutivo->Consecutivo, $Consecutivo->ModifiedAt];
    $this->db->UpdateBulk("via_consecutivo", $Columns, [$Data]);
  }

  public function GetPersonaByNombreOrCedula(string $NombreOrCedula) : array
  {
    $query = "SELECT p.*
    from via_terceros as p
    WHERE p.Nombres LIKE '%$NombreOrCedula%' 
    OR p.Cedula LIKE '%$NombreOrCedula%'";
    return $this->db->Search($query);
  }

  public function GetSolicitudesByUsuarioSolicitaId(string $UsuarioSolicitaId) : array
  {
    $query = "SELECT ps.PreSolicitudId, ps.Sede, ps.Fecha, IF(s.SolicitudId IS NOT NULL, s.Estado, ps.Estado) as Estado, CONCAT(ps.ResPrimerNombre, ' ', ps.ResPrimerApellido) as ANombreDe, 
    CONCAT(ps.DepartamentoOrigen, ' - ', ps.MunicipioOrigen) AS Origen, 
    CONCAT(ps.DepartamentoDestino, ' - ', ps.MunicipioDestino) AS Destino, 
    ps.DescripcionSolicitud, s.SolicitudId
    FROM via_presolicitud as ps 
    LEFT JOIN via_solicitud as s on s.PreSolicitudId = ps.PreSolicitudId
    WHERE ps.UsuarioSolicitaId = $UsuarioSolicitaId ORDER BY ps.PreSolicitudId DESC;";
    return ["data" => $this->db->Search($query)];
  }

  public function GetPreSolicitudes() : array
  {
    $query = "SELECT 
          ps.PreSolicitudId,
          ps.Sede,
          ps.Fecha,
          'Pre-Solicitud' AS Estado,
          CONCAT(ps.ResPrimerNombre,
                  ' ',
                  ps.ResPrimerApellido) AS ANombreDe,
          CONCAT(ps.DepartamentoOrigen,
                  ' - ',
                  ps.MunicipioOrigen) AS Origen,
          CONCAT(ps.DepartamentoDestino,
                  ' - ',
                  ps.MunicipioDestino) AS Destino,
          ps.DescripcionSolicitud,
          s.SolicitudId,
          '' AS Prefijo,
          '' AS HasFirma,
          '' AS OrdenEncurso
      FROM
          via_preSolicitud AS ps
              LEFT JOIN
          via_solicitud AS s ON s.PreSolicitudId = ps.PreSolicitudId
      WHERE
          s.SolicitudId IS NULL 
      UNION SELECT 
          s.PreSolicitudId,
          s.Sede,
          s.Fecha,
          s.Estado,
          CONCAT(s.ResPrimerNombre,
                  ' ',
                  s.ResPrimerApellido) AS ANombreDe,
          CONCAT(s.DepartamentoOrigen,
                  ' - ',
                  s.MunicipioOrigen) AS Origen,
          CONCAT(s.DepartamentoDestino,
                  ' - ',
                  s.MunicipioDestino) AS Destino,
          s.DescripcionSolicitud,
          s.SolicitudId,
          ft.Prefijo,
          p.HasFirma,
          s.OrdenEncurso
      FROM
          via_solicitud AS s
              INNER JOIN
          via_flujotrabajo AS ft ON s.OrdenEncurso = ft.Orden
              AND s.ProcesoId = ft.ProcesoId
              INNER JOIN
          via_proceso AS p ON s.ProcesoId = p.ProcesoId
      ORDER BY PreSolicitudId DESC;";
    return ["data" => $this->db->Search($query)];
  }

  public function GetPreSolicitudesAuth() : array
  {
    $hs = new Auth();
    $jwt = $this->getBearerToken();
    $result = $hs->DecodeJWT($jwt);
    if (is_object($result)) {
      $query = "SELECT 
            s.PreSolicitudId,
            s.Sede,
            s.Fecha,
            s.Estado,
            CONCAT(s.ResPrimerNombre,
                    ' ',
                    s.ResPrimerApellido) AS ANombreDe,
            CONCAT(s.DepartamentoOrigen,
                    ' - ',
                    s.MunicipioOrigen) AS Origen,
            CONCAT(s.DepartamentoDestino,
                    ' - ',
                    s.MunicipioDestino) AS Destino,
            s.DescripcionSolicitud,
            s.SolicitudId,
            ft.IsUltimo,
            ft.Orden = s.OrdenEncurso AS IsTuTurno,
            ft.Prefijo,
            p.HasFirma,
            s.OrdenEncurso,
            ft.MensajeEstado,
            s.ResPersonaId,
            s.ResCorreo
        FROM
            via_solicitud AS s
                INNER JOIN
            via_flujotrabajo AS ft ON ft.ProcesoId = s.ProcesoId
                INNER JOIN
            via_proceso AS p ON p.ProcesoId = s.ProcesoId
        WHERE
            ft.UsuarioVerificaId = {$result->data->UserId}
        ORDER BY PreSolicitudId DESC;";
    return ["data" => $this->db->Search($query)];
    } else {
        header("HTTP/1.1 401 Unauthorized");
        exit;
    }
    
  }

  public function GetPresolicitudById(string $PresolicitudId) : array
  {
    $query = "SELECT ps.PreSolicitudId, ps.Sede, ps.Fecha, IF(s.SolicitudId IS NOT NULL, s.Estado, ps.Estado) as Estado, 
    ps.ResPrimerNombre, ps.ResSegundoNombre, ps.ResPrimerApellido, ps.ResSegundoApellido,
    ps.ResCargo, ps.ResCedula, ps.ResCelular, ps.NombreSolicita, ps.CargoSolicita, ps.UsuarioSolicitaId, ps.SedeId,
    ps.DepartamentoOrigen, ps.MunicipioOrigen, ps.DepartamentoOrigenId, ps.MunicipioOrigenId,
    ps.DepartamentoDestino, ps.MunicipioDestino, ps.DepartamentoDestinoId, ps.MunicipioDestinoId, 
    ps.DescripcionSolicitud, s.SolicitudId, ps.ResPersonaId, ps.ResIsExterno, ps.ResCorreo 
    FROM via_presolicitud as ps 
    LEFT JOIN via_solicitud as s on s.PreSolicitudId = ps.PreSolicitudId
    WHERE ps.PreSolicitudId = $PresolicitudId ORDER BY ps.PreSolicitudId DESC";
    return ["data" => $this->db->Search($query)];
  }

  public function EnviarEmail(array $Emails, string $Asunto, string $Contenido, array $Adjuntos): void
  {
    $Eh = new EmpresaBLL();
    $Empresa = $Eh->GetEmpresa();
    $Email = new sendMail();
    $mensajes = $Email->EnviarEmail_NotificacionMULTIPLE((object) $Empresa, $Asunto, $Contenido,  $Emails, $Adjuntos);
    $this->LogFile($mensajes, "log-emails.txt"); # Guardamos un log
  }

  public function ActualizarOCrearTercero(object $Sol) : void
  {
    $Tercero = $this->db->Search("SELECT TerceroId FROM via_terceros where Cedula = '$Sol->ResCedula';");
    if (!empty($Tercero) && is_array($Tercero)) {
      $this->db->UpdateBulk(
        "via_terceros", 
        ["TerceroId", "Cargo", "Cedula", "Celular", "Email"], 
        [[$Tercero[0]->TerceroId, $Sol->ResCargo, $Sol->ResCedula, $Sol->ResCelular, $Sol->ResCorreo]]
      );
    } else {
      $this->db->Create("via_terceros", [
        [
          "PersonaId" => $Sol->ResPersonaId,
          "PrimerNombre" => $Sol->ResPrimerNombre,
          "SegundoNombre" => $Sol->ResSegundoNombre,
          "PrimerApellido" => $Sol->ResPrimerApellido,
          "SegundoApellido" => $Sol->ResSegundoApellido,
          "Cargo" => $Sol->ResCargo,
          "Cedula" => $Sol->ResCedula,
          "Celular" => $Sol->ResCelular,
          "Email" => $Sol->ResCorreo,
          "IsExterno" => $Sol->ResIsExterno
        ]
      ]);
    }
  }

  /**
   * Obtenemos el pdf completo de la solicitud para mostrarlo o solo obtenemos el string para mandarlo por correo o guardarlo en la DB
   *
   * @param string $SolicitudId
   * @param boolean $Show
   * @return string|null
   */
  public function GetSolPDF(string $SolicitudId, bool $Show) : ?string
  {
    $Solicitud = $this->db->Search("SELECT * FROM via_solicitud where SolicitudId = $SolicitudId;");
    $Conceptos = $this->db->Search("SELECT * FROM via_detallesolicitud where SolicitudId = $SolicitudId;");
    if (!empty($Solicitud) && is_array($Solicitud)) {
      $pdf = new PDF();
      if ($Show) {
        $pdf->GetCompSolicitud($Solicitud[0], $Solicitud[0]->Consecutivo, $Conceptos, false, $Show); # $Solicitud[0]->Estado === "Borrador"
        exit;
      } else {
        return $pdf->GetCompSolicitud($Solicitud[0], $Solicitud[0]->Consecutivo, $Conceptos, false, $Show);
      }
    } else {
      echo json_encode(["error" => "Hubo un error"]);
    }
  }

  /**
     * Obtener fechas
     *
     * @param string $F Fecha|fh|h
     * @return string
     */
  private function GetFHNow(string $F = "Fecha"): string
  {
      $tz_object = new DateTimeZone('America/Bogota');
      $datetime = new DateTime();
      $datetime->setTimezone($tz_object);
      $format = '';
      switch ($F) {
          case 'Fecha':
              $format = 'Y-m-d';
              break;
          case 'fh':
              $format = 'Y-m-d H:i:s';
              break;

          default:
              $format = 'H:i:s';
              break;
      }
      return $datetime->format($format);
  }

  /**
   * get access token from header
   * */
  private function getBearerToken()
  {
      $headers = $this->getAuthorizationHeader();
      // HEADER: Get the access token from the header
      if (!empty($headers)) {
          if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
              return $matches[1];
          }
      }
      return null;
  }
  /**
   * Get header Authorization
   * */
  private function getAuthorizationHeader()
  {
      $headers = null;
      if (isset($_SERVER['Authorization'])) {
          $headers = trim($_SERVER["Authorization"]);
      } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
          $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
      } elseif (function_exists('apache_request_headers')) {
          $requestHeaders = apache_request_headers();
          // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
          $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
          //print_r($requestHeaders);
          if (isset($requestHeaders['Authorization'])) {
              $headers = trim($requestHeaders['Authorization']);
          }
      }
      return $headers;
  }

  function CatchError(Exception $e) {
    // Handle exception
    $this->LogFile($this->GetFHNow('fh'). ":\t\t" . $e->getMessage() . "\n\n","log-pools.txt");
    echo json_decode(["error" => "Hubo un error al momento de guardar los datos"]);
    exit;
  }

  public function LogFile(string $Msg, string $fileName)
  {
    $fp = fopen(__DIR__ . "/$fileName", 'a+');
    fwrite($fp, $Msg);
    fclose($fp);
  }
  private function ImgToDataUri($image) : string
  {
      if (!trim($image)) {
          return "http://190.131.221.26:8080/no-image";
      }
      // Descargamos la imagen
      $ch = curl_init('http://190.131.221.26:8080/' . $image);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
      $image = curl_exec($ch); 
      curl_close($ch);
      // obtenemos el tipo
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      // reads your image's data and convert it to base64
      $data = base64_encode($image);
      // Create the image's SRC:  "data:{mime};base64,{data};"
      return 'data: ' . finfo_buffer($finfo, $image) . ';base64,' . $data;
  }
}

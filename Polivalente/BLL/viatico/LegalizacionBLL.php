<?php
include_once __DIR__ . '/../../DAL/viatico/LegalizacionDAL.php';
require_once __DIR__ . '/../Helpers/Mail/sendMail.php';
require_once __DIR__ . '/../configuracion/EmpresaBLL.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/PreSolDto.php';
require_once __DIR__ . '/SolDto.php';
require_once __DIR__ . '/../../Auth.php';
require_once __DIR__ . '/../Helpers/PDF/PDF.php';

use Spatie\Async\Pool;

class LegalizacionBLL {
  private $db;
  public function __construct()
  {
    $this->db = new LegalizacionDAL();
  }

  private function getQueryStringLegalizacion(string $UsuarioId, string $Tipo) : string
  {
    $QueryString = "";
    switch ($Tipo) {
      case 'Normal':
        $QueryString = "SELECT s.SolicitudId, CONCAT(s.ResPrimerNombre, ' ', s.ResPrimerApellido) AS ANombreDe, 
          s.ResCedula, s.ResPersonaId, s.Fecha, 
          Via_GetTotalPendienteLegalizar(s.SolicitudId) AS TLegalizar, 
          Via_GetTotalLegalizado(l.LegalizacionId) AS TLegalizado,
          l.LegalizacionId,
          IFNULL(ft.MensajeEstado, 'Pendiente Por Legalizar') AS Estado,
          IFNULL(l.ProcesoId, IF('$Tipo' = 'Normal', 2, 3)) AS ProcesoId, 
          l.OrdenEncurso
          FROM via_solicitud as s
            LEFT JOIN via_Legalizacion as l on s.SolicitudId = l.SolicitudId
            LEFT JOIN via_flujotrabajo as ft on ft.ProcesoId = l.ProcesoId AND l.OrdenEncurso = ft.Orden
          WHERE 
            EXISTS(SELECT ds.DetalleSolicitudId FROM via_detallesolicitud AS ds WHERE ds.Legalizable = 1) AND s.Estado = 'Entregado'
             ORDER BY s.SolicitudId DESC;";
        break;
      case 'Aguachica':
        $QueryString = "SELECT 
              l.SolicitudId,
              l.LegalizacionId,
              CONCAT(l.ResPrimerNombre,
                      ' ',
                      l.ResPrimerApellido) AS ANombreDe,
              l.ResCedula,
              l.ResPersonaId,
              l.Fecha,
              VIA_GETTOTALLEGALIZADO(l.LegalizacionId) AS TLegalizado,
              l.ProcesoId,
              l.OrdenEncurso,
              ft.MensajeEstado AS Estado
          FROM
              via_legalizacion AS l
          INNER JOIN via_flujotrabajo as ft on ft.ProcesoId = l.ProcesoId AND ft.Orden = l.OrdenEncurso
          WHERE
              l.ProcesoId = 3 ORDER BY l.LegalizacionId DESC";
        break;
      default:
        $QueryString = "";
        break;
    }
    return $QueryString;
  }

  /**
   * Esta funcion controla la consulta de las solicitudes pendientes por legalizar para REEBOLSO ó para Aguachica
   *
   * @param string $UsuarioId
   * @param string $Tipo
   * @return array
   */
  public function GetSolicitudesPendientesLegalizar(string $UsuarioId, string $Tipo) : array
  {
    $Data = $this->db->Search($this->getQueryStringLegalizacion($UsuarioId, $Tipo)); # AND s.Estado <> 'Borrador'
    return ['data' => !empty($Data) && is_array($Data) ? $Data : []];
  }

  public function GetAnexosByLegalizacionId(string $LegalizacionId) : array
  {
    $Data = $this->db->Search("SELECT dl.DetalleLegalizacionId, dl.Fecha, dl.Factura, dl.Responsable, dl.Concepto, dl.Valor 
    FROM via_detallelegalizacion AS dl
    WHERE 
    dl.LegalizacionId = $LegalizacionId AND dl.Anexo IS NOT NULL");
    return ['data' => !empty($Data) && is_array($Data) ? $Data : []];
  }

  public function GetAnexoById(string $DetalleLegalizacionId) : array
  {
    $Data = $this->db->Search("SELECT dl.Anexo
    FROM via_detallelegalizacion AS dl
    WHERE 
    dl.DetalleLegalizacionId = $DetalleLegalizacionId AND dl.Anexo IS NOT NULL");
    return ['data' => !empty($Data) && is_array($Data) ? $Data : []];
  }

  public function GetLegalizaciones(string $UsuarioId) : array
  {
    $Data = $this->db->Search("SELECT 
          l.LegalizacionId,
          l.SolicitudId,
          l.Fecha,
          IF(l.TipoLegalizacion = 'Normal',
              CONCAT(s.ResPrimerNombre,
                      ' ',
                      s.ResPrimerApellido),
              '') AS ANombreDe,
          l.OrdenEncurso,
          IF(l.TipoLegalizacion = 'Normal',
              VIA_GETTOTALPENDIENTELEGALIZAR(s.SolicitudId),
              0) AS TLegalizar,
          VIA_GETTOTALLEGALIZADO(l.LegalizacionId) AS TLegalizado,
          IF(l.Estado <> 'Finalizado', VIA_GETVERIFICADORLEGALIZACION(l.LegalizacionId), '--') AS IsTurnoDe,
          l.DL,
          l.RC,
          l.NC,
          IF(l.Estado <> 'Finalizado', ft.MensajeEstado, 'Finalizado') AS MensajeEstado,
          ft.IsUltimo,
          ft.Orden = l.OrdenEncurso AS IsYouTurno ,
          ft.Prefijo,
          l.SolicitudId,
          IF(s.SolicitudId,
              s.ResCorreo,
              l.ResCorreo) AS ResCorreo,
          l.Estado
      FROM
          via_legalizacion AS l
              INNER JOIN
          via_flujotrabajo AS ft ON l.ProcesoId = ft.ProcesoId
              LEFT JOIN
          via_Solicitud AS s ON s.SolicitudId = l.SolicitudId
      WHERE
          ft.UsuarioVerificaId = $UsuarioId
      ORDER BY l.LegalizacionId DESC;"); # AND s.Estado <> 'Borrador'
    return ['data' => !empty($Data) && is_array($Data) ? $Data : []];
  }

  /**
   * AUTORIZAR LEGALIZACION
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
      "LegalizacionId",
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
      $VistoBueno->LegalizacionId,
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
      array_push($Columns, "Estado");
      array_push($Data, "Finalizado");
    } else {
      array_push($Columns, "OrdenEncurso");
      array_push($Data, (int)$VistoBueno->OrdenEncurso + 1);
    }
    # Actualizar Solicitud
    $num = 0;
    if (!empty($Persona) && is_array($Persona)) {
      $num = $this->db->UpdateBulk("via_Legalizacion", $Columns, [$Data]);
    }
    if ($VistoBueno->IsUltimo) {
      # Notificar
      try {
        $pdfString = $this->GetLegalizacionPDFById($VistoBueno->LegalizacionId, False);
        $this->NotificarUsuarioProceso($VistoBueno->LegalizacionId, 
          [$pdfString], 
          "{$Persona[0]->PrimerNombre} {$Persona[0]->PrimerApellido} ha revisado la legalización, es tu turno revisar dicha legalización.");
      } catch (Exception $e) {
        $this->CatchError($e);
      }
    }
    return $num > 0 ? ["data" => "Se han actualizado los datos satisfactoriamente"] : ["error" => "Hubo un error al momento de guardar"];
  }

  public function GetLegalizacionPDFById(string $LegalizacionId, bool $Show) : string
  {
    $Legalizacion = $this->db->Search("SELECT * FROM via_Legalizacion where LegalizacionId = $LegalizacionId;");
    $Conceptos = $this->db->Search("SELECT * FROM via_DetalleLegalizacion where LegalizacionId = $LegalizacionId;");
    if (!empty($Legalizacion) && is_array($Legalizacion)) {
      $pdf = new PDF(['orientation' => 'L']);
      if ($Show) {
        $pdf->GetLegalizacion($Legalizacion[0], $Conceptos, false, $Show); # $Legalizacion[0]->Estado === "Borrador"
        exit;
      } else {
        return $pdf->GetLegalizacion($Legalizacion[0], $Conceptos, false, $Show);
      }
    } else {
      echo json_encode(["error" => "Hubo un error"]);
    }
  }
  /**
   * Crear legalizacion Normal
   *
   * @param LegalizacionDto $Legalizacion
   * @return void
   */
  public function CreateLegalizacionViatico(object $Legalizacion) : array
  {
    /**
     * @var PerViaDto[] $Persona
     */
    $Persona = $this->db->Search("SELECT p.PersonaId, p.PrimerNombre, p.SegundoNombre, p.PrimerApellido, p.SegundoApellido, p.Celular, c.Cargo, p.Firma, u.Email
    FROM ct_persona as p 
    LEFT JOIN ct_cargo as c on p.CargoId = c.CargoId
    INNER JOIN usuario AS u ON u.UsuarioId = p.UsuarioIntranetId
    WHERE p.PersonaId = {$Legalizacion->ResPersonaId}");
    if (!empty($Persona) && is_array($Persona)) {
      $FechaActual = $this->GetFHNow('fh');
      /**
       * @var ConceptoLegDto[] $Conceptos
       */
      $Conceptos = $Legalizacion->Conceptos;
      unset($Legalizacion->Conceptos);
      unset($Legalizacion->ANombreDe);
      $Legalizacion->ResCargo = $Persona[0]->Cargo;
      $Legalizacion->ResCelular = $Persona[0]->Celular;
      $Legalizacion->ResPrimerNombre = $Persona[0]->PrimerNombre;
      $Legalizacion->ResSegundoNombre = $Persona[0]->SegundoNombre;
      $Legalizacion->ResPrimerApellido = $Persona[0]->PrimerApellido;
      $Legalizacion->ResSegundoApellido = $Persona[0]->SegundoApellido;
      $Legalizacion->ResCorreo = $Persona[0]->Email;
      $Legalizacion->ResFirma = $this->ImgToDataUri($Persona[0]->Firma);
      $Legalizacion->Fecha = $FechaActual;
      $Legalizacion->CreatedAt = $FechaActual;
      $Legalizacion->OrdenEncurso = 0;
      $LegId = $this->db->Create('via_Legalizacion', [
        (array) $Legalizacion
      ],
      False,
      False);
      if (!empty($LegId) && is_array($LegId)) {
        # guardamos los conceptos
        $CListId = $this->CreateConceptos($LegId, $Conceptos, $Legalizacion->CreatedBy, $Legalizacion->CreatedAt);
        # Get pdf string
        $StringPdf = $this->GetLegalizacionPDFById($LegId[0], False);
        # Notificar usuario Proceso
        $this->NotificarUsuarioProceso($LegId[0], [$StringPdf], "Se ha iniciado un proceso de legalización de viatico");
        return !empty($CListId) && is_array($CListId) ? 
        ["data" => "Se han guardado los datos correctamente"] : 
        ["error"=> 'Hubo un error al momento de guardar los conceptos.'];
      } else {
        return ["error"=> 'Hubo un error al momento de guardar la legalización.'];
      }
    }
    return [];
  }

  public function NotificarUsuarioProceso(string $LegalizacionId, array $Adjuntos, string $Mensaje): void
  {
    $queryUAuth = "SELECT u.Email, IF(s.SolicitudId,
    s.ResCorreo,
    l.ResCorreo) AS ResCorreo FROM via_flujotrabajo as ft 
    INNER JOIN via_legalizacion as l on l.ProcesoId = ft.ProcesoId
    LEFT JOIN via_solicitud as s on l.SolicitudId = s.SolicitudId
    INNER JOIN usuario as u on u.UsuarioId = ft.UsuarioVerificaId
    WHERE l.LegalizacionId = $LegalizacionId AND ft.Orden = l.OrdenEncurso;";
    $u = $this->db->Search($queryUAuth);
    if (!empty($u)) {
      $Contenido = "<p>$Mensaje</p>
        <br>
        <br>
        <small>Este enlace vencera en <strong>10 dias</strong> luego de la recepción del mismo.</small>";
      $Asunto = "Solicitud de viatico para revisar";
      $this->EnviarEmail(['ospi89@hotmail.com'], $Asunto, $Contenido, $Adjuntos); #TODO: $u[0]->Email
    }
  }

  /**
   * Crear conceptos
   *
   * @param int[] $LegId
   * @param ConceptoLegDto[] $Conceptos
   * @param string $CreatedBy
   * @param string $CreatedAt
   * @return array
   */
  private function CreateConceptos(array $LegId, array $Conceptos, string $CreatedBy, string $CreatedAt) : array
  {
    $ConData = [];
    foreach ($Conceptos as $c) {
      if ($c->Valor === 0) {
        # Ignoramos los valores que sean 0
        continue;
      }
      $c->LegalizacionId = $LegId[0];
      $c->CreatedBy = $CreatedBy;
      $c->CreatedAt =  $CreatedAt;
      $c->Anexo = is_object($c->Anexo) ? $c->Anexo->data : NULL;
      array_push($ConData, (array) $c);
    }
    return $this->db->Create('via_DetalleLegalizacion', $ConData, True, False);
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
      case 'f':
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
  public function EnviarEmail(array $Emails, string $Asunto, string $Contenido, array $Adjuntos): void
  {
    $Eh = new EmpresaBLL();
    $Empresa = $Eh->GetEmpresa();
    $Email = new sendMail();
    $mensajes = $Email->EnviarEmail_NotificacionMULTIPLE((object) $Empresa, $Asunto, $Contenido,  $Emails, $Adjuntos);
    $this->LogFile($mensajes, "log-emails.txt");
  }
  public function LogFile(string $Msg, string $fileName)
  {
    $fp = fopen(__DIR__ . "/$fileName", 'a+');
    fwrite($fp, $Msg);
    fclose($fp);
  }
  function CatchError(Exception $e) {
    // Handle exception
    $this->LogFile($this->GetFHNow('fh'). ":\t\t" . $e->getMessage() . "\n\n","log-pools.txt");
    echo json_decode(["error" => "Hubo un error al momento de guardar los datos"]);
    exit;
  }
}
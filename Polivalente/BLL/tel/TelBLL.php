<?php

require_once dirname(__FILE__) . '/../../DAL/tel/TelDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require_once dirname(__FILE__) . '/../../vendor/autoload.php';

use Spatie\Async\Pool;

/**
 * Description of TelBLL
 *
 * @author DESARROLLO2
 */
class TelBLL
{
  private $db;
  public function __construct()
  {
    $this->db = new TelDAL();
  }

  public function GetSolicitudesByToken(string $Token)
  {
    $Security = new Security();
    $tv = $Security->validateToken($Token, "TEntrega_123458");
    if (!empty($tv) && !is_string($tv)) {
      return $this->db->GetSolicitudesByPersonaId($tv['sub']);
    } else {
      return [];
    }
  }

  public function getPersonaByCedulaOrNombre(string $NombreOrCedula): array
  {
    $query = "SELECT CONCAT(p.PrimerNombre, ' ', p.SegundoNombre, ' ', p.PrimerApellido, ' ', p.SegundoApellido) AS Nombres, p.Cedula, p.personaId
        from ct_persona as p
        WHERE CONCAT(p.PrimerNombre, ' ', p.SegundoNombre, ' ', p.PrimerApellido, ' ', p.SegundoApellido) LIKE '%$NombreOrCedula%' 
        OR p.Cedula LIKE '%$NombreOrCedula%' AND p.Estado = 'Activo'";
    return $this->db->Search($query);
  }

  public function GetHTID(string $TelefonoId): ?array
  {
    $data = $this->db->GetHTID($TelefonoId);
    return empty($data) ? [] : $data;
  }

  public function GetInventario(): ?array
  {
    $data = $this->db->GetInventario();
    return empty($data) ? [] : $data;
  }

  public function GetEntregaBySolicitudId(string $SolicitudId): ?array
  {
    $data = $this->db->GetEntregaBySolicitudId($SolicitudId);
    return empty($data) ? [] : $data;
  }

  public function ReenviarNotificacion(string $EntregaId)
  {
    $Data = $this->db->GetEntregaById($EntregaId);
    if (!empty($Data)) {
      $Usuario = $this->db->GetUsuarioBySolicitudId($Data[0]->SolicitudId);
      $mensaje = $this->NotificarUsuario($Usuario, $Data[0]);
      return $mensaje;
    }
    return "Hubo un error, no se encuentra la entrega registrada en la BD";
  }

  public function GetSolicitudById(string $SolicitudId): ?array
  {
    $data = $this->db->GetSolicitudById($SolicitudId);
    return empty($data) ? [] : $data;
  }

  public function GetSolucitudesByUsuarioId(string $Dia, string $Mes, string $Year, string $UsuarioId): ?array
  {
    $data = $this->db->GetTelByUsuarioId($Dia, $Mes, $Year, $UsuarioId);
    return empty($data) ? [] : $data;
  }

  public function GetSolicitudes(string $Dia, string $Mes, string $Year): ?array
  {
    $data = $this->db->GetSolicitudes($Dia, $Mes, $Year);
    return empty($data) ? [] : $data;
  }

  public function GetTelefonos(): ?array
  {
    $data = $this->db->GetTelefonos();
    return empty($data) ? [] : $data;
  }

  public function GetTelefonosByPersonaId($PersonaId): ?array
  {
    $data = $this->db->GetTelefonosByPersonaId($PersonaId);
    return empty($data) ? [] : $data;
  }

  public function CreateSolicitud($Data)
  {
    if ($this->ExistSolReciente($this->GetFHNow("f"), $Data->USolicitaId, $Data->TelefonoId)) {
      return utf8_encode("Debes esperar un año para poder realizar una solicitud");
    }
    if (property_exists($Data, "Fotos")) {
      if (property_exists($Data->Fotos, "data")) {
        $data = $Data->Fotos->data;
        $Data->UrlImagen = $this->SaveFileData($data, $Data->Fotos->name);
      }
    }
    return $this->db->Create("tel_Solicitud", [
      [
        "Fecha" => $this->GetFHNow("fh"),
        "Usuario" => $Data->CreatedBy,
        "USolicitaId" => $Data->USolicitaId,
        "RSolicitaId" => $Data->RSolicitaId,
        "TelefonoId" => $Data->TelefonoId,
        "DescripcionDano" => $Data->DescripcionDano,
        "UrlImagen" => property_exists($Data, "UrlImagen") ? $Data->UrlImagen : "",
        "CreatedBy" => $Data->CreatedBy,
      ]
    ]);
  }

  public function CreateEntrega($Data)
  {
    $u = $this->db->GetUsuarioBySolicitudId($Data->SolicitudId);
    $pe = $this->db->GetDatosPersonaEntrega($Data->REntregaId);
    $id = $this->db->Create("tel_Entrega", [
      [
        "Fecha" => $Data->Fecha,
        "Marca" => $Data->Marca,
        "Modelo" => $Data->Modelo,
        "Operador" => $Data->Operador,
        "Color" => $Data->Color,
        "IMEI1" => $Data->IMEI1,
        "IMEI2" => $Data->IMEI2,
        "Descripcion" => $Data->Descripcion,
        "InventarioId" => $Data->InventarioId,
        "SolicitudId" => $Data->SolicitudId,
        "TelefonoId" => $Data->TelefonoId,
        "Ciudad" => $Data->Ciudad,
        "Solicita" => $Data->Solicita,
        "CargoSolicita" => $Data->Cargo,
        "Tipo" => $Data->Tipo,
        "CreatedBy" => $Data->CreatedBy,
        "REntregaId" => $Data->REntregaId,
        "Entrega" => $pe[0]->Nombres,
        "CargoEntrega" => $pe[0]->Cargo,
        "FirmaEntrega" => $this->ImgToDataUri($pe[0]->Firma),
      ]
    ]);
    if (!empty($id)) {
      $pool = Pool::create();
      $pool->add(function () use ($u, $Data) {
        $this->NotificarUsuario($u, $Data);
      });
      $pool->add(function () use ($Data) {
        $this->db->UpdateBulk("tel_inventario", ["InventarioId", "EstadoArticulo", "Stock"], [[$Data->InventarioId, 'Usado', 0]]);
      });
      $pool->add(function () use ($Data) {
        $this->db->UpdateBulk("tel_solicitud", ["SolicitudId", "Estado"], [[$Data->SolicitudId, 'Entregada']]);
      });
      $pool->wait();
    }
    return $id;
  }

  public function CreateInv(stdclass $Data)
  {
    $id = $this->db->Create("tel_inventario", [
      [
        "Marca" => $Data->Marca,
        "Modelo" => $Data->Modelo,
        "Operador" => $Data->Operador,
        "Color" => $Data->Color,
        "IMEI1" => $Data->IMEI1,
        "IMEI2" => $Data->IMEI2,
        "Stock" => $Data->Stock,
        "EstadoArticulo" => $Data->EstadoArticulo,
        "CreatedBy" => $Data->CreatedBy,
      ]
    ]);
    return $id;
  }

  public function createTelefono(object $telefono) : array
  {
    unset($telefono->Lider);
    $id = $this->db->Create("tel_telefonos", [
      (array) $telefono
    ]);
    return is_array($id) ? ['data' => 'Se han guardado los datos satisfactoriamente'] : ['error' => 'Hubo un error al momento de guardar los datos'];
  }

  public function UpdateTelefono(object $telefono)
  {
    $column = ["TelefonoId", "LiderTelefonoId", "Responsable", "Sede", "Area", "Telefono", "TelefonoAnt", "Plan", "Estado", "ModifiedBy", "ModifiedAt"];
    $datos = [[$telefono->TelefonoId, $telefono->LiderTelefonoId, $telefono->Responsable, $telefono->Sede, $telefono->Area, $telefono->Telefono, $telefono->TelefonoAnt, $telefono->Plan, $telefono->Estado, $telefono->ModifiedBy, $this->GetFHNow('fh')]];
    $count = $this->db->UpdateBulk("tel_telefonos", $column, $datos);
    return $count > 0 ? ['data' => 'Se han actualizado los datos satisfactoriamente'] : ['error' => 'Ha ocurrido un error al actualizar este telefono'];
  }

  public function UpdateInv(stdclass $Data)
  {
    $column = ["InventarioId", "Marca", "Modelo", "Operador", "Color", "IMEI1", "IMEI2", "Stock", "EstadoArticulo", "ModifiedBy", "ModifiedAt"];
    $datos = [[$Data->InventarioId, $Data->Marca, $Data->Modelo, $Data->Operador, $Data->Color, $Data->IMEI1, $Data->IMEI2, $Data->Stock, $Data->EstadoArticulo, $Data->ModifiedBy, $this->GetFHNow('fh')]];
    $this->db->UpdateBulk("tel_inventario", $column, $datos);
    return [true];
  }

  public function ExistSolReciente($Fecha, $UsuarioId, $TelefonoId)
  {
    $v = $this->db->ValidarSolicitud($Fecha, $UsuarioId, $TelefonoId);
    if (!empty($v)) {
      return $v[0]->VFecha;
    }
    return false; # normalmente no esta vacio
  }

  public function UpdateFirmaEntrega($Data)
  {
    $Fecha = $this->GetFHNow('fh');
    $u = $this->db->GetUsuarioBySolicitudId($Data->SolicitudId);
    if (empty($u)) {
      return "No se reconoce el usuario que realizó la solicitud";
    }
    $pool = Pool::create();
    $pool->add(function () use ($u, $Data, $Fecha) {
      $this->NotificarUsuarioFirmado($u, $Data, $Fecha);
    });
    $pool->add(function () use ($Data, $Fecha, $u) {
      $this->db->UpdateBulk("tel_Entrega", ["EntregaId", "FirmaSolicita", "FechaFirma", "Estado"], [[$Data->EntregaId, $this->ImgToDataUri($u[0]->Firma), $Fecha, 'Firmado']]);
      $this->db->UpdateBulk("tel_Solicitud", ["SolicitudId", "FechaFinSol", "Estado"], [[$Data->SolicitudId, $Fecha, 'Finalizada']]);
    });
    $pool->wait();
    return [true];
  }
  public function SaveFileData(string $data, string $name)
  {
    $cadena = "";
    if (substr($data, 0, 4) === "data") {
      list($type, $data) = explode(';', $data);
      list(, $data) = explode(',', $data);
      list(, $type) = explode('/', $type);
      $data = base64_decode($data);
      if ($data === false) {
        return 'Archivo incorrecto.';
      }
      $cadena = $name;
      file_put_contents(dirname(__FILE__) . "/../..//error_tel//" . $cadena, $data);
    }
    return $cadena;
  }

  public function NotificarUsuario($Usuario, $Data)
  {
    $Eh = new EmpresaBLL();
    $Empresa = $Eh->GetEmpresa();
    $Email = new sendMail();
    $URL = $this->BuildUrlPersonaId($Usuario[0]->PersonaId);
    $mensaje = "";
    $CONTENIDO = "<p>Para ver las entregas que tienes pendientes, debes hacer click en el enlace que aparece acontinuación:</p>
        <br>
        <div style='center'>
            <a href='$URL'>Ver entregas pendientes por firmar</a>
        </div>
        <br>
        <br>
        <small>Este enlace vencera en <strong>10 dias</strong> luego de la recepción del mismo.</small>";
    if (!empty($Usuario)) {
      $mensaje = $Email->EnviarEmail_Notificacion($Empresa, "Recibir: $Data->Marca $Data->Modelo", $CONTENIDO, $Usuario[0]->Email,  $Data->Solicita);
    }
    return $mensaje;
  }

  public function NotificarUsuarioFirmado($Usuario, $Data, $Fecha)
  {
    $Eh = new EmpresaBLL();
    $Empresa = $Eh->GetEmpresa();
    $Email = new sendMail();
    $URL = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Polivalente/api/tel/Tel.php?TPDF=$Data->EntregaId";
    $mensaje = "";
    $CONTENIDO = "<p>Para poder descargar tu archivo correspondiente a la entrega, debes hacer click en el enlace que aparece acontinuación:</p>
        <br>
        Entregado el $Fecha.
        <br>
        <br>
        <div style='center'>
            <a href='$URL'>Ver PDF</a>
        </div>
        <br>
        <br>
        <small>Este enlace <strong>NO VENCE</strong>.</small>";
    if (!empty($Usuario)) {
      $pdf = $this->GetPDF($Data->EntregaId);
      $mensaje = $Email->EnviarEmail_Notificacion_pdf($Empresa, "Entrega FIRMADA", $CONTENIDO, $Usuario[0]->Email,  $Usuario[0]->PrimerNombre, $pdf);
    }
    return $mensaje;
  }

  public function SHOWPDF($EntregaId)
  {
    $Entrega = $this->db->GetEntregaById($EntregaId);
    if (!empty($Entrega)) {
      $mpdf = new \Mpdf\Mpdf();
      $H1 = file_get_contents(__DIR__ . '/H1.html');
      $H2 = file_get_contents(__DIR__ . '/H2.html');
      // replace
      $Fecha = strtotime($Entrega[0]->Fecha);
      $m = date('m', $Fecha);
      $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
      $H1 = str_replace('{{ Entrega.Ciudad }}', $Entrega[0]->Ciudad, $H1);
      $H1 = str_replace('{{ DIA }}', date('d', $Fecha), $H1);
      $H1 = str_replace('{{ MES }}', $meses[$m - 1], $H1);
      $H1 = str_replace('{{ YEAR }}', date('Y', $Fecha), $H1);
      $H1 = str_replace('{{ Entrega.Solicita }}', $Entrega[0]->Solicita, $H1);
      $H1 = str_replace('{{ Entrega.CargoSolicita }}', $Entrega[0]->CargoSolicita, $H1);
      $H1 = str_replace('{{ Entrega.Marca }}', $Entrega[0]->Marca, $H1);
      $H1 = str_replace('{{ Entrega.Modelo }}', $Entrega[0]->Modelo, $H1);
      $H1 = str_replace('{{ Entrega.Tipo }}', $Entrega[0]->Tipo, $H1);
      $H1 = str_replace('{{ Entrega.IMEI1 }}', $Entrega[0]->IMEI1, $H1);
      $H1 = str_replace('{{ Entrega.IMEI2 }}', $Entrega[0]->IMEI2, $H1);
      $H1 = str_replace('{{ Entrega.Color }}', $Entrega[0]->Color, $H1);
      $H1 = str_replace('{{ Entrega.Entrega }}', $Entrega[0]->Entrega, $H1);
      $H1 = str_replace('{{ Entrega.Institucion }}', $Entrega[0]->Institucion, $H1);
      $H1 = str_replace('{{ Entrega.Descripcion }}', $Entrega[0]->Descripcion, $H1);
      $H1 = str_replace('{{ Entrega.CargoEntrega }}', $Entrega[0]->CargoEntrega, $H1);
      $H1 = str_replace('{{ Entrega.FirmaEntrega }}', $Entrega[0]->FirmaEntrega, $H1);
      $H1 = str_replace('{{ Entrega.FirmaSolicita }}', $Entrega[0]->FirmaSolicita, $H1);
      // Hoja 2
      $H2 = str_replace('{{ Entrega.FirmaEntrega }}', $Entrega[0]->FirmaEntrega, $H2);
      $H2 = str_replace('{{ DIA }}', date('d', $Fecha), $H2);
      $H2 = str_replace('{{ MES }}', $meses[$m - 1], $H2);
      $H2 = str_replace('{{ YEAR }}', date('Y', $Fecha), $H2);
      $mpdf->WriteHTML($H1);
      $mpdf->AddPage();
      $mpdf->WriteHTML($H2);
      $mpdf->Output();
    }
  }

  public function GetPDF($EntregaId)
  {
    $Entrega = $this->db->GetEntregaById($EntregaId);
    $pdf = null;
    if (!empty($Entrega)) {
      $mpdf = new \Mpdf\Mpdf();
      $H1 = file_get_contents(__DIR__ . '/H1.html');
      $H2 = file_get_contents(__DIR__ . '/H2.html');
      // replace
      $Fecha = strtotime($Entrega[0]->Fecha);
      $m = date('m', $Fecha);
      $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
      $H1 = str_replace('{{ Entrega.Ciudad }}', $Entrega[0]->Ciudad, $H1);
      $H1 = str_replace('{{ DIA }}', date('d', $Fecha), $H1);
      $H1 = str_replace('{{ MES }}', $meses[$m - 1], $H1);
      $H1 = str_replace('{{ YEAR }}', date('Y', $Fecha), $H1);
      $H1 = str_replace('{{ Entrega.Solicita }}', $Entrega[0]->Solicita, $H1);
      $H1 = str_replace('{{ Entrega.CargoSolicita }}', $Entrega[0]->CargoSolicita, $H1);
      $H1 = str_replace('{{ Entrega.Marca }}', $Entrega[0]->Marca, $H1);
      $H1 = str_replace('{{ Entrega.Modelo }}', $Entrega[0]->Modelo, $H1);
      $H1 = str_replace('{{ Entrega.Tipo }}', $Entrega[0]->Tipo, $H1);
      $H1 = str_replace('{{ Entrega.IMEI1 }}', $Entrega[0]->IMEI1, $H1);
      $H1 = str_replace('{{ Entrega.IMEI2 }}', $Entrega[0]->IMEI2, $H1);
      $H1 = str_replace('{{ Entrega.Color }}', $Entrega[0]->Color, $H1);
      $H1 = str_replace('{{ Entrega.Entrega }}', $Entrega[0]->Entrega, $H1);
      $H1 = str_replace('{{ Entrega.Institucion }}', $Entrega[0]->Institucion, $H1);
      $H1 = str_replace('{{ Entrega.Descripcion }}', $Entrega[0]->Descripcion, $H1);
      $H1 = str_replace('{{ Entrega.CargoEntrega }}', $Entrega[0]->CargoEntrega, $H1);
      $H1 = str_replace('{{ Entrega.FirmaEntrega }}', $Entrega[0]->FirmaEntrega, $H1);
      $H1 = str_replace('{{ Entrega.FirmaSolicita }}', $Entrega[0]->FirmaSolicita, $H1);
      // Hoja 2
      $H2 = str_replace('{{ Entrega.FirmaEntrega }}', $Entrega[0]->FirmaEntrega, $H2);
      $H2 = str_replace('{{ DIA }}', date('d', $Fecha), $H2);
      $H2 = str_replace('{{ MES }}', $meses[$m - 1], $H2);
      $H2 = str_replace('{{ YEAR }}', date('Y', $Fecha), $H2);
      $mpdf->WriteHTML($H1);
      $mpdf->AddPage();
      $mpdf->WriteHTML($H2);
      $pdf = $mpdf->Output('', 'S');
    }
    return $pdf;
  }

  private function BuildUrlPersonaId($PersonaId)
  {
    $Security = new Security();
    $token = $Security->GenerateToken($PersonaId, "TEntrega_123458", 10, []);
    $Server = $_SERVER['SERVER_NAME'] != 'localhost' ? '190.131.221.26' : 'localhost';
    $Url = "http://" . $Server . ":" . $_SERVER['SERVER_PORT'] . "/Polivalente/#/tel_entregas/$token";
    return $Url;
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

  private function ImgToDataUri($image)
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

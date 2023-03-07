<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/procesos/ProcesosDAL.php';
require_once dirname(__FILE__) . '/../seguridad/UsuarioBLL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../Helpers/PDF/OrdenCompraPDF.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require_once dirname(__FILE__) . '/../configuracion/FlujoTrabajoBLL.php';
require_once dirname(__FILE__) . '/../configuracion/VerificadorBLL.php';
require_once dirname(__FILE__) . '/../../DAL/formulario/SolicitudDAL.php';
require_once dirname(__FILE__) . '/../../vendor/autoload.php';

use Spatie\Async\Pool;

class ProcesosBLL
{
  private $db;
  public function __construct()
  {
    $this->db = new ProcesosDAL();
  }
  public function GetAll()
  {
    return $this->db->getAll();
  }

  public function GetProcesosByProcesosId($ProcesosId)
  {
    return $this->db->GetProcesosByProcesosId($ProcesosId);
  }

  public function GetFirmaPDFByProcesoId($ProcesoId)
  {
    $Firmas = $this->db->GetFirmasByProcesosId($ProcesoId);
    if (empty($Firmas)) {
      echo "ERROR - Nadie a firmado";
      exit;
    }
    $tabla = "<table class='table table-bordered'>
      <thead>
        <tr>
          <th>PROCESO ID</th>
          <th colspan='3'>$ProcesoId</th>
        </tr> 
        <tr>
          <th>Fecha</th>
          <th>Nombres</th>
          <th>Cargo</th>
          <th>Firma</th>
        </tr>
      </thead>
      <tbody>
        {{TableDATA}}
      </tbody>
    </table>";
    $TableData = "";
    foreach ($Firmas as $f) {
      $img = $f->Firma !== NULL ? "<img src='{$f->Firma}' width='130'>" : '';
      $TableData .= "<tr>
        <td>{$f->Fecha}</td>
        <td>{$f->Nombres}</td>
        <td>{$f->Cargo}</td>
        <td>
          {$img}
        </td>
      </tr>";
    }
    $tabla = str_replace("{{TableDATA}}", $TableData, $tabla);
    $mpdf = new \Mpdf\Mpdf();
    $HF = file_get_contents(__DIR__ . '/HojaFirmas.html');
    $HF = str_replace('{{ PROCESOID }}', $ProcesoId, $HF);
    $HF = str_replace('{{ TABLA }}', $tabla, $HF);
    $mpdf->WriteHTML($HF);
    $mpdf->Output();
  }
  public function GetNotasBySolMantId($SolicitudId)
  {
    return $this->db->GetNotasBySolMantId($SolicitudId);
  }

  public function GetNotasByProcesoId(string $ProcesoId): array
  {
    return ["data" => $this->db->GetNotasByProcesoId($ProcesoId)];
  }

  public function GetPrefijoByReporteId($ReporteId)
  {
    return ["data" => $this->db->GetPrefijoByReporteId($ReporteId)];
  }

  public function GetAllByVerificadorId($VerificadorId, $Estado)
  {
    $Procesos = $this->db->GetAllByVerificadorId($VerificadorId, $Estado);
    return $Procesos;
  }

  public function getAllFinalizadoByVerificadorId(string $usuarioid, string $estado, string $mes, string $year) : array
  {
    $query = "SELECT pp.ProcesoId, se.Nombre as Sede, pp.SedeId, pp.ServicioId,  
    ser.Nombre as Servicio, pp.ProtocoloId, pp.SolicitanteId,pp.OrdenEnCurso, 
    pp.Nombre, pp.CreatedAt, pp.Estado, p.Nombre as Protocolo 
    FROM pc_proceso as pp FORCE INDEX (ProtocoloId) 
    STRAIGHT_JOIN pc_protocolo as p on pp.ProtocoloId = p.ProtocoloId
    INNER JOIN pc_flujotrabajo as fp on pp.ProtocoloId = fp.ProtocoloId AND fp.Estado = 'Activo'
    STRAIGHT_JOIN pc_verificador as v on fp.FlujoTrabajoId = v.FlujoTrabajoId AND v.Estado = 'Activo'
    STRAIGHT_JOIN Sede as se on pp.SedeId = se.SedeId
    STRAIGHT_JOIN Servicio as ser on pp.ServicioId = ser.ServicioId
    where v.UsuarioId = $usuarioid and YEAR(pp.CreatedAt) = $year and MONTH(pp.CreatedAt) = $mes  and pp.Estado <> '$estado' 
    group by pp.ProcesoId
    order by pp.ProcesoId desc;";
    $listado = $this->db->Search($query);
    return ["data" => !empty($listado) ? $listado : []];
  }

  public function getVendedores(string $dato) : array
  {
    $query = "SELECT VendedorId, Nombre, Nit, Direccion, Telefono 
    FROM pc_oc_vendedor WHERE Nombre LIKE '%$dato%' ORDER BY Nombre;";
    $listado = $this->db->Search($query);
    return $listado;
  }

  public function getProcesosOrdenCompra() : array
  {
    $query = "SELECT p.ProcesoVerificacionId, p.Nombre, 
    CONCAT(per.PrimerNombre, ' ', per.PrimerApellido) AS NombreRevisa 
    from pc_oc_procesoverificacion as p 
    INNER JOIN pc_oc_flujopv as fp ON p.ProcesoVerificacionId = fp.ProcesoId
    INNER JOIN ct_persona as per ON fp.UsuarioId = per.UsuarioIntranetId
    WHERE fp.IsUltimo = 0";
    $listado = $this->db->Search($query);
    return ["data" => !empty($listado) ? $listado : []];
  }

  public function GetProcesosForAuditoria(string $Estado)
  {
    return $this->db->GetProcesosForAuditoria($Estado);
  }

  public function GetProcesoData($ProcesoId)
  {
    $vh = new VerificadorBLL();
    $FlujoTrabajo = $this->db->GetAllFlujoTrabajo($ProcesoId);
    foreach ($FlujoTrabajo as $ft) {
      $Seguimientos = $this->db->GetSeguimientoByFlujoTrabajoId($ft->FlujoTrabajoId, $ProcesoId);
      foreach ($Seguimientos as $s) {
        $s->DatosAnexos = json_decode($s->DatosAnexos);
      }
      $ft->Seguimiento = $Seguimientos;
      $ft->Verificadores = $vh->GetUsuarioByFlujoTrabajoId($ft->FlujoTrabajoId);
    }
    return $FlujoTrabajo;
  }

  public function GetAllByUserId($UserId)
  {
    return $this->db->getAllByUserId($UserId);
  }

  public function GetAllFlujoTrabajo($ProcesoId)
  {
    return $this->db->GetAllFlujoTrabajo($ProcesoId);
  }

  public function GetFlujoTrabajoById($FlujoTrabajoId)
  {
    return $this->db->GetFlujoTrabajoById($FlujoTrabajoId);
  }

  public function GetFlujoTrabajoByOrden($Orden, $ProtocoloId)
  {
    return $this->db->GetFlujoTrabajoByOrden($Orden, $ProtocoloId);
  }

  public function GetAllByToken($Token)
  {
    return $this->db->GetAllByToken($Token);
  }

  public function GetAllByNombre($Nombre)
  {
    return $this->db->getAllByNombre($Nombre);
  }

  public function CreateProcesos($Procesos)
  {
    $ft = new FlujoTrabajoBLL();
    $vh = new VerificadorBLL();
    $Helper = new EmpresaBLL();
    $EmpresaObj = $Helper->GetEmpresa();
    $he = new sendMail();
    $id = $this->db->CreateProcesos($this->MAPToArray($Procesos));
    if (!empty($id) && is_array($id)) {
      $Verificadores = $vh->GetUsuarioByFirstFlujoTrabajo($Procesos[0]->ProtocoloId);
      foreach ($Verificadores as $u) {
        $Url = $this->BuildUrl($u->Email, $id[0], $u->UsuarioId);
        $Contenido = '<p>El Sr(a) ' . $Procesos[0]->CreatedBy . ' Solicitado un proceso:</p>
                        <br>
                        <div style="text-align:justify">
                            <h2> ' . $Procesos[0]->Nombre . ' </h2>
                            <p>Puedes revisar el progreso haciendo clic en el siguiente enlace <a href="' . $Url . '">' . $Url . '</a></p>
                        </div>
                        <br>
                        <br>
                        <small></small>';
        $he->EnviarEmail_Notificacion($EmpresaObj, 'El Sr(a) ' . $Procesos[0]->CreatedBy . ' Solicitado un proceso', $Contenido, $u->Email, $u->NombreCompleto);
      }
      if ($Procesos[0]->Prefijo && $Procesos[0]->EventoSolicitudId) {
        $this->db->UpdateEventoSolicitud([
          [
            "ProcesoId" => $id[0],
            "ModifiedAt" => $this->getDatetimeNow()
          ]
        ], $Procesos[0]->EventoSolicitudId, $Procesos[0]->Prefijo);
      } else if ($Procesos[0]->SolicitudId) {
        $this->db->CreateEventoSolicitud([
          [
            "ProcesoId" => $id[0],
            "NombreBreveEvento" => $Procesos[0]->Nombre,
            "NombreUsuario" => $Procesos[0]->CreatedBy,
            "Descripcion" => $Procesos[0]->Nombre,
            "SolicitudId" => $Procesos[0]->SolicitudId,
            "FechaEvento" => $this->getDatetimeNow()
          ]
        ], $Procesos[0]->Prefijo);
      }
    }
    return $id;
  }

  public function getPdfOrdenCompra(string $ordenCompraId, bool $show) : ?string
  {
    $pdf = new OrdenCompraPDF();
    $ordenCompra = $this->db->Search("SELECT * FROM pc_oc_ordencompra WHERE OrdenCompraId = $ordenCompraId;");
    $detalles = $this->db->Search("SELECT * FROM pc_oc_detalleordencompra WHERE OrdenCompraId = $ordenCompraId;");
    if (empty($ordenCompra) || empty($detalles)) {
      return "Hubo un error al consultar la orden de compra y sus detalles";
    }
    if (!$show) {
      $pdf->getOrdenCompra($ordenCompra[0], $detalles, $show);
      exit;
    }
    return $pdf->getOrdenCompra($ordenCompra[0], $detalles, $show);
  }

  public function createOrdenCompra(object $ordenCompra) : array
  {
    $fecha = $this->getDatetimeNow();
    $ordenCompra->Fecha = $fecha;
    $ordenCompra->CreatedAt = $fecha;
    # get Consecutivo
    $consecutivo = $this->db->Search("SELECT ConsecutivoId, Numero FROM pc_oc_consecutivo LIMIT 1;");
    # set firma elaborado
    $this->setPersonaData($ordenCompra->ElaboradoId, $ordenCompra, 'Elaborado', true);
    $detalleOrden = $ordenCompra->Detalles;
    unset($ordenCompra->Detalles);
    $ordenCompra->TelefonoEmpresa = $ordenCompra->TelEmpresa;
    unset($ordenCompra->TelEmpresa);
    $ordenCompra->Consecutivo = $consecutivo[0]->Numero;
    $ordenId = $this->db->Create('pc_oc_ordencompra', [(array) $ordenCompra], false, false);
    if (!empty($ordenId) && is_array($ordenId)) {
      # Crear el vendedor si no existe o actualizar sus datos
      $this->createOrUpdateVendedor($ordenCompra, $ordenId);
      # crear detalles de la orden
      $ordenCompra->Detalles = $detalleOrden;
      $detallesId = $this->crearDetallesOrdenCompra($ordenCompra, $ordenId);
      $hasDetalles = !empty($detallesId) && is_array($detallesId);
      # update consecutivo
      $this->updateConsecutivo($consecutivo[0], $hasDetalles);
      return $hasDetalles ? ['data' => 'Se han guardado los datos satisfactoriamente'] : ['error' => 'Hubo un error al momento de guardar los detalles'];
    } else {
      return ['error' => 'Hubo un error al momento de guardar la orden'];
    }
  }

  public function crearDetallesOrdenCompra(object $ordenCompra,  array $ordenId) : array
  {
    $fecha = $this->getDatetimeNow();
    $detalles = [];
    foreach ($ordenCompra->Detalles as $d) {
      $d->OrdenCompraId = $ordenId[0];
      $d->CreatedBy = $ordenCompra->CreatedBy;
      $d->CreatedAt = $fecha;
      array_push($detalles, (array) $d);
    }
    return $this->db->Create('pc_oc_DetalleOrdenCompra', $detalles, true, false);
  }

  public function updateConsecutivo(object $consecutivo, bool $hasDetalles)
  {
    if ($hasDetalles) {
      $consecutivo->Numero++;
      $this->db->UpdateBulk('pc_oc_consecutivo', ['ConsecutivoId', 'Numero'], [[$consecutivo->ConsecutivoId, $consecutivo->Numero]]);
    }
  }

  private function createOrUpdateVendedor(object $ordenCompra,  array $ordenId)
  {
    if (property_exists($ordenCompra, 'VendedorId')) {
      $this->updateVendedor($ordenCompra);
    } else {
      $this->crearVendedor($ordenCompra, $ordenId);
    }
  }

  private function crearVendedor(object $ordenCompra,  array $ordenId) : void
  {
    $vendedor = new stdClass();
    $vendedor->Nombre = $ordenCompra->NombreEmpresa;
    $vendedor->Nit = $ordenCompra->NitEmpresa;
    $vendedor->Direccion = $ordenCompra->DireccionEmpresa;
    $vendedor->Telefono = $ordenCompra->TelefonoEmpresa;
    $vendedorId = $this->db->Create('pc_oc_Vendedor', [(array) $vendedor], false, true);
    if (!empty($vendedorId) && is_array($vendedorId)) {
      # actualizamos el id del vendedor en la orden de compra
      $this->db->UpdateBulk('pc_oc_ordencompra', ['OrdenCompraId', 'VendedorId'], [[$ordenId[0], $vendedorId[0]]]);
    }
  }

  private function updateVendedor(object $ordenCompra) : void
  {
    $this->db->UpdateBulk(
    'pc_oc_Vendedor', 
    ['VendedorId', 'Nombre', 'Nit', 'Direccion', 'Telefono'], 
    [[$ordenCompra->VendedorId, $ordenCompra->NombreEmpresa, $ordenCompra->NitEmpresa, $ordenCompra->DireccionEmpresa, $ordenCompra->TelefonoEmpresa]]);
  }

  private function setPersonaData(string $usuarioId, object &$ordenCompra, string $prefijo, bool $setFirma = false) :void
  {
    $persona = $this->db->Search("SELECT p.PersonaId, u.Email,  
    CONCAT(p.PrimerNombre, ' ', p.SegundoNombre, ' ', p.PrimerApellido, ' ', p.SegundoApellido) AS Nombres, 
    c.Cargo, p.Firma
    FROM ct_persona as p 
    LEFT JOIN ct_cargo as c on p.CargoId = c.CargoId
    INNER JOIN usuario AS u on u.UsuarioId = p.UsuarioIntranetId
    WHERE p.UsuarioIntranetId = {$usuarioId}");
    if (!empty($persona) && is_array($persona)) {
      $ordenCompra->{$prefijo . 'Id'} = $usuarioId;
      $ordenCompra->{'Fecha' . $prefijo} = $this->getDatetimeNow();
      $ordenCompra->{'Email' . $prefijo} = $persona[0]->Email;
      $ordenCompra->{'Nombre' . $prefijo} = $persona[0]->Nombres;
      $ordenCompra->{'Cargo' . $prefijo} = $persona[0]->Cargo;
      $ordenCompra->{'Firma' . $prefijo} = $setFirma ? $this->ImgToDataUri($persona[0]->Firma) : null;
    }
  }

  public function CreateNota($Nota)
  {
    $ProcesoId = property_exists($Nota, "ProcesoId") ? $Nota->ProcesoId : NULL;
    $SolicitudId = property_exists($Nota, "SolicitudId") ? $Nota->SolicitudId : NULL;
    if ($SolicitudId === NULL && $Nota->PREFIJO !== NULL) {
      $SOL = $this->db->GetSolicitudIdByProcesoId($ProcesoId, $Nota->PREFIJO);
      if (!empty($SOL) && is_array($SOL)) {
        $SolicitudId = $SOL[0]->SolicitudId;
      }
    }
    if ($ProcesoId === NULL && $Nota->PREFIJO !== NULL) {
      $PRO = $this->db->GetProcesoIdBySolicitudId($SolicitudId, $Nota->PREFIJO);
      if (!empty($PRO) && is_array($PRO)) {
        $ProcesoId = $PRO[0]->ProcesoId;
      }
    }
    $id = $this->db->CreateNota([
      [
        "Fecha" => $this->getDatetimeNow(),
        "PersonaId" => $Nota->PersonaId,
        "UsuarioVerificadorId" => $Nota->UsuarioVerificadorId,
        "Nombres" => $Nota->Nombres,
        #"Cargo" => $Nota->Cargo,
        "Descripcion" => $Nota->Descripcion,
        "ProcesoId" => $ProcesoId,
        "SolicitudId" => $SolicitudId,
        "CreatedBy" => $Nota->Nombres,
        "CreatedAt" => $this->getDatetimeNow(),
      ]
    ]);
    if (!empty($id) && is_array($id)) {
      if ($Nota->PREFIJO !== NULL) {
        $this->NotificarUsuarios($SolicitudId, $ProcesoId, $Nota->PREFIJO, $Nota->Tipo, $Nota->Descripcion);
      }
    }
    return $id;
  }

  private function NotificarUsuarios(?string $SolicitudId, ?string $ProcesoId, string $PREFIJO, string $Tipo, string $Descripcion): void
  {
    $pool = Pool::create();
    if ($Tipo === "SOL" && $ProcesoId === NULL) {
      $DataT = $this->GetDataForNotas($PREFIJO);
      $pool->add(function () use ($DataT, $Descripcion) {
        $PersonaAdmin = $this->db->GetPersonaById($DataT->UsuarioAdminId);
        if (!empty($PersonaAdmin) && is_array($PersonaAdmin)) {
          $Helper = new EmpresaBLL();
          $EmpresaObj = $Helper->GetEmpresa();
          $he = new sendMail();
          $Contenido = "<p>$Descripcion</p>";
          $he->EnviarEmail_Notificacion($EmpresaObj, "Nueva Nota", $Contenido, $PersonaAdmin[0]->Email, $PersonaAdmin[0]->PrimerNombre);
        }
      })->catch(function (Throwable $exception) {
        $fp = fopen(__DIR__ . '/log-notas.txt', 'w');
        fwrite($fp, $exception->getMessage());
        fclose($fp);
      });
    } else if ($Tipo === "SOL-admin") {
      $pool->add(function () use ($SolicitudId, $PREFIJO, $Descripcion) {
        $PersonaSol = $this->db->GetPersonaBySolicitudId($SolicitudId, $PREFIJO);
        if (!empty($PersonaSol) && is_array($PersonaSol)) {
          $Helper = new EmpresaBLL();
          $EmpresaObj = $Helper->GetEmpresa();
          $he = new sendMail();
          $Contenido = "<p>$Descripcion</p>";
          $he->EnviarEmail_Notificacion($EmpresaObj, "Nueva Nota de $PREFIJO", $Contenido, $PersonaSol[0]->Email, $PersonaSol[0]->PrimerNombre);
        }
      })->catch(function (Throwable $exception) {
        $fp = fopen(__DIR__ . '/log-notas.txt', 'w');
        fwrite($fp, $exception->getMessage());
        fclose($fp);
      });
    } else if ($Tipo === "PC-admin") {
      $Helper = new EmpresaBLL();
      $EmpresaObj = $Helper->GetEmpresa();
      $Contenido = "<p>$Descripcion</p>";
      $pool->add(function () use ($ProcesoId, $PREFIJO) {
        return $this->db->GetUsuariosANotificar($ProcesoId, $PREFIJO);
      })->then(function ($UsuariosANotificar) use ($EmpresaObj, $Contenido, $PREFIJO) {
        $UsuariosLst = [];
        $he = new sendMail();
        foreach ($UsuariosANotificar as $u) {
          array_push($UsuariosLst, $u->Email);
        }
        $Mensajes = $he->EnviarEmail_NotificacionMULTIPLE($EmpresaObj, "Nueva Nota de $PREFIJO", $Contenido, $UsuariosLst);
        $fp = fopen(__DIR__ . '/mensajes-enviados.txt', 'a');
        fwrite($fp, $Mensajes);
        fclose($fp);
      });
    } else if ($Tipo === "SOL" && is_numeric($ProcesoId)) {
      $Helper = new EmpresaBLL();
      $EmpresaObj = $Helper->GetEmpresa();
      $Contenido = "<p>$Descripcion</p>";
      $pool->add(function () use ($ProcesoId, $PREFIJO) {
        return $this->db->GetUsuariosANotificar($ProcesoId, $PREFIJO);
      })->then(function ($UsuariosANotificar) use ($EmpresaObj, $Contenido, $PREFIJO) {
        $UsuariosLst = [];
        $he = new sendMail();
        foreach ($UsuariosANotificar as $u) {
          if ($u->Orden != '-') {
            array_push($UsuariosLst, $u->Email);
          }
        }
        $Mensajes = $he->EnviarEmail_NotificacionMULTIPLE($EmpresaObj, "Nueva Nota de $PREFIJO", $Contenido, $UsuariosLst);
        $fp = fopen(__DIR__ . '/mensajes-enviados.txt', 'a');
        fwrite($fp, $Mensajes);
        fclose($fp);
      });
    }
    $pool->wait();
  }
  /**
   * Get DATA para las notas
   *
   * @param string $Prefijo
   * @return object
   */
  private function GetDataForNotas(string $Prefijo): object
  {
    // contruyo las tablas y columnas para la consulta
    $data = new stdClass();
    $data->prefijo = "pol";
    $data->UsuarioAdminId = 2462;
    if (strtolower($Prefijo) == 'biomedicos') {
      $data->prefijo = "biomedicos";
      $data->UsuarioAdminId = 1117;
    } elseif (strtolower($Prefijo) == 'sistemas') {
      $data->prefijo = "sistemas";
      $data->UsuarioAdminId = 83;
    }
    return $data;
  }

  public function NotificarProcesoPendiente()
  {
    $Helper = new EmpresaBLL();
    $EmpresaObj = $Helper->GetEmpresa();
    $he = new sendMail();
    $Procesos = $this->db->GetPendientesPorAprobar();
    try {
      foreach ($Procesos as $Proceso) {
        $Url = $this->BuildUrl($Proceso->Email, $Proceso->ProcesoId, $Proceso->UsuarioId);
        $Contenido = "<p>El Sr(a) " . $Proceso->CreatedBy . " Solicitado un proceso y ESTA PENDIENTE A QUE LO ATIENDAS:</p>
                <br>
                <div style='text-align:justify'>
                    <h2> " . $Proceso->Nombre . " </h2>
                    <p>Puedes revisar el progreso haciendo clic en el siguiente enlace <a href='$Url'>$Url</a></p>
                </div>
                <br>
                <br>
                <small>Nota: este mensaje se generara de forma automatica hasta que TU RESPONDAS</small>";

        $he->EnviarEmail_Notificacion($EmpresaObj, 'PENDIENTE: proceso  ' . $Proceso->CreatedBy . ' - ' . $Proceso->Servicio, $Contenido, $Proceso->Email, $Proceso->NombreCompleto);
      }
      return "Mensajes Enviados";
    } catch (Exception $exc) {
      return $exc->getTraceAsString();
    }
  }
  /**
   * Descargar imagen y convertirla a DATAURI
   *
   * @param [type] $image
   * @return void
   */
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

  public function CreateSeguimiento($Seguimiento)
  {
    $sh = new SolicitudDAL();
    $Hu = new UsuarioBLL();
    $v = new VerificadorBLL();
    $u = $Hu->GetUsuarioById($Seguimiento[0]->UsuarioId);
    $p = $this->GetProcesosByProcesosId($Seguimiento[0]->ProcesoId);
    $ContadorCheckUser = 0;
    $Id = $this->db->CreateSeguimiento($this->MAPToCreateSeguimiento($Seguimiento, $u->Firma));
    $Url = $this->BuildUrl($u->Email, $Seguimiento[0]->ProcesoId, $u->UsuarioId);
    if (count($Id) > 0) {
      // Notificamos al solicitante
      $Solicitante = $Hu->GetUsuarioById($p->SolicitanteId);
      $Helper = new EmpresaBLL();
      $EmpresaObj = $Helper->GetEmpresa();
      $hs = new sendMail();
      $VB = $Seguimiento[0]->VB ? "Aprobado" : "Rechazado";
      $Contenido = '<p>El Sr(a) ' . $u->NombreCompleto . ' ha revisado el proceso:</p>
                            <br>
                            <div style="text-align:justify">
                                <p>Puedes revisar el progreso haciendo clic en el siguiente enlace <a href="' . $Url . '">' . $Url . '</a></p>
                            </div>
                            <br>
                            <br>
                            <small>.</small>';
      if ($VB === "Aprobado") {
        $f = $this->getDatetimeNow();
        $Verificador = $this->db->GetOrdenByVerificadorId2($Seguimiento[0]->UsuarioId, $p->ProtocoloId);
        $this->db->SaveFirma([
          [
            "Fecha" => $f,
            "PersonaId" => $u->PersonaId,
            "Nombres" => $u->Nombres,
            "Cargo" => $u->Cargo,
            "Firma" => $this->ImgToDataUri($u->Firma),
            "ProcesoId" => $Seguimiento[0]->ProcesoId,
            "SeguimientoId" => $Id[0],
            "Orden" => $Verificador[0]->Orden,
            "CreatedBy" => $u->Nombres,
            "CreatedAt" => $f,
          ]
        ]);
      }

      $sol = $sh->GetSolIfExistByProceso($Seguimiento[0]->ProcesoId);
      if (count($sol) > 0) {
        $sh->CreateEventoSolicitudPol([
          array(
            'NombreUsuario' => $Seguimiento[0]->CreatedBy,
            'NombreBreveEvento' => "Proceso $VB",
            'SolicitudId' => $sol[0]->SolicitudId,
            'TipoEvento' => "Proceso $VB",
            'Descripcion' => "Proceso $VB",
            'TecnicoResponsable' => $Seguimiento[0]->CreatedBy,
            'FechaEvento' => $this->getDatetimeNow(),
            'CreatedAt' => $this->getDatetimeNow()
          )
        ], $sol[0]->Tipo);
      }
      if ($Seguimiento[0]->EstadoProceso === 'Activo') {
        if ($VB === "Rechazado") {
          // Notificamos al siguiente
          $f = $this->GetFlujoTrabajoById($Seguimiento[0]->FlujoTrabajoId);
          $f_Principal = $this->GetFlujoTrabajoByOrden(0, $f[0]->ProtocoloId);
          if (count($f_Principal) > 0) {
            $Url = $this->BuildUrl($Solicitante->Email, $Seguimiento[0]->ProcesoId, $Solicitante->UsuarioId);
            $Contenido2 = '<p>El Sr(a) ' . $u->NombreCompleto . ' ha revisado el proceso:</p>
                            <br>
                            <div style="text-align:justify">
                                <p>Puedes revisar el progreso haciendo clic en el siguiente enlace <a href="' . $Url . '">' . $Url . '</a></p>
                            </div>
                            <br>
                            <br>
                            <small></small>';
            // actualizamos el orden
            $hs = new sendMail();
            # FIXME: Error cuando la solicitud no existe
            if (!empty($sol)) {
              $sh->UpdateSolicitudPol([
                array(
                  'IsFinalizada' => 1,
                  'FechaFinalizacion' => $this->getDatetimeNow(),
                  'NombreUsuarioFinaliza' => $Seguimiento[0]->CreatedBy,
                  'ModifiedAt' => $this->getDatetimeNow()
                )
              ], $sol[0]->SolicitudId, $sol[0]->Tipo);
            }
            $this->db->UpdateProcesos($this->MAPToChangeOrden($f[0]->Orden, $Seguimiento[0]->CreatedBy, 'Rechazado'), $Seguimiento[0]->ProcesoId);
            //                    $hs->EnviarEmail_Notificacion($EmpresaObj, "Te han devuelto un proceso para revision", $Contenido2, $Usuario->Email, $Usuario->NombreCompleto);
            $hs->EnviarEmail_Notificacion($EmpresaObj, "El proceso ha sido rechazado -- Procesos CLD", $Contenido, $Solicitante->Email, $Solicitante->NombreCompleto);
          }
        } else {
          // Notificamos al siguiente
          $f = $this->GetFlujoTrabajoById($Seguimiento[0]->FlujoTrabajoId);
          $f_next = NULL;
          $orden_inicial = $Seguimiento[0]->OrdenEnCurso;
          //                    $Usuariolst = $v->GetUsuarioByFlujoTrabajoId($f_Principal[0]->FlujoTrabajoId);
          $Usuariolst = $v->GetUsuarioByFlujoTrabajoId($Seguimiento[0]->FlujoTrabajoId);
          $ContadorCheckUser = count($Usuariolst) > 1 ? 0 : 1;
          foreach ($Usuariolst as $Usuario) {
            $s = $this->db->GetSeguimientoByUsuarioId($Usuario->UsuarioId, $Seguimiento[0]->FlujoTrabajoId, $f[0]->Orden, $Seguimiento[0]->ProcesoId);
            if (count($s) > 0) {
              $ContadorCheckUser++;
            }
          }
          do {
            $orden_inicial++;
            $f_next = $this->GetFlujoTrabajoByOrden($orden_inicial, $f[0]->ProtocoloId);
            if (count($f_next) == 0) {
              break;
            }
          } while ($f_next[0]->Estado == 'Inactivo');
          //                echo $ContadorCheckUser  . " == " . (count($Usuariolst));
          if (count($f_next) > 0 && $ContadorCheckUser >= count($Usuariolst)) {
            $Usuarios = $v->GetUsuarioByFlujoTrabajoId($f_next[0]->FlujoTrabajoId);
            foreach ($Usuarios as $Usuario) {
              $hs = new sendMail();
              $Url = $this->BuildUrl($Usuario->Email, $Seguimiento[0]->ProcesoId, $Usuario->UsuarioId);
              $Contenido2 = '<p>El Sr(a) ' . $u->NombreCompleto . ' ha revisado el proceso:</p>
                            <br>
                            <div style="text-align:justify">
                                <p>Puedes revisar el progreso haciendo clic en el siguiente enlace <a href="' . $Url . '">' . $Url . '</a></p>
                            </div>
                            <br>
                            <br>
                            <small>.</small>';
              $hs->EnviarEmail_Notificacion($EmpresaObj, "Tienes un nuevo proceso", $Contenido2, $Usuario->Email, $Usuario->NombreCompleto);
            }
            $data = $this->db->UpdateProcesos($this->MAPToChangeOrden($f_next[0]->Orden, $Seguimiento[0]->CreatedBy), $Seguimiento[0]->ProcesoId);
            $Url = $this->BuildUrl($Solicitante->Email, $Seguimiento[0]->ProcesoId, $Solicitante->UsuarioId);
            $Contenido = '<p>El Sr(a) ' . $u->NombreCompleto . ' ha revisado el proceso:</p>
                            <br>
                            <div style="text-align:justify">
                                <p>Puedes revisar el progreso haciendo clic en el siguiente enlace <a href="' . $Url . '">' . $Url . '</a></p>
                            </div>
                            <br>
                            <br>
                            <small>.</small>';
            $hs = new sendMail();
            $hs->EnviarEmail_Notificacion($EmpresaObj, "El proceso ha sido revisado -- Procesos CLD", $Contenido, $Solicitante->Email, $Solicitante->NombreCompleto);
          } else {
            // este es el paso final
            $f_Principal = $this->GetFlujoTrabajoByOrden(0, $f[0]->ProtocoloId);
            //                    $Usuariolst = $v->GetUsuarioByFlujoTrabajoId($f_Principal[0]->FlujoTrabajoId);
            $Usuariolst = $v->GetUsuarioByFlujoTrabajoId($Seguimiento[0]->FlujoTrabajoId);
            //                    $FlujoTrabajoEnCurso = $this->GetFlujoTrabajoById($Seguimiento[0]->FlujoTrabajoId);
            //                    $ContadorCheckUser = 0;
            //                    foreach ($Usuariolst as $Usuario) {
            //                        $s = $this->db->GetSeguimientoByUsuarioId($Usuario->UsuarioId, $Seguimiento[0]->FlujoTrabajoId, $FlujoTrabajoEnCurso[0]->Orden, $Seguimiento[0]->ProcesoId);
            //                        if (count($s) > 0) {
            //                            $ContadorCheckUser++;
            //                        }
            //                    }
            //                    echo $ContadorCheckUser  . " == " . count($Usuariolst);
            if ($ContadorCheckUser >= count($Usuariolst)) {
              $Url = $this->BuildUrl($Solicitante->Email, $Seguimiento[0]->ProcesoId, $Solicitante->UsuarioId);
              $Contenido = '<p>El proceso ' . $p->Nombre . ' ha finalizado:</p>
                            <br>
                            <div style="text-align:justify">
                                <p>El proceso ha sido ' . $VB . '</p>
                                <p>Puedes revisar el progreso haciendo clic en el siguiente enlace <a href="' . $Url . '">' . $Url . '</a></p>
                            </div>
                            <br>
                            <br>
                            <small>.</small>';
              //                        foreach ($Usuariolst as $Usuario) {
              //                            $hs->EnviarEmail_Notificacion($EmpresaObj, "El proceso " . $p->Nombre . " ha sido finalizado", $Contenido, $Usuario->Email, $Usuario->NombreCompleto);
              //                        }
              $this->db->UpdateProcesos($this->MAPToChangeOrden(0, $Seguimiento[0]->CreatedBy, 'Finalizado'), $Seguimiento[0]->ProcesoId);
              $hs = new sendMail();
              $hs->EnviarEmail_Notificacion($EmpresaObj, "El proceso " . $p->Nombre . " ha sido finalizado -- Procesos CLD", $Contenido, $Solicitante->Email, $Solicitante->NombreCompleto);
            }
          }
        }
      } else if ($Seguimiento[0]->EstadoProceso === 'Cancelado') {
        $f = $this->GetFlujoTrabajoById($Seguimiento[0]->FlujoTrabajoId);
        $f_Principal = $this->GetFlujoTrabajoByOrden(0, $f[0]->ProtocoloId);
        $Usuario = $Hu->GetUsuarioById($f_Principal[0]->VerificadorId);
        $Contenido = '<p>El proceso ' . $p->Nombre . ':</p>
                            <br>
                            <div style="text-align:justify">
                                <p>Ha sido CANCELADO.</p>
                            </div>
                            <br>
                            <br>
                            <small>.</small>';
        $this->db->UpdateProcesos($this->MAPToChangeOrden(0, $Seguimiento[0]->CreatedBy, 'Cancelado'), $Seguimiento[0]->ProcesoId);
        $hs = new sendMail();
        $hs->EnviarEmail_Notificacion($EmpresaObj, "El proceso " . $p->Nombre . " ha sido cancelado", $Contenido, $Solicitante->Email, $Solicitante->NombreCompleto);
        $hs = new sendMail();
        $hs->EnviarEmail_Notificacion($EmpresaObj, "El proceso " . $p->Nombre . " ha sido cancelado", $Contenido, $Usuario->Email, $Usuario->NombreCompleto);
      }
    }
    return $Id;
  }

  public function CreateSeguimiento_devolver($Seguimiento)
  {
    $Hu = new UsuarioBLL();
    $v = new VerificadorBLL();
    $u = $Hu->GetUsuarioById($Seguimiento[0]->UsuarioId);
    $p = $this->GetProcesosByProcesosId($Seguimiento[0]->ProcesoId);
    $ContadorCheckUser = 0;
    $Id = $this->db->CreateSeguimiento($this->MAPToCreateSeguimiento($Seguimiento, $u->Firma));
    $Url = $this->BuildUrl($u->Email, $Seguimiento[0]->ProcesoId, $u->UsuarioId);
    $Verificador = $this->db->GetOrdenByVerificadorId($Seguimiento[0]->DevolverVerificadorId);
    $OrdenParaDevolver = $Verificador[0]->Orden;
    if (count($Id) > 0) {
      // Notificamos al solicitante
      $Solicitante = $Hu->GetUsuarioById($p->SolicitanteId);
      $Helper = new EmpresaBLL();
      $EmpresaObj = $Helper->GetEmpresa();
      $hs = new sendMail();
      $VB = $Seguimiento[0]->VB ? "Aprobado" : "Rechazado";
      $Contenido = '<p>El Sr(a) ' . $u->NombreCompleto . ' ha revisado el proceso:</p>
                            <br>
                            <div style="text-align:justify">
                                <p>Puedes revisar el progreso haciendo clic en el siguiente enlace <a href="' . $Url . '">' . $Url . '</a></p>
                            </div>
                            <br>
                            <br>
                            <small>.</small>';

      if ($Seguimiento[0]->EstadoProceso === 'Devolver') {

        $f = $this->GetFlujoTrabajoById($Seguimiento[0]->FlujoTrabajoId);
        $orden_inicial = $f[0]->Orden;
        //                    $Usuariolst = $v->GetUsuarioByFlujoTrabajoId($f_Principal[0]->FlujoTrabajoId);
        $Usuariolst = $v->GetUsuarioByFlujoTrabajoId($Seguimiento[0]->FlujoTrabajoId);
        $ContadorCheckUser = count($Usuariolst) > 1 ? 0 : 1;
        foreach ($Usuariolst as $Usuario) {
          $s = $this->db->GetSeguimientoByUsuarioId($Usuario->UsuarioId, $Seguimiento[0]->FlujoTrabajoId, $f[0]->Orden, $Seguimiento[0]->ProcesoId);
          if (count($s) > 0) {
            $ContadorCheckUser++;
          }
        }
        $Usuarios = $v->GetUsuarioByFlujoTrabajoId($Verificador[0]->FlujoTrabajoId);
        foreach ($Usuarios as $Usuario) {
          $hs = new sendMail();
          $Url = $this->BuildUrl($Usuario->Email, $Seguimiento[0]->ProcesoId, $Usuario->UsuarioId);
          $Contenido2 = '<p>El Sr(a) ' . $u->NombreCompleto . 'te ha DEVUELTO el proceso:</p>
                            <br>
                            <div style="text-align:justify">
                                <p>Puedes revisar el progreso haciendo clic en el siguiente enlace <a href="' . $Url . '">' . $Url . '</a></p>
                            </div>
                            <br>
                            <br>
                            <small>.</small>';
          $hs->EnviarEmail_Notificacion($EmpresaObj, "Te han DEVUELTO", $Contenido2, $Usuario->Email, $Usuario->NombreCompleto);
        }
        $this->db->UpdateProcesos($this->MAPToChangeOrden($OrdenParaDevolver, $Seguimiento[0]->CreatedBy), $Seguimiento[0]->ProcesoId);
        $Url = $this->BuildUrl($Solicitante->Email, $Seguimiento[0]->ProcesoId, $Solicitante->UsuarioId);
        $Contenido = '<p>El Sr(a) ' . $u->NombreCompleto . ' ha revisado el proceso:</p>
                            <br>
                            <div style="text-align:justify">
                                <p>Puedes revisar el progreso haciendo clic en el siguiente enlace <a href="' . $Url . '">' . $Url . '</a></p>
                            </div>
                            <br>
                            <br>
                            <small>.</small>';
        $hs = new sendMail();
        $hs->EnviarEmail_Notificacion($EmpresaObj, "Te han DEVUELTO -- Procesos CLD", $Contenido, $Solicitante->Email, $Solicitante->NombreCompleto);
      }
    }
    return $Id;
  }

  public function UpdateProcesos($list, $id)
  {
    return $this->db->UpdateProcesos($this->MAPToArray2($list), $id);
  }

  public function ReanudarProceso($Proceso)
  {
    $Hu = new UsuarioBLL();
    $v = new VerificadorBLL();
    // Notificamos al solicitante
    $Helper = new EmpresaBLL();
    $EmpresaObj = $Helper->GetEmpresa();
    $hs = new sendMail();
    $f_Principal = $this->GetFlujoTrabajoByOrden($Proceso[0]->OrdenEnCurso, $Proceso[0]->ProtocoloId);
    //        $Usuario = $Hu->GetUsuarioById($f_Principal[0]->VerificadorId);
    $Usuarios = $v->GetUsuarioByFlujoTrabajoId($f_Principal[0]->FlujoTrabajoId);

    foreach ($Usuarios as $Usuario) {
      $Url = $this->BuildUrl($Usuario->Email, $Proceso[0]->ProcesoId, $Usuario->UsuarioId);
      $Contenido = '<p>El proceso ' . $Proceso[0]->Nombre . ' ha solicitado reanudar el proceso:</p>
                            <br>
                            <div style="text-align:justify">
                                <p>El proceso ha sido Reanudado, puedes revisarlo.</p>
                                <p>Puedes revisar el progreso haciendo clic en el siguiente enlace <a href="' . $Url . '">' . $Url . '</a></p>
                            </div>
                            <br>
                            <br>';
      $hs->EnviarEmail_Notificacion($EmpresaObj, "El proceso " . $Proceso[0]->Nombre . " ha sido reanudado", $Contenido, $Usuario->Email, $Usuario->NombreCompleto);
    }
    $this->db->UpdateProcesos($this->MAPToChangeOrden($Proceso[0]->OrdenEnCurso, $Proceso[0]->ModifiedBy, 'Activo'), $Proceso[0]->ProcesoId);
    return $this->db->UpdateProcesos($this->MAPToReanudarProceso($Proceso), $Proceso[0]->ProcesoId);
  }

  public function MAPToArray($list)
  {
    $list2 = array();
    for ($index = 0; $index < count($list); $index++) {
      array_push($list2, array(
        'SedeId' => $list[$index]->SedeId,
        'ServicioId' => $list[$index]->ServicioId,
        'Nombre' => $list[$index]->Nombre,
        'SolicitanteId' => $list[$index]->SolicitanteId,
        'ProtocoloId' => $list[$index]->ProtocoloId,
        'DatosFormulario' => $list[$index]->DatosFormulario,
        'CreatedBy' => $list[$index]->CreatedBy
      ));
    }
    return $list2;
  }

  public function MAPToCreateSeguimiento($list, $Firma)
  {
    $list2 = array();
    for ($index = 0; $index < count($list); $index++) {
      array_push($list2, array(
        'ProcesoId' => $list[$index]->ProcesoId,
        'FlujoTrabajoId' => $list[$index]->FlujoTrabajoId,
        'VB' => $list[$index]->VB,
        'DatosAnexos' => $list[$index]->DatosAnexos,
        'Observacion' => $list[$index]->Observacion,
        'VerificadorId' => $list[$index]->VerificadorId,
        'FirmaVerificador' => $Firma,
        'Estado' => $list[$index]->EstadoProceso,
        'CreatedBy' => $list[$index]->CreatedBy
      ));
    }
    return $list2;
  }

  public function MAPToArray2($list)
  {
    $list2 = array();
    for ($index = 0; $index < count($list); $index++) {

      array_push($list2, array(
        'Nombre' => $list[$index]->Nombre,
        'DatosFormulario' => $list[$index]->DatosFormulario,
        'ModifiedBy' => $list[$index]->ModifiedBy,
        'ModifiedAt' => $this->getDatetimeNow(),
      ));
    }
    return $list2;
  }

  public function MAPToChangeOrden($OrdenEnCurso, $ModifiedBy, $Estado = 'Activo')
  {
    $list2 = array();
    array_push($list2, array(
      'OrdenEnCurso' => $OrdenEnCurso,
      'Estado' => $Estado,
      'ModifiedBy' => $ModifiedBy,
      'ModifiedAt' => $this->getDatetimeNow(),
    ));
    return $list2;
  }

  public function MAPToReanudarProceso($list)
  {
    $list2 = array();
    for ($index = 0; $index < count($list); $index++) {
      array_push($list2, array(
        'Estado' => $list[$index]->Estado,
        'ModifiedBy' => $list[$index]->ModifiedBy,
        'ModifiedAt' => $this->getDatetimeNow(),
      ));
    }
    return $list2;
  }

  private function BuildUrl($Email, $ProcesoId, $UsuarioId)
  {
    $Security = new Security();
    $token = $Security->GenerateToken_PC($Email, "Biomedico_123458", $ProcesoId, $UsuarioId, 10, []);
    $Url = "http://190.131.221.26:8080/Polivalente/#/ver_proceso/$token";
    return $Url;
  }

  function getDatetimeNow()
  {
    $tz_object = new DateTimeZone('America/Bogota');
    //date_default_timezone_set('Brazil/East');

    $datetime = new DateTime();
    $datetime->setTimezone($tz_object);
    return $datetime->format('Y\-m\-d\ h:i:s');
  }
}

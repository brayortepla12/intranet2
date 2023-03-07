<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

class PDF
{
  private $mpdf;

  public function __construct(array $options = NULL)
  {
    $this->mpdf = $options ? new \Mpdf\Mpdf($options) : new \Mpdf\Mpdf();
    // [
    //   'fontDir' => array_merge([dirname(__FILE__) . "/"])
    // ]
  }

  private function GetHojaPreSolicitud(object $PreSolicitud) : string
  {
    $H1 = file_get_contents(__DIR__ . '/formatos/SolicitudViatico.html');
    $H1 = str_replace('{{ Fecha }}', $PreSolicitud->Fecha, $H1);
    $H1 = str_replace('{{ Sede }}', $PreSolicitud->Sede, $H1);
    $H1 = str_replace('{{ ANombreDe }}', "{$PreSolicitud->ResPrimerNombre} {$PreSolicitud->ResPrimerApellido}", $H1);
    $H1 = str_replace('{{ Cedula }}', $PreSolicitud->ResCedula, $H1);
    $H1 = str_replace('{{ Cargo }}', $PreSolicitud->ResCargo, $H1);
    $H1 = str_replace('{{ DescripcionSolicitud }}', $PreSolicitud->DescripcionSolicitud, $H1);
    $H1 = str_replace('{{ Origen }}', "{$PreSolicitud->DepartamentoOrigen} {$PreSolicitud->MunicipioOrigen}", $H1);
    $H1 = str_replace('{{ Destino }}', "{$PreSolicitud->DepartamentoDestino} {$PreSolicitud->MunicipioDestino}", $H1);
    return $H1;
  }

  private function GetHojaCompSolicitud(object $Solicitud, int $Consecutivo, array $Conceptos, bool $IsBorrador) : string
  {
    $style = " 
    z-index: -1;
    background-image: url('http://190.131.221.26:8080/Polivalente/ImagenesPDF/borrador.png') !important;
    background-size: contain;";
    $H1 = file_get_contents(__DIR__ . '/formatos/AutorizacionViatico.html');
    $FirmaVacia = 'http://190.131.221.26:8080/Polivalente/ImagenesPDF/firma-blank.jpg';
    $H1 = str_replace('{{ SolicitudId }}', property_exists($Solicitud, 'SolicitudId') ? $Solicitud->SolicitudId :  '--', $H1);
    $H1 = str_replace('{{ Consecutivo }}', str_pad($Consecutivo, 6, "0", STR_PAD_LEFT), $H1);
    $H1 = str_replace('{{ Fecha }}', $Solicitud->Fecha, $H1);
    $H1 = str_replace('{{ Sede }}', $Solicitud->Sede, $H1);
    $H1 = str_replace('{{ ANombreDe }}', "{$Solicitud->ResPrimerNombre} {$Solicitud->ResPrimerApellido}", $H1);
    $H1 = str_replace('{{ Cedula }}', $Solicitud->ResCedula, $H1);
    $H1 = str_replace('{{ Cargo }}', $Solicitud->ResCargo, $H1);
    $H1 = str_replace('{{ DescripcionSolicitud }}', $Solicitud->DescripcionSolicitud, $H1);
    $H1 = str_replace('{{ Observaciones }}', $Solicitud->Observacion, $H1);
    $H1 = str_replace('{{ TipoSolicitud }}', $Solicitud->TipoSolicitud, $H1);
    $H1 = str_replace('{{ Conceptos }}', $this->GenerateConceptos($Conceptos), $H1);
    $H1 = str_replace('{{ Total }}', $this->GetTotalConceptos($Conceptos), $H1);
    if ($Solicitud->TipoSolicitud === "PASAJE") {
      $H1 = str_replace('{{ Gastos }}', "http://190.131.221.26:8080/Polivalente/ImagenesPDF/uncheck.png", $H1);
      $H1 = str_replace('{{ Pasaje }}', "http://190.131.221.26:8080/Polivalente/ImagenesPDF/check.png", $H1);
    } else {
      $H1 = str_replace('{{ Gastos }}', "http://190.131.221.26:8080/Polivalente/ImagenesPDF/check.png", $H1);
      $H1 = str_replace('{{ Pasaje }}', "http://190.131.221.26:8080/Polivalente/ImagenesPDF/uncheck.png", $H1);
    }
    $H1 = str_replace('{{ Origen }}', "{$Solicitud->DepartamentoOrigen} {$Solicitud->MunicipioOrigen}", $H1);
    $H1 = str_replace('{{ Destino }}', "{$Solicitud->DepartamentoDestino} {$Solicitud->MunicipioDestino}", $H1);
    if ($IsBorrador) {
      $H1 = str_replace('{{ borrador }}', $style, $H1); # mensaje de borrador
      $H1 = str_replace('{{ ResFirma }}', $FirmaVacia, $H1);
      $H1 = str_replace('{{ NombreVB }}', '--', $H1);
      $H1 = str_replace('{{ CargoVB }}', '--', $H1);
      $H1 = str_replace('{{ FirmaVB }}', $FirmaVacia, $H1);
    } else {
      $H1 = str_replace('{{ borrador }}', '', $H1);
      $H1 = str_replace('{{ ResFirma }}', $Solicitud->ResFirma, $H1);
      $H1 = str_replace('{{ NombreVB }}', "{$Solicitud->GHPrimerNombre} {$Solicitud->GHPrimerApellido}", $H1);
      $H1 = str_replace('{{ CargoVB }}', $Solicitud->GHCargo, $H1);
      $H1 = str_replace('{{ FirmaVB }}', $Solicitud->GHFirma, $H1);
    }
    return $H1;
  }
  /**
    * Undocumented function
    *
    * @param LegalizacionDto $Legalizacion
    * @param ConceptoLegDto[] $Conceptos
    * @param boolean $IsBorrador
    * @return string
    */
  private function GetHojaLegalizacionN(object $Legalizacion, array $Conceptos, bool $IsBorrador) : string
  {
    $H1 = file_get_contents(__DIR__ . '/formatos/LegalizacionN.html');
    $FirmaVacia = 'http://190.131.221.26:8080/Polivalente/ImagenesPDF/firma-blank.jpg';
    $H1 = str_replace('{{ LegalizacionId }}', $Legalizacion->LegalizacionId, $H1);
    $H1 = str_replace('{{ Fecha }}', $Legalizacion->Fecha, $H1);
    $H1 = str_replace('{{ Descripcion }}', $Legalizacion->Descripcion, $H1);
    $H1 = str_replace('{{ NC }}', $Legalizacion->NC, $H1);
    $H1 = str_replace('{{ RC }}', $Legalizacion->RC, $H1);
    $H1 = str_replace('{{ DL }}', $Legalizacion->DL, $H1);
    $H1 = str_replace('{{ Conceptos }}', $this->GenerateConceptosLegN($Conceptos), $H1);
    $H1 = str_replace('{{ TotalEgreso }}', $this->GetTotalConceptos($Conceptos), $H1);
    $H1 = str_replace('{{ ResNombres }}', "{$Legalizacion->ResPrimerNombre} {$Legalizacion->ResPrimerApellido}", $H1);
    $H1 = str_replace('{{ ResCedula }}', $Legalizacion->ResCedula, $H1);
    $H1 = str_replace('{{ ResCargo }}', $Legalizacion->ResCargo, $H1);
    $H1 = str_replace('{{ ResFirma }}', $Legalizacion->ResFirma, $H1);
    $H1 = str_replace('{{ CINombres }}', "{$Legalizacion->CIPrimerNombre} {$Legalizacion->CIPrimerApellido}", $H1);
    $H1 = str_replace('{{ CICargo }}', $Legalizacion->CICargo, $H1);
    $H1 = str_replace('{{ CIFirma }}', $Legalizacion->CIFirma ? $Legalizacion->CIFirma : $FirmaVacia, $H1);
    $H1 = str_replace('{{ ContNombres }}', "{$Legalizacion->ContPrimerNombre} {$Legalizacion->ContPrimerApellido}", $H1);
    $H1 = str_replace('{{ ContCargo }}', $Legalizacion->ContCargo, $H1);
    $H1 = str_replace('{{ ContFirma }}', $Legalizacion->ContFirma ? $Legalizacion->ContFirma : $FirmaVacia, $H1);
    return $H1;
  }

  /**
    * Undocumented function
    *
    * @param LegalizacionDto $Legalizacion
    * @param ConceptoLegDto[] $Conceptos
    * @param boolean $IsBorrador
    * @return string
    */
  private function GetHojaLegalizacionAguachica(object $Legalizacion, array $Conceptos, bool $IsBorrador) : string
  {
    $H1 = file_get_contents(__DIR__ . '/formatos/LegalizacionAguachica.html');
    $FirmaVacia = 'http://190.131.221.26:8080/Polivalente/ImagenesPDF/firma-blank.jpg';
    $H1 = str_replace('{{ LegalizacionId }}', $Legalizacion->LegalizacionId, $H1);
    $H1 = str_replace('{{ Fecha }}', $Legalizacion->Fecha, $H1);
    $H1 = str_replace('{{ Descripcion }}', $Legalizacion->Descripcion, $H1);
    $H1 = str_replace('{{ NC }}', $Legalizacion->NC, $H1);
    $H1 = str_replace('{{ RC }}', $Legalizacion->RC, $H1);
    $H1 = str_replace('{{ DL }}', $Legalizacion->DL, $H1);
    $H1 = str_replace('{{ Conceptos }}', $this->GenerateConceptosLegAguachica($Conceptos), $H1);
    $H1 = str_replace('{{ TotalEgreso }}', $this->GetTotalConceptos($Conceptos), $H1);
    $H1 = str_replace('{{ ResNombres }}', "{$Legalizacion->ResPrimerNombre} {$Legalizacion->ResPrimerApellido}", $H1);
    $H1 = str_replace('{{ ResCedula }}', $Legalizacion->ResCedula, $H1);
    $H1 = str_replace('{{ ResCargo }}', $Legalizacion->ResCargo, $H1);
    $H1 = str_replace('{{ ResFirma }}', $Legalizacion->ResFirma, $H1);
    $H1 = str_replace('{{ CINombres }}', "{$Legalizacion->CIPrimerNombre} {$Legalizacion->CIPrimerApellido}", $H1);
    $H1 = str_replace('{{ CICargo }}', $Legalizacion->CICargo, $H1);
    $H1 = str_replace('{{ CIFirma }}', $Legalizacion->CIFirma ? $Legalizacion->CIFirma : $FirmaVacia, $H1);
    $H1 = str_replace('{{ GerNombres }}', "{$Legalizacion->GerPrimerNombre} {$Legalizacion->GerPrimerApellido}", $H1);
    $H1 = str_replace('{{ GerCargo }}', $Legalizacion->GerCargo, $H1);
    $H1 = str_replace('{{ GerFirma }}', $Legalizacion->GerFirma ? $Legalizacion->GerFirma : $FirmaVacia, $H1);
    $H1 = str_replace('{{ ContNombres }}', "{$Legalizacion->ContPrimerNombre} {$Legalizacion->ContPrimerApellido}", $H1);
    $H1 = str_replace('{{ ContCargo }}', $Legalizacion->ContCargo, $H1);
    $H1 = str_replace('{{ ContFirma }}', $Legalizacion->ContFirma ? $Legalizacion->ContFirma : $FirmaVacia, $H1);
    $H1 = str_replace('{{ TesNombres }}', "{$Legalizacion->TesPrimerNombre} {$Legalizacion->TesPrimerApellido}", $H1);
    $H1 = str_replace('{{ TesCargo }}', $Legalizacion->TesCargo, $H1);
    $H1 = str_replace('{{ TesFirma }}', $Legalizacion->TesFirma ? $Legalizacion->TesFirma : $FirmaVacia, $H1);
    return $H1;
  }

  public function getAutorizacionViaticos(object $Solicitud, ?array $Analisis, bool $show): ?String
  {
    $PIEPAGINA = "<div class='row'>
          <div class='col-sm-3'>
              <p class='texto-pie-pagina'>
                  <strong>ELABORACIÓN</strong><br>
                  MARIA DAZA Y MARIA ORTIZ<br>
                  ANALISTA DE CONTROL INTERNO Y <br>
                  ANALISTA DE CALIDAD
              </p>
          </div>
          <div class='col-sm-3'>
              <p class='texto-pie-pagina'>
                  <strong>REVISIÓN TECNICA</strong><br>
                  CARLOS MENA MEDINA<br>
                  COORD. DE CONTROL INTERNO
              </p>
          </div>
          <div class='col-sm-3'>
              <p class='texto-pie-pagina'>
                  <strong>REVISIÓN ESTRUCTURAL</strong><br>
                  KEYLA PINEDA VALERA<br>
                  COORD. DE ASEG. DE LA CALIDAD
              </p>
          </div>
          <div class='col-sm-3'>
              <p class='texto-pie-pagina'>
                  <strong>APROBACIÓN</strong><br>
                  MARIA MORILLO DAZA<br>
                  GERENTE
              </p>
          </div>
      </div>";
    $H1 = $this->GetHojaSolicitud($Solicitud);
    $this->mpdf->fontdata['Candara'] = [
      'R' => "Candara.ttf",
      'B' => "Candara.ttf",
    ];
    $this->mpdf->SetDefaultFont('Candara');
    $this->mpdf->WriteHTML($H1);
    $this->mpdf->SetHTMLFooter($PIEPAGINA);
    if ($Analisis) {
      $H2 = $this->GetHojaAnalisis($Analisis);
      $this->mpdf->AddPage();
      $this->mpdf->WriteHTML($H2);
      $this->mpdf->SetHTMLFooter($PIEPAGINA);
    }
    if ($show) {
      $this->mpdf->Output();
      return NULL;
    } else {
      return $this->mpdf->Output('', 'S');
    }
  }

  public function GetPreSolicitud(object $PreSolicitud, bool $show): ?String
  {
    $H1 = $this->GetHojaPreSolicitud($PreSolicitud);
    $this->mpdf->fontdata['Candara'] = [
      'R' => "Candara.ttf",
      'B' => "Candara.ttf",
    ];
    $this->mpdf->SetDefaultFont('Candara');
    $this->mpdf->WriteHTML($H1);
    // $this->mpdf->SetHTMLFooter($PIEPAGINA);
    if ($show) {
      $this->mpdf->Output();
      return NULL;
    } else {
      return $this->mpdf->Output('', 'S');
    }
  }

  public function GetCompSolicitud(object $CompSolicitud, int $Consecutivo, array $Conceptos, bool $IsBorrador, bool $show): ?String
  {
    $H1 = $this->GetHojaCompSolicitud($CompSolicitud, $Consecutivo, $Conceptos, $IsBorrador);
    $this->mpdf->fontdata['Candara'] = [
      'R' => "Candara.ttf",
      'B' => "Candara.ttf",
    ];
    $this->mpdf->SetDefaultFont('Candara');
    $this->mpdf->WriteHTML($H1);
    // $this->mpdf->SetHTMLFooter($PIEPAGINA);
    if ($show) {
      $this->mpdf->Output();
      return NULL;
    } else {
      return $this->mpdf->Output('', 'S');
    }
  }

  public function GetLegalizacion(object $legalizacion, array $Conceptos, bool $IsBorrador, bool $show)
  {
    $Hcss = file_get_contents(__DIR__ . '/formatos/style.css');
    $PIEPAGINA = "<div class='row'>
          <div class='col-sm-3'>
              <p class='texto-pie-pagina'>
                  <strong>ELABORACIÓN</strong><br>
                  MARIA DAZA Y MARIA ORTIZ<br>
                  ANALISTA DE CONTROL INTERNO Y <br>
                  ANALISTA DE CALIDAD
              </p>
          </div>
          <div class='col-sm-3'>
              <p class='texto-pie-pagina'>
                  <strong>REVISIÓN TECNICA</strong><br>
                  CARLOS MENA MEDINA<br>
                  COORD. DE CONTROL INTERNO
              </p>
          </div>
          <div class='col-sm-3'>
              <p class='texto-pie-pagina'>
                  <strong>REVISIÓN ESTRUCTURAL</strong><br>
                  KEYLA PINEDA VALERA<br>
                  COORD. DE ASEG. DE LA CALIDAD
              </p>
          </div>
          <div class='col-sm-3'>
              <p class='texto-pie-pagina'>
                  <strong>APROBACIÓN</strong><br>
                  MARIA MORILLO DAZA<br>
                  GERENTE
              </p>
          </div>
      </div>";
    $H1 = $legalizacion->TipoLegalizacion === 'Normal' ? $this->GetHojaLegalizacionN($legalizacion, $Conceptos, $IsBorrador) : $this->GetHojaLegalizacionAguachica($legalizacion, $Conceptos, $IsBorrador);
    $this->mpdf->fontdata['Candara'] = [
      'R' => "Candara.ttf",
      'B' => "Candara.ttf",
    ];
    $this->mpdf->SetDefaultFont('Candara');
    
    $this->mpdf->WriteHTML($Hcss,\Mpdf\HTMLParserMode::HEADER_CSS);
    $this->mpdf->WriteHTML($H1,\Mpdf\HTMLParserMode::DEFAULT_MODE);
    $this->mpdf->SetHTMLFooter($PIEPAGINA);
    if ($show) {
      $this->mpdf->Output();
      return NULL;
    } else {
      return $this->mpdf->Output('', 'S');
    }
  }

  private function GenerateConceptos(array $Conceptos) : string
  {
    $SConceptos = "";
    foreach ($Conceptos as $c) {
      if ($c->Valor != 0) {
        $legaliza = $c->Legalizable == 1 ? "check" : "uncheck";
        $SConceptos .= "<tr>
          <td colspan='4'>{$c->Concepto}</td>
          <td>{$c->Dias}</td>
          <td>
            <img src='http://190.131.221.26:8080/Polivalente/ImagenesPDF/{$legaliza}.png' width='20' alt=''>
          </td>
          <td>{$c->Valor}</td>
        </tr>";
      }
    }
    return $SConceptos;
  }

  /**
   * Generar Conceptos Leg N
   *
   * @param ConceptoLegDto $Conceptos
   * @return string
   */
  private function GenerateConceptosLegN(array $Conceptos) : string
  {
    $SConceptos = "";
    foreach ($Conceptos as $c) {
      if ($c->Valor != 0) {
        $SConceptos .= "<tr>
          <td>{$c->Fecha}</td>
          <td>{$c->Factura}</td>
          <td colspan='2'>{$c->Responsable}</td>
          <td colspan='4'>{$c->Concepto}</td>
          <td>{$c->Valor}</td>
        </tr>";
      }
    }
    return $SConceptos;
  }

  /**
   * Generar Conceptos Leg N
   *
   * @param ConceptoLegDto $Conceptos
   * @return string
   */
  private function GenerateConceptosLegAguachica(array $Conceptos) : string
  {
    $SConceptos = "";
    foreach ($Conceptos as $c) {
      if ($c->Valor != 0) {
        $SConceptos .= "<tr>
          <td>{$c->Fecha}</td>
          <td>{$c->NombresPaciente}</td>
          <td>{$c->Origen}</td>
          <td>{$c->Destino}</td>
          <td>{$c->Factura}</td>
          <td>{$c->Responsable}</td>
          <td>{$c->Tripulacion}</td>
          <td>{$c->Concepto}</td>
          <td>{$c->Valor}</td>
        </tr>";
      }
    }
    return $SConceptos;
  }

  private function GetTotalConceptos(array $Conceptos) : float
  {
    $TConceptos = 0;
    foreach ($Conceptos as $c) {
      if ($c->Valor != 0) {
        $TConceptos += $c->Valor;
      }
    }
    return $TConceptos;
  }
}

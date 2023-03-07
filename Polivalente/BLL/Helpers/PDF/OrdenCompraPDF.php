<?php
require_once('PDF.php');
class OrdenCompraPDF extends PDF
{
  public function __construct(array $options = NULL)
  {
    $this->mpdf = $options ? new \Mpdf\Mpdf($options) : new \Mpdf\Mpdf();
  }
  public function getOrdenCompra(object $ordenCompra, array $detalles, bool $show): ?String
  {
    $h1 = $this->generateOrdenCompra($ordenCompra, $detalles);
    $this->mpdf->fontdata['Candara'] = [
      'R' => "Candara.ttf",
      'B' => "Candara.ttf",
    ];
    $this->mpdf->SetDefaultFont('Candara');
    $this->mpdf->WriteHTML($h1);
    // $this->mpdf->SetHTMLFooter($PIEPAGINA);
    if ($show) {
      $this->mpdf->Output();
      return NULL;
    } else {
      return $this->mpdf->Output('', 'S');
    }
  }

  private function generateOrdenCompra(object $ordenCompra, array $detalles) : string
  {
    $datetime = new DateTime($ordenCompra->Fecha);
    $h1 = file_get_contents(__DIR__ . '/formatos/AutorizacionViatico.html');
    $FirmaVacia = 'http://190.131.221.26:8080/Polivalente/ImagenesPDF/firma-blank.jpg';
    $h1 = str_replace('{{ OrdenCompraId }}', property_exists($ordenCompra, 'OrdenCompraId') ? $ordenCompra->SolicitudId :  '--', $h1);
    $h1 = str_replace('{{ Consecutivo }}', str_pad($ordenCompra->Consecutivo, 5, "0", STR_PAD_LEFT), $h1);
    $h1 = str_replace('{{ Fecha }}', $datetime->format('d/m/Y'), $h1);
    $h1 = str_replace('{{ NumeroCotizacion }}', $ordenCompra->NumeroCotizacion, $h1);
    $h1 = str_replace('{{ NombreEmpresa }}', $ordenCompra->NombreEmpresa, $h1);
    $h1 = str_replace('{{ NitEmpresa }}', $ordenCompra->NitEmpresa, $h1);
    $h1 = str_replace('{{ DireccionEmpresa }}', $ordenCompra->DireccionEmpresa, $h1);
    $h1 = str_replace('{{ TelefonoEmpresa }}', $ordenCompra->TelefonoEmpresa, $h1);
    $h1 = str_replace('{{ EnvNombre }}', $ordenCompra->EnvNombre, $h1);
    $h1 = str_replace('{{ EnvEmpresa }}', $ordenCompra->EnvEmpresa, $h1);
    $h1 = str_replace('{{ EnvDireccion }}', $ordenCompra->EnvDireccion, $h1);
    $h1 = str_replace('{{ EnvCiudad }}', $ordenCompra->EnvCiudad, $h1);
    $h1 = str_replace('{{ EnvTel }}', $ordenCompra->EnvTel, $h1);
    $h1 = str_replace('{{ Sede }}', $ordenCompra->Sede, $h1);
    $h1 = str_replace('{{ Servicio }}', $ordenCompra->Servicio, $h1);
    $h1 = str_replace('{{ FormaPago }}', $ordenCompra->FormaPago, $h1);
    $h1 = str_replace('{{ Observacion }}', $ordenCompra->Observacion, $h1);
    $h1 = str_replace('{{ SubTotal }}', $ordenCompra->SubTotal, $h1);
    $h1 = str_replace('{{ Iva }}', $ordenCompra->Iva, $h1);
    $h1 = str_replace('{{ Envio }}', $ordenCompra->Envio, $h1);
    $h1 = str_replace('{{ Otro }}', $ordenCompra->Otro, $h1);
    $h1 = str_replace('{{ Total }}', $ordenCompra->Total, $h1);
    
    if (!property_exists($ordenCompra, 'NombreElaborado')) {
      $h1 = str_replace('{{ NombreElaborado }}', '--', $h1);
      $h1 = str_replace('{{ CargoElaborado }}', '--', $h1);
      $h1 = str_replace('{{ FirmaElaborado }}', $FirmaVacia, $h1);
    } else {
      $h1 = str_replace('{{ NombreElaborado }}', $ordenCompra->NombreElaborado, $h1);
      $h1 = str_replace('{{ CargoElaborado }}', $ordenCompra->CargoElaborado, $h1);
      $h1 = str_replace('{{ FirmaElaborado }}', $ordenCompra->NombreElaborado, $h1);
    }
    if (!property_exists($ordenCompra, 'NombreRevisado')) {
      $h1 = str_replace('{{ NombreRevisado }}', '--', $h1);
      $h1 = str_replace('{{ CargoRevisado }}', '--', $h1);
      $h1 = str_replace('{{ FirmaRevisado }}', $FirmaVacia, $h1);
    } else {
      $h1 = str_replace('{{ NombreRevisado }}', $ordenCompra->NombreRevisado, $h1);
      $h1 = str_replace('{{ CargoRevisado }}', $ordenCompra->CargoRevisado, $h1);
      $h1 = str_replace('{{ FirmaRevisado }}', $ordenCompra->NombreRevisado, $h1);
    }
    if (!property_exists($ordenCompra, 'NombreAprobado')) {
      $h1 = str_replace('{{ NombreAprobado }}', '--', $h1);
      $h1 = str_replace('{{ CargoAprobado }}', '--', $h1);
      $h1 = str_replace('{{ FirmaAprobado }}', $FirmaVacia, $h1);
    } else {
      $h1 = str_replace('{{ NombreAprobado }}', $ordenCompra->NombreAprobado, $h1);
      $h1 = str_replace('{{ CargoAprobado }}', $ordenCompra->CargoAprobado, $h1);
      $h1 = str_replace('{{ FirmaAprobado }}', $ordenCompra->NombreAprobado, $h1);
    }
    return $h1;
  }
}

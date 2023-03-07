<?php
class LegalizacionDto{
  public $SolicitudId;
  public $ResPersonaId;
  public $ResPrimerNombre;
  public $ResSegundoNombre;
  public $ResPrimerApellido;
  public $ResSegundoApellido;
  public $ResCelular;
  public $ResCorreo;
  public $ResCedula;
  public $ResCargo;
  public $ResFirma;
  public $AprobacionCI;
  public $FechaCI;
  public $CIPersonaId;
  public $CIPrimerNombre;
  public $CISegundoNombre;
  public $CIPrimerApellido;
  public $CISegundoApellido;
  public $CICelular;
  public $CICargo;
  public $CIFirma;
  public $AprobacionCont;
  public $FechaCont;
  public $ContPersonaId;
  public $ContPrimerNombre;
  public $ContSegundoNombre;
  public $ContPrimerApellido;
  public $ContSegundoApellido;
  public $ContCelular;
  public $ContCargo;
  public $ContFirma;
  public $ProcesoId;
  public $Descripcion;
  public $OrdenEncurso;
  public $TipoLegalizacion;
  /**
   * Conceptos
   *
   * @var ConceptoLegDto
   */
  public $Conceptos;
  public $NC;
  public $RC;
  public $DL;
  public $CreatedBy;
  public $CreatedAt;
}


class ConceptoLegDto{
  public $LegalizacionId;
  public $Fecha;
  public $Factura;
  public $Responsable;
  /**
   * Archivo
   *
   * @var FileDto
   */
  public $Anexo;
  public $Concepto;
  public $Valor;
  public $CreatedBy;
  public $CreatedAt;
}

class FileDto{
  public $lastModified;
  public $lastModifiedDate;
  public $name;
  public $size;
  public $type;
  public $data;
}
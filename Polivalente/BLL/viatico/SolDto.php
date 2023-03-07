<?php
class SolDto
{
  public $SedeId;
  public $Sede;
  public $Fecha;
  public $UsuarioSolicitaId;
  public $NombreSolicita;
  public $CargoSolicita;
  public $ResCedula;
  public $ResPrimerNombre;
  public $ResSegundoNombre;
  public $ResPrimerApellido;
  public $ResSegundoApellido;
  public $ResCelular;
  public $ResCargo;
  public $DepartamentoOrigen;
  public $MunicipioOrigen;
  public $DepartamentoOrigenId;
  public $MunicipioOrigenId;
  public $DepartamentoDestino;
  public $MunicipioDestino;
  public $DepartamentoDestinoId;
  public $MunicipioDestinoId;
  public $DescripcionSolicitud;
  /**
   * Array de conceptos
   *
   * @var array ConceptoDto
   */
  public $Conceptos;
  public $TipoSolicitud;
  public $Observacion;
  public $CreatedBy;
  public $CreatedAt;
  public $PreSolicitudId;
  public $ProcesoId;
  public $OrdenEnCurso;
}

class ConceptoDto
{
  public $ConceptoId;
  public $Concepto;
  public $SolicitudId;
  public $Dia;
  public $Valor;
  public $Legalizable;
  public $CreatedBy;
  public $CreatedAt;
}


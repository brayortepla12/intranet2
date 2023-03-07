app.controller('SolicitudCtrl', ["$scope", "$rootScope", "SolicitudService", "ServicioService", "SedeService", "$state", "SesionService", "$filter",
  "HojaVidaService", "ReporteService", "$controller",
  function ($scope, $rootScope, SolicitudService, ServicioService, SedeService, $state, SesionService, $filter, HojaVidaService, ReporteService, $controller) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    let vm = $scope
    let popup = {}
    vm.cargarEvento = false
    vm.EventoData = []
    vm.PREFIJO = ""
    vm.IsSaving = false
    vm.cargado = false
    vm.Servicios = []
    vm.Servicios2 = []
    vm.Sedes = []
    vm.Equipos = []
    vm.ServicioId = "--"
    vm.SedeId = "--"
    vm.sol = "--"
    vm.solicitud = {
      SedeId: "--",
      ServicioId: "--",
      EquipoId: "--",
      EquipoOtro: "",
      HasNotEquipo: 0,
      TipoSolicitud: '',
      UsuarioSolicitaId: SesionService.get("UserData_Polivalente").UserId,
      NombreUsuarioSolicita: SesionService.get("UserData_Polivalente").NombreCompleto,
      CargoUsuarioSolicita: SesionService.get("UserData_Polivalente").Cargo,
      Fecha: moment().format("YYYY-MM-DD"),
      Ubicacion: "",
      Foto: "",
      Descripcion: "",
      IsVisto: 0,
      CreatedBy: SesionService.get("UserData_Polivalente").NombreCompleto
    }
    vm.FotoEquipo = ""
    vm.simpleTableOptions = {}
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.Guardar = function () {
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
      } else if (vm.solicitud.SedeId == "--") {
        swal("Error", "Debe seleccionar una sede", "error")
      } else if (vm.solicitud.ServicioId == "--") {
        swal("Error", "Debe seleccionar un servicio", "error")
      } else if (vm.solicitud.SolicitanteId == "--") {
        swal("Error", "Solicitante es requerido", "error")
      } else {
        var obj = {
          SolicitudPolivalente: JSON.stringify([vm.solicitud]),
          PREFIJO: vm.PREFIJO
        }
        if (!vm.IsSaving) {
          vm.IsSaving = true
          SolicitudService.postSolicitud(obj).then(function (d) {
            if (typeof d.data != "string") {
              swal("Enhorabuena!", 'Se han guardado los datos satisfactoriamente, se ha enviado un mensaje de texto a los tecnicos encargados', "success")
              vm.Reset()
              // enviar notificacion RT al SERVER
              //                        vm.SendMensaje("Actualizate")
              vm.IsSaving = false
            } else {
              swal("Error", d.data, "error")
              vm.IsSaving = false
            }
          }, function (e) {
            vm.IsSaving = false
            swal("Error", e, "error")
          })
        }
      }
    }

    vm.ViewItem = function (i) {
      vm.ResetParcial()
      SolicitudService.GetSolicitudPolById(vm.simpleTableOptions.data[i].SolicitudId, vm.PREFIJO).then(function (d) {
        if (d.data.length > 0) {
          vm.Solicitud = d.data[0]
          $('#ModalViewSolicitud').modal('show')
        }
      }).then(() => {
        GetEventos(vm.simpleTableOptions.data[i].SolicitudId)
      })
    }
    vm.NuevaNota = (i) => {
      const url = '/intranet-2/#/create-nota-solicitud'
      const strWindowFeatures = "location=no,height=800,width=1000,scrollbars=yes,status=yes"
      popup = window.open(url, 'Crear Nota', strWindowFeatures)
      popup.SOL = vm.simpleTableOptions.data[i]
      popup.TIPO = 'SOL'
      popup.PREFIJO = vm.PREFIJO
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="consultas">
    function GetEventos(_SolicitudId) {
      vm.cargarEvento = false
      SolicitudService.GetEventoBySolicitudId_Pol(_SolicitudId, vm.PREFIJO).then(function (d) {
        vm.EventoData = {
          data: [],
          aoColumns: [{
              mData: 'EventoSolicitudId'
            },
            {
              mData: 'FechaEvento'
            },
            {
              mData: 'NombreBreveEvento'
            },
            {
              mData: 'NombreUsuario'
            },
            {
              mData: 'TecnicoResponsable'
            },
            {
              mData: 'Descripcion'
            }
          ],
          "searching": true,
          "iDisplayLength": 25,
          "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": " No hay Items Registrados ",
            "infoFiltered": "(filtro de _MAX_ registros totales)",
            "search": " Filtrar : ",
            "oPaginate": {
              "sPrevious": "Anterior",
              "sNext": "Siguiente"
            }
          },
          "aaSorting": []
        }
        vm.EventoData.data = d.data
        vm.cargarEvento = true
      })
    }

    function GetServicio() {
      ServicioService.getServicioBySedeWithTA(vm.solicitud.SedeId, SesionService.get("UserData_Polivalente").UserId, vm.PREFIJO).then(function (c) {
        vm.Servicios = c.data
        vm.solicitud.ServicioId = "--"
        vm.ResetParcial()
      })
    }

    function GetSede() {
      SedeService.getAllSedeByUserId_TA(SesionService.get("UserData_Polivalente").UserId, vm.PREFIJO).then(function (c) {
        vm.Sedes = c.data
        if (c.data.length == 1) {
          vm.solicitud.SedeId = c.data[0].SedeId
          vm.ChangeSede()
        }
      })
    }

    function GetSolicitudesByUsuario() {
      vm.cargado = false
      SolicitudService.GetSolicitudesPolByUsuario(SesionService.get("UserData_Polivalente").UserId, vm.PREFIJO).then(function (d) {
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [{
              mData: 'SolicitudId'
            },
            {
              mData: 'Sede'
            },
            {
              mData: 'Servicio'
            },
            {
              mData: 'Descripcion'
            },
            {
              mData: 'FechaSolicitud'
            },
            {
              mData: 'Estado'
            },
            {
              mData: 'Opciones'
            }
          ],
          "searching": true,
          //                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
          //                "oTableTools": {
          //                    "aButtons": [
          //                        "xls", "pdf"
          //                    ],
          //                    "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
          //                },
          "iDisplayLength": 25,
          "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": " No hay Items Registrados ",
            "infoFiltered": "(filtro de _MAX_ registros totales)",
            "search": " Filtrar : ",
            "oPaginate": {
              "sPrevious": "Anterior",
              "sNext": "Siguiente"
            }
          },
          "aaSorting": []
        }
        vm.simpleTableOptions.data = SetFormat(d.data)
        vm.cargado = true
      })
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function SetFormat(lst) {
      for (var i in lst) {
        if (lst[i].Estado === "Activo") {
          lst[i].Opciones = '<a style="cursor:pointer" class="btn btn-info btn-xs" onclick=\"angular.element(this).scope().ViewItem(' + i + ')\"><i class="fa fa-info-circle"></i></a>'
        }
        if (lst[i].IsFinalizada === 1) {
          lst[i].Estado = "Finalizada"
        }
        lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Ver Notas" onclick=\"angular.element(this).scope().NuevaNota(' + i + ')\"><i class="fa fa-sticky-note"></i></a>'
      }
      return lst
    }
    vm.ChangeSede = function () {
      for (let i in vm.Sedes) {
        if (vm.Sedes[i].SedeId == vm.solicitud.SedeId) {
          vm.solicitud.Sede = vm.Sedes[i].Nombre
          break
        }
      }
      GetServicio()
    }
    vm.GetSolicitudesByUsuario = () => {
      GetSede()
      GetSolicitudesByUsuario()
    }
    vm.ResetParcial = function () {
      vm.sol = "--"
      vm.solicitud.Marca = ""
      vm.solicitud.Ubicacion = ""
      vm.solicitud.Serie = ""
      vm.solicitud.Modelo = ""
      vm.solicitud.Inventario = ""
      vm.solicitud.EquipoId = ""
      vm.FotoEquipo = ""
    }
    vm.Reset = function () {
      vm.cargado = false
      vm.Servicios = []
      vm.Servicios2 = []
      vm.Sedes = []
      vm.Equipos = []
      vm.ServicioId = "--"
      vm.SedeId = "--"
      vm.sol = "--"
      vm.solicitud = {
        ServicioId: "--",
        EquipoId: "--",
        UsuarioSolicitaId: SesionService.get("UserData_Polivalente").UserId,
        NombreUsuarioSolicita: SesionService.get("UserData_Polivalente").NombreCompleto,
        CargoUsuarioSolicita: SesionService.get("UserData_Polivalente").Cargo,
        Fecha: moment().format("YYYY-MM-DD"),
        Ubicacion: "",
        Foto: "",
        EquipoOtro: "",
        TipoSolicitud: '',
        HasNotEquipo: 0,
        Descripcion: "",
        IsVisto: 0,
        CreatedBy: SesionService.get("UserData_Polivalente").NombreCompleto
      }
      vm.FotoEquipo = ""
      vm.simpleTableOptions = {}
      _init()
      $('#ModalCrearSolicitud').modal('hide')
    }
    vm.SetPrefijo = (prefijo) => {
      vm.PREFIJO = prefijo
    }
    // WATCHERS
    vm.$on('fileSelected', (e, f) => {
      console.log(f)
      vm.solicitud.Archivo = f
    })
    window.notify = function () {
      popup = {}
      GetSolicitudesByUsuario()
    }
    function _init() {
      let tipo = 'pol'
      switch ($state.current.name) {
        case "solicitud.solicitud-pol":
          tipo = 'pol'
          break
        case "solicitud.solicitud-sis":
          tipo = 'sistemas'
          break
        case "solicitud.solicitud-bio":
          tipo = 'biomedicos'
          break
      }
      vm.PREFIJO = tipo
      //            GetServicio(tipo)
      GetSede()
      GetSolicitudesByUsuario()
    }
    _init()
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })
  }
])
app.controller('SolicitudAdminCtrl', ["$scope", "$rootScope", "$state", "SolicitudService", "$cacheFactory", "SesionService",
  function ($scope, $rootScope, $state, SolicitudService, $cacheFactory, SesionService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    var vm = $scope
    $rootScope.IsCentral = false
    vm.PREFIJO = ""
    $rootScope.PREFIJO = ""
    vm.Mes = moment().format('M')
    vm.Year = moment().format('YYYY')
    vm.Proceso = {}
    vm.OrdenP = null
    vm.PedidoAlmacen = {}
    vm.PedidoSRepuesto = {}
    vm.SOLICITUD = null
    vm.Orden = 0
    vm.Filtro = "Activo"
    var no_leidos = function (data, type, full) {
      if (full.IsVisto == 0 && vm.Filtro == 'Activo') {
        return `<strong>${data}</strong>`
      } else {
        return data
      }
    }
    $rootScope.View = false
    vm.cargado = false
    vm.NActivos = 0
    vm.simpleTableOptions = null
    vm.cargarEvento = false
    vm.EventoData = null
    vm.NewEvento = false
    vm.MODAL = false
    vm.MODAL_VER = false
    vm.REPORTEID = null
    vm.REPORTEEXTERNOID = null
    vm.PEDIDOALMACENID = false
    vm.PEDIDOSREPUESTOID = false
    vm.PROCESOID = false
    vm.TIPOPEDIDO = "Almacen"
    let popup = {}
    vm.ES = {}
    // ONs
    vm.$on('Close-Evento', () => {
      $rootScope.View = false
      GetSolicitudes()
    })
    // EVENTOS
    vm.atras = function () {
      $rootScope.View = false
      vm.cargado = true
    }
    vm.ViewItem = function (i) {
      vm.ResetParcial()
      // if (vm.simpleTableOptions.data[i].Estado === 'Activo') {
      //   CambiarEstadoALeido(vm.simpleTableOptions.data[i].SolicitudId)
      // }
      $rootScope.$broadcast('view-solicitud', {Sol: vm.simpleTableOptions.data[i], PREFIJO: vm.PREFIJO})
      $rootScope.View = true
    }
    vm.ConsultarSolicitudes = () => {
      GetSolicitudes()
    }
    vm.NuevoProceso = (i) => {
      const url = '/intranet-2/#/proceso-solicitud'
      const strWindowFeatures = "location=no,height=600,width=600,scrollbars=yes,status=yes"
      popup = window.open(url, 'Crear Proceso', strWindowFeatures)
      const obj = JSON.stringify({
        Solicitud: vm.simpleTableOptions.data[i],
        PREFIJO: vm.PREFIJO,
      })
      popup.SOL = obj
      popup.EventoSolicitudId = null
    }
    vm.NuevaNota = (i) => {
      const url = '/intranet-2/#/create-nota-solicitud'
      const strWindowFeatures = "location=no,height=800,width=1000,scrollbars=yes,status=yes"
      popup = window.open(url, 'Crear Nota', strWindowFeatures)
      popup.SOL = vm.simpleTableOptions.data[i]
      popup.TIPO = 'SOL-admin'
      popup.PREFIJO = vm.PREFIJO
    }
    // CONSULTAS
    function GetSolicitudes() {
      vm.cargado = false
      SolicitudService.GetAllSolicitudesPol(SesionService.get("UserData_Polivalente").Key, vm.PREFIJO, vm.Mes, vm.Year).then(function (d) {
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [{
              mData: 'SolicitudId',
              "mRender": no_leidos
            },
            {
              mData: 'NombreUsuarioSolicita',
              "mRender": no_leidos
            },
            {
              mData: 'Sede',
              "mRender": no_leidos
            },
            {
              mData: 'Servicio',
              "mRender": no_leidos
            },
            {
              mData: 'Descripcion',
              "mRender": no_leidos
            },
            {
              mData: 'FechaSolicitud',
              "mRender": no_leidos
            },
            {
              mData: 'Estado',
              "mRender": no_leidos
            },
            {
              mData: 'Opciones'
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
        vm.simpleTableOptions.data = SetFormat(d.data)
        vm.cargado = true
      })
    }
    // HELPERS
    function CambiarEstadoALeido(SolicitudId) {
      let obj = {
        EventoSolicitudPol: JSON.stringify({
          SolicitudId: SolicitudId,
          IsVisto: 0, // para que cambie ele stado la primera vez
          Pedido2_0Id: null,
          PedidoId: null,
          PedidoFarmaciaId: null,
          ReporteId: null,
          ReporteExternoId: null,
          ProcesoId: null,
          UsuarioEventoId: SesionService.get("UserData_Polivalente").UserId,
          NombreUsuario: SesionService.get("UserData_Polivalente").NombreCompleto,
          NombreBreveEvento: "Fue Revisado"
        }),
        PREFIJO: vm.PREFIJO
      }
      SolicitudService.postSolicitud(obj).then(function (d) {
        GetSolicitudes()
      })
    }
    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones = '<a style="cursor:pointer" class="btn btn-success btn-xs" onclick=\"angular.element(this).scope().ViewItem(' + i + ')\"><i class="fa fa-info-circle"></i></a>'
        if (!lst[i].ProcesoId) {
          lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Iniciar Proceso" onclick=\"angular.element(this).scope().NuevoProceso(' + i + ')\"><i class="fa fa-file"></i></a>'
        } else {
          lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Crear Nota" onclick=\"angular.element(this).scope().NuevaNota(' + i + ')\"><i class="fa fa-sticky-note"></i></a>'
        }
        if (lst[i].IsFinalizada === 1) {
          lst[i].Estado = "Finalizada"
        } else if (lst[i].ESTADOSOLICITUD) {
          lst[i].Estado = lst[i].ESTADOSOLICITUD
        }
      }
      return lst
    }
    vm.ResetParcial = function () {
      $rootScope.Solicitud = {
        SedeId: "--",
        ServicioId: "--",
        EquipoId: "--",
        SolicitanteId: "",
        Solicitante: "",
        Ubicacion: "",
        Foto: "",
        FotoEquipo: "",
        Descripcion: "",
      }
    }
    function _init() {
      let tipo = 'pol'
      switch ($state.current.name) {
        case "solicitud.solicitudAdmin-pol":
          tipo = 'pol'
          break
        case "solicitud.solicitudAdmin-sis":
          tipo = 'sistemas'
          break
        case "solicitud.solicitudAdmin-bio":
          tipo = 'biomedicos'
          break
      }
      vm.PREFIJO = tipo
      $rootScope.PREFIJO = tipo
      GetSolicitudes()
    }
    window.notify = function () {
      popup = {}
      GetSolicitudes()
    }
    _init()
    vm.$on('$destroy', function () {
      const cacheObject = $cacheFactory.get('Solicitud-mto') || $cacheFactory("Solicitud-mto")
      cacheObject.destroy()
      vm = null
      $scope = null
    })
  }
])
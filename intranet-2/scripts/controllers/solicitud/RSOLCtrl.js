app.controller('RSOLCtrl', ["$scope", "$rootScope", "SolicitudService", "SOLFactory", "$cacheFactory", "SesionService",
  function ($scope, $rootScope, SolicitudService, SOLFactory, $cacheFactory, SesionService) {
    // VARIABLES
    let vm = $scope
    vm.Sol = SOLFactory.data.Sol
    vm.PREFIJO = SOLFactory.data.PREFIJO
    const cacheObject = $cacheFactory.get('Solicitud-mto') || $cacheFactory("Solicitud-mto")
    vm.RC = false
    vm.LR = false
    vm.CS = false
    vm.Reportes = cacheObject.get('Reportes' + vm.Sol.SolicitudId) || []
    let popup = {}
    // ONs
    vm.$on('reload-reportes', () => {
      GetReportes()
    })
    vm.$on('Close-Evento', () => {
      vm.RC = false
      vm.LR = false
      vm.CS = false
    })
    // EVENTOS
    vm.CerrarSolicitud = () => {
      vm.CS = true
    }
    vm.Atras = () => {
      vm.CS = false
    }
    vm.NuevoReporte = () => {
      const strWindowFeatures = "location=yes,height=800,width=1600,scrollbars=yes,status=yes"
      const url = '/intranet-2/#/vsolicitud'
      popup = window.open(url, 'Crear Reporte', strWindowFeatures)
      const obj = JSON.stringify({
        Solicitud: SOLFactory.data.Sol,
        PREFIJO: SOLFactory.data.PREFIJO,
      })
      popup.SOL = obj
    }
    vm.ViewReporte = (i) => {
      const strWindowFeatures = "location=yes,height=800,width=1600,scrollbars=yes,status=yes"
      const url = '/intranet-2/#/vsolicitud'
      popup = window.open(url, 'Ver Reporte', strWindowFeatures)
      popup.ReporteId = vm.Reportes.data[i].ReporteId
      popup.PREFIJO = SOLFactory.data.PREFIJO
    }
    vm.NuevoProceso = (i) => {
      const url = '/intranet-2/#/proceso-solicitud'
      const strWindowFeatures = "location=yes,height=600,width=600,scrollbars=yes,status=yes"
      popup = window.open(url, 'Crear Proceso', strWindowFeatures)
      const obj = JSON.stringify({
        Solicitud: SOLFactory.data.Sol,
        PREFIJO: SOLFactory.data.PREFIJO,
      })
      popup.SOL = obj
      popup.EventoSolicitudId = vm.Reportes.data[i].EventoSolicitudId
    }
    vm.GetReportes = () => {
      vm.LR = false
      GetReportes()
    }
    // CONSULTAS
    function GetReportes() {
      vm.RC = false
      vm.Reportes = {}
      SolicitudService.GetReporteBySolicitudId(vm.Sol.SolicitudId, vm.PREFIJO).then(r => {
        vm.Reportes = {
          data: [],
          aoColumns: [{
              mData: 'ReporteId'
            },
            {
              mData: 'Ubicacion'
            },
            {
              mData: 'TipoServicio'
            },
            {
              mData: 'Solicitante'
            },
            {
              mData: 'Equipo'
            },
            {
              mData: 'Responsable'
            },
            {
              mData: 'CreatedAt'
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
        vm.Reportes.data = Array.isArray(r.data) ? format(r.data) : []
        cacheObject.put('Reportes' + vm.Sol.SolicitudId, vm.Reportes)
        vm.RC = true
      })
    }
    // HELPERS
    function format(lst) {
      for (var i in lst) {
        lst[i].Opciones = '<a style="cursor:pointer" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Ver Rerporte" onclick=\"angular.element(this).scope().ViewReporte(' + i + ')\"><i class="fa fa-info-circle"></i></a>'
        if (!lst[i].ProcesoId) {
          lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-success btn-xs" data-toggle="tooltip" title="Iniciar Proceso" onclick=\"angular.element(this).scope().NuevoProceso(' + i + ')\"><i class="fa fa-file"></i></a>'
        }
      }
      return lst
    }
    // EVENTO VENTANA
    window.notify = function () {
      popup = {}
      GetReportes()
    }
    function _init_(){
      if (cacheObject.get('Reportes' + vm.Sol.SolicitudId)) {
        vm.RC = true
        vm.LR = true
        return false
      }
      GetReportes()
    }
    _init_()
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })
  }])
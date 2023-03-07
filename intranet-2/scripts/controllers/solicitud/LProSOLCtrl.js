app.controller('LProSOLCtrl', ["$scope", "$rootScope", "SolicitudService", "SOLFactory", "$cacheFactory", "SesionService",
  function ($scope, $rootScope, SolicitudService, SOLFactory, $cacheFactory, SesionService) {
    // VARIABLES
    let vm = $scope
    vm.Sol = SOLFactory.data.Sol
    vm.PREFIJO = SOLFactory.data.PREFIJO
    const cacheObject = $cacheFactory.get('Solicitud-mto') || $cacheFactory("Solicitud-mto")
    vm.LP = false
    vm.RLP = false
    vm.Procesos = cacheObject.get('Procesos' + vm.Sol.SolicitudId) || []
    let popup = {}
    // EVENTOS
    vm.GetProcesos = () => {
      vm.RLP = false
      GetProcesos()
    }
    vm.ViewProceso = (i) => {
      const obj = {
        SolicitudId: vm.Sol.SolicitudId,
        ProcesoId: vm.Procesos.data[i].ProcesoId,
        PREFIJO: vm.PREFIJO
      }
      const strWindowFeatures = "location=yes,height=800,width=1600,scrollbars=yes,status=yes"
      const url = '/intranet-2/#/ver-proceso-solicitud'
      popup = window.open(url, 'Ver Proceso', strWindowFeatures)
      popup.ProSOL = obj
    }
    // CONSULTAS
    function GetProcesos(){
      vm.LP = false
      vm.Procesos = {}
      SolicitudService.GetProcesosBySolicitudId(vm.Sol.SolicitudId, vm.PREFIJO).then(r => {
        vm.Procesos = {
          data: [],
          aoColumns: [{
              mData: 'ProcesoId'
            },
            {
              mData: 'Protocolo'
            },
            {
              mData: 'Nombre'
            },
            {
              mData: 'PerEnTurno'
            },
            {
              mData: 'CreatedAt'
            },
            {
              mData: 'Estado'
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
        vm.Procesos.data = Array.isArray(r.data) ? format(r.data) : []
        cacheObject.put('Procesos' + vm.Sol.SolicitudId, vm.Procesos)
        vm.LP = true
      })
    }
    // HELPERS
    function format(lst) {
      for (var i in lst) {
        lst[i].Opciones = '<a style="cursor:pointer" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Ver Proceso" onclick=\"angular.element(this).scope().ViewProceso(' + i + ')\"><i class="fa fa-info-circle"></i></a>'
      }
      return lst
    }
    // EVENTO VENTANA
    window.notify = function () {
      popup = {}
      GetProcesos()
    }
    function _init_(){
      if (cacheObject.get('Procesos' + vm.Sol.SolicitudId)) {
        vm.LP = true
        vm.RLP = true
        return false
      }
      GetProcesos()
    }
    _init_()
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })
  }])
app.controller('GenerarSolicitudViaticosCrtl', ["$scope", "$rootScope", "ViaticoService", "SedeService", "SesionService",
function ($scope, $rootScope, ViaticoService, SedeService, SesionService) {
  //<editor-fold defaultstate="collapsed" desc="Variables">
  let vm = $scope
  let popup = {}
  vm.ViaticosDATA = {}
  //</editor-fold>
  //<editor-fold defaultstate="collapsed" desc="Botones">

  vm.VerSolicitud = function (i) {
    const url = '/Polivalente/api/viatico/Viatico.php?SolicitudId_pdf=' + vm.ViaticosDATA.data[i].SolicitudId
    const strWindowFeatures = "location=no,height=1000,width=1200,scrollbars=yes,status=yes"
    popup = window.open(url, 'Completar Solicitud viatico', strWindowFeatures)
  }
  vm.CompletarSolicitud = function (i) {
    const url = '/intranet-2/#/completar-solicitud-viatico'
    const strWindowFeatures = "location=no,height=1000,width=1200,scrollbars=yes,status=yes"
    popup = window.open(url, 'Completar Solicitud viatico', strWindowFeatures)
    popup.PreSolicitudId = vm.ViaticosDATA.data[i].PreSolicitudId
  }
  vm.NuevaSolicitudCompleta = () => {
    const url = '/intranet-2/#/completar-solicitud-viatico'
    const strWindowFeatures = "location=no,height=1000,width=1200,scrollbars=yes,status=yes"
    popup = window.open(url, 'Crear Solicitud viatico - completa', strWindowFeatures)
    popup.PreSolicitudId = null
  }
  vm.ChangeIsExterno = () => {
    vm.Sol.ResIsExterno = !vm.Sol.ResIsExterno
  }
  //</editor-fold>
  //<editor-fold defaultstate="collapsed" desc="consultas">
  function GetPresolicitudes() {
    vm.cargado = false
    ViaticoService.GetSolicitudesPendientes(1, 2021).then(function (d) {
      vm.ViaticosDATA = {
        data: [],
        aoColumns: [{
            mData: 'PreSolicitudId'
          },
          {
            mData: 'Fecha'
          },
          {
            mData: 'Sede'
          },
          {
            mData: 'ANombreDe'
          },
          {
            mData: 'Origen'
          },
          {
            mData: 'Destino'
          },
          {
            mData: 'DescripcionSolicitud'
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
      vm.ViaticosDATA.data = SetFormat(d.data.data)
      vm.cargado = true
    })
  }
  //</editor-fold>
  //<editor-fold defaultstate="collapsed" desc="Helpers">
  function SetFormat(lst) {
    for (var i in lst) {
      lst[i].Opciones = ''
      if (lst[i].Estado === 'Pre-Solicitud') {
        lst[i].Opciones = '<a style="cursor:pointer" class="btn btn-warning btn-xs" onclick=\"angular.element(this).scope().CompletarSolicitud(' + i + ')\">Completar</a>'
      }
      lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-info btn-xs" onclick=\"angular.element(this).scope().VerSolicitud(' + i + ')\">Ver Solicitud</a>'
      // lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Ver Notas" onclick=\"angular.element(this).scope().NuevaNota(' + i + ')\"><i class="fa fa-sticky-note"></i></a>'
    }
    return lst
  }
  vm.GetPresolicitudes = () => {
    GetPresolicitudes()
  }
  window.notify = function (msg) {
    popup = {}
    swal("Enhorabuena", msg, 'success')
    GetPresolicitudes()
  }
  function _init() {
    GetPresolicitudes()
  }
  _init()
  vm.$on('$destroy', function () {
    vm = null
    $scope = null
  })
}
])
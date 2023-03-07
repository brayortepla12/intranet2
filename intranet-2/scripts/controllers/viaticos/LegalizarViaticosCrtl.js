app.controller('LegalizarViaticosCrtl', ["$scope", "$rootScope", "$state", "LegalizacionService", "SesionService",
  function ($scope, $rootScope, $state, LegalizacionService, SesionService) {
    // VARIABLES
    let vm = $scope
    const u = SesionService.get("UserData_Polivalente")
    if (u != undefined) {
      $rootScope.username = u
    }
    vm.Tipo = $state.current.name === "viatico.LegalizacionViaticos" ? 'Normal' : 'Aguachica';
    let popup = {}
    vm.LT = false
    vm.LegalizacionDATA = {}
    const DATE = moment()
    // EVENTOS
    vm.VerAnexos = (i) => {
      const url = '/intranet-2/#/anexos-legalizacion'
      const strWindowFeatures = "location=no,height=1000,width=1200,scrollbars=yes,status=yes"
      popup = window.open(url, 'Crear Solicitud viatico - completa', strWindowFeatures)
      popup.LegalizacionId = vm.LegalizacionDATA.data[i].LegalizacionId
    }
    vm.VerSolicitud = function (i) {
      const url = '/Polivalente/api/viatico/Viatico.php?SolicitudId_pdf=' + vm.LegalizacionDATA.data[i].SolicitudId
      const strWindowFeatures = "location=no,height=1000,width=1200,scrollbars=yes,status=yes"
      popup = window.open(url, 'Ver Solicitud viatico', strWindowFeatures)
    }
    vm.LegalizarSolicitud = function (i) {
      const url = '/intranet-2/#/legalizar-solicitud-viatico'
      const strWindowFeatures = "location=no,height=1000,width=1400,scrollbars=yes,status=yes"
      popup = window.open(url, 'legalizar Solicitud viatico', strWindowFeatures)
      popup.SolicitudId = vm.LegalizacionDATA.data[i].SolicitudId
      popup.ANombreDe = vm.LegalizacionDATA.data[i].ANombreDe
      popup.ResCedula = vm.LegalizacionDATA.data[i].ResCedula
      popup.ResPersonaId = vm.LegalizacionDATA.data[i].ResPersonaId
      popup.ProcesoId = vm.LegalizacionDATA.data[i].ProcesoId
      popup.OrdenEncurso = vm.LegalizacionDATA.data[i].OrdenEncurso
      popup.TipoLegalizacion = vm.Tipo
    }
    vm.NuevaLegalizacion = () => {
      const url = '/intranet-2/#/legalizar-solicitud-viatico'
      const strWindowFeatures = "location=no,height=1000,width=1400,scrollbars=yes,status=yes"
      popup = window.open(url, 'legalizar Solicitud viatico', strWindowFeatures)
      popup.TipoLegalizacion = vm.Tipo
    }
    vm.VerLegalizacion = (i) => {
      const url = '/Polivalente/api/viatico/Legalizacion.php?LegalizacionId_pdf=' + vm.LegalizacionDATA.data[i].LegalizacionId
      const strWindowFeatures = "location=no,height=1000,width=1200,scrollbars=yes,status=yes"
      popup = window.open(url, 'Ver legalización viatico', strWindowFeatures)
    }
    // CONSULTAS
    function getSolicitudesPendienteLegalizacion() {
      vm.LT = false
      LegalizacionService.getSolicitudesPendienteLegalizacion(DATE.format('M'), DATE.format('YYYY'), vm.Tipo, $rootScope.username.UserId).then(r => {
        vm.LegalizacionDATA = {
          data: [],
          aoColumns: [{
              mData: 'LegalizacionId'
            },
            {
              mData: 'Fecha'
            },
            {
              mData: 'ANombreDe'
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
        vm.LegalizacionDATA.data = SetFormat(r.data.data)
        vm.LT = true
      })
    }
    // HELPERS
    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones = ''
        if (lst[i].Estado === 'Pendiente Por Legalizar') {
          lst[i].Opciones = '<a style="cursor:pointer" class="btn btn-warning btn-xs" onclick=\"angular.element(this).scope().LegalizarSolicitud(' + i + ')\">Legalizar</a>'
        }
        if (lst[i].LegalizacionId) {
          lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-default btn-xs" onclick=\"angular.element(this).scope().VerLegalizacion(' + i + ')\">Ver Legalicación</a>'
          lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-primary btn-outline btn-xs" onclick=\"angular.element(this).scope().VerAnexos(' + i + ')\">Ver Anexos</a>'
        }
        if (lst[i].SolicitudId) {
          lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-info btn-xs" onclick=\"angular.element(this).scope().VerSolicitud(' + i + ')\">Ver Solicitud</a>'
        }
      }
      return lst
    }
    window.notify = function (msg) {
      popup = {}
      swal("Enhorabuena", msg, 'success')
      getSolicitudesPendienteLegalizacion()
    }

    function __init__() {
      getSolicitudesPendienteLegalizacion()
    }
    
    __init__()
  }
])
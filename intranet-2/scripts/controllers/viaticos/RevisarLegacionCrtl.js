app.controller('RevisarLegalizacionCrtl', ["$scope", "$rootScope", "LegalizacionService", "SesionService",
  function ($scope, $rootScope, LegalizacionService, SesionService) {
    // VARIABLES
    let vm = $scope
    vm.IsLoading = false
    const u = SesionService.get("UserData_Polivalente")
    if (u != undefined) {
      $rootScope.username = u
    }
    let popup = {}
    vm.LT = false
    vm.LegalizacionDATA = {}
    const DATE = moment()
    // EVENTOS
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
    }
    vm.VerLegalizacion = (i) => {
      const url = '/Polivalente/api/viatico/Legalizacion.php?LegalizacionId_pdf=' + vm.LegalizacionDATA.data[i].LegalizacionId
      const strWindowFeatures = "location=no,height=1000,width=1200,scrollbars=yes,status=yes"
      popup = window.open(url, 'Ver legalización viatico', strWindowFeatures)
    }
    vm.Aprobar = (i) => {
      swal({
        title: "¿Deseas dar tu visto bueno?",
        text: "Nota: una vez dado el visto bueno, no se puede deshacer!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "SI",
        cancelButtonText: "NO",
        closeOnConfirm: false,
        closeOnCancel: true
      }, function (isConfirm) {
        if (isConfirm) {
          UpdateLegalizacion(vm.LegalizacionDATA.data[i])
        }
      });
    }
    vm.VerAnexos = (i) => {
      const url = '/intranet-2/#/anexos-legalizacion'
      const strWindowFeatures = "location=no,height=1000,width=1200,scrollbars=yes,status=yes"
      popup = window.open(url, 'Crear Solicitud viatico - completa', strWindowFeatures)
      popup.LegalizacionId = vm.LegalizacionDATA.data[i].LegalizacionId
    }
    function UpdateLegalizacion(Legalizacion){
      if (vm.IsLoading) {
        return false
      }
      vm.IsLoading = true
      const obj = {
        VistoBueno: JSON.stringify({
          LegalizacionId: Legalizacion.LegalizacionId,
          SolicitudId: Legalizacion.SolicitudId,
          ResCorreo: Legalizacion.ResCorreo,
          OrdenEncurso: Legalizacion.OrdenEncurso,
          PersonaId: $rootScope.username.PersonaId,
          Prefijo: Legalizacion.Prefijo,
          IsUltimo: Legalizacion.IsUltimo,
        })
      }
      LegalizacionService
        .put(obj)
        .then(function (d) {
          vm.IsLoading = false
          if (d.data.data) {
            swal("Enhorabuena!", d.data.data, "success");
            getLegalizaciones()
          } else {
            swal("Error", d.data.error, "error");
          }
        }).catch(e => { vm.IsLoading = false })
    }
    // CONSULTAS
    function getLegalizaciones() {
      vm.LT = false
      LegalizacionService
        .getLegalizacion(DATE.format('M'), DATE.format('YYYY'), $rootScope.username.UserId)
        .then(r => {
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
                mData: 'TLegalizar'
              },
              {
                mData: 'TLegalizado'
              },
              {
                mData: 'DL'
              },
              {
                mData: 'RC'
              },
              {
                mData: 'NC'
              },
              {
                mData: 'MensajeEstado'
              },
              {
                mData: 'IsTurnoDe'
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
        if (lst[i].LegalizacionId) {
          lst[i].Opciones = '<a style="cursor:pointer" class="btn btn-default btn-xs" onclick=\"angular.element(this).scope().VerLegalizacion(' + i + ')\">Ver Legalicación</a>'
        }
        if (lst[i].SolicitudId) {
          lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-info btn-xs" onclick=\"angular.element(this).scope().VerSolicitud(' + i + ')\">Ver Solicitud</a>'
        }
        if (lst[i].IsYouTurno && lst[i].Estado !== 'Finalizado') {
          lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-warning btn-xs" onclick=\"angular.element(this).scope().Aprobar(' + i + ')\">Aprobar</a>'
        }
        lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-primary btn-outline btn-xs" onclick=\"angular.element(this).scope().VerAnexos(' + i + ')\">Ver Anexos</a>'
      }
      return lst
    }
    window.notify = function (msg) {
      popup = {}
      swal("Enhorabuena", msg, 'success')
      getLegalizaciones()
    }

    function __init__() {
      getLegalizaciones()
    }
    
    __init__()
  }
])
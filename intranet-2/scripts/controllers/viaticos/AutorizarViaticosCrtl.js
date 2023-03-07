app.controller('AutorizarViaticosCrtl', ["$scope", "$rootScope", "ViaticoService", "SedeService", "SesionService",
  function ($scope, $rootScope, ViaticoService, SedeService, SesionService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    let vm = $scope
    let popup = {}
    vm.ViaticosDATA = {}
    vm.IsLoading = false
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">

    vm.VerSolicitud = function (i) {
      const url = '/Polivalente/api/viatico/Viatico.php?SolicitudId_pdf=' + vm.ViaticosDATA.data[i].SolicitudId
      const strWindowFeatures = "location=no,height=1000,width=1200,scrollbars=yes,status=yes"
      popup = window.open(url, 'Completar Solicitud viatico', strWindowFeatures)
    }
    vm.AutorizarSolicitud = function (i) {
      if (!vm.ViaticosDATA.data[i].IsUltimo) {
        vm.Autorizar(i)
      } else {
        const url = '/intranet-2/#/autorizar-solicitud-viatico'
        const strWindowFeatures = "location=no,height=300,width=400,scrollbars=yes,status=yes"
        popup = window.open(url, 'Autorizar Solicitud viatico', strWindowFeatures)
        popup.Solicitud = vm.ViaticosDATA.data[i]
      }
    }
    vm.Autorizar = (i) => {
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
          UpdateSolicitud(vm.ViaticosDATA.data[i])
        }
      });
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="consultas">
    function GetPresolicitudes() {
      vm.cargado = false
      ViaticoService.GetSolicitudesAutorizar(1, 2021).then(function (d) {
        vm.ViaticosDATA = {
          data: [],
          aoColumns: [{
              mData: 'SolicitudId'
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
              mData: 'MensajeEstado'
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
        if (lst[i].IsTuTurno && lst[i].Estado === 'Activo') {
          lst[i].Opciones = '<a style="cursor:pointer" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Autorizar Solicitud" onclick=\"angular.element(this).scope().AutorizarSolicitud(' + i + ')\">Autorizar</a>'
        }
        lst[i].Opciones += '<a style="cursor:pointer" class="btn btn-info btn-xs" data-toggle="tooltip" title="Ver Solicitud" onclick=\"angular.element(this).scope().VerSolicitud(' + i + ')\">Ver Solicitud</a>'
      }
      return lst
    }
    vm.GetPresolicitudes = () => {
      GetPresolicitudes()
    }
    function UpdateSolicitud(Solicitud){
      if (vm.IsLoading) {
        return false
      }
      vm.IsLoading = true
      const obj = {
        VistoBueno: JSON.stringify({
          SolicitudId: Solicitud.SolicitudId,
          ResCorreo: Solicitud.ResCorreo,
          OrdenEncurso: Solicitud.OrdenEncurso,
          PersonaId: $rootScope.username.PersonaId,
          Prefijo: Solicitud.Prefijo,
          IsUltimo: Solicitud.IsUltimo,
          ResFirma: Solicitud.ResFirma || null
        })
      }
      ViaticoService
        .put(obj)
        .then(function (d) {
          vm.IsLoading = false
          if (d.data.data) {
            swal("Enhorabuena!", d.data.data, "success");
            GetPresolicitudes()
          } else {
            swal("Error", d.data.error, "error");
          }
        }).catch(e => { vm.IsLoading = false })
    }
    window.notify = function (sol) {
      popup = {}
      const Solicitud = JSON.parse(sol)
      UpdateSolicitud(Solicitud)
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
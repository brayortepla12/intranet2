app.controller('PreSolicitudViaticosCrtl', ["$scope", "$rootScope", "ViaticoService", "SedeService", "SesionService",
  function ($scope, $rootScope, ViaticoService, SedeService, SesionService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    let vm = $scope
    let popup = {}
    vm.ViaticosDATA = {}
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.NuevaSolicitud = () => {
      const url = '/intranet-2/#/create-solicitud-viatico'
      const strWindowFeatures = "location=no,height=800,width=1000,scrollbars=yes,status=yes"
      popup = window.open(url, 'Crear Solicitud viatico', strWindowFeatures)
    }
    vm.VerSolicitud = function (i) {
      const url = '/Polivalente/api/viatico/Viatico.php?SolicitudId_pdf=' + vm.ViaticosDATA.data[i].SolicitudId
      const strWindowFeatures = "location=no,height=1000,width=1200,scrollbars=yes,status=yes"
      popup = window.open(url, 'Completar Solicitud viatico', strWindowFeatures)
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="consultas">
    function GetSolicitudesByUsuario() {
      vm.cargado = false
      ViaticoService.GetSolicitudesViaticoByUsuario(SesionService.get("UserData_Polivalente").UserId).then(function (d) {
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
        vm.ViaticosDATA.data = SetFormat(d.data.data)
        vm.cargado = true
      })
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones = ''
        if (lst[i].SolicitudId) {
          lst[i].Opciones = '<a style="cursor:pointer" class="btn btn-info btn-xs" onclick=\"angular.element(this).scope().VerSolicitud(' + i + ')\"><i class="fa fa-info-circle"></i></a>'
        }
      }
      return lst
    }
    vm.GetSolicitudesByUsuario = () => {
      GetSolicitudesByUsuario()
    }
    window.notify = function (msg) {
      popup = {}
      swal("Enhorabuena", msg, 'success')
      GetSolicitudesByUsuario()
    }
    function _init() {
      GetSolicitudesByUsuario()
    }
    _init()
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })
  }
])
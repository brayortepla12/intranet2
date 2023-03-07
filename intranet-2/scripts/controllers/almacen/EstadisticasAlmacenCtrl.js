app.controller('EstadisticasAlmacenCtrl', [
  '$scope',
  '$rootScope',
  '$filter',
  'PedidoAlmacenService',
  'ServicioService',
  'SedeService',
  function (
    $scope,
    $rootScope,
    $filter,
    PedidoAlmacenService,
    ServicioService,
    SedeService
  ) {
    $scope.hasta = null
    $scope.desde = null
    $scope.cargado = false
    $scope.dirigidoA = false
    $scope.simpleTableOptions = {}
    $scope.ServicioId = '--'
    $scope.SedeId = '--'
    $scope.tipoSolicitud = '--'
    $scope.TipoFormato = 'Almacen'

    //<editor-fold defaultstate="collapsed" desc="Botones">
    $scope.GenerarExcel = function () {
      $scope.tSolicitud = $scope.tipoSolicitud == 'repuesto' ? 1 : 0
      if (new Date($scope.desde) < new Date($scope.hasta)) {
        PedidoAlmacenService.GetFileExcel(
          $scope.desde,
          $scope.hasta,
          $scope.SedeId,
          $scope.ServicioId,
          $scope.TipoFormato,
          $scope.tSolicitud
        )
      } else {
        swal(
          'Error!!',
          'La fecha DESDE debe ser mayor que la de HASTA.',
          'error'
        )
      }
    }

    $scope.Consultar = function () {
      $scope.cargado = false
      $scope.simpleTableOptions = {}
      if (new Date($scope.desde) < new Date($scope.hasta)) {
        if ($scope.tipoSolicitud === 'pedidio') {
          $scope.dirigidoA = false
          PedidoAlmacenService.GetEstadisticaPedidos(
            $scope.desde,
            $scope.hasta,
            $scope.SedeId,
            $scope.ServicioId,
            $scope.TipoFormato
          ).then(function (d) {
            $scope.simpleTableOptions = {
              data: [],
              aoColumns: [
                { mData: 'CodigoKrystalos' },
                { mData: 'Nombre' },
                { mData: 'NombreSolicitante' },
                { mData: 'Sede' },
                { mData: 'Servicio' },
                { mData: 'FechaEntrega' },
                { mData: 'TotalEntregado' }
              ],
              searching: true,
              //                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
              //                "oTableTools": {
              //                    "aButtons": [
              //                        "xls", "pdf"
              //                    ],
              //                    "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
              //                },
              iDisplayLength: 25,
              language: {
                lengthMenu: 'Mostrar _MENU_ registros por página',
                zeroRecords: ' No hay Items Registrados ',
                infoFiltered: '(filtro de _MAX_ registros totales)',
                search: ' Filtrar : ',
                oPaginate: {
                  sPrevious: 'Anterior',
                  sNext: 'Siguiente'
                }
              },
              aaSorting: []
            }
            $scope.simpleTableOptions.data = d.data
            $scope.cargado = true
          })
        } else {
          $scope.dirigidoA = true
          PedidoAlmacenService.GetEstadisticaPedidosRepuestos(
            $scope.desde,
            $scope.hasta,
            $scope.SedeId,
            $scope.ServicioId,
            $scope.TipoFormato
          ).then(function (d) {
            $scope.simpleTableOptions = {
              data: [],
              aoColumns: [
                { mData: 'CodigoKrystalos' },
                { mData: 'Nombre' },
                { mData: 'NombreSolicitante' },
                { mData: 'Sede' },
                { mData: 'Servicio' },
                { mData: 'ServicioDirigido' },
                { mData: 'FechaEntrega' },
                { mData: 'TotalEntregado' }
              ],
              searching: true,
              //                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
              //                "oTableTools": {
              //                    "aButtons": [
              //                        "xls", "pdf"
              //                    ],
              //                    "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
              //                },
              iDisplayLength: 25,
              language: {
                lengthMenu: 'Mostrar _MENU_ registros por página',
                zeroRecords: ' No hay Items Registrados ',
                infoFiltered: '(filtro de _MAX_ registros totales)',
                search: ' Filtrar : ',
                oPaginate: {
                  sPrevious: 'Anterior',
                  sNext: 'Siguiente'
                }
              },
              aaSorting: []
            }
            $scope.simpleTableOptions.data = d.data
            $scope.cargado = true
          })
        }
      } else {
        swal(
          'Error!!',
          'La fecha DESDE debe ser mayor que la de HASTA.',
          'error'
        )
      }
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Eventos">
    $scope.ChangeEstadisticas = function () {}
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consultas">
    function GetServicio() {
      ServicioService.getServicioBySede($scope.SedeId).then(function (c) {
        $scope.Servicios = c.data
      })
    }
    $scope.ChangeSede = function () {
      $scope.ServicioId = null
      GetServicio()
      $scope.cargado = false
      $scope.simpleTableOptions = {}
    }
    $scope.ChangeServicios = function () {
      GetServicio()
    }

    function GetServicio() {
      ServicioService.getServicioByUserId($rootScope.username.UserId).then(
        function (c) {
          $scope.Servicios = $filter('orderBy')(
            $filter('filter')(c.data, { SedeId: $scope.SedeId }),
            'Nombre'
          )
          $scope.ServicioId = 'TODO'
        }
      )
    }
    function GetSede() {
      SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (
        c
      ) {
        $scope.Sedes = c.data
        $scope.SedeId = c.data[0].SedeId
        GetServicio()
      })
    }
    //</editor-fold>
    function _init_() {
      GetSede()
    }

    _init_()
  }
])

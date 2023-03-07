app.controller('SolicitudesPedidosAdminCtrl', [
  '$scope',
  '$state',
  '$rootScope',
  '$filter',
  'EmpresaService',
  'EncabezadoService',
  'PedidoAlmacenService',
  'ServicioService',
  'SedeService',
  function (
    $scope,
    $state,
    $rootScope,
    $filter,
    EmpresaService,
    EncabezadoService,
    PedidoAlmacenService,
    ServicioService,
    SedeService
  ) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    $scope.Filtro = 'Activo'
    var no_leidos = function (data, type, full) {
      if (full.IsLeido == 0 && $scope.Filtro == 'Activo') {
        return `<strong>${data}</strong>`
      } else {
        return data
      }
    }
    $scope.VerPedido = false
    $scope.ToPrint = false
    $scope.Usuario = $rootScope.username
    $scope.TipoFormato = 'Almacen'
    $scope.Empresa = {}
    $scope.PedidoUpdate = {
      SedeId: '--',
      ServicioId: '--',
      SolicitanteId: $scope.Usuario.UserId,
      NombreSolicitante: $scope.Usuario.NombreCompleto,
      CargoSolicitante: $scope.Usuario.Cargo,
      FechaSolicitud: new Date(),
      Items: [],
      FechaEntrega: '',
      FechaRecibe: '',
      Observacion: '',
      NombreEntrega: '',
      NombreRecibe: '',
      ModifiedBy: $scope.Usuario.NombreCompleto
    }
    $scope.accesorio = {
      Articulo: '',
      CantidadSolicitada: 0,
      CantidadEntregada: 0,
      Pendiente: ''
    }
    $scope.obj = {
      Articulo: '',
      CantidadSolicitada: 0,
      CantidadEntregada: 0,
      Pendiente: ''
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">
    $scope.UpdatePedido = function (e) {
      if (!$scope.Datos2.$valid) {
        angular
          .element("[name='" + $scope.Datos2.$name + "']")
          .find('.ng-invalid:visible:first')
          .focus()
      } else {
        var Estado = e == 'Activo' ? 'Actualizar' : e
        swal(
          {
            title: 'Desea ' + Estado + ' el pedido?',
            text: 'Nota: una vez hecho no se podran deshacer los cambios.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: Estado,
            cancelButtonText: 'Cancelar',
            closeOnConfirm: false,
            closeOnCancel: true
          },
          function (isConfirm) {
            if (isConfirm) {
              if (e == 'Entregar') {
                $scope.PedidoUpdate.NombreEntrega =
                  $rootScope.username.NombreUsuario
              } else if (e == 'Recibir') {
                $scope.PedidoUpdate.RecibeNombre =
                  $rootScope.username.NombreUsuario
              }
              $scope.PedidoUpdate.Estado = e
              $scope.PedidoUpdate.ModifiedBy = $rootScope.username.NombreUsuario
              var obj = {
                PedidoAlmacen_2: JSON.stringify($scope.PedidoUpdate)
              }

              PedidoAlmacenService.PutPedidoAlmacen(obj).then(function (d) {
                if (typeof d.data != 'string') {
                  swal(
                    'Enhorabuena',
                    'Se ha actualizado los datos con exito',
                    'success'
                  )
                  $scope.Atras()
                  GetPedidos()
                } else {
                  swal('Error', d.data, 'error')
                }
              })
            }
          }
        )
      }
    }
    $scope.Imprimir = function () {
      $scope.ToPrint = true
      setTimeout(function () {
        printDiv()
      }, 500)
    }
    $scope.DeletePedido = function (i) {
      swal(
        {
          title: 'Desea eliminar el pedido?',
          text: 'Nota: una vez eliminado no se podran deshacer los cambios.',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Eliminar',
          cancelButtonText: 'Cancelar',
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function (isConfirm) {
          if (isConfirm) {
            $scope.simpleTableOptions.data[i].ModifiedBy =
              $rootScope.username.NombreUsuario
            var obj = {
              PedidoAlmacenId: $scope.simpleTableOptions.data[i].PedidoAlmacenId
            }
            PedidoAlmacenService.DeletePedidoAlmacen(obj).then(function (d) {
              if (typeof d.data !== 'string') {
                swal(
                  'Enhorabuena',
                  'Se ha eliminado el pedido con exito',
                  'success'
                )
                GetPedidos()
              } else {
                swal('Error', d.data, 'error')
              }
            })
          }
        }
      )
    }
    $scope.ViewPedido = function (i) {
      $scope.PedidoUpdate = {}
      // Refrescamos la tabla
      $scope.simpleTableOptions.data[i].IsLeido = 1
      let tabla_data = angular.copy($scope.simpleTableOptions.data)
      $scope.cargado = false
      $scope.simpleTableOptions = {
        data: tabla_data,
        aoColumns: [
          { mData: 'PedidoAlmacenId', mRender: no_leidos },
          { mData: 'FechaSolicitud', mRender: no_leidos },
          { mData: 'NombreSolicitante', mRender: no_leidos },
          { mData: 'Sede', mRender: no_leidos },
          { mData: 'Servicio', mRender: no_leidos },
          { mData: 'Estado', mRender: no_leidos },
          { mData: 'Opciones' }
        ],
        searching: true,
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
      // fin refrescar tabla
      $scope.VerPedido = true
      $scope.PedidoUpdate = angular.copy($scope.simpleTableOptions.data[i])
      PedidoAlmacenService.getItemsPedidoAlmacenAdmin(
        $scope.PedidoUpdate.PedidoAlmacenId
      ).then(function (d) {
        if (typeof d.data != 'string') {
          console.log(d.data)
          $scope.PedidoUpdate.Items = d.data
          $scope.PedidoUpdate.FechaSolicitud = new Date(
            $scope.PedidoUpdate.FechaSolicitud
          )
          if ($scope.PedidoUpdate.FechaEntrega) {
            $scope.PedidoUpdate.FechaEntrega = new Date(
              $scope.PedidoUpdate.FechaEntrega
            )
          }
        } else {
          swal('Error', d.data, 'error')
        }
      })
    }

    $scope.Atras = function () {
      $scope.PedidoNuevo = false
      $scope.VerPedido = false
      $scope.cargado = true
    }

    $scope.ChangeFiltro = function () {
      GetPedidos()
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="consultas">
    function GetSede() {
      SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (
        c
      ) {
        $scope.Sedes = c.data
        $scope.PedidoUpdate.SedeId = c.data[0].SedeId
        GetServicio()
      })
    }
    function GetServicio() {
      ServicioService.getServicioByUserId($rootScope.username.UserId).then(
        function (c) {
          $scope.Servicios = $filter('orderBy')(
            $filter('filter')(c.data, { SedeId: $scope.PedidoUpdate.SedeId }),
            'Nombre'
          )
          $scope.PedidoUpdate.ServicioId = $scope.Servicios[0].ServicioId
        }
      )
    }
    function GetEmpresa() {
      EmpresaService.getEmpresa().then(function (e) {
        $scope.Empresa = e.data
      })
    }
    function GetEncabezado() {
      EncabezadoService.getEncabezado().then(function (e) {
        $scope.Encabezado = e.data
      })
    }
    function GetPedidos() {
      $scope.cargado = false
      $scope.simpleTableOptions = {}
      let Tipo = 'Almacen'
      if ($state.current.name == 'farmacia.solicitudes_pedidos_2_central') {
        Tipo = 'Central'
      }
      console.log($state.current.name)
      PedidoAlmacenService.getAllPedidoAlmacenBySede_2(
        $scope.Usuario.UserId,
        $scope.Filtro,
        Tipo
      ).then(function (d) {
        //                let no_leidos = GetNoleidos(d.data);

        $scope.simpleTableOptions = {
          data: [],
          aoColumns: [
            { mData: 'PedidoAlmacenId', mRender: no_leidos },
            { mData: 'FechaSolicitud', mRender: no_leidos },
            { mData: 'NombreSolicitante', mRender: no_leidos },
            { mData: 'Sede', mRender: no_leidos },
            { mData: 'Servicio', mRender: no_leidos },
            { mData: 'Estado', mRender: no_leidos },
            { mData: 'Opciones' }
          ],
          searching: true,
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
        $scope.simpleTableOptions.data = SetFormat(
          $filter('orderBy')(d.data, '-PedidoAlmacenId')
        )
        $scope.cargado = true
      })
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function printDiv(div) {
      $('#imprimirPedido').print({
        globalStyles: true,
        mediaPrint: false,
        stylesheet: null,
        noPrintSelector: '.no-print',
        iframe: true,
        append: null,
        prepend: null,
        manuallyCopyFormValues: true,
        deferred: $.Deferred(),
        timeout: 750,
        title: null,
        doctype: '<!doctype html>'
      })

      setTimeout(function () {
        $scope.ToPrint = false
        $scope.$apply()
      }, 1000)
    }

    function lpad(str, padString, length) {
      while (str.length < length) str = padString + str
      return str
    }
    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].NumeroPedido =
          'N°         ' + lpad(lst[i].PedidoAlmacenId.toString(), '0', 4)
        if (lst[i].Estado === 'Recibir') {
          lst[i].Estado = 'Recibido'
        }
        lst[i].Opciones =
          '<a class="btn  btn-info btn-xs icon-only white"  onclick="angular.element(this).scope().ViewPedido(' +
          i +
          ')"><i class="fa fa-info"></i></a>'
      }

      return lst
    }

    function GetNoleidos(lst) {
      let indices = []
      let cont = 0
      lst.map(function (x) {
        if (parseInt(x.IsLeido) === 0) {
          indices.push(cont)
        }
        cont++
      })

      return indices
    }
    //</editor-fold>
    function _init() {
      GetEmpresa()
      GetEncabezado()
      GetSede()
      GetPedidos()
      if ($state.current.name == 'farmacia.solicitudes_pedidos_2_central') {
        $scope.TipoFormato = 'Farmacia'
      }
    }
    _init()
  }
])

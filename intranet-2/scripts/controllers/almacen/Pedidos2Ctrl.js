app.controller('Pedidos2Ctrl', [
  '$scope',
  '$state',
  '$rootScope',
  '$filter',
  'EmpresaService',
  'EncabezadoService',
  'PedidoAlmacenService',
  'ServicioService',
  'SedeService',
  'SesionService',
  'ArticuloService',
  function (
    $scope,
    $state,
    $rootScope,
    $filter,
    EmpresaService,
    EncabezadoService,
    PedidoAlmacenService,
    ServicioService,
    SedeService,
    SesionService,
    ArticuloService
  ) {
    swal(
      'ATENCIÓN!!',
      'AL SOLICITAR LOS PEDIDOS SUS CANTIDADES DEBEN SER EN UNIDADES',
      'warning'
    )
    //<editor-fold defaultstate="collapsed" desc="Variables">
    $scope.CargandoBandera = false
    $scope.PedidoNuevo = false
    $scope.VerPedido = false
    $scope.ToPrint = false
    $scope.TipoFormato = 'Almacen'
    var tipo_p =
      $state.current.name === 'farmacia.pedidos2_central' ||
      $rootScope.IsCentral
        ? 'Central'
        : 'Almacen'
    $scope.Articulos = []
    $scope.Usuario = $rootScope.username
    $scope.Empresa = {}
    $scope.PedidoUpdate = {}
    $scope.pedido = SesionService.get(
      `Pedido_2.0_${tipo_p}_` + $scope.Usuario.NombreCompleto
    ) || {
      SedeId: '',
      ServicioId: '',
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
      TipoPedido: tipo_p,
      CreatedBy: $scope.Usuario.NombreCompleto
    }
    $scope.pedido.FechaSolicitud = new Date()
    $scope.accesorio = {
      Articulo: '',
      CantidadSolicitada: 1,
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
    $scope.CreatePedido = function () {
      if (!$scope.CargandoBandera) {
        if (!$scope.Datos.$valid) {
          angular
            .element("[name='" + $scope.Datos.$name + "']")
            .find('.ng-invalid:visible:first')
            .focus()
        } else if (!HasPedido()) {
          swal(
            'Error',
            'Debes solicitar minimo 1 item de tu plantilla.',
            'error'
          )
        } else {
          var obj = {
            PedidoAlmacen2: JSON.stringify($scope.pedido)
          }
          $scope.CargandoBandera = true
          PedidoAlmacenService.PostPedidoAlmacen(obj).then(
            function (d) {
              if (typeof d.data != 'string') {
                $rootScope.$broadcast('Pedido2_0Id_new', {
                  PedidoId: d.data[0]
                })
                swal(
                  'Enhorabuena',
                  'Se ha guardado los datos con exito',
                  'success'
                )
                $('#PedidoAlmacenModal').modal('hide')
                SesionService.remove(
                  `Pedido_2.0_${tipo_p}_` + $scope.Usuario.NombreCompleto
                )
                GetPedidos()
                $scope.Atras()
              } else {
                swal('Error', d.data, 'error')
              }
              $scope.CargandoBandera = false
            },
            () => {
              $scope.CargandoBandera = false
            }
          )
        }
      }
    }

    $scope.EliminarBorrador = () => {
      SesionService.remove(
        `Pedido_2.0_${tipo_p}_` + $scope.Usuario.NombreCompleto
      )
      location.reload()
    }

    $scope.NewPedidoModal = function () {
      $scope.PedidoUpdate = {}
      $scope.pedido = SesionService.get(
        `Pedido_2.0_${tipo_p}_` + $scope.Usuario.NombreCompleto
      ) || {
        SedeId: '',
        ServicioId: '',
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
        TipoPedido: tipo_p,
        CreatedBy: $scope.Usuario.NombreCompleto
      }

      $scope.PedidoNuevo = true
      if (
        SesionService.get(
          `Pedido_2.0_${tipo_p}_` + $scope.Usuario.NombreCompleto
        )
      ) {
        $scope.ChangeServicio()
      }
    }

    $scope.Atras = function () {
      $scope.PedidoNuevo = false
      $scope.VerPedido = false
    }

    $scope.GuardarBorrador = function () {
      SesionService.set(
        $scope.pedido,
        `Pedido_2.0_${tipo_p}_` + $scope.Usuario.NombreCompleto
      )
      swal('Enhorabuena', 'Se ha guardado EL BORRADOR con exito', 'success')
    }

    $scope.DeleteItem = function (i) {
      $scope.pedido.Items.splice(i, 1)
      $scope.obj = {
        Articulo: '',
        CantidadSolicitada: 1,
        CantidadEntregada: 0,
        Pendiente: ''
      }
    }
    $scope.DeleteItem2 = function (i) {
      $scope.PedidoUpdate.Items.splice(i, 1)
      $scope.obj = {
        Articulo: '',
        CantidadSolicitada: 1,
        CantidadEntregada: 0,
        Pendiente: ''
      }
    }
    $scope.Imprimir = function () {
      $scope.ToPrint = true
      setTimeout(function () {
        printDiv()
      }, 500)
    }
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
            if ($scope.simpleTableOptions.data[i].Estado === 'Activo') {
              $scope.simpleTableOptions.data[i].ModifiedBy =
                $rootScope.username.NombreUsuario
              var obj = {
                PedidoAlmacenId_2:
                  $scope.simpleTableOptions.data[i].PedidoAlmacenId
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
            } else {
              swal('Error', 'Ya no se puede borrar :(', 'error')
            }
          }
        }
      )
    }
    $scope.ViewPedido = function (i) {
      $scope.PedidoUpdate = {}
      $scope.VerPedido = true
      $scope.PedidoUpdate = angular.copy($scope.simpleTableOptions.data[i])
      PedidoAlmacenService.getItemsPedidoAlmacen(
        $scope.PedidoUpdate.PedidoAlmacenId
      ).then(function (d) {
        if (typeof d.data != 'string') {
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
    $scope.ChangeSede = function () {
      $scope.pedido.ServicioId = ''
      GetServicio()
    }

    $scope.ChangeServicio = function () {
      $scope.pedido.Items = []
      let Tipo = tipo_p
      if ($scope.pedido.ServicioId && $scope.pedido.ServicioId != '--') {
        ArticuloService.getArticulosByPlantilla(
          $scope.pedido.ServicioId,
          $rootScope.username.UserId,
          Tipo
        ).then(function (d) {
          if (typeof d.data != 'string') {
            for (var i in d.data) {
              d.data[i].CantidadSolicitada = 0
              d.data[i].CantidadEntregada = 0
              d.data[i].Pendiente = ''
            }
            var items = d.data
            for (var i in items) {
              if (
                SesionService.get(
                  `Pedido_2.0_${tipo_p}_` + $scope.Usuario.NombreCompleto
                )
              ) {
                $scope.pedido =
                  SesionService.get(
                    `Pedido_2.0_${tipo_p}_` + $scope.Usuario.NombreCompleto
                  ) || {}
                for (var k in $scope.pedido.Items) {
                  if (
                    $scope.pedido.Items[k].ArticuloId === items[i].ArticuloId
                  ) {
                    $scope.pedido.Items[k].Limite = items[i].Limite
                    break
                  }
                }
                // Luego vere como añadir el nuevo
              } else {
                $scope.pedido.Items = d.data
                break
              }
            }
            //                    console.log($scope.pedido.Items)
          } else {
            swal('Error', d.data, 'error')
          }
        })
      }
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="consultas">
    function GetArticulos(Tipo) {
      ArticuloService.getAllArticulo(Tipo).then(function (a) {
        $scope.Articulos = a.data
      })
    }
    function HasPedido() {
      for (var i in $scope.pedido.Items) {
        if ($scope.pedido.Items[i].CantidadSolicitada > 0) {
          return true
        }
      }
      return false
    }
    function GetSede() {
      SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (
        c
      ) {
        $scope.Sedes = c.data
        $scope.pedido.SedeId = c.data[0].SedeId
        GetServicio()
      })
    }
    function GetServicio() {
      ServicioService.getServicioByUserId($rootScope.username.UserId).then(
        function (c) {
          $scope.Servicios = $filter('orderBy')(
            $filter('filter')(c.data, { SedeId: $scope.pedido.SedeId }),
            'Nombre'
          )
          //                for (var k in $scope.Servicios) {
          //                    if ($scope.Servicios[k].IsVisible && $scope.pedido.ServicioId === "--") {
          //                        $scope.pedido.ServicioId = $scope.Servicios[k].ServicioId;
          //                        $scope.ChangeServicio();
          //                        break;
          //                    }
          //                }
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
    function GetPedidos(Tipo) {
      $scope.cargado = false
      Tipo = tipo_p
      PedidoAlmacenService.GetBySolicitanteIdVer2(
        $scope.Usuario.UserId,
        Tipo
      ).then(function (d) {
        $scope.simpleTableOptions = {
          data: [],
          aoColumns: [
            { mData: 'PedidoAlmacenId' },
            { mData: 'FechaSolicitud' },
            { mData: 'NombreSolicitante' },
            { mData: 'Sede' },
            { mData: 'Servicio' },
            { mData: 'Estado' },
            { mData: 'Opciones' }
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
            zeroRecords: 'distrados ',
            infoFiltered: '(filtro de _MAX_ registros totales)',
            search: ' Filtrar : ',
            oPaginate: {
              sPrevious: 'Anterior',
              sNext: 'Siguiente'
            }
          },
          aaSorting: []
        }
        $scope.simpleTableOptions.data = SetFormat(d.data)
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
        timeout: 1000,
        title: null,
        doctype: '<!doctype html>'
      })

      setTimeout(function () {
        $scope.ToPrint = false
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
        if (lst[i].Estado !== 'Recibir') {
          lst[i].Opciones =
            '<a class="btn  btn-info btn-xs icon-only white"  onclick="angular.element(this).scope().ViewPedido(' +
            i +
            ')"><i class="fa fa-info"></i></a>' +
            '<a class="btn  btn-danger btn-xs icon-only white" onclick="angular.element(this).scope().DeletePedido(' +
            i +
            ')"><i class="fa fa-trash"></i></a>'
        } else {
          lst[i].Estado = 'Recibido'
          lst[i].Opciones =
            '<a class="btn  btn-info btn-xs icon-only white"  onclick="angular.element(this).scope().ViewPedido(' +
            i +
            ')"><i class="fa fa-info"></i></a>'
        }
      }
      return lst
    }
    //</editor-fold>
    function _init() {
      GetEmpresa()
      GetEncabezado()
      GetSede()
      if (tipo_p === 'Central') {
        GetPedidos('Central')
        GetArticulos('Central')
        $scope.TipoFormato = 'Farmacia'
      } else {
        GetPedidos('Almacen')
        GetArticulos('Almacen')
        $scope.TipoFormato = 'Almacen'
      }
    }
    _init()
  }
])

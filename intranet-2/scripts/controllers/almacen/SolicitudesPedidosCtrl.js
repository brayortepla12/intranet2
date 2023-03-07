app.controller('SolicitudesPedidosCtrl', ["$scope", "$rootScope", "$filter", "EmpresaService", "EncabezadoService", "PedidoAlmacenService",
  "ServicioService", "SedeService",
  function ($scope, $rootScope, $filter, EmpresaService, EncabezadoService, PedidoAlmacenService, ServicioService, SedeService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    let vm = $scope
    vm.Usuario = $rootScope.username
    vm.Empresa = {}
    vm.PedidoUpdate = {
      SedeId: "--",
      ServicioId: "--",
      SolicitanteId: vm.Usuario.UserId,
      NombreSolicitante: vm.Usuario.NombreCompleto,
      CargoSolicitante: vm.Usuario.Cargo,
      FechaSolicitud: new Date(),
      Items: [],
      FechaEntrega: "",
      FechaRecibe: "",
      Observacion: "",
      NombreEntrega: "",
      NombreRecibe: "",
      ModifiedBy: vm.Usuario.NombreCompleto,
    }
    vm.accesorio = {
      Articulo: "",
      CantidadSolicitada: 0,
      CantidadEntregada: 0,
      Pendiente: "",
    }
    vm.obj = {
      Articulo: "",
      CantidadSolicitada: 0,
      CantidadEntregada: 0,
      Pendiente: "",
    }
    vm.Filtro = 'Activo'
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.UpdatePedido = function (Estado) {
      swal({
        title: "Desea " + Estado + " el pedido?",
        text: "Nota: una vez " + Estado + " no se podran deshacer los cambios.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: Estado,
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
      }, function (isConfirm) {
        if (isConfirm) {
          if (Estado === "Entregar") {
            vm.PedidoUpdate.NombreEntrega = $rootScope.username.NombreUsuario
          } else if (Estado === "Recibir") {
            vm.PedidoUpdate.RecibeNombre = $rootScope.username.RecibeNombre
          }
          vm.PedidoUpdate.Estado = Estado
          vm.PedidoUpdate.ModifiedBy = $rootScope.username.NombreUsuario
          var obj = {
            PedidoAlmacen: JSON.stringify(vm.PedidoUpdate)
          }
          PedidoAlmacenService.PutPedidoAlmacen(obj).then(function (d) {
            if (typeof d.data != "string") {
              swal("Enhorabuena", "Se ha actualizado los datos con exito", "success")
              $("#EditarPedidoAlmacenModal").modal('hide')
              GetPedidos()
            } else {
              swal("Error", d.data, "error")
            }
          })
        }
      })
    }
    vm.AddItem = function () {
      vm.PedidoUpdate.Items.push(vm.accesorio)
      vm.accesorio = {
        Articulo: "",
        CantidadSolicitada: 0,
        CantidadEntregada: 0,
        Pendiente: "",
      }
    }
    vm.UpdateItem = function (o, i) {
      o.EditarItem = false
      vm.PedidoUpdate.Items[i] = o
      vm.obj = {
        Articulo: "",
        CantidadSolicitada: 0,
        CantidadEntregada: 0,
        Pendiente: "",
      }
    }
    vm.DeleteItem = function (i) {
      vm.PedidoUpdate.Items.splice(i, 1)
      vm.obj = {
        Articulo: "",
        CantidadSolicitada: 0,
        CantidadEntregada: 0,
        Pendiente: "",
      }
    }
    vm.Imprimir = function () {
      //            vm.ToPrint = true
      printDiv()

    }
    vm.DeletePedido = function (i) {
      swal({
        title: "Desea eliminar el pedido?",
        text: "Nota: una vez eliminado no se podran deshacer los cambios.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Eliminar',
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
      }, function (isConfirm) {
        if (isConfirm) {
          vm.simpleTableOptions.data[i].ModifiedBy = $rootScope.username.NombreUsuario
          var obj = {
            PedidoAlmacenId: vm.simpleTableOptions.data[i].PedidoAlmacenId
          }
          PedidoAlmacenService.DeletePedidoAlmacen(obj).then(function (d) {
            if (typeof d.data !== "string") {
              swal("Enhorabuena", "Se ha eliminado el pedido con exito", "success")
              GetPedidos()
            } else {
              swal("Error", d.data, "error")
            }
          })
        }
      })
    }
    vm.ViewPedido = function (i) {
      vm.PedidoUpdate = {}
    
      //            vm.PedidoUpdate = angular.copy(vm.simpleTableOptions.data[i])
      PedidoAlmacenService.GetById(vm.simpleTableOptions.data[i].PedidoAlmacenId).then(function (d) {
       
        vm.PedidoUpdate = d.data[0]
        vm.PedidoUpdate.Items = JSON.parse(vm.PedidoUpdate.Items)
        vm.PedidoUpdate.FechaSolicitud = new Date(vm.PedidoUpdate.FechaSolicitud)
        if (vm.PedidoUpdate.FechaEntrega) {
          vm.PedidoUpdate.FechaEntrega = new Date(vm.PedidoUpdate.FechaEntrega)
        }
     
        for (var k in vm.PedidoUpdate.Items) {
          if (vm.PedidoUpdate.Items[k]) {
            vm.PedidoUpdate.Items[k].EditarItem = false
          }
        }
      })
      //            
      vm.PedidoUpdate.ModifiedBy = vm.Usuario.NombreCompleto
      $("#EditarPedidoAlmacenModal").modal("show")
      vm.$apply()
    }
    vm.ChangeFiltro = () => {
      GetPedidos()
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="consultas">
    function GetSede() {
      SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
        vm.Sedes = c.data
        vm.PedidoUpdate.SedeId = c.data[0].SedeId
        GetServicio()
      })
    }

    function GetServicio() {
      ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
        vm.Servicios = $filter("orderBy")($filter('filter')(c.data, {
          SedeId: vm.PedidoUpdate.SedeId
        }), "Nombre")
        vm.PedidoUpdate.ServicioId = vm.Servicios[0].ServicioId
      })
    }

    function GetEmpresa() {
      EmpresaService.getEmpresa().then(function (e) {
        vm.Empresa = e.data
      })
    }

    function GetEncabezado() {
      EncabezadoService.getEncabezado().then(function (e) {
        vm.Encabezado = e.data
      })
    }

    function GetPedidos() {
      vm.cargado = false
      vm.simpleTableOptions = {}
      PedidoAlmacenService.getAllPedidoAlmacenBySede(vm.Usuario.UserId, vm.Filtro).then(function (d) {
       
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [{
              mData: 'PedidoAlmacenId'
            },
            {
              mData: 'FechaSolicitud'
            },
            {
              mData: 'NombreSolicitante'
            },
            {
              mData: 'Sede'
            },
            {
              mData: 'Servicio'
            },
            {
              mData: 'Estado'
            },
            {
              mData: 'Opciones'
            },
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
        vm.simpleTableOptions.data = SetFormat($filter("orderBy")(d.data, "-PedidoAlmacenId"))
        vm.cargado = true
      })
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function printDiv(div) {
      $("#imprimirPedido").print({
        globalStyles: true,
        mediaPrint: false,
        stylesheet: null,
        noPrintSelector: ".no-print",
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
        vm.ToPrint = false
        vm.$apply()
      }, 1000)
    }

    function lpad(str, padString, length) {
      while (str.length < length)
        str = padString + str
      return str
    }

    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].NumeroPedido = 'N°         ' + lpad((lst[i].PedidoAlmacenId.toString()), '0', 4)
        if (lst[i].Estado === 'Recibir') {
          lst[i].Estado = 'Recibido'
        }
        lst[i].Opciones = '<a class="btn  btn-info btn-xs icon-only white"  onclick=\"angular.element(this).scope().ViewPedido(' + i + ')\"><i class="fa fa-info"></i></a>'

      }

      return lst
    }
    //</editor-fold>
    function _init() {
      GetEmpresa()
      GetEncabezado()
      GetSede()
      GetPedidos()
    }
    _init()
  }
])
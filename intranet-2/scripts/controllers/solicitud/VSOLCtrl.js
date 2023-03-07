app.controller('VSOLCtrl', ["$scope", "$rootScope", "$state", "SolicitudService", "PedidoAlmacenService", "ProcesosService", "SOLFactory",
  function ($scope, $rootScope, $state, SolicitudService, PedidoAlmacenService, ProcesosService, SOLFactory) {
    // VARIABLES
    let vm = $scope
    vm.REPORTEID = null
    vm.PEDIDOALMACENID = false
    vm.PEDIDOSREPUESTOID = false
    vm.PROCESOID = false
    vm.MODAL_VER = true
    vm.PREFIJO = true
    vm.Solicitud = {}
    // EVENTOS
    vm.VerReporte = (ReporteId) => {
      vm.REPORTEEXTERNOID = false
      vm.PEDIDOALMACENID = false
      vm.PEDIDOSREPUESTOID = false
      vm.PROCESOID = false
      vm.MODAL_VER = true
      $rootScope.REPORTEID = ReporteId
      vm.REPORTEID = ReporteId
      vm.$apply()
    }
    vm.VerReporteExterno = (ReporteExternoId) => {
      vm.REPORTEID = null
      vm.PEDIDOALMACENID = false
      vm.PEDIDOSREPUESTOID = false
      vm.PROCESOID = false
      SolicitudService.GetReporteExternoById_Pol(ReporteExternoId, vm.PREFIJO).then((d) => {
        vm.MODAL_VER = true
        $rootScope.REPORTEEXTERNOID = ReporteExternoId
        vm.REPORTEEXTERNOID = ReporteExternoId
        if (vm.PREFIJO === "biomedicos") {
          vm.ReporteExternoUrl = `/Biomedico/upload_files/` + d.data[0].ReporteArchivo
        } else {
          vm.ReporteExternoUrl = `/polivalente/upload_files/` + d.data[0].ReporteArchivo
        }
        vm.$apply()
      })
    }
    vm.VerPedidoAlmacen = (PedidoId, TIPO) => {
      vm.REPORTEID = false
      vm.REPORTEEXTERNOID = false
      vm.PEDIDOSREPUESTOID = false
      vm.PROCESOID = false
      PedidoAlmacenService.GetPedidoById_sm(PedidoId).then(function (p) {
        vm.PedidoAlmacen = p.data[0]
        PedidoAlmacenService.getItemsPedidoAlmacen(PedidoId).then(function (d) {
          if (typeof d.data != "string") {
            vm.TIPOPEDIDO = TIPO
            vm.PEDIDOALMACENID = true
            vm.MODAL_VER = true
            vm.PedidoAlmacen.Items = d.data
            vm.PedidoAlmacen.FechaSolicitud = new Date(vm.PedidoAlmacen.FechaSolicitud)
            if (vm.PedidoAlmacen.FechaEntrega) {
              vm.PedidoAlmacen.FechaEntrega = new Date(vm.PedidoAlmacen.FechaEntrega)
            }
          } else {
            swal("Error", d.data, "error")
          }
        })
      })
    }
    vm.VerPedidoSRepuestos = (PedidoId) => {
      vm.REPORTEID = false
      vm.REPORTEEXTERNOID = false
      vm.PEDIDOALMACENID = false
      vm.PROCESOID = false
      PedidoAlmacenService.GetById(PedidoId).then(function (p) {
        vm.PedidoSRepuesto = p.data[0]
        vm.PEDIDOSREPUESTOID = true
        vm.PedidoSRepuesto.Items = JSON.parse(vm.PedidoSRepuesto.Items)
        vm.PedidoSRepuesto.FechaSolicitud = new Date(vm.PedidoSRepuesto.FechaSolicitud)
        if (vm.PedidoSRepuesto.FechaEntrega) {
          vm.PedidoSRepuesto.FechaEntrega = new Date(vm.PedidoSRepuesto.FechaEntrega)
        }
        vm.MODAL_VER = true
      })
    }
    vm.VerProceso = (ProcesoId) => {
      ProcesosService.GetProcesosById(ProcesoId).then(function (p) {
        vm.Proceso = angular.copy(p.data)
        vm.OrdenP = parseInt(vm.Proceso.OrdenEnCurso)
        ProcesosService.GetProcesoData(vm.Proceso.ProcesoId).then(function (d) {
          vm.PROCESOID = true
          vm.Proceso.FlujoTrabajo = d.data
          for (var i in vm.Proceso.FlujoTrabajo) {
            for (var k in vm.Proceso.FlujoTrabajo[i].Seguimiento) {
              $scope.Seguimientos.push(vm.Proceso.FlujoTrabajo[i].Seguimiento[k])
            }
          }
          vm.Proceso.DatosFormulario = JSON.parse(vm.Proceso.DatosFormulario)
          vm.MODAL_VER = true
        })
      })
    }
    vm.GetEventosBySolicitudId = () => {
      GetEventos(vm.SOLICITUD)
    }
    vm.OpenCloseFormNewEvent = () => {
      vm.NewEvento = vm.NewEvento ? false : true
    }
    vm.EventoCreado = () => {
      vm.OpenCloseFormNewEvent()
      vm.atras()
      GetSolicitudes()
    }

    // ONs
    vm.$on('view-solicitud', (e, obj) => {
      vm.PREFIJO = obj.PREFIJO
      SOLFactory.data.PREFIJO = obj.PREFIJO
      SolicitudService.GetSolicitudPolById(obj.Sol.SolicitudId, obj.PREFIJO).then(function (d) {
        if (d.data.length > 0) {
          vm.SOLICITUD = angular.copy(obj.Sol.SolicitudId)
          $rootScope.SOLICITUDID = angular.copy(obj.Sol.SolicitudId)
          $rootScope.ISFINALIZADA = angular.copy(obj.Sol.IsFinalizada)
          vm.Orden = 0
          vm.Solicitud = d.data[0]
          SOLFactory.data.Sol = d.data[0]
          $rootScope.View = true
          // vm.GetEventosBySolicitudId()
        }
      })
    })
    // CONSULTAS
    function GetEventos(SolicitudId) {
      vm.cargado = false
      vm.cargarEvento = false
      SolicitudService.GetEventoBySolicitudId_Pol(SolicitudId, vm.PREFIJO).then(function (d) {
        vm.EventoData = {
          data: [],
          aoColumns: [{
              mData: 'EventoSolicitudId'
            },
            {
              mData: 'FechaEvento'
            },
            {
              mData: 'NombreBreveEvento'
            },
            {
              mData: 'NombreUsuario'
            },
            {
              mData: 'TecnicoResponsable'
            },
            {
              mData: 'Descripcion'
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
        vm.EventoData.data = setFormatEventos(d.data)
      })
      vm.cargarEvento = true
    }
    // HELPERS
    vm.changeView = (orden) => {
      vm.Orden = orden
    }
    vm.CloseModal = () => {
      vm.MODAL = false
      vm.MODAL_VER = false
    }
    vm.$on('ToggleModal', function (event, args) {
      vm.MODAL = args.bandera
      vm.ES = args.EventoSol
    })
    function setFormatEventos(lst) {
      for (var i in lst) {
        lst[i].Opciones = '<a class="btn btn-info btn-xs white" data-toggle="tooltip" title="Ver detalle evento" onclick=\"angular.element(this).scope().VerEvento(' + i + ')\"><i class="fa fa-info"></i></a>'
        if (lst[i].Pedido2_0Id) {
          lst[i].Opciones += '<a class="btn-success btn-xs white" data-toggle="tooltip" title="Ver Pedido Almacen" onclick=\"angular.element(this).scope().VerPedidoAlmacen(' + lst[i].Pedido2_0Id + ', \'Almacen\')\"><i class="fa fa-file-text"></i></a>'
        } else if (lst[i].PedidoId) {
          lst[i].Opciones += '<a class="btn-primary btn-xs white" data-toggle="tooltip" title="Ver Pedido Respuesto" onclick=\"angular.element(this).scope().VerPedidoSRepuestos(' + lst[i].PedidoId + ')\"><i class="fa fa-file-text"></i></a>'
        } else if (lst[i].PedidoFarmaciaId) {
          lst[i].Opciones += '<a class="btn-warning btn-xs white" data-toggle="tooltip" title="Ver Pedido Farmacia" onclick=\"angular.element(this).scope().VerPedidoAlmacen(' + lst[i].PedidoFarmaciaId + ', \'Central\')\"><i class="fa fa-file-text"></i></a>'
        } else if (lst[i].ReporteId) {
          lst[i].Opciones += '<a class="btn-info btn-xs white" data-toggle="tooltip" title="Ver Reporte" onclick=\"angular.element(this).scope().VerReporte(' + lst[i].ReporteId + ')\"><i class="fa fa-file-text"></i></a>'
        } else if (lst[i].ReporteExternoId) {
          lst[i].Opciones += '<a class="btn-info btn-xs white" data-toggle="tooltip" title="Ver Reporte Externo" onclick=\"angular.element(this).scope().VerReporteExterno(' + lst[i].ReporteExternoId + ')\"><i class="fa fa-paperclip"></i></a>'
        } else if (lst[i].ProcesoId) {
          lst[i].Opciones += '<a class="btn-info btn-xs white" data-toggle="tooltip" title="Ver proceso" onclick=\"angular.element(this).scope().VerProceso(' + lst[i].ProcesoId + ')\"><i class="fa fa-file-text-o"></i></a>'
        }
      }
      return lst
    }
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })
  }])
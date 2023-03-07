app.controller('OrdenCompraCtrl', ["$scope", "$rootScope", "ProcesosService", "ProtocoloService", "$builder", "$validator",
  function ($scope, $rootScope, ProcesosService, ProtocoloService, $builder, $validator) {
    //<editor-fold defaultstate="collapsed" desc="Variables Iniciales">
    let vm = $scope
    let popup = {}
    vm.Devolver = false
    vm.simpleTableOptions = null
    vm.Estado = "Finalizado"
    let DATE = moment()
    vm.Mes = DATE.format('M')
    vm.Year = DATE.format('YYYY')
    vm.Orden = 0
    vm.Protocolos = []
    vm.Seguimientos = []
    $builder.forms['JSON'] = null
    vm.CargarAnexo = false
    vm.Isloading = false
    vm.VB = {
      FlujoTrabajoId: "",
      ProcesoId: "",
      VerificadorId: "",
      UsuarioId: $rootScope.username.UserId,
      VB: "",
      Observacion: "",
      EstadoProceso: "Activo",
      DatosAnexos: "",
      CreatedBy: $rootScope.username.NombreCompleto
    }
    vm.Proceso = {
      ProtocoloId: "",
      DatosFormulario: "",
      Nombre: "",
      SolicitanteId: $rootScope.username.UserId,
      CreatedBy: $rootScope.username.NombreCompleto
    }
    vm.UProceso = {
      ProtocoloId: "",
      DatosFormulario: "",
      Nombre: "",
      SolicitanteId: $rootScope.username.UserId,
      ModifiedBy: $rootScope.username.NombreCompleto
    }
    vm.input = []
    vm.inputEdit = []
    //</editor-fold>
    _init()
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.OpenURL = function (url, type) {
      var blob = b64toBlob(url.split("base64,")[1], type)
      var blobUrl = window.URL.createObjectURL(blob)
      var w1 = window.open("", "_blank")
      w1.location = blobUrl
      swal("Atención!", "Si tienes problemas para visualizar el archivo, por favor deshabilita el Adblock", "warning")
      swal({
        title: "¿Tienes problemas para visualizar este archivo?",
        text: "Nota: Si tienes problemas para visualizar el archivo, por favor deshabilita el Adblock.",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "SI!",
        cancelButtonText: "NO!",
        closeOnConfirm: true,
        closeOnCancel: true
      }, function (isConfirm) {
        if (isConfirm) {
          window.location = blobUrl
        }
      })
    }
    vm.NuevaOrdenCompra = (i) => {
      const url = '/intranet-2/#/create-orden-compra'
      const strWindowFeatures = "location=no,height=1000,width=1400,scrollbars=yes,status=yes"
      popup = window.open(url, 'Crear orden de compra', strWindowFeatures)
      popup.ProcesoId = vm.simpleTableOptions.data[i].ProcesoId
      popup.SedeId = vm.simpleTableOptions.data[i].SedeId
      popup.ServicioId = vm.simpleTableOptions.data[i].ServicioId
      popup.Sede = vm.simpleTableOptions.data[i].Sede
      popup.Servicio = vm.simpleTableOptions.data[i].Servicio
    }

    vm.changeView = function (o) {
      vm.Orden = o
    }

    vm.IsInArray = function (lst, UsuarioId) {
      for (var i in lst) {
        if (lst[i].VerificadorId == UsuarioId && lst[i].VB) {
          return true
        }
      }
      return false
    }

    vm.VerFirmas = (i) => {
      const strWindowFeatures = "location=yes,height=800,width=1600,scrollbars=yes,status=yes"
      const url = '/Polivalente/api/Procesos.php?VERFProcesoId=' + vm.simpleTableOptions.data[i].ProcesoId
      window.open(url, 'Ver Firmas Proceso', strWindowFeatures)
    }

    vm.GetListadoSolicitudes = function () {
      GetListadoSolicitudes()
    }

    vm.VerReporte = (i) => {
      ProcesosService.GetPrefijoByReporteId(vm.simpleTableOptions.data[i].ReporteId).then(r => {
        const strWindowFeatures = "location=yes,height=800,width=1600,scrollbars=yes,status=yes"
        const url = '/intranet-2/#/vsolicitud'
        popup = window.open(url, 'Ver Reporte', strWindowFeatures)
        popup.ReporteId = vm.simpleTableOptions.data[i].ReporteId
        popup.PREFIJO = r.data.data.length > 0 ? r.data.data[0].Prefijo : ""
      })
    }
    vm.EditProceso = function (i) {
      vm.Seguimientos = []
      ProcesosService.GetProcesosById(vm.simpleTableOptions.data[i].ProcesoId).then(function (p) {
        vm.UProceso = angular.copy(p.data)
        vm.Orden = parseInt(vm.UProceso.OrdenEnCurso)
        ProcesosService.GetProcesoData(vm.UProceso.ProcesoId).then(function (d) {
          console.log(d.data)
          vm.UProceso.FlujoTrabajo = d.data
          for (var i in vm.UProceso.FlujoTrabajo) {
            for (var k in vm.UProceso.FlujoTrabajo[i].Seguimiento) {
              vm.Seguimientos.push(vm.UProceso.FlujoTrabajo[i].Seguimiento[k])
            }
          }
          vm.UProceso.DatosFormulario = JSON.parse(vm.UProceso.DatosFormulario)
          $('#ProcesoModal').modal('show')
        })
      })
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consulta">
    function GetListadoSolicitudes() {
      vm.cargado = false
      ProcesosService.GetProcesosFinalizadosByVerificador($rootScope.username.UserId, vm.Estado, vm.Mes, vm.Year).then(function (d) {
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [{
              mData: 'ProcesoId'
            },
            {
              mData: 'Sede'
            },
            {
              mData: 'Servicio'
            },
            {
              mData: 'Protocolo'
            },
            {
              mData: 'Nombre'
            },
            {
              mData: 'CreatedAt'
            },
            {
              mData: 'Estado'
            },
            {
              mData: 'Opciones'
            },
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
        vm.simpleTableOptions.data = SetFormat(d.data.data)
        vm.cargado = true
      })
    }

    function _init() {
      GetListadoSolicitudes()
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function SetFormat(lst) {
      for (var i in lst) {
        if (lst[i].IsYouTurn) {
          lst[i].IsYouTurn = "<img src='/intranet-2/public_html/image/flag_green.png' width='40'/>"
        } else {
          lst[i].IsYouTurn = "--"
        }
        lst[i].Opciones = '<a class="btn btn-info btn-xs icon-only white" onclick=\"angular.element(this).scope().EditProceso(' + i + ')\">Ver Proceso</a>'
        if (lst[i].ReporteId) {
          lst[i].Opciones += '<a class="btn btn-warning btn-xs icon-only white" onclick=\"angular.element(this).scope().VerReporte(' + i + ')\">Ver Reporte</a>'
        }
        lst[i].Opciones += '<a class="btn btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().NuevaOrdenCompra(' + i + ')\">Crear Orden</a>'
      }
      return lst
    }

    function b64toBlob(b64Data, contentType, sliceSize) {
      contentType = contentType || ''
      sliceSize = sliceSize || 512

      var byteCharacters = atob(b64Data)
      var byteArrays = []

      for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize)

        var byteNumbers = new Array(slice.length)
        for (var i = 0; i < slice.length; i++) {
          byteNumbers[i] = slice.charCodeAt(i)
        }

        var byteArray = new Uint8Array(byteNumbers)

        byteArrays.push(byteArray)
      }

      var blob = new Blob(byteArrays, {
        type: contentType
      })
      return blob
    }
    window.notify = function () {
      popup = {}
    }
    //</editor-fold>
  }
])